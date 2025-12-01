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
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('director', 'like', "%{$search}%");
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

        $data = $request->except('poster');
        $data['slug'] = Str::slug($request->title);

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $film = Film::create($data);

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
    public function edit(Film $film)
    {
        $genres = Genre::orderBy('name')->get();
        return view('pegawai.catalog.edit', compact('film', 'genres'));
    }

    // Update film
    public function update(Request $request, Film $film)
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
            'is_available' => 'required|boolean',
        ]);

        $oldData = $film->toArray();
        $data = $request->except('poster');
        $data['slug'] = Str::slug($request->title);

        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old poster
            if ($film->poster) {
                Storage::disk('public')->delete($film->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $film->update($data);

        // Audit log
        AuditLog::log(
            'update',
            'Film',
            $film->id,
            "Film {$film->title} diupdate oleh " . auth()->user()->name,
            $oldData,
            $film->toArray()
        );

        return redirect()->route('pegawai.catalog.index')
            ->with('success', 'Film berhasil diupdate.');
    }

    // Hapus film
    public function destroy(Film $film)
    {
        // Check if film has active rentals
        if ($film->rentals()->whereIn('status', ['pending', 'active', 'extended', 'overdue'])->exists()) {
            return back()->with('error', 'Film tidak dapat dihapus karena masih ada rental aktif.');
        }

        $oldData = $film->toArray();

        // Delete poster
        if ($film->poster) {
            Storage::disk('public')->delete($film->poster);
        }

        $film->delete();

        // Audit log
        AuditLog::log(
            'delete',
            'Film',
            $film->id,
            "Film {$film->title} dihapus oleh " . auth()->user()->name,
            $oldData,
            null
        );

        return redirect()->route('pegawai.catalog.index')
            ->with('success', 'Film berhasil dihapus.');
    }
}
