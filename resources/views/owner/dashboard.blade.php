@extends('layouts.app')

@section('title', 'Owner Dashboard - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Owner Dashboard</h1>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-indigo-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Customers</p>
                    <h3 class="text-3xl font-bold">{{ $stats['total_customers'] }}</h3>
                    <p class="text-xs text-green-600 mt-1">+{{ $stats['new_customers_month'] }} this month</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Films</p>
                    <h3 class="text-3xl font-bold">{{ $stats['total_films'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['available_films'] }} available</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Active Rentals</p>
                    <h3 class="text-3xl font-bold">{{ $stats['active_rentals'] }}</h3>
                    <p class="text-xs text-red-600 mt-1">{{ $stats['overdue_rentals'] }} overdue</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Revenue Chart (Monthly) -->
    <x-card title="Monthly Revenue (Last 6 Months)" class="mb-8">
        <div class="space-y-3">
            @foreach($monthlyRevenue as $month => $revenue)
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium">{{ $month }}</span>
                        <span class="text-sm font-bold text-indigo-600">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" 
                            style="width: {{ max(5, ($revenue / max($monthlyRevenue)) * 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-card>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('owner.promos.index') }}" class="group">
            <x-card class="hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 p-3 rounded-lg group-hover:bg-yellow-200 transition-colors">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Manage Promos</h3>
                        <p class="text-gray-600 text-sm">{{ $stats['active_promos'] }} active</p>
                    </div>
                </div>
            </x-card>
        </a>

        <a href="{{ route('owner.users.index') }}" class="group">
            <x-card class="hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-lg group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">User Management</h3>
                        <p class="text-gray-600 text-sm">Manage roles</p>
                    </div>
                </div>
            </x-card>
        </a>

        <a href="{{ route('owner.audit-logs.index') }}" class="group">
            <x-card class="hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="bg-red-100 p-3 rounded-lg group-hover:bg-red-200 transition-colors">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Audit Logs</h3>
                        <p class="text-gray-600 text-sm">View system logs</p>
                    </div>
                </div>
            </x-card>
        </a>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <x-card title="Recent Transactions">
            @if(count($recentRentals) > 0)
                <div class="space-y-3">
                    @foreach($recentRentals as $rental)
                        <div class="flex justify-between items-center pb-3 border-b last:border-b-0">
                            <div>
                                <p class="font-medium">{{ $rental->film->title }}</p>
                                <p class="text-sm text-gray-600">{{ $rental->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-indigo-600">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-600">{{ $rental->rental_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No recent transactions</p>
            @endif
        </x-card>

        <!-- Recent Audit Logs -->
        <x-card title="Recent System Activities">
            @if(count($recentLogs) > 0)
                <div class="space-y-3">
                    @foreach($recentLogs as $log)
                        <div class="pb-3 border-b last:border-b-0">
                            <div class="flex justify-between items-start mb-1">
                                <p class="font-medium text-sm">{{ $log->action }}</p>
                                <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $log->user->name }} - {{ $log->description }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No recent activities</p>
            @endif
        </x-card>
    </div>
</div>
@endsection
