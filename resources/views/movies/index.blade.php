@php use App\Models\Movie; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Movies') }}
            </h2>
            <div class="flex items-center gap-3 text-sm">
                @if(request()->anyFilled(['search', 'genre', 'language', 'premiere_date', 'sort']))
                    <a href="{{ route('movies.index') }}" class="text-white/50 hover:text-[#f5af19] transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear Filters
                    </a>
                @endif
                <span class="text-white/30">|</span>
                <span class="text-white/50" id="result-count">{{ $total }} movies</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Featured Hero --}}
            @if(!request()->anyFilled(['search', 'genre', 'language', 'premiere_date', 'sort']) && isset($featured))
                <a href="{{ route('movies.show', $featured) }}" class="block group">
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-white/[0.05] to-white/[0.02] border border-white/10">
                        <div class="md:flex items-center">
                            @php
                                $featuredPoster = $featured->poster
                                    ? (str_starts_with($featured->poster, 'http') ? $featured->poster : asset('storage/' . $featured->poster))
                                    : 'https://picsum.photos/seed/' . $featured->id . '/600/400';
                            @endphp
                            <div class="md:w-2/5 lg:w-1/3">
                                <img src="{{ $featuredPoster }}" alt="{{ $featured->title }}"
                                     class="w-full h-64 md:h-80 object-cover transition-transform duration-700 group-hover:scale-105"
                                     loading="lazy">
                            </div>
                            <div class="p-6 md:p-10 md:w-3/5 lg:w-2/3">
                                <span class="text-xs font-semibold uppercase tracking-widest text-[#f5af19]">Featured Movie</span>
                                <h3 class="text-2xl md:text-4xl font-bold text-white mt-2 mb-3 group-hover:text-[#f5af19] transition-colors">{{ $featured->title }}</h3>
                                <div class="flex flex-wrap gap-3 text-sm text-white/60 mb-4">
                                    <span class="px-3 py-1 bg-[#f5af19]/10 text-[#f5af19] rounded-full">{{ $featured->duration }} min</span>
                                    <span class="px-3 py-1 bg-white/5 text-white/60 rounded-full">{{ $featured->language }}</span>
                                    @foreach($featured->genres->take(3) as $genre)
                                        <span class="px-3 py-1 bg-white/5 text-white/60 rounded-full">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                                @if($featured->rating > 0)
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= round($featured->rating / 2) ? 'text-[#f5af19]' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-white/60 text-sm">{{ number_format($featured->rating, 1) }}/10</span>
                                    </div>
                                @endif
                                <p class="text-white/50 line-clamp-2 mb-6">{{ $featured->description }}</p>
                                <span class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white font-medium rounded-xl hover:from-[#d42d02] hover:to-[#e09e00] transition-all duration-300 shadow-lg shadow-[#f12711]/20 group-hover:shadow-[#f12711]/40">
                                    View Details
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endif

            {{-- Filters --}}
            <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-4 md:p-6">
                <form id="movie-filters"
                      hx-get="{{ route('movies.index') }}"
                      hx-target="#movie-grid-container"
                      hx-trigger="input delay:300ms from:#search, change from:select, change from:#movie-filters, change from:[name=sort]"
                      hx-indicator="#loading"
                      class="space-y-4">

                    {{-- Search + Sort row --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Search movies..."
                                   class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-[#f5af19]/50 focus:border-[#f5af19]/50 transition-all">
                        </div>
                        <div class="relative">
                            <select name="sort"
                                    class="appearance-none w-full sm:w-auto px-4 py-3 pr-10 bg-white/5 border border-white/10 rounded-xl text-white/70 focus:outline-none focus:ring-2 focus:ring-[#f5af19]/50 focus:border-[#f5af19]/50 transition-all text-sm">
                                <option value="latest" @selected(request('sort', 'latest') === 'latest')>Latest</option>
                                <option value="rating" @selected(request('sort') === 'rating')>Highest Rated</option>
                                <option value="title" @selected(request('sort') === 'title')>A–Z</option>
                                <option value="release_date" @selected(request('sort') === 'release_date')>Release Date</option>
                                <option value="duration" @selected(request('sort') === 'duration')>Duration</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Genre pills + Language --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-white/40 font-medium uppercase tracking-wider mr-1">Genres</span>
                        <button type="button"
                                onclick="this.closest('form').querySelector('[name=genre]').value = ''; this.closest('form').dispatchEvent(new Event('change'))"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200 {{ !request('genre') ? 'bg-[#f5af19]/20 text-[#f5af19] border border-[#f5af19]/30' : 'bg-white/5 text-white/50 hover:bg-white/10 border border-white/10' }}">
                            All
                        </button>
                        @foreach($genres as $genre)
                            <button type="button"
                                    onclick="this.closest('form').querySelector('[name=genre]').value = '{{ $genre->slug }}'; this.closest('form').dispatchEvent(new Event('change'))"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200 {{ request('genre') === $genre->slug ? 'bg-[#f5af19]/20 text-[#f5af19] border border-[#f5af19]/30' : 'bg-white/5 text-white/50 hover:bg-white/10 border border-white/10' }}">
                                {{ $genre->name }}
                            </button>
                        @endforeach
                        <input type="hidden" name="genre" value="{{ request('genre') }}">

                        <span class="w-px h-6 bg-white/10 mx-2 hidden sm:block"></span>

                        <select name="language" class="appearance-none px-3 py-1.5 text-xs bg-white/5 border border-white/10 rounded-lg text-white/50 focus:outline-none focus:ring-2 focus:ring-[#f5af19]/50 transition-all">
                            <option value="">All Languages</option>
                            @foreach($languages as $lang)
                                <option value="{{ $lang }}" @selected(request('language') === $lang)>{{ $lang }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                {{-- Loading skeleton --}}
                <div id="loading" class="hidden">
                    <div class="space-y-8 mt-6">
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-1 h-6 bg-white/10 rounded-full"></div>
                                <div class="h-5 bg-white/10 rounded w-32"></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @for($i = 0; $i < 4; $i++)
                                    <div class="animate-pulse">
                                        <div class="bg-white/5 rounded-xl overflow-hidden">
                                            <div class="aspect-[2/3] bg-white/10"></div>
                                            <div class="p-4 space-y-3">
                                                <div class="h-4 bg-white/10 rounded w-3/4"></div>
                                                <div class="h-3 bg-white/10 rounded w-1/2"></div>
                                                <div class="h-3 bg-white/10 rounded w-1/3"></div>
                                                <div class="h-9 bg-white/10 rounded-lg mt-4"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Movie grid --}}
                <div id="movie-grid-container" class="mt-6">
                    @include('movies.partials.movie-grid')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.body.addEventListener('htmx:beforeSwap', function(evt) {
                if (evt.detail.target.id === 'movie-grid-container') {
                    const count = evt.detail.serverResponse.match(/data-total="(\d+)"/);
                    const el = document.getElementById('result-count');
                    if (count && el) el.textContent = count[1] + ' movies';
                }
            });
        </script>
    @endpush
</x-app-layout>
