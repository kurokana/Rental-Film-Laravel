@extends('layouts.app')

@section('title', 'Add New Film - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add New Film</h1>
        <a href="{{ route('pegawai.catalog.index') }}">
            <x-button variant="outline">← Back to Catalog</x-button>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <strong class="font-bold">Whoops! Something went wrong.</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pegawai.catalog.store') }}" enctype="multipart/form-data">
        @csrf

        <x-card title="Basic Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-form.input 
                        name="title" 
                        label="Film Title" 
                        required 
                        placeholder="Enter film title" 
                    />
                </div>

                <x-form.select name="genre_id" label="Genre" required>
                    <option value="">Select Genre</option>
                    @forelse($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @empty
                        <option value="" disabled>No genres available</option>
                    @endforelse
                </x-form.select>

                <x-form.input 
                    name="year" 
                    label="Release Year" 
                    type="number" 
                    required 
                    placeholder="2024" 
                    min="1900" 
                    max="2099" 
                />

                <x-form.input 
                    name="duration" 
                    label="Duration (minutes)" 
                    type="number" 
                    required 
                    placeholder="120" 
                    min="1" 
                />

                <div class="md:col-span-2">
                    <x-form.input 
                        name="director" 
                        label="Director" 
                        required 
                        placeholder="Enter director name" 
                    />
                </div>

                <div class="md:col-span-2">
                    <x-form.input 
                        name="cast" 
                        label="Cast" 
                        required 
                        placeholder="Actor 1, Actor 2, Actor 3..." 
                    />
                </div>

                <div class="md:col-span-2">
                    <x-form.textarea 
                        name="synopsis" 
                        label="Synopsis" 
                        required 
                        rows="4" 
                        placeholder="Enter film synopsis..." 
                    />
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
                    placeholder="50000" 
                    min="0" 
                    step="1000" 
                />

                <x-form.input 
                    name="stock" 
                    label="Stock Quantity" 
                    type="number" 
                    required 
                    placeholder="10" 
                    min="0" 
                />

                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_available" value="1" checked 
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-2 text-gray-700">Film is available for rental</span>
                    </label>
                </div>
            </div>
        </x-card>

        <x-card title="Media" class="mb-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Film Poster
                </label>
                <input type="file" name="poster" accept="image/*" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                <p class="text-sm text-gray-600 mt-2">Recommended size: 300x450px. Max size: 2MB</p>
            </div>
        </x-card>

        <div class="flex gap-3">
            <x-button type="submit" variant="primary" size="lg">Create Film</x-button>
            <a href="{{ route('pegawai.catalog.index') }}">
                <x-button type="button" variant="outline" size="lg">Cancel</x-button>
            </a>
        </div>
    </form>
</div>
@endsection
