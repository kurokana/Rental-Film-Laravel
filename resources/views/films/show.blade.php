@extends('layouts.app')

@section('title', $film->title . ' - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('films.index') }}" class="text-indigo-600 hover:underline">← Back to Films</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Film Poster & Info -->
        <div class="lg:col-span-1">
            <x-card :padding="false">
                <div class="aspect-w-2 aspect-h-3 bg-gray-300">
                    @if($film->poster)
                        <img src="{{ Storage::url($film->poster) }}" alt="{{ $film->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-96">
                            <span class="text-gray-400 text-6xl">🎬</span>
                        </div>
                    @endif
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-600 text-sm">Genre</span>
                            <p class="font-medium">{{ $film->genre->name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Year</span>
                            <p class="font-medium">{{ $film->year }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Duration</span>
                            <p class="font-medium">{{ $film->duration }} minutes</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Director</span>
                            <p class="font-medium">{{ $film->director }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Stock Available</span>
                            <p class="font-medium {{ $film->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $film->stock }} copies
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Rental Price</span>
                            <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($film->rental_price, 0, ',', '.') }}/hari</p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Film Details & Reviews -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title & Rating -->
            <x-card>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $film->title }}</h1>
                
                @if($film->average_rating > 0)
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-2xl {{ $i <= $film->average_rating ? 'text-yellow-500' : 'text-gray-300' }}">⭐</span>
                            @endfor
                        </div>
                        <span class="ml-3 text-lg font-semibold">{{ number_format($film->average_rating, 1) }}</span>
                        <span class="ml-2 text-gray-600">({{ $film->total_reviews }} reviews)</span>
                    </div>
                @else
                    <p class="text-gray-500 mb-4">No reviews yet</p>
                @endif

                <!-- Cast -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Cast</h3>
                    <p class="text-gray-600">{{ $film->cast }}</p>
                </div>

                <!-- Synopsis -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Synopsis</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $film->synopsis }}</p>
                </div>

                <!-- Add to Cart Form -->
                @auth
                    @if(auth()->user()->isUser())
                        @if($film->isAvailable())
                            <form method="POST" action="{{ route('cart.store') }}" class="border-t pt-6">
                                @csrf
                                <input type="hidden" name="film_id" value="{{ $film->id }}">
                                
                                <div class="flex items-end gap-4">
                                    <div class="flex-1">
                                        <label for="rental_days" class="block text-gray-700 font-medium mb-2">
                                            Rental Duration (days)
                                        </label>
                                        <input type="number" name="rental_days" id="rental_days" value="1" min="1" max="30" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <x-button type="submit" variant="primary" size="lg">
                                        🛒 Add to Cart
                                    </x-button>
                                </div>
                            </form>
                        @else
                            <div class="border-t pt-6">
                                <x-alert type="warning" message="Film is currently out of stock" />
                            </div>
                        @endif
                    @endif
                @else
                    <div class="border-t pt-6">
                        <x-alert type="info" message="Please login to rent this film" />
                        <div class="mt-4">
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a> or 
                            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register</a>
                        </div>
                    </div>
                @endauth
            </x-card>

            <!-- Reviews Section -->
            <x-card title="Reviews ({{ $film->reviews->count() }})">
                @if($film->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($film->reviews as $review)
                            <div class="border-b pb-4 last:border-b-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <p class="font-semibold">{{ $review->user->name }}</p>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-sm {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">⭐</span>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">{{ $review->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No reviews yet. Be the first to review!</p>
                @endif
            </x-card>
        </div>
    </div>
</div>
@endsection
