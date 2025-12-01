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
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="online" x-model="paymentMethod" required class="mr-3">
                            <div>
                                <p class="font-semibold">Online Payment</p>
                                <p class="text-sm text-gray-600">Payment will be automatically verified</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="manual" x-model="paymentMethod" required class="mr-3">
                            <div>
                                <p class="font-semibold">Manual Transfer</p>
                                <p class="text-sm text-gray-600">Upload proof of transfer (will be verified by staff)</p>
                            </div>
                        </label>

                        <!-- Upload Proof (Manual Payment) -->
                        <div x-show="paymentMethod === 'manual'" class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <label class="block text-gray-700 font-medium mb-2">
                                Upload Proof of Transfer <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="proof_image" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                            <p class="text-sm text-gray-600 mt-2">Accepted formats: JPG, PNG. Max size: 2MB</p>
                            
                            <div class="mt-4 p-4 bg-blue-50 rounded">
                                <p class="font-semibold mb-2">Transfer to:</p>
                                <p class="text-sm">Bank BCA: 1234567890</p>
                                <p class="text-sm">a.n. Rental Film</p>
                            </div>
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
        paymentMethod: 'online',

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
