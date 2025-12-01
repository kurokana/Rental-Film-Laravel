@extends('layouts.app')

@section('title', 'Edit Film - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Film</h1>
        <a href="{{ route('pegawai.catalog.index') }}">
            <x-button variant="outline">← Back to Catalog</x-button>
        </a>
    </div>

    <form method="POST" action="{{ route('pegawai.catalog.update', $film) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <x-card title="Basic Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-form.input 
                        name="title" 
                        label="Film Title" 
                        required 
                        :value="$film->title" 
                    />
                </div>

                <x-form.select name="genre_id" label="Genre" required>
                    <option value="">Select Genre</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" {{ $film->genre_id == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.input 
                    name="year" 
                    label="Release Year" 
                    type="number" 
                    required 
                    :value="$film->year" 
                    min="1900" 
                    max="2099" 
                />

                <div class="md:col-span-2">
                    <x-form.input 
                        name="director" 
                        label="Director" 
                        required 
                        :value="$film->director" 
                    />
                </div>

                <div class="md:col-span-2">
                    <x-form.input 
                        name="cast" 
                        label="Cast" 
                        required 
                        :value="$film->cast" 
                    />
                </div>

                <div class="md:col-span-2">
                    <x-form.textarea 
                        name="synopsis" 
                        label="Synopsis" 
                        required 
                        rows="4" 
                    >{{ $film->synopsis }}</x-form.textarea>
                </div>
            </div>
        </x-card>

        <x-card title="Rental Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input 
                    name="rental_price" 
                    label="Rental Price (per day)" 
                    type="number" 
                    required 
                    :value="$film->rental_price" 
                    min="0" 
                    step="1000" 
                />

                <x-form.input 
                    name="stock" 
                    label="Stock Quantity" 
                    type="number" 
                    required 
                    :value="$film->stock" 
                    min="0" 
                />
            </div>
        </x-card>

        <x-card title="Media" class="mb-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Film Poster
                </label>
                
                @if($film->poster)
                    <div class="mb-4">
                        <img src="{{ Storage::url($film->poster) }}" alt="{{ $film->title }}" 
                            class="w-48 h-auto rounded border">
                        <p class="text-sm text-gray-600 mt-2">Current poster</p>
                    </div>
                @endif

                <input type="file" name="poster" accept="image/*" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                <p class="text-sm text-gray-600 mt-2">Leave empty to keep current poster. Recommended size: 300x450px. Max size: 2MB</p>
            </div>
        </x-card>

        <div class="flex gap-3">
            <x-button type="submit" variant="primary" size="lg">Update Film</x-button>
            <a href="{{ route('pegawai.catalog.index') }}">
                <x-button type="button" variant="outline" size="lg">Cancel</x-button>
            </a>
        </div>
    </form>
</div>
@endsection
