@props(['film'])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
    <div class="h-64 bg-gray-300 flex items-center justify-center">
        @if($film->poster)
            <img src="{{ Storage::url($film->poster) }}" alt="{{ $film->title }}" class="h-full w-full object-cover">
        @else
            <span class="text-gray-400 text-5xl">🎬</span>
        @endif
    </div>
    <div class="p-4">
        <h3 class="font-bold text-lg mb-2 truncate">{{ $film->title }}</h3>
        <p class="text-gray-600 text-sm mb-2">{{ $film->genre->name }} • {{ $film->year }}</p>
        
        @if($film->average_rating > 0)
            <div class="flex items-center mb-3">
                <span class="text-yellow-500">⭐</span>
                <span class="ml-1 text-sm">{{ number_format($film->average_rating, 1) }}</span>
                <span class="ml-2 text-gray-500 text-sm">({{ $film->total_reviews }} reviews)</span>
            </div>
        @endif
        
        <div class="flex justify-between items-center">
            <span class="text-indigo-600 font-bold">Rp {{ number_format($film->rental_price, 0, ',', '.') }}/hari</span>
            <a href="{{ route('films.show', $film->slug) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                Detail
            </a>
        </div>
    </div>
</div>
