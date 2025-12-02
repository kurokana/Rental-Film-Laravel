@extends('layouts.app')

@section('title', 'Payment Verification - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Payment Verification</h1>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b">
        <nav class="flex space-x-8">
            <a href="{{ route('pegawai.payments.index') }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('status') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                All Payments
            </a>
            <a href="{{ route('pegawai.payments.index', ['status' => 'pending']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Pending
            </a>
            <a href="{{ route('pegawai.payments.index', ['status' => 'paid']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'paid' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Paid
            </a>
            <a href="{{ route('pegawai.payments.index', ['status' => 'rejected']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'rejected' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Rejected
            </a>
        </nav>
    </div>

    @if($payments->count() > 0)
        <div class="space-y-4">
            @foreach($payments as $payment)
                <x-card>
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Payment Info -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $payment->payment_code }}</h3>
                                    <p class="text-gray-600">{{ $payment->rental->user->name }}</p>
                                </div>
                                <x-badge :type="$payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'error')">
                                    {{ strtoupper($payment->status) }}
                                </x-badge>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <span class="text-gray-600 text-sm">Payment Method:</span>
                                    <p class="font-medium">{{ ucfirst($payment->payment_method) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600 text-sm">Amount:</span>
                                    <p class="font-medium text-indigo-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600 text-sm">Created:</span>
                                    <p class="font-medium">{{ $payment->created_at->format('d M Y H:i') }}</p>
                                </div>
                                @if($payment->payment_date)
                                    <div>
                                        <span class="text-gray-600 text-sm">Paid Date:</span>
                                        <p class="font-medium">{{ $payment->payment_date->format('d M Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Film Info -->
                            <div class="border-t pt-4">
                                <p class="text-sm text-gray-600 mb-2">Rental Details:</p>
                                <p class="font-medium">{{ $payment->rental->film->title }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $payment->rental->rental_days }} days • 
                                    {{ $payment->rental->rental_date->format('d M Y') }} - {{ $payment->rental->due_date->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Proof Image (Manual Payment) -->
                        @if($payment->payment_method === 'manual' && $payment->proof_image)
                            <div class="w-full md:w-64">
                                <p class="text-sm text-gray-600 mb-2">Proof of Transfer:</p>
                                <a href="{{ Storage::url($payment->proof_image) }}" target="_blank">
                                    <img src="{{ Storage::url($payment->proof_image) }}" alt="Proof" 
                                        class="w-full h-auto rounded border hover:opacity-75 transition">
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    @if($payment->status === 'pending')
                        <div class="flex gap-2 mt-4 pt-4 border-t">
                            <form method="POST" action="{{ route('pegawai.payments.verify', $payment) }}" class="inline">
                                @csrf
                                <x-button type="submit" variant="success" size="sm">Approve Payment</x-button>
                            </form>

                            <form method="POST" action="{{ route('pegawai.payments.reject', $payment) }}" 
                                class="inline" onsubmit="return confirm('Reject this payment?')">
                                @csrf
                                <x-button type="submit" variant="danger" size="sm">Reject Payment</x-button>
                            </form>

                            <a href="{{ route('pegawai.payments.show', $payment) }}">
                                <x-button variant="outline" size="sm">View Details</x-button>
                            </a>
                        </div>
                    @endif
                </x-card>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No payments found</p>
            </div>
        </x-card>
    @endif
</div>
@endsection
