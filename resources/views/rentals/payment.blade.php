@extends('layouts.app')

@section('title', 'Payment - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Waiting for Payment</h1>
        <p class="text-gray-600">Complete your payment to continue</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- QR Code Section -->
        <div class="lg:col-span-2">
            @if($payment->payment_method === 'qris')
                <x-card title="Scan QR Code">
                    <div class="text-center">
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 p-8 rounded-lg mb-4">
                            <div class="bg-white p-6 rounded-lg shadow-lg inline-block">
                                <img src="{{ $qrCode }}" alt="QRIS Code" class="w-64 h-64 mx-auto">
                            </div>
                        </div>

                        <div class="space-y-3 text-left">
                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded">
                                <div class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 text-sm font-bold">1</div>
                                <div>
                                    <p class="font-semibold text-gray-800">Open E-Wallet App</p>
                                    <p class="text-sm text-gray-600">GoPay, OVO, Dana, ShopeePay, LinkAja, etc.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded">
                                <div class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 text-sm font-bold">2</div>
                                <div>
                                    <p class="font-semibold text-gray-800">Scan QR Code</p>
                                    <p class="text-sm text-gray-600">Use the scan feature in your e-wallet</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded">
                                <div class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 text-sm font-bold">3</div>
                                <div>
                                    <p class="font-semibold text-gray-800">Complete Payment</p>
                                    <p class="text-sm text-gray-600">Confirm the amount and complete transaction</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-green-50 rounded">
                                <div class="bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 text-sm font-bold">4</div>
                                <div>
                                    <p class="font-semibold text-gray-800">Upload Proof</p>
                                    <p class="text-sm text-gray-600">Take screenshot and upload below</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Upload Proof Form -->
                <x-card title="Upload Payment Proof" class="mt-6">
                    <form method="POST" action="{{ route('rentals.upload-proof', $payment) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">
                                Payment Screenshot <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="proof_image" accept="image/*" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                            <p class="text-sm text-gray-600 mt-2">📸 Upload screenshot from your e-wallet (JPG/PNG, max 2MB)</p>
                        </div>

                        <x-button type="submit" variant="success" size="lg" class="w-full">
                            Upload Proof & Submit
                        </x-button>
                    </form>
                </x-card>
            @else
                <x-card title="Bank Transfer Instructions">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg mb-6">
                        <h3 class="font-bold text-xl mb-4">Transfer Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center border-b border-blue-400 pb-2">
                                <span>Bank</span>
                                <span class="font-bold text-lg">BCA</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-blue-400 pb-2">
                                <span>Account Number</span>
                                <span class="font-bold text-lg">1234567890</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Account Name</span>
                                <span class="font-bold text-lg">RENTAL FILM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <form method="POST" action="{{ route('rentals.upload-proof', $payment) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">
                                Transfer Receipt <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="proof_image" accept="image/*" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                            <p class="text-sm text-gray-600 mt-2">📸 Upload your bank transfer receipt</p>
                        </div>

                        <x-button type="submit" variant="success" size="lg" class="w-full">
                            Upload Proof & Submit
                        </x-button>
                    </form>
                </x-card>
            @endif
        </div>

        <!-- Payment Summary -->
        <div class="lg:col-span-1">
            <x-card title="Payment Summary" class="sticky top-4">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Payment Code</span>
                        <span class="font-mono font-bold">{{ $payment->payment_code }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Method</span>
                        <span class="font-semibold">{{ strtoupper($payment->payment_method) }}</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Amount</span>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <p class="text-sm text-yellow-800">
                        <strong>⏰ Important:</strong> Please complete payment and upload proof within 24 hours
                    </p>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('rentals.my-rentals') }}" class="block">
                        <x-button variant="outline" size="sm" class="w-full">View My Rentals</x-button>
                    </a>
                    <a href="{{ route('home') }}" class="block text-center text-indigo-600 hover:underline text-sm">
                        Continue Shopping
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
