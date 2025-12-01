<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Featured films (highest rating)
        $featuredFilms = Film::where('is_available', true)
                            ->orderBy('average_rating', 'desc')
                            ->take(6)
                            ->get();

        // New releases (recent year)
        $newReleases = Film::where('is_available', true)
                          ->orderBy('year', 'desc')
                          ->take(6)
                          ->get();

        // Popular films (most rented)
        $popularFilms = Film::withCount(['rentals' => function($q) {
                            $q->where('status', '!=', 'pending');
                        }])
                        ->where('is_available', true)
                        ->orderBy('rentals_count', 'desc')
                        ->take(6)
                        ->get();

        return view('home', compact('featuredFilms', 'newReleases', 'popularFilms'));
    }
}
