@extends('layouts.app')

@section('title', 'Film Catalog - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Film Catalog</h1>
        <a href="{{ route('pegawai.catalog.create') }}">
            <x-button variant="primary">+ Add New Film</x-button>
        </a>
    </div>

    <!-- Filters -->
    <x-card class="mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-form.input 
                name="search" 
                label="Search" 
                placeholder="Film title or director..." 
                :value="request('search')" 
            />

            <x-form.select name="genre_id" label="Genre">
                <option value="">All Genres</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </x-form.select>

            <x-form.select name="stock" label="Stock">
                <option value="">All Stock</option>
                <option value="available" {{ request('stock') === 'available' ? 'selected' : '' }}>Available (>0)</option>
                <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
            </x-form.select>

            <div class="flex items-end gap-2">
                <x-button type="submit" variant="primary" class="flex-1">Filter</x-button>
                <a href="{{ route('pegawai.catalog.index') }}">
                    <x-button type="button" variant="outline">Reset</x-button>
                </a>
            </div>
        </form>
    </x-card>

    @if($films->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($films as $film)
                <x-card>
                    <div class="flex gap-4">
                        <!-- Film Poster -->
                        <div class="w-24 h-32 bg-gray-300 rounded flex-shrink-0">
                            @if($film->poster)
                                <img src="{{ Storage::url($film->poster) }}" alt="{{ $film->title }}" 
                                    class="w-full h-full object-cover rounded">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <span class="text-gray-400 text-2xl">🎬</span>
                                </div>
                            @endif
                        </div>

                        <!-- Film Info -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-xl">{{ $film->title }}</h3>
                                    <p class="text-gray-600">{{ $film->genre->name }} • {{ $film->year }}</p>
                                    <p class="text-sm text-gray-600">Director: {{ $film->director }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($film->rental_price, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600">per day</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-4 mb-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Stock:</span>
                                    <span class="font-medium {{ $film->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $film->stock }} available
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Rating:</span>
                                    <span class="font-medium">⭐ {{ number_format($film->rating, 1) }}/5.0</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Total Rentals:</span>
                                    <span class="font-medium">{{ $film->rentals_count ?? 0 }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('films.show', $film) }}" target="_blank">
                                    <x-button variant="outline" size="sm">View Public</x-button>
                                </a>
                                <a href="{{ route('pegawai.catalog.edit', $film) }}">
                                    <x-button variant="primary" size="sm">Edit</x-button>
                                </a>
                                <form method="POST" action="{{ route('pegawai.catalog.destroy', $film) }}" 
                                    class="inline" onsubmit="return confirm('Delete this film? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="danger" size="sm">Delete</x-button>
                                </form>
                            </div>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $films->links() }}
        </div>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">No films found</p>
                <a href="{{ route('pegawai.catalog.create') }}">
                    <x-button variant="primary">Add First Film</x-button>
                </a>
            </div>
        </x-card>
    @endif
</div>
@endsection
