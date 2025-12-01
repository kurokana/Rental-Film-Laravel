@extends('layouts.app')

@section('title', 'My Rentals - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Rentals</h1>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b">
        <nav class="flex space-x-8">
            <a href="{{ route('rentals.my-rentals') }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('status') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                All Rentals
            </a>
            <a href="{{ route('rentals.my-rentals', ['status' => 'pending']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Pending
            </a>
            <a href="{{ route('rentals.my-rentals', ['status' => 'active']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'active' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Active
            </a>
            <a href="{{ route('rentals.my-rentals', ['status' => 'returned']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'returned' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Returned
            </a>
            <a href="{{ route('rentals.my-rentals', ['status' => 'cancelled']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'cancelled' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Cancelled
            </a>
        </nav>
    </div>

    @if($rentals->count() > 0)
        <div class="space-y-6">
            @foreach($rentals as $rental)
                <x-card>
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Film Poster -->
                        <div class="w-32 h-44 bg-gray-300 rounded flex-shrink-0">
                            @if($rental->film->poster)
                                <img src="{{ Storage::url($rental->film->poster) }}" alt="{{ $rental->film->title }}" 
                                    class="w-full h-full object-cover rounded">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <span class="text-gray-400 text-3xl">🎬</span>
                                </div>
                            @endif
                        </div>

                        <!-- Rental Details -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-xl">{{ $rental->film->title }}</h3>
                                <x-rental-status-badge :status="$rental->status" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm mb-4">
                                <div>
                                    <span class="text-gray-600">Rental Date:</span>
                                    <span class="font-medium">{{ $rental->rental_date->format('d M Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Due Date:</span>
                                    <span class="font-medium">{{ $rental->due_date->format('d M Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium">{{ $rental->rental_days }} days</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Total Amount:</span>
                                    <span class="font-medium text-indigo-600">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</span>
                                </div>
                                @if($rental->return_date)
                                    <div>
                                        <span class="text-gray-600">Returned:</span>
                                        <span class="font-medium">{{ $rental->return_date->format('d M Y') }}</span>
                                    </div>
                                @endif
                                @if($rental->late_fee > 0)
                                    <div>
                                        <span class="text-gray-600">Late Fee:</span>
                                        <span class="font-medium text-red-600">Rp {{ number_format($rental->late_fee, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2">
                                @if($rental->status === 'pending')
                                    <form method="POST" action="{{ route('rentals.cancel', $rental) }}" 
                                        onsubmit="return confirm('Cancel this rental?')">
                                        @csrf
                                        @method('PUT')
                                        <x-button type="submit" variant="danger" size="sm">Cancel Rental</x-button>
                                    </form>
                                @endif

                                @if($rental->status === 'returned' && !$rental->review)
                                    <a href="{{ route('reviews.create', ['film' => $rental->film_id, 'rental' => $rental->id]) }}">
                                        <x-button variant="primary" size="sm">Write Review</x-button>
                                    </a>
                                @endif

                                @if($rental->payment)
                                    <a href="{{ route('rentals.invoice', $rental) }}" target="_blank">
                                        <x-button variant="outline" size="sm">View Invoice</x-button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $rentals->links() }}
        </div>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">No rentals found</p>
                <a href="{{ route('films.index') }}">
                    <x-button variant="primary">Browse Films</x-button>
                </a>
            </div>
        </x-card>
    @endif
</div>
@endsection
