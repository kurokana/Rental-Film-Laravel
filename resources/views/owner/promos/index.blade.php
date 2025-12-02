@extends('layouts.app')

@section('title', 'Promo Management - Rental Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Promo Management</h1>
        <a href="{{ route('owner.promos.create') }}">
            <x-button variant="primary">+ Create Promo</x-button>
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b">
        <nav class="flex space-x-8">
            <a href="{{ route('owner.promos.index') }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('status') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                All Promos
            </a>
            <a href="{{ route('owner.promos.index', ['status' => 'active']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'active' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Active
            </a>
            <a href="{{ route('owner.promos.index', ['status' => 'expired']) }}" 
                class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'expired' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Expired
            </a>
        </nav>
    </div>

    @if($promos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($promos as $promo)
                <x-card>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-xl">{{ $promo->code }}</h3>
                            <p class="text-gray-600">{{ $promo->description }}</p>
                        </div>
                        <x-badge :type="$promo->isValid() ? 'success' : 'error'">
                            {{ $promo->isValid() ? 'Active' : 'Expired' }}
                        </x-badge>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                        <div>
                            <span class="text-gray-600">Discount:</span>
                            <p class="font-bold text-indigo-600 text-lg">
                                @if($promo->type === 'percentage')
                                    {{ $promo->value }}%
                                @else
                                    Rp {{ number_format($promo->value, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Type:</span>
                            <p class="font-medium">{{ ucfirst($promo->type) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Valid From:</span>
                            <p class="font-medium">{{ $promo->start_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Valid Until:</span>
                            <p class="font-medium">{{ $promo->end_date->format('d M Y') }}</p>
                        </div>
                        @if($promo->min_transaction)
                            <div>
                                <span class="text-gray-600">Min Transaction:</span>
                                <p class="font-medium">Rp {{ number_format($promo->min_transaction, 0, ',', '.') }}</p>
                            </div>
                        @endif
                        @if($promo->usage_limit)
                            <div>
                                <span class="text-gray-600">Usage:</span>
                                <p class="font-medium">{{ $promo->used_count ?? 0 }} / {{ $promo->usage_limit }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-2 pt-4 border-t">
                        <a href="{{ route('owner.promos.edit', $promo) }}">
                            <x-button variant="primary" size="sm">Edit</x-button>
                        </a>
                        <form method="POST" action="{{ route('owner.promos.destroy', $promo) }}" 
                            class="inline" onsubmit="return confirm('Delete this promo?')">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger" size="sm">Delete</x-button>
                        </form>
                    </div>
                </x-card>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $promos->links() }}
        </div>
    @else
        <x-card>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">No promos found</p>
                <a href="{{ route('owner.promos.create') }}">
                    <x-button variant="primary">Create First Promo</x-button>
                </a>
            </div>
        </x-card>
    @endif
</div>
@endsection
