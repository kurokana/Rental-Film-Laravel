@extends('layouts.app')

@section('title', 'Rental Details - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Rental Details</h1>
        <a href="{{ route('pegawai.rentals.index') }}">
            <x-button variant="outline">← Back to List</x-button>
        </a>
    </div>

    <!-- Rental Status -->
    <x-card class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold mb-2">Rental #{{ $rental->id }}</h2>
                <x-rental-status-badge :status="$rental->status" />
            </div>
            <div class="text-right">
                <p class="text-gray-600 text-sm">Total Amount</p>
                <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($rental->total_amount + $rental->late_fee, 0, ',', '.') }}</p>
            </div>
        </div>
    </x-card>

    <!-- Film Info -->
    <x-card title="Film Information" class="mb-6">
        <div class="flex gap-4">
            <div class="w-32 h-44 bg-gray-300 rounded flex-shrink-0">
                @if($rental->film->poster)
                    <img src="{{ Storage::url($rental->film->poster) }}" alt="{{ $rental->film->title }}" 
                        class="w-full h-full object-cover rounded">
                @endif
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-xl mb-2">{{ $rental->film->title }}</h3>
                <div class="space-y-2">
                    <p><span class="text-gray-600">Genre:</span> {{ $rental->film->genre->name }}</p>
                    <p><span class="text-gray-600">Year:</span> {{ $rental->film->year }}</p>
                    <p><span class="text-gray-600">Director:</span> {{ $rental->film->director }}</p>
                    <p><span class="text-gray-600">Rating:</span> ⭐ {{ number_format($rental->film->rating, 1) }}/5.0</p>
                    <p><span class="text-gray-600">Rental Price:</span> Rp {{ number_format($rental->film->rental_price, 0, ',', '.') }}/day</p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Customer Info -->
    <x-card title="Customer Information" class="mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="text-gray-600 text-sm">Name</label>
                <p class="font-semibold">{{ $rental->user->name }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Email</label>
                <p class="font-semibold">{{ $rental->user->email }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Phone</label>
                <p class="font-semibold">{{ $rental->user->phone ?? '-' }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Address</label>
                <p class="font-semibold">{{ $rental->user->address ?? '-' }}</p>
            </div>
        </div>
    </x-card>

    <!-- Rental Details -->
    <x-card title="Rental Details" class="mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="text-gray-600 text-sm">Rental Date</label>
                <p class="font-semibold">{{ $rental->rental_date->format('d F Y') }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Due Date</label>
                <p class="font-semibold">{{ $rental->due_date->format('d F Y') }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Duration</label>
                <p class="font-semibold">{{ $rental->rental_days }} days</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Base Amount</label>
                <p class="font-semibold">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</p>
            </div>
            @if($rental->return_date)
                <div>
                    <label class="text-gray-600 text-sm">Return Date</label>
                    <p class="font-semibold">{{ $rental->return_date->format('d F Y') }}</p>
                </div>
            @endif
            @if($rental->late_fee > 0)
                <div>
                    <label class="text-gray-600 text-sm">Late Fee</label>
                    <p class="font-semibold text-red-600">Rp {{ number_format($rental->late_fee, 0, ',', '.') }}</p>
                </div>
            @endif
        </div>
    </x-card>

    <!-- Payment Info -->
    @if($rental->payment)
        <x-card title="Payment Information" class="mb-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-gray-600 text-sm">Payment Code</label>
                    <p class="font-semibold">{{ $rental->payment->payment_code }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Payment Status</label>
                    <div class="mt-1">
                        <x-badge :type="$rental->payment->status === 'paid' ? 'success' : 'warning'">
                            {{ strtoupper($rental->payment->status) }}
                        </x-badge>
                    </div>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Payment Method</label>
                    <p class="font-semibold">{{ ucfirst($rental->payment->payment_method) }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Payment Date</label>
                    <p class="font-semibold">{{ $rental->payment->payment_date ? $rental->payment->payment_date->format('d F Y H:i') : '-' }}</p>
                </div>
            </div>
        </x-card>
    @endif

    <!-- Actions -->
    <div class="flex gap-3">
        @if($rental->status === 'pending')
            <form method="POST" action="{{ route('pegawai.rentals.activate', $rental) }}" class="flex-1">
                @csrf
                <x-button type="submit" variant="success" size="lg" class="w-full">Activate Rental</x-button>
            </form>
        @endif

        @if($rental->status === 'active')
            <form method="POST" action="{{ route('pegawai.rentals.return', $rental) }}" class="flex-1">
                @csrf
                <x-button type="submit" variant="success" size="lg" class="w-full">Process Return</x-button>
            </form>
        @endif

        @if(in_array($rental->status, ['pending', 'active']))
            <form method="POST" action="{{ route('pegawai.rentals.cancel', $rental) }}" 
                class="flex-1" onsubmit="return confirm('Are you sure you want to cancel this rental?')">
                @csrf
                <x-button type="submit" variant="danger" size="lg" class="w-full">Cancel Rental</x-button>
            </form>
        @endif

        @if($rental->payment)
            <a href="{{ route('rentals.invoice', $rental) }}" target="_blank" class="flex-1">
                <x-button variant="outline" size="lg" class="w-full">View Invoice</x-button>
            </a>
        @endif
    </div>
</div>
@endsection
