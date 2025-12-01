@extends('layouts.app')

@section('title', 'Shopping Cart - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                    <x-card>
                        <div class="flex gap-4">
                            <!-- Film Poster -->
                            <div class="w-24 h-32 bg-gray-300 rounded flex-shrink-0">
                                @if($item->film->poster)
                                    <img src="{{ Storage::url($item->film->poster) }}" alt="{{ $item->film->title }}" class="w-full h-full object-cover rounded">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <span class="text-gray-400 text-2xl">🎬</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Film Info -->
                            <div class="flex-1">
                                <h3 class="font-bold text-lg mb-1">{{ $item->film->title }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $item->film->genre->name }} • {{ $item->film->year }}</p>
                                <p class="text-indigo-600 font-semibold mb-3">
                                    Rp {{ number_format($item->film->rental_price, 0, ',', '.') }}/hari
                                </p>

                                <!-- Update Duration Form -->
                                <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <label class="text-sm text-gray-700">Duration:</label>
                                    <input type="number" name="rental_days" value="{{ $item->rental_days }}" 
                                        min="1" max="30" class="w-20 px-2 py-1 border border-gray-300 rounded text-center">
                                    <span class="text-sm text-gray-700">days</span>
                                    <x-button type="submit" variant="outline" size="sm">Update</x-button>
                                </form>

                                <!-- Subtotal -->
                                <p class="mt-3 text-lg font-bold">
                                    Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Delete Button -->
                            <div>
                                <form method="POST" action="{{ route('cart.destroy', $item) }}" 
                                    onsubmit="return confirm('Remove this film from cart?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="lg:col-span-1">
                <x-card title="Order Summary">
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-700">
                            <span>Items:</span>
                            <span>{{ $cartItems->count() }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('rentals.checkout') }}" class="block">
                            <x-button type="button" variant="primary" size="lg" class="w-full">
                                Proceed to Checkout
                            </x-button>
                        </a>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('films.index') }}" class="block text-center text-indigo-600 hover:underline">
                            Continue Shopping
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">Your cart is empty</p>
                <a href="{{ route('films.index') }}">
                    <x-button variant="primary">Browse Films</x-button>
                </a>
            </div>
        </x-card>
    @endif
</div>
@endsection
