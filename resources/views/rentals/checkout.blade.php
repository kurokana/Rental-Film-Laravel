@extends('layouts.app')

@section('title', 'Checkout - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <form method="POST" action="{{ route('rentals.process-checkout') }}" enctype="multipart/form-data" x-data="checkoutForm()">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Cart Items -->
                <x-card title="Order Items">
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between items-center pb-4 border-b last:border-b-0">
                                <div>
                                    <h4 class="font-semibold">{{ $item->film->title }}</h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $item->rental_days }} days × Rp {{ number_format($item->film->rental_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <p class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <!-- Promo Code -->
                <x-card title="Promo Code">
                    <div class="flex gap-2">
                        <input type="text" x-model="promoCode" placeholder="Enter promo code" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <x-button type="button" @click="checkPromo" variant="outline">
                            Apply
                        </x-button>
                    </div>
                    <input type="hidden" name="promo_code" x-model="appliedPromo">
                    
                    <div x-show="promoMessage" class="mt-3">
                        <x-alert x-bind:type="promoSuccess ? 'success' : 'error'" x-text="promoMessage"></x-alert>
                    </div>
                </x-card>

                <!-- Payment Method -->
                <x-card title="Payment Method">
                    <div class="space-y-4">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50"
                            :class="paymentMethod === 'qris' ? 'border-indigo-500 bg-indigo-50' : ''">
                            <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" required class="mr-3">
                            <div class="flex-1">
                                <p class="font-semibold">QRIS Payment</p>
                                <p class="text-sm text-gray-600">Scan QR Code and upload proof</p>
                            </div>
                            <div class="bg-indigo-100 p-2 rounded">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50"
                            :class="paymentMethod === 'manual' ? 'border-indigo-500 bg-indigo-50' : ''">
                            <input type="radio" name="payment_method" value="manual" x-model="paymentMethod" required class="mr-3">
                            <div class="flex-1">
                                <p class="font-semibold">Bank Transfer</p>
                                <p class="text-sm text-gray-600">Manual transfer to bank account</p>
                            </div>
                            <div class="bg-green-100 p-2 rounded">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                        </label>

                        <!-- QRIS Payment -->
                        <div x-show="paymentMethod === 'qris'" class="mt-4 p-6 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg border border-indigo-200">
                            <div class="text-center mb-4">
                                <h3 class="font-bold text-lg text-gray-800 mb-2">Scan QR Code to Pay</h3>
                                <p class="text-sm text-gray-600">Use any e-wallet app (GoPay, OVO, Dana, ShopeePay, etc)</p>
                            </div>

                            <!-- QR Code Display -->
                            <div class="bg-white p-6 rounded-lg shadow-lg mb-4 flex justify-center">
                                <div class="text-center">
                                    <div class="bg-gray-100 p-4 rounded-lg inline-block">
                                        <svg class="w-48 h-48 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zm-2 8h8v8H3v-8zm2 2v4h4v-4H5zm8-12v8h8V3h-8zm2 2h4v4h-4V5zm4 8h-2v2h2v-2zm-2 2h-2v2h2v-2zm-2-2h-2v2h2v-2zm4 2h2v-2h-2v2zm0 0v2h2v-2h-2zm0 4h2v-2h-2v2zm0 0h-2v2h2v-2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">QR Code akan muncul setelah submit</p>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-yellow-700">
                                        <strong>Important:</strong> After scanning and paying, please upload proof of payment below
                                    </p>
                                </div>
                            </div>

                            <label class="block text-gray-700 font-medium mb-2">
                                Upload Payment Proof <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="proof_image" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 bg-white">
                            <p class="text-sm text-gray-600 mt-2">📸 Screenshot from your e-wallet app (JPG/PNG, max 2MB)</p>
                        </div>

                        <!-- Bank Transfer -->
                        <div x-show="paymentMethod === 'manual'" class="mt-4 p-6 bg-green-50 rounded-lg border border-green-200">
                            <div class="mb-4 p-4 bg-white rounded border border-green-300">
                                <p class="font-semibold mb-3 text-gray-800">Transfer to:</p>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center pb-2 border-b">
                                        <span class="text-gray-600">Bank</span>
                                        <span class="font-bold">BCA</span>
                                    </div>
                                    <div class="flex justify-between items-center pb-2 border-b">
                                        <span class="text-gray-600">Account Number</span>
                                        <span class="font-bold">1234567890</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Account Name</span>
                                        <span class="font-bold">RENTAL FILM</span>
                                    </div>
                                </div>
                            </div>

                            <label class="block text-gray-700 font-medium mb-2">
                                Upload Transfer Proof <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="proof_image" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 bg-white">
                            <p class="text-sm text-gray-600 mt-2">📸 Upload your transfer receipt (JPG/PNG, max 2MB)</p>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <x-card title="Order Summary" class="sticky top-4">
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal:</span>
                            <span x-text="formatCurrency({{ $subtotal }})">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div x-show="discount > 0" class="flex justify-between text-green-600">
                            <span>Discount:</span>
                            <span x-text="'-' + formatCurrency(discount)">- Rp 0</span>
                        </div>

                        <div class="border-t pt-3 flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-indigo-600" x-text="formatCurrency(total)">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-button type="submit" variant="primary" size="lg" class="w-full">
                            Complete Checkout
                        </x-button>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('cart.index') }}" class="block text-center text-indigo-600 hover:underline">
                            Back to Cart
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function checkoutForm() {
    return {
        promoCode: '',
        appliedPromo: '',
        promoSuccess: false,
        promoMessage: '',
        discount: 0,
        subtotal: {{ $subtotal }},
        total: {{ $subtotal }},
        paymentMethod: 'qris',

        async checkPromo() {
            if (!this.promoCode) {
                this.promoMessage = 'Please enter a promo code';
                this.promoSuccess = false;
                return;
            }

            try {
                const response = await fetch('{{ route("rentals.check-promo") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        promo_code: this.promoCode,
                        subtotal: this.subtotal
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    this.discount = data.discount;
                    this.total = this.subtotal - this.discount;
                    this.appliedPromo = this.promoCode;
                    this.promoMessage = data.message;
                    this.promoSuccess = true;
                } else {
                    this.discount = 0;
                    this.total = this.subtotal;
                    this.appliedPromo = '';
                    this.promoMessage = data.message;
                    this.promoSuccess = false;
                }
            } catch (error) {
                this.promoMessage = 'Failed to check promo code';
                this.promoSuccess = false;
            }
        },

        formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
    }
}
</script>
@endpush
@endsection
