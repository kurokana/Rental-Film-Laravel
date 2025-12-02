<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    // Browse/Search Film
    public function index(Request $request)
    {
        $query = Film::with('genre')->where('is_available', true);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('director', 'ilike', "%{$search}%")
                  ->orWhere('cast', 'ilike', "%{$search}%");
            });
        }

        // Filter by genre
        if ($request->has('genre') && $request->genre != '') {
            $query->where('genre_id', $request->genre);
        }

        // Filter by year
        if ($request->has('year') && $request->year != '') {
            $query->where('year', $request->year);
        }

        // Sort
        $sort = $request->get('sort', 'title');
        switch ($sort) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('rental_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('rental_price', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
        }

        $films = $query->paginate(12);
        $genres = Genre::orderBy('name')->get();

        return view('films.index', compact('films', 'genres'));
    }

    // Lihat Detail Film
    public function show($slug)
    {
        $film = Film::with(['genre', 'reviews.user'])->where('slug', $slug)->firstOrFail();
        
        return view('films.show', compact('film'));
    }
}
