@extends('layouts.app')

@section('title', 'Transaction Report - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Transaction Report</h1>
        <div class="flex gap-2">
            <a href="{{ route('pegawai.reports.index') }}">
                <x-button variant="outline">← Back</x-button>
            </a>
            <form method="GET" action="{{ route('pegawai.reports.pdf') }}" class="inline">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="status" value="{{ $status }}">
                <x-button type="submit" variant="primary">Download PDF</x-button>
            </form>
        </div>
    </div>

    <!-- Report Period -->
    <x-card class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="font-semibold text-lg mb-2">Report Period</h3>
                <p class="text-gray-600">{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
                @if($status)
                    <p class="text-sm text-gray-600 mt-1">Status: <span class="font-medium">{{ ucfirst($status) }}</span></p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-gray-600 text-sm">Total Revenue</p>
                <p class="text-3xl font-bold text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ $rentals->count() }} transactions</p>
            </div>
        </div>
    </x-card>

    @if($rentals->count() > 0)
        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Film</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Late Fee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rentals as $rental)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $rental->rental_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <p class="font-medium">{{ $rental->user->name }}</p>
                                    <p class="text-gray-600">{{ $rental->user->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <p class="font-medium">{{ $rental->film->title }}</p>
                                    <p class="text-gray-600">{{ $rental->film->genre->name }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $rental->rental_days }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    Rp {{ number_format($rental->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($rental->late_fee > 0)
                                        <span class="text-red-600 font-medium">
                                            Rp {{ number_format($rental->late_fee, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                    Rp {{ number_format($rental->total_amount + $rental->late_fee, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <x-rental-status-badge :status="$rental->status" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-right font-bold text-gray-900">Total Revenue:</td>
                            <td class="px-6 py-4 font-bold text-indigo-600 text-lg">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-card>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No transactions found for this period</p>
            </div>
        </x-card>
    @endif
</div>
@endsection
