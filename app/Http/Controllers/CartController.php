<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Film;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Lihat keranjang
    public function index()
    {
        $cartItems = Cart::with('film')->where('user_id', auth()->id())->get();
        $total = $cartItems->sum('subtotal');

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Tambah ke keranjang
    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'rental_days' => 'required|integer|min:1|max:30',
        ]);

        $film = Film::findOrFail($request->film_id);

        // Check stock
        if (!$film->isAvailable()) {
            return back()->with('error', 'Film tidak tersedia atau stock habis.');
        }

        // Check apakah film sudah ada di cart
        $existingCart = Cart::where('user_id', auth()->id())
                            ->where('film_id', $request->film_id)
                            ->first();

        if ($existingCart) {
            // Update rental days
            $existingCart->rental_days = $request->rental_days;
            $existingCart->save();
            return back()->with('success', 'Durasi sewa film di keranjang berhasil diupdate.');
        }

        Cart::create([
            'user_id' => auth()->id(),
            'film_id' => $request->film_id,
            'rental_days' => $request->rental_days,
        ]);

        return back()->with('success', 'Film berhasil ditambahkan ke keranjang.');
    }

    // Update rental days
    public function update(Request $request, Cart $cart)
    {
        // Check ownership
        if ($cart->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rental_days' => 'required|integer|min:1|max:30',
        ]);

        $cart->update([
            'rental_days' => $request->rental_days,
        ]);

        return back()->with('success', 'Durasi sewa berhasil diupdate.');
    }

    // Hapus dari keranjang
    public function destroy(Cart $cart)
    {
        // Check ownership
        if ($cart->user_id != auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Film berhasil dihapus dari keranjang.');
    }
}
