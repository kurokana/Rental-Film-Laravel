@extends('layouts.app')

@section('title', 'Reports - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Reports & Analytics</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-card>
            <p class="text-gray-600 text-sm mb-1">Total Rentals</p>
            <h3 class="text-3xl font-bold">{{ $stats['total_rentals'] }}</h3>
            <p class="text-sm text-green-600 mt-1">All time</p>
        </x-card>

        <x-card>
            <p class="text-gray-600 text-sm mb-1">Total Revenue</p>
            <h3 class="text-2xl font-bold text-indigo-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            <p class="text-sm text-green-600 mt-1">All time</p>
        </x-card>

        <x-card>
            <p class="text-gray-600 text-sm mb-1">This Month</p>
            <h3 class="text-3xl font-bold">{{ $stats['month_rentals'] }}</h3>
            <p class="text-sm text-gray-600 mt-1">Rentals</p>
        </x-card>

        <x-card>
            <p class="text-gray-600 text-sm mb-1">Month Revenue</p>
            <h3 class="text-2xl font-bold text-indigo-600">Rp {{ number_format($stats['month_revenue'], 0, ',', '.') }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ date('F Y') }}</p>
        </x-card>
    </div>

    <!-- Generate Report -->
    <x-card title="Generate Report" class="mb-8">
        <form method="GET" action="{{ route('pegawai.reports.transactions') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-form.input 
                name="start_date" 
                label="Start Date" 
                type="date" 
                required 
                :value="request('start_date', now()->startOfMonth()->format('Y-m-d'))" 
            />

            <x-form.input 
                name="end_date" 
                label="End Date" 
                type="date" 
                required 
                :value="request('end_date', now()->format('Y-m-d'))" 
            />

            <x-form.select name="status" label="Status">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="returned">Returned</option>
                <option value="cancelled">Cancelled</option>
            </x-form.select>

            <div class="flex items-end gap-2">
                <x-button type="submit" variant="primary" class="flex-1">View Report</x-button>
            </div>
        </form>
    </x-card>

    <!-- Popular Films -->
    <x-card title="Most Rented Films" class="mb-8">
        @if(count($popularFilms) > 0)
            <div class="space-y-4">
                @foreach($popularFilms as $index => $film)
                    <div class="flex items-center gap-4 pb-4 border-b last:border-b-0">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-lg">#{{ $index + 1 }}</span>
                        </div>
                        <div class="w-16 h-20 bg-gray-300 rounded flex-shrink-0">
                            @if($film->poster)
                                <img src="{{ Storage::url($film->poster) }}" alt="{{ $film->title }}" 
                                    class="w-full h-full object-cover rounded">
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold">{{ $film->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $film->genre->name }} • {{ $film->year }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-indigo-600">{{ $film->rentals_count }}</p>
                            <p class="text-sm text-gray-600">rentals</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No rental data available</p>
        @endif
    </x-card>

    <!-- Top Customers -->
    <x-card title="Top Customers">
        @if(count($topCustomers) > 0)
            <div class="space-y-4">
                @foreach($topCustomers as $index => $customer)
                    <div class="flex items-center gap-4 pb-4 border-b last:border-b-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-bold text-lg">#{{ $index + 1 }}</span>
                        </div>
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-gray-600 font-bold">{{ substr($customer->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold">{{ $customer->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $customer->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-green-600">{{ $customer->rentals_count }}</p>
                            <p class="text-sm text-gray-600">rentals</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No customer data available</p>
        @endif
    </x-card>
</div>
@endsection
