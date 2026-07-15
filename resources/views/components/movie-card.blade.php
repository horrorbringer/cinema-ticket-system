@props(['movie'])

<div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl overflow-hidden hover:border-white/20 hover:shadow-xl hover:shadow-black/30 transition-all duration-300 group">
    <div class="relative">
        <a href="{{ route('movies.show', $movie) }}" class="block">
            @php
    $posterUrl = $movie->poster
        ? (str_starts_with($movie->poster, 'http') ? $movie->poster : asset('storage/' . $movie->poster))
        : 'https://picsum.photos/seed/' . $movie->id . '/300/450';
@endphp
                <img src="{{ $posterUrl }}" alt="{{ $movie->title }}"
                     class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105"
                     loading="lazy"
                     onerror="this.parentElement.innerHTML = `<div class='w-full h-64 bg-gradient-to-br from-white/5 to-white/10 flex items-center justify-center'><svg class='w-16 h-16 text-white/20' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z'/></svg></div>`">
        </a>
        <div class="absolute top-2 right-2">
            <x-badge :type="$movie->status === 'now_showing' ? 'success' : 'warning'" size="sm">
                {{ $movie->status === 'now_showing' ? 'Now Showing' : 'Coming Soon' }}
            </x-badge>
        </div>
    </div>
    <div class="p-5">
        <h3 class="font-bold text-lg mb-2 line-clamp-1 group-hover:text-[#f5af19] transition-colors">
            <a href="{{ route('movies.show', $movie) }}">{{ $movie->title }}</a>
        </h3>
        <div class="flex items-center gap-3 text-sm text-white/50 mb-3">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $movie->duration }} min</span>
            </div>
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $movie->language }}</span>
            </div>
        </div>
        <div class="flex items-center justify-between">
            @if($movie->rating > 0)
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-[#f5af19]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-sm font-medium text-white/80">{{ $movie->rating }}</span>
                </div>
            @endif
            <a href="{{ route('movies.show', $movie) }}" class="text-sm text-[#f5af19]/70 hover:text-[#f5af19] font-medium transition-colors opacity-0 group-hover:opacity-100">
                View Details &rarr;
            </a>
        </div>
    </div>
</div>
