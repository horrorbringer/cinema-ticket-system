<div data-total="{{ $total }}">
    {{-- Now Showing --}}
    @if($nowShowing->isNotEmpty())
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-green-400 rounded-full"></div>
                <h3 class="text-xl font-bold text-white">Now Showing</h3>
                <span class="text-sm text-white/40">({{ $nowShowing->count() }})</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($nowShowing as $movie)
                    <x-movie-card :movie="$movie" />
                @endforeach
            </div>
        </div>
    @endif

    {{-- Coming Soon --}}
    @if($comingSoon->isNotEmpty())
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-[#f5af19] rounded-full"></div>
                <h3 class="text-xl font-bold text-white">Coming Soon</h3>
                <span class="text-sm text-white/40">({{ $comingSoon->count() }})</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($comingSoon as $movie)
                    <x-movie-card :movie="$movie" />
                @endforeach
            </div>
        </div>
    @endif

    {{-- Empty --}}
    @if($nowShowing->isEmpty() && $comingSoon->isEmpty())
        <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-12 text-center">
            <div class="inline-flex flex-col items-center">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-white/80 mb-2">No movies found</h3>
                <p class="text-sm text-white/50 mb-6">Try adjusting your search or filters</p>
                <a href="{{ route('movies.index') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-xl text-white bg-gradient-to-r from-[#f12711] to-[#f5af19] hover:from-[#d42d02] hover:to-[#e09e00] transition-all shadow-lg shadow-[#f12711]/20">
                    Clear all filters
                </a>
            </div>
        </div>
    @endif
</div>
