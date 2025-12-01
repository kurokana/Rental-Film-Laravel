@extends('layouts.app')

@section('title', 'Browse Films - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header & Search -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Browse Films</h1>
        
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('films.index') }}" class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search films..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                
                <!-- Genre Filter -->
                <div>
                    <select name="genre" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">All Genres</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Year Filter -->
                <div>
                    <input type="number" name="year" value="{{ request('year') }}" 
                        placeholder="Year" min="1900" max="{{ date('Y') + 5 }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                
                <!-- Sort -->
                <div>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                        <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>Year (Newest)</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating (High to Low)</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex gap-2">
                <x-button type="submit" variant="primary">
                    🔍 Search
                </x-button>
                <a href="{{ route('films.index') }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Films Grid -->
    @if($films->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($films as $film)
                <x-film-card :film="$film" />
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mb-8">
            {{ $films->links() }}
        </div>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No films found.</p>
                <a href="{{ route('films.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">
                    View all films
                </a>
            </div>
        </x-card>
    @endif
</div>
@endsection
