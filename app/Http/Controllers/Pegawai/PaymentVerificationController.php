<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\Notification;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentVerificationController extends Controller
{
    // Daftar pembayaran yang perlu diverifikasi
    public function index()
    {
        $payments = Payment::with(['rental.film', 'user'])
                          ->where('payment_method', 'manual')
                          ->where('status', 'pending')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return view('pegawai.payments.index', compact('payments'));
    }

    // Detail pembayaran
    public function show(Payment $payment)
    {
        $payment->load(['rental.film', 'user']);
        
        return view('pegawai.payments.show', compact('payment'));
    }

    // Verifikasi pembayaran
    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Update payment status
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'notes' => $request->notes,
            ]);

            $rental = $payment->rental;

            // Update rental based on payment type
            if ($payment->payment_type === 'rental') {
                $rental->update(['status' => 'active']);
                
                // Kurangi stock
                $rental->film->decrement('stock');
                
            } elseif ($payment->payment_type === 'extension') {
                // Calculate extension days from payment amount
                $extendDays = $payment->amount / $rental->rental_price;
                
                $rental->update([
                    'rental_days' => $rental->rental_days + $extendDays,
                    'due_date' => $rental->due_date->addDays($extendDays),
                    'status' => 'extended',
                ]);
                
            } elseif ($payment->payment_type === 'fine') {
                // Reset overdue
                $rental->update([
                    'overdue_days' => 0,
                    'overdue_fine' => 0,
                    'status' => 'active',
                ]);
            }

            // Create notification for user
            Notification::create([
                'user_id' => $payment->user_id,
                'title' => 'Pembayaran Diverifikasi',
                'message' => "Pembayaran Anda untuk rental {$rental->rental_code} telah diverifikasi.",
                'type' => 'payment',
            ]);

            // Audit log
            AuditLog::log(
                'verify',
                'Payment',
                $payment->id,
                "Pembayaran {$payment->payment_code} diverifikasi oleh " . auth()->user()->name,
                null,
                ['status' => 'verified']
            );

            DB::commit();

            return redirect()->route('pegawai.payments.index')
                ->with('success', 'Pembayaran berhasil diverifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Tolak pembayaran
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Update payment status
            $payment->update([
                'status' => 'rejected',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'notes' => $request->notes,
            ]);

            // Create notification for user
            Notification::create([
                'user_id' => $payment->user_id,
                'title' => 'Pembayaran Ditolak',
                'message' => "Pembayaran Anda untuk rental {$payment->rental->rental_code} ditolak. Alasan: {$request->notes}",
                'type' => 'payment',
            ]);

            // Audit log
            AuditLog::log(
                'reject',
                'Payment',
                $payment->id,
                "Pembayaran {$payment->payment_code} ditolak oleh " . auth()->user()->name,
                null,
                ['status' => 'rejected', 'notes' => $request->notes]
            );

            DB::commit();

            return redirect()->route('pegawai.payments.index')
                ->with('success', 'Pembayaran ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
