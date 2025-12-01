@extends('layouts.app')

@section('title', 'Create Promo - Rental Film')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create Promo</h1>
        <a href="{{ route('owner.promos.index') }}">
            <x-button variant="outline">← Back to List</x-button>
        </a>
    </div>

    <form method="POST" action="{{ route('owner.promos.store') }}" x-data="promoForm()">
        @csrf

        <x-card title="Promo Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-form.input 
                        name="code" 
                        label="Promo Code" 
                        required 
                        placeholder="PROMO2024" 
                    />
                    <p class="text-sm text-gray-600 mt-1">Uppercase letters and numbers only</p>
                </div>

                <div class="md:col-span-2">
                    <x-form.textarea 
                        name="description" 
                        label="Description" 
                        required 
                        rows="3" 
                        placeholder="Description of the promo..." 
                    />
                </div>
            </div>
        </x-card>

        <x-card title="Discount Settings" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-form.select name="discount_type" label="Discount Type" required x-model="discountType">
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed Amount</option>
                    </x-form.select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Discount Value <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="discount_value" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                            :placeholder="discountType === 'percentage' ? '10' : '50000'"
                            :max="discountType === 'percentage' ? 100 : null"
                            min="0"
                            step="discountType === 'percentage' ? 1 : 1000">
                        <span class="absolute right-3 top-2.5 text-gray-600" x-text="discountType === 'percentage' ? '%' : 'Rp'"></span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1" x-show="discountType === 'percentage'">Maximum 100%</p>
                </div>
            </div>
        </x-card>

        <x-card title="Validity Period" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input 
                    name="start_date" 
                    label="Start Date" 
                    type="date" 
                    required 
                    :value="now()->format('Y-m-d')" 
                />

                <x-form.input 
                    name="end_date" 
                    label="End Date" 
                    type="date" 
                    required 
                />
            </div>
        </x-card>

        <x-card title="Usage Restrictions (Optional)" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input 
                    name="min_rental_days" 
                    label="Minimum Rental Days" 
                    type="number" 
                    placeholder="Leave empty for no minimum" 
                    min="1" 
                />

                <x-form.input 
                    name="usage_limit" 
                    label="Usage Limit" 
                    type="number" 
                    placeholder="Leave empty for unlimited" 
                    min="1" 
                />
            </div>
        </x-card>

        <div class="flex gap-3">
            <x-button type="submit" variant="primary" size="lg">Create Promo</x-button>
            <a href="{{ route('owner.promos.index') }}">
                <x-button type="button" variant="outline" size="lg">Cancel</x-button>
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function promoForm() {
    return {
        discountType: 'percentage'
    }
}
</script>
@endpush
@endsection
