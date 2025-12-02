<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Notification;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalManagementController extends Controller
{
    // Daftar semua rental
    public function index(Request $request)
    {
        $query = Rental::with(['user', 'film']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by rental code or user name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('rental_code', 'ilike', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        $rentals = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('pegawai.rentals.index', compact('rentals'));
    }

    // Detail rental
    public function show(Rental $rental)
    {
        $rental->load(['user', 'film', 'payments', 'reviews']);
        
        return view('pegawai.rentals.show', compact('rental'));
    }

    // Aktivasi rental oleh pegawai (dari pending ke active)
    public function activateRental(Rental $rental)
    {
        if ($rental->status !== 'pending') {
            return back()->with('error', 'Rental ini sudah diaktifkan atau tidak dapat diaktifkan.');
        }

        DB::beginTransaction();
        try {
            $rental->update(['status' => 'active']);

            // Create notification for user
            Notification::create([
                'user_id' => $rental->user_id,
                'title' => 'Rental Diaktifkan',
                'message' => "Rental film {$rental->film->title} telah diaktifkan oleh staff. Selamat menonton!",
                'type' => 'rental',
            ]);

            // Audit log
            AuditLog::log(
                'activate',
                'Rental',
                $rental->id,
                "Rental {$rental->rental_code} diaktifkan oleh " . auth()->user()->name,
                ['status' => 'pending'],
                ['status' => 'active']
            );

            DB::commit();

            return back()->with('success', 'Rental berhasil diaktifkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Proses pengembalian manual oleh pegawai
    public function processReturn(Rental $rental)
    {
        // Check status
        if (!in_array($rental->status, ['active', 'extended', 'overdue'])) {
            return back()->with('error', 'Rental tidak dapat dikembalikan.');
        }

        // If overdue, calculate fine
        if ($rental->status === 'overdue' || $rental->isOverdue()) {
            $rental->updateOverdueStatus();
            
            // Check if fine is paid
            $finePaid = $rental->payments()
                              ->where('payment_type', 'fine')
                              ->where('status', 'verified')
                              ->exists();

            if (!$finePaid && $rental->overdue_fine > 0) {
                return back()->with('error', 'User harus membayar denda keterlambatan terlebih dahulu. Denda: Rp ' . number_format($rental->overdue_fine, 0, ',', '.'));
            }
        }

        DB::beginTransaction();
        try {
            $rental->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);

            // Increment stock
            $rental->film->increment('stock');

            // Create notification for user
            Notification::create([
                'user_id' => $rental->user_id,
                'title' => 'Pengembalian Diproses',
                'message' => "Pengembalian film {$rental->film->title} telah diproses oleh staff. Terima kasih!",
                'type' => 'return',
            ]);

            // Audit log
            AuditLog::log(
                'return',
                'Rental',
                $rental->id,
                "Pengembalian rental {$rental->rental_code} diproses oleh " . auth()->user()->name,
                ['status' => $rental->getOriginal('status')],
                ['status' => 'returned']
            );

            DB::commit();

            return back()->with('success', 'Pengembalian berhasil diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Perpanjang sewa oleh pegawai (atas permintaan user)
    public function extendRental(Request $request, Rental $rental)
    {
        $request->validate([
            'extend_days' => 'required|integer|min:1|max:30',
        ]);

        // Check status
        if (!in_array($rental->status, ['active', 'extended', 'overdue'])) {
            return back()->with('error', 'Rental tidak dapat diperpanjang.');
        }

        DB::beginTransaction();
        try {
            $extendDays = $request->extend_days;

            $rental->update([
                'rental_days' => $rental->rental_days + $extendDays,
                'due_date' => $rental->due_date->addDays($extendDays),
                'status' => 'extended',
                'overdue_days' => 0,
                'overdue_fine' => 0,
            ]);

            // Create notification for user
            Notification::create([
                'user_id' => $rental->user_id,
                'title' => 'Sewa Diperpanjang',
                'message' => "Sewa film {$rental->film->title} telah diperpanjang {$extendDays} hari oleh staff.",
                'type' => 'rental',
            ]);

            // Audit log
            AuditLog::log(
                'extend',
                'Rental',
                $rental->id,
                "Rental {$rental->rental_code} diperpanjang {$extendDays} hari oleh " . auth()->user()->name,
                ['rental_days' => $rental->getOriginal('rental_days')],
                ['rental_days' => $rental->rental_days]
            );

            DB::commit();

            return back()->with('success', 'Rental berhasil diperpanjang.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Kelola keterlambatan (set denda, update status)
    public function handleOverdue(Rental $rental)
    {
        if ($rental->status === 'returned') {
            return back()->with('error', 'Rental sudah dikembalikan.');
        }

        DB::beginTransaction();
        try {
            // Update overdue status and calculate fine
            $rental->updateOverdueStatus();

            // Create notification for user
            if ($rental->status === 'overdue' && $rental->overdue_fine > 0) {
                Notification::create([
                    'user_id' => $rental->user_id,
                    'title' => 'Keterlambatan Pengembalian',
                    'message' => "Anda terlambat mengembalikan film {$rental->film->title}. Denda: Rp " . number_format($rental->overdue_fine, 0, ',', '.'),
                    'type' => 'overdue',
                ]);
            }

            // Audit log
            AuditLog::log(
                'overdue',
                'Rental',
                $rental->id,
                "Keterlambatan rental {$rental->rental_code} diproses oleh " . auth()->user()->name,
                null,
                ['overdue_days' => $rental->overdue_days, 'overdue_fine' => $rental->overdue_fine]
            );

            DB::commit();

            return back()->with('success', 'Keterlambatan berhasil diproses. Denda: Rp ' . number_format($rental->overdue_fine, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Cancel rental oleh pegawai
    public function cancelRental(Rental $rental)
    {
        // Only pending or active rentals can be cancelled
        if (!in_array($rental->status, ['pending', 'active'])) {
            return back()->with('error', 'Rental tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            $rental->update(['status' => 'cancelled']);

            // Restore stock
            $rental->film->increment('stock');

            // Cancel related pending payments
            $rental->payments()->where('status', 'pending')->update(['status' => 'cancelled']);

            // Create notification for user
            Notification::create([
                'user_id' => $rental->user_id,
                'title' => 'Rental Dibatalkan',
                'message' => "Rental film {$rental->film->title} telah dibatalkan oleh staff.",
                'type' => 'rental',
            ]);

            // Audit log
            AuditLog::log(
                'cancel',
                'Rental',
                $rental->id,
                "Rental {$rental->rental_code} dibatalkan oleh " . auth()->user()->name,
                ['status' => $rental->getOriginal('status')],
                ['status' => 'cancelled']
            );

            DB::commit();

            return back()->with('success', 'Rental berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
