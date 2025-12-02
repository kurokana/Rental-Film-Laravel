@extends('layouts.app')

@section('title', 'Rental Management - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Rental Management</h1>

    <!-- Filters -->
    <x-card class="mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-form.input 
                name="search" 
                label="Search" 
                placeholder="Film or customer name..." 
                :value="request('search')" 
            />

            <x-form.select name="status" label="Status">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </x-form.select>

            <x-form.input 
                name="date" 
                label="Date" 
                type="date" 
                :value="request('date')" 
            />

            <div class="flex items-end gap-2">
                <x-button type="submit" variant="primary" class="flex-1">Filter</x-button>
                <a href="{{ route('pegawai.rentals.index') }}">
                    <x-button type="button" variant="outline">Reset</x-button>
                </a>
            </div>
        </form>
    </x-card>

    @if($rentals->count() > 0)
        <div class="space-y-4">
            @foreach($rentals as $rental)
                <x-card>
                    <div class="flex gap-4">
                        <!-- Film Poster -->
                        <div class="w-20 h-28 bg-gray-300 rounded flex-shrink-0">
                            @if($rental->film->poster)
                                <img src="{{ Storage::url($rental->film->poster) }}" alt="{{ $rental->film->title }}" 
                                    class="w-full h-full object-cover rounded">
                            @endif
                        </div>

                        <!-- Rental Info -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $rental->film->title }}</h3>
                                    <p class="text-gray-600">{{ $rental->user->name }}</p>
                                </div>
                                <x-rental-status-badge :status="$rental->status" />
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-3">
                                <div>
                                    <span class="text-gray-600">Rental Date:</span>
                                    <p class="font-medium">{{ $rental->rental_date->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Due Date:</span>
                                    <p class="font-medium">{{ $rental->due_date->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Duration:</span>
                                    <p class="font-medium">{{ $rental->rental_days }} days</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Amount:</span>
                                    <p class="font-medium text-indigo-600">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</p>
                                </div>
                                @if($rental->return_date)
                                    <div>
                                        <span class="text-gray-600">Returned:</span>
                                        <p class="font-medium">{{ $rental->return_date->format('d M Y') }}</p>
                                    </div>
                                @endif
                                @if($rental->late_fee > 0)
                                    <div>
                                        <span class="text-gray-600">Late Fee:</span>
                                        <p class="font-medium text-red-600">Rp {{ number_format($rental->late_fee, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('pegawai.rentals.show', $rental) }}">
                                    <x-button variant="primary" size="sm">View Details</x-button>
                                </a>

                                @if($rental->status === 'pending')
                                    <form method="POST" action="{{ route('pegawai.rentals.activate', $rental) }}" class="inline">
                                        @csrf
                                        <x-button type="submit" variant="success" size="sm">Activate Rental</x-button>
                                    </form>
                                @endif

                                @if($rental->status === 'active')
                                    <form method="POST" action="{{ route('pegawai.rentals.return', $rental) }}" class="inline">
                                        @csrf
                                        <x-button type="submit" variant="success" size="sm">Process Return</x-button>
                                    </form>
                                @endif

                                @if(in_array($rental->status, ['pending', 'active']))
                                    <form method="POST" action="{{ route('pegawai.rentals.cancel', $rental) }}" 
                                        class="inline" onsubmit="return confirm('Cancel this rental?')">
                                        @csrf
                                        <x-button type="submit" variant="danger" size="sm">Cancel</x-button>
                                    </form>
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
                <p class="text-gray-500 text-lg">No rentals found</p>
            </div>
        </x-card>
    @endif
</div>
@endsection
