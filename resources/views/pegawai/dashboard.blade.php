@extends('layouts.app')

@section('title', 'Pegawai Dashboard - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Pegawai Dashboard</h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pending Payments -->
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Pending Payments</p>
                    <h3 class="text-3xl font-bold">{{ $stats['pending_payments'] }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Active Rentals -->
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Active Rentals</p>
                    <h3 class="text-3xl font-bold">{{ $stats['active_rentals'] }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Overdue Rentals -->
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Overdue Rentals</p>
                    <h3 class="text-3xl font-bold text-red-600">{{ $stats['overdue_rentals'] }}</h3>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Today's Revenue -->
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Today's Revenue</p>
                    <h3 class="text-2xl font-bold text-indigo-600">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</h3>
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('pegawai.payments.index') }}" class="group">
            <x-card class="hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 p-3 rounded-lg group-hover:bg-yellow-200 transition-colors">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Verify Payments</h3>
                        <p class="text-gray-600 text-sm">{{ $stats['pending_payments'] }} pending</p>
                    </div>
                </div>
            </x-card>
        </a>

        <a href="{{ route('pegawai.rentals.index') }}" class="group">
            <x-card class="hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-lg group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Manage Rentals</h3>
                        <p class="text-gray-600 text-sm">{{ $stats['active_rentals'] }} active</p>
                    </div>
                </div>
            </x-card>
        </a>

        <a href="{{ route('pegawai.catalog.index') }}" class="group">
            <x-card class="hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-lg group-hover:bg-purple-200 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Film Catalog</h3>
                        <p class="text-gray-600 text-sm">Manage films</p>
                    </div>
                </div>
            </x-card>
        </a>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Payments -->
        <x-card title="Recent Payments">
            @if($recentPayments->count() > 0)
                <div class="space-y-3">
                    @foreach($recentPayments as $payment)
                        <div class="flex justify-between items-center pb-3 border-b last:border-b-0">
                            <div>
                                <p class="font-medium">{{ $payment->rental->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $payment->payment_code }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <x-badge :type="$payment->status === 'paid' ? 'success' : 'warning'">
                                    {{ $payment->status }}
                                </x-badge>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('pegawai.payments.index') }}" class="block mt-4 text-indigo-600 hover:underline text-sm">
                    View all payments →
                </a>
            @else
                <p class="text-gray-500 text-center py-4">No recent payments</p>
            @endif
        </x-card>

        <!-- Recent Rentals -->
        <x-card title="Recent Rentals">
            @if($recentRentals->count() > 0)
                <div class="space-y-3">
                    @foreach($recentRentals as $rental)
                        <div class="flex justify-between items-center pb-3 border-b last:border-b-0">
                            <div>
                                <p class="font-medium">{{ $rental->film->title }}</p>
                                <p class="text-sm text-gray-600">{{ $rental->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $rental->rental_date->format('d M Y') }}</p>
                                <x-rental-status-badge :status="$rental->status" />
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('pegawai.rentals.index') }}" class="block mt-4 text-indigo-600 hover:underline text-sm">
                    View all rentals →
                </a>
            @else
                <p class="text-gray-500 text-center py-4">No recent rentals</p>
            @endif
        </x-card>
    </div>
</div>
@endsection
