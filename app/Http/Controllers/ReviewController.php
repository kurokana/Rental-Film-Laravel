<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    // Tampilkan form review
    public function create(Rental $rental)
    {
        // Check ownership
        if ($rental->user_id != auth()->id()) {
            abort(403);
        }

        // Check if rental is returned
        if ($rental->status !== 'returned') {
            return back()->with('error', 'Anda hanya bisa memberikan review setelah mengembalikan film.');
        }

        // Check if already reviewed
        if (Review::where('rental_id', $rental->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan review untuk rental ini.');
        }

        return view('reviews.create', compact('rental'));
    }

    // Submit review
    public function store(Request $request, Rental $rental)
    {
        // Check ownership
        if ($rental->user_id != auth()->id()) {
            abort(403);
        }

        // Check if rental is returned
        if ($rental->status !== 'returned') {
            return back()->with('error', 'Anda hanya bisa memberikan review setelah mengembalikan film.');
        }

        // Check if already reviewed
        if (Review::where('rental_id', $rental->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan review untuk rental ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Create review
            Review::create([
                'user_id' => auth()->id(),
                'film_id' => $rental->film_id,
                'rental_id' => $rental->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // Update film rating
            $rental->film->updateRating();

            DB::commit();

            return redirect()->route('films.show', $rental->film->slug)
                ->with('success', 'Terima kasih! Review Anda berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
