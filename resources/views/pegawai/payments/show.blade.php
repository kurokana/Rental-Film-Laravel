@extends('layouts.app')

@section('title', 'Payment Details - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Payment Details</h1>
        <a href="{{ route('pegawai.payments.index') }}">
            <x-button variant="outline">← Back to List</x-button>
        </a>
    </div>

    <!-- Payment Info -->
    <x-card title="Payment Information">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="text-gray-600 text-sm">Payment Code</label>
                <p class="font-semibold text-lg">{{ $payment->payment_code }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Status</label>
                <div class="mt-1">
                    <x-badge :type="$payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'error')">
                        {{ strtoupper($payment->status) }}
                    </x-badge>
                </div>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Payment Method</label>
                <p class="font-semibold">{{ ucfirst($payment->payment_method) }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Amount</label>
                <p class="font-semibold text-indigo-600 text-lg">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Created At</label>
                <p class="font-semibold">{{ $payment->created_at->format('d F Y H:i') }}</p>
            </div>
            @if($payment->payment_date)
                <div>
                    <label class="text-gray-600 text-sm">Payment Date</label>
                    <p class="font-semibold">{{ $payment->payment_date->format('d F Y H:i') }}</p>
                </div>
            @endif
            @if($payment->verified_by)
                <div>
                    <label class="text-gray-600 text-sm">Verified By</label>
                    <p class="font-semibold">{{ $payment->verifiedBy->name }}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Verified At</label>
                    <p class="font-semibold">{{ $payment->verified_at->format('d F Y H:i') }}</p>
                </div>
            @endif
        </div>

        @if($payment->payment_method === 'manual' && $payment->proof_image)
            <div class="border-t pt-6">
                <label class="text-gray-600 text-sm mb-2 block">Proof of Transfer</label>
                <a href="{{ Storage::url($payment->proof_image) }}" target="_blank">
                    <img src="{{ Storage::url($payment->proof_image) }}" alt="Proof" 
                        class="max-w-md rounded border hover:opacity-75 transition">
                </a>
            </div>
        @endif
    </x-card>

    <!-- Customer Info -->
    <x-card title="Customer Information" class="mt-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="text-gray-600 text-sm">Name</label>
                <p class="font-semibold">{{ $payment->rental->user->name }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Email</label>
                <p class="font-semibold">{{ $payment->rental->user->email }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Phone</label>
                <p class="font-semibold">{{ $payment->rental->user->phone ?? '-' }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Address</label>
                <p class="font-semibold">{{ $payment->rental->user->address ?? '-' }}</p>
            </div>
        </div>
    </x-card>

    <!-- Rental Info -->
    <x-card title="Rental Information" class="mt-6">
        <div class="grid grid-cols-2 gap-6">
            <div class="col-span-2">
                <label class="text-gray-600 text-sm">Film</label>
                <p class="font-semibold text-lg">{{ $payment->rental->film->title }}</p>
                <p class="text-gray-600">{{ $payment->rental->film->genre->name }} • {{ $payment->rental->film->year }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Rental Date</label>
                <p class="font-semibold">{{ $payment->rental->rental_date->format('d F Y') }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Due Date</label>
                <p class="font-semibold">{{ $payment->rental->due_date->format('d F Y') }}</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Duration</label>
                <p class="font-semibold">{{ $payment->rental->rental_days }} days</p>
            </div>
            <div>
                <label class="text-gray-600 text-sm">Status</label>
                <div class="mt-1">
                    <x-rental-status-badge :status="$payment->rental->status" />
                </div>
            </div>
        </div>
    </x-card>

    <!-- Actions -->
    @if($payment->status === 'pending')
        <div class="flex gap-3 mt-6">
            <form method="POST" action="{{ route('pegawai.payments.verify', $payment) }}" class="flex-1">
                @csrf
                <x-button type="submit" variant="success" size="lg" class="w-full">
                    Approve Payment
                </x-button>
            </form>

            <form method="POST" action="{{ route('pegawai.payments.reject', $payment) }}" 
                class="flex-1" onsubmit="return confirm('Are you sure you want to reject this payment?')">
                @csrf
                <x-button type="submit" variant="danger" size="lg" class="w-full">
                    Reject Payment
                </x-button>
            </form>
        </div>
    @endif
</div>
@endsection
