@extends('layouts.app')

@section('title', 'Write Review - Rental Film')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Write Review</h1>

    <x-card>
        <!-- Film Info -->
        <div class="flex gap-4 pb-6 border-b mb-6">
            <div class="w-24 h-32 bg-gray-300 rounded flex-shrink-0">
                @if($film->poster)
                    <img src="{{ Storage::url($film->poster) }}" alt="{{ $film->title }}" 
                        class="w-full h-full object-cover rounded">
                @else
                    <div class="flex items-center justify-center h-full">
                        <span class="text-gray-400 text-2xl">🎬</span>
                    </div>
                @endif
            </div>
            <div>
                <h2 class="font-bold text-xl mb-1">{{ $film->title }}</h2>
                <p class="text-gray-600">{{ $film->genre->name }} • {{ $film->year }}</p>
                <p class="text-sm text-gray-500 mt-2">
                    Rented on {{ $rental->rental_date->format('d M Y') }}
                </p>
            </div>
        </div>

        <!-- Review Form -->
        <form method="POST" action="{{ route('reviews.store') }}" x-data="reviewForm()">
            @csrf
            <input type="hidden" name="film_id" value="{{ $film->id }}">
            <input type="hidden" name="rental_id" value="{{ $rental->id }}">

            <!-- Rating -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-3">Rating <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <template x-for="star in 5" :key="star">
                        <button type="button" @click="rating = star" 
                            class="focus:outline-none transition-colors">
                            <svg class="w-10 h-10" :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'" 
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </button>
                    </template>
                </div>
                <input type="hidden" name="rating" x-model="rating" required>
                <p class="text-sm text-gray-600 mt-2" x-show="rating > 0">
                    You rated this film <span x-text="rating"></span> out of 5 stars
                </p>
                @error('rating')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Review Comment -->
            <x-form.textarea 
                name="comment" 
                label="Your Review" 
                rows="6" 
                placeholder="Share your thoughts about this film..." 
                required 
            />

            <!-- Submit Buttons -->
            <div class="flex gap-3 mt-6">
                <x-button type="submit" variant="primary">Submit Review</x-button>
                <a href="{{ route('rentals.my-rentals') }}">
                    <x-button type="button" variant="outline">Cancel</x-button>
                </a>
            </div>
        </form>
    </x-card>
</div>

@push('scripts')
<script>
function reviewForm() {
    return {
        rating: 0
    }
}
</script>
@endpush
@endsection
