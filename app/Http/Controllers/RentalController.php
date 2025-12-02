<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Film;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Promo;
use App\Models\Notification;
use App\Helpers\QRISHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RentalController extends Controller
{
    // Lihat Sewa Saya
    public function myRentals()
    {
        $rentals = Rental::with(['film', 'payments'])
                        ->where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('rentals.my-rentals', compact('rentals'));
    }

    // Checkout
    public function checkout()
    {
        $cartItems = Cart::with('film')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        // Check stock semua film
        foreach ($cartItems as $item) {
            if (!$item->film->isAvailable()) {
                return redirect()->route('cart.index')
                    ->with('error', "Film {$item->film->title} tidak tersedia atau stock habis.");
            }
        }

        $subtotal = $cartItems->sum('subtotal');

        return view('rentals.checkout', compact('cartItems', 'subtotal'));
    }

    // Process Checkout
    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:qris,manual',
            'promo_code' => 'nullable|string',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $cartItems = Cart::with('film')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();
        try {
            $subtotal = $cartItems->sum('subtotal');
            $discount = 0;
            $promo = null;

            // Apply promo
            if ($request->promo_code) {
                $promo = Promo::where('code', $request->promo_code)->first();
                
                if ($promo && $promo->isValid()) {
                    $discount = $promo->calculateDiscount($subtotal);
                    
                    // Increment usage count
                    $promo->increment('usage_count');
                }
            }

            $total = $subtotal - $discount;
            $rentalDate = Carbon::now();
            
            // Create single payment for all rentals
            $paymentCode = Payment::generatePaymentCode();
            
            // Handle proof image upload (optional saat checkout)
            $proofImage = null;
            if ($request->hasFile('proof_image')) {
                $proofImage = $request->file('proof_image')->store('payments', 'public');
            }

            // Create rentals
            $rentals = [];
            foreach ($cartItems as $item) {
                $dueDate = $rentalDate->copy()->addDays($item->rental_days);

                // Create rental
                $rental = Rental::create([
                    'rental_code' => Rental::generateRentalCode(),
                    'user_id' => auth()->id(),
                    'film_id' => $item->film_id,
                    'promo_id' => $promo ? $promo->id : null,
                    'rental_days' => $item->rental_days,
                    'rental_price' => $item->film->rental_price,
                    'subtotal' => $item->subtotal,
                    'discount' => $discount / $cartItems->count(), // Split discount
                    'total' => $item->subtotal - ($discount / $cartItems->count()),
                    'rental_date' => $rentalDate,
                    'due_date' => $dueDate,
                    'status' => 'pending',
                ]);
                
                $rentals[] = $rental;
            }

            // Create single payment for all rentals
            $payment = Payment::create([
                'payment_code' => $paymentCode,
                'rental_id' => $rentals[0]->id, // Link ke rental pertama
                'user_id' => auth()->id(),
                'amount' => $total,
                'payment_type' => 'rental',
                'payment_method' => $request->payment_method,
                'proof_image' => $proofImage,
                'status' => 'pending',
            ]);

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            // Create notification
            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Checkout Berhasil',
                'message' => 'Silakan selesaikan pembayaran Anda.',
                'type' => 'payment',
            ]);

            DB::commit();

            // Redirect ke halaman payment dengan QRIS
            return redirect()->route('rentals.payment', $payment)
                ->with('success', 'Checkout berhasil. Silakan selesaikan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Halaman Payment dengan QRIS
    public function showPayment(Payment $payment)
    {
        // Check ownership
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        $qrCode = null;
        if ($payment->payment_method === 'qris') {
            // Generate QRIS
            $qrCode = QRISHelper::generateSimple($payment->payment_code, $payment->amount);
        }

        return view('rentals.payment', compact('payment', 'qrCode'));
    }

    // Upload Payment Proof
    public function uploadProof(Request $request, Payment $payment)
    {
        // Check ownership
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Delete old proof if exists
            if ($payment->proof_image) {
                Storage::disk('public')->delete($payment->proof_image);
            }

            // Upload new proof
            $proofImage = $request->file('proof_image')->store('payments', 'public');
            
            $payment->update([
                'proof_image' => $proofImage,
                'paid_at' => now(),
                'status' => 'pending', // Menunggu verifikasi staff
            ]);

            // Notification
            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Bukti Pembayaran Diterima',
                'message' => 'Bukti pembayaran Anda sedang diverifikasi oleh staff kami.',
                'type' => 'payment',
            ]);

            DB::commit();

            return redirect()->route('rentals.my-rentals')
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi staff.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal upload bukti: ' . $e->getMessage());
        }
    }

    // Perpanjang Sewa
    public function extend(Request $request, Rental $rental)
    {
        // Check ownership
        if ($rental->user_id != auth()->id()) {
            abort(403);
        }

        // Check status
        if (!in_array($rental->status, ['active', 'overdue'])) {
            return back()->with('error', 'Rental tidak dapat diperpanjang.');
        }

        $request->validate([
            'extend_days' => 'required|integer|min:1|max:30',
            'payment_method' => 'required|in:online,manual',
            'proof_image' => 'required_if:payment_method,manual|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $extendDays = $request->extend_days;
            $amount = $rental->rental_price * $extendDays;

            // Add overdue fine if exists
            if ($rental->status === 'overdue') {
                $amount += $rental->overdue_fine;
            }

            // Handle proof image upload
            $proofImage = null;
            if ($request->payment_method === 'manual' && $request->hasFile('proof_image')) {
                $proofImage = $request->file('proof_image')->store('payments', 'public');
            }

            // Create payment for extension
            $payment = Payment::create([
                'payment_code' => Payment::generatePaymentCode(),
                'rental_id' => $rental->id,
                'user_id' => auth()->id(),
                'amount' => $amount,
                'payment_type' => $rental->status === 'overdue' ? 'fine' : 'extension',
                'payment_method' => $request->payment_method,
                'proof_image' => $proofImage,
                'status' => $request->payment_method === 'online' ? 'verified' : 'pending',
                'paid_at' => now(),
                'verified_at' => $request->payment_method === 'online' ? now() : null,
            ]);

            // Update rental if online payment
            if ($request->payment_method === 'online') {
                $rental->update([
                    'rental_days' => $rental->rental_days + $extendDays,
                    'due_date' => $rental->due_date->addDays($extendDays),
                    'status' => 'extended',
                    'overdue_days' => 0,
                    'overdue_fine' => 0,
                ]);

                // Create notification
                Notification::create([
                    'user_id' => auth()->id(),
                    'title' => 'Perpanjangan Sewa Berhasil',
                    'message' => "Sewa film {$rental->film->title} berhasil diperpanjang {$extendDays} hari.",
                    'type' => 'rental',
                ]);
            }

            DB::commit();

            return redirect()->route('rentals.my-rentals')
                ->with('success', $request->payment_method === 'online' 
                    ? 'Perpanjangan sewa berhasil!' 
                    : 'Pembayaran perpanjangan akan segera diverifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Request Pengembalian
    public function return(Rental $rental)
    {
        // Check ownership
        if ($rental->user_id != auth()->id()) {
            abort(403);
        }

        // Check status
        if (!in_array($rental->status, ['active', 'extended', 'overdue'])) {
            return back()->with('error', 'Rental tidak dapat dikembalikan.');
        }

        // If overdue, user must pay fine first
        if ($rental->status === 'overdue' && $rental->overdue_fine > 0) {
            // Check if fine is paid
            $finePaid = Payment::where('rental_id', $rental->id)
                              ->where('payment_type', 'fine')
                              ->where('status', 'verified')
                              ->exists();

            if (!$finePaid) {
                return back()->with('error', 'Anda harus membayar denda keterlambatan terlebih dahulu.');
            }
        }

        $rental->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        // Increment stock
        $rental->film->increment('stock');

        // Create notification
        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Pengembalian Berhasil',
            'message' => "Film {$rental->film->title} berhasil dikembalikan. Terima kasih!",
            'type' => 'return',
        ]);

        return back()->with('success', 'Film berhasil dikembalikan. Terima kasih!');
    }

    // Check Promo
    public function checkPromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        $promo = Promo::where('code', $request->promo_code)->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan.',
            ]);
        }

        if (!$promo->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
            ]);
        }

        $discount = $promo->calculateDiscount($request->subtotal);

        if ($discount == 0) {
            return response()->json([
                'success' => false,
                'message' => "Minimal transaksi untuk promo ini adalah Rp " . number_format($promo->min_transaction, 0, ',', '.'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil diterapkan!',
            'discount' => $discount,
            'promo_name' => $promo->name,
        ]);
    }
}
