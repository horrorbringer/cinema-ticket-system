@props(['movie'])

@php
    $posterUrl = $movie->poster
        ? (str_starts_with($movie->poster, 'http') ? $movie->poster : asset('storage/' . $movie->poster))
        : 'https://picsum.photos/seed/' . $movie->id . '/300/450';
    $nextShowtime = $movie->showtimes()
        ->where('start_time', '>=', now())
        ->orderBy('start_time')
        ->first();
@endphp

<div class="group bg-white/[0.03] border border-white/10 rounded-xl overflow-hidden hover:border-[#f5af19]/30 hover:shadow-xl hover:shadow-black/40 transition-all duration-500 relative">
    <a href="{{ route('movies.show', $movie) }}" class="block relative overflow-hidden aspect-[2/3]">
        <img src="{{ $posterUrl }}" alt="{{ $movie->title }}"
             class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:brightness-75"
             loading="lazy"
             onerror="this.outerHTML = `<div class='w-full h-full bg-gradient-to-br from-white/5 to-white/10 flex items-center justify-center'><svg class='w-16 h-16 text-white/20' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z'/></svg></div>`">

        @if($movie->trailer)
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                <div class="w-16 h-16 rounded-full bg-[#f5af19]/90 flex items-center justify-center shadow-lg shadow-black/50">
                    <svg class="w-7 h-7 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
            </div>
        @endif

        <div class="absolute top-3 left-3 z-10">
            @if($movie->status === 'now_showing')
                <span class="px-2.5 py-1 text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30 rounded-full backdrop-blur-sm">Now Showing</span>
            @elseif($movie->status === 'coming_soon')
                <span class="px-2.5 py-1 text-xs font-semibold bg-[#f5af19]/20 text-[#f5af19] border border-[#f5af19]/30 rounded-full backdrop-blur-sm">Coming Soon</span>
            @endif
        </div>

        @if($movie->rating > 0)
            <div class="absolute top-3 right-3 z-10">
                <div class="flex items-center gap-1 px-2 py-1 bg-black/60 backdrop-blur-sm rounded-lg text-xs">
                    <svg class="w-3.5 h-3.5 text-[#f5af19]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="font-semibold text-white">{{ number_format($movie->rating, 1) }}</span>
                </div>
            </div>
        @endif
    </a>

    <div class="p-4 space-y-3">
        <a href="{{ route('movies.show', $movie) }}">
            <h3 class="font-bold text-white group-hover:text-[#f5af19] transition-colors line-clamp-1">{{ $movie->title }}</h3>
        </a>

        <div class="flex items-center gap-3 text-xs text-white/50">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $movie->duration }} min
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $movie->language }}
            </span>
        </div>

        @if($movie->relationLoaded('genres') && $movie->genres->isNotEmpty())
            <div class="flex flex-wrap gap-1.5">
                @foreach($movie->genres->take(3) as $genre)
                    <span class="px-2 py-0.5 text-xs bg-white/5 text-white/60 rounded-md border border-white/10">{{ $genre->name }}</span>
                @endforeach
                @if($movie->genres->count() > 3)
                    <span class="px-2 py-0.5 text-xs text-white/40">+{{ $movie->genres->count() - 3 }}</span>
                @endif
            </div>
        @endif

        <div class="pt-2 border-t border-white/5">
            @if($nextShowtime)
                <a href="{{ route('showtimes.seats', $nextShowtime) }}"
                   class="block w-full text-center text-sm font-medium py-2.5 rounded-lg bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white hover:from-[#d42d02] hover:to-[#e09e00] transition-all duration-300 shadow-lg shadow-[#f12711]/20">
                    Book Now — ${{ number_format($nextShowtime->base_price, 2) }}
                </a>
            @else
                <a href="{{ route('movies.show', $movie) }}"
                   class="block w-full text-center text-sm font-medium py-2.5 rounded-lg border border-white/10 text-white/60 hover:bg-white/5 hover:text-white transition-all duration-300">
                    View Details
                </a>
            @endif
        </div>
    </div>
</div>
