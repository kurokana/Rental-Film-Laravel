<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Genre;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    // Daftar film
    public function index(Request $request)
    {
        $query = Film::with('genre');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('director', 'ilike', "%{$search}%");
            });
        }

        // Filter by genre
        if ($request->has('genre') && $request->genre != '') {
            $query->where('genre_id', $request->genre);
        }

        $films = $query->orderBy('title')->paginate(20);
        $genres = Genre::orderBy('name')->get();

        return view('pegawai.catalog.index', compact('films', 'genres'));
    }

    // Form tambah film
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('pegawai.catalog.create', compact('genres'));
    }

    // Simpan film baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'synopsis' => 'required|string',
            'director' => 'required|string|max:255',
            'cast' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'duration' => 'required|integer|min:1',
            'rental_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['poster', 'is_available']);
        
        // Generate unique slug
        $slug = Str::slug($request->title);
        $count = Film::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        $data['slug'] = $slug;
        
        $data['is_available'] = $request->has('is_available');
        $data['average_rating'] = 0;
        $data['total_reviews'] = 0;

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        \Log::info('Creating film with data:', $data);
        
        $film = Film::create($data);
        
        \Log::info('Film created:', ['id' => $film->id, 'is_available' => $film->is_available]);

        // Audit log
        AuditLog::log(
            'create',
            'Film',
            $film->id,
            "Film {$film->title} ditambahkan oleh " . auth()->user()->name,
            null,
            $film->toArray()
        );

        return redirect()->route('pegawai.catalog.index')
            ->with('success', 'Film berhasil ditambahkan.');
    }

    // Form edit film
    public function edit(Film $catalog)
    {
        $genres = Genre::orderBy('name')->get();
        return view('pegawai.catalog.edit', compact('catalog', 'genres'));
    }

    // Update film
    public function update(Request $request, Film $catalog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'synopsis' => 'required|string',
            'director' => 'required|string|max:255',
            'cast' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'duration' => 'required|integer|min:1',
            'rental_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $oldData = $catalog->toArray();
        $data = $request->except(['poster', 'is_available']);
        $data['slug'] = Str::slug($request->title);
        $data['is_available'] = $request->has('is_available');

        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old poster
            if ($catalog->poster) {
                Storage::disk('public')->delete($catalog->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $catalog->update($data);

        // Audit log
        AuditLog::log(
            'update',
            'Film',
            $catalog->id,
            "Film {$catalog->title} diupdate oleh " . auth()->user()->name,
            $oldData,
            $catalog->toArray()
        );

        return redirect()->route('pegawai.catalog.index')
            ->with('success', 'Film berhasil diupdate.');
    }

    // Hapus film
    public function destroy(Film $catalog)
    {
        // Check if film has active rentals
        if ($catalog->rentals()->whereIn('status', ['pending', 'active', 'extended', 'overdue'])->exists()) {
            return back()->with('error', 'Film tidak dapat dihapus karena masih ada rental aktif.');
        }

        $oldData = $catalog->toArray();

        // Delete poster
        if ($catalog->poster) {
            Storage::disk('public')->delete($catalog->poster);
        }

        $catalog->delete();

        // Audit log
        AuditLog::log(
            'delete',
            'Film',
            $catalog->id,
            "Film {$catalog->title} dihapus oleh " . auth()->user()->name,
            $oldData,
            null
        );

        return back()->with('success', 'Film berhasil dihapus.');
    }
}
