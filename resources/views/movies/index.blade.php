@php use App\Models\Movie; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Now Showing') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl p-6 mb-8">
            <form id="movie-filters"
                  hx-get="{{ route('movies.index') }}"
                  hx-target="#movie-grid"
                  hx-trigger="input delay:300ms from:#search, change from:select, change from:#status-select, change from:#date-filter"
                  hx-indicator="#loading"
                  class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-white/30 group-focus-within:text-[#f5af19] transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.76l4.7 4.7a1 1 0 01-1.42 1.42l-4.7-4.7A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <x-form-input
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        class="pl-10 pr-4 py-3"
                        placeholder="Search movies..."
                    />
                </div>

                <div class="relative group">
                    <x-form-input
                        name="genre"
                        id="genre-select"
                        :label="false"
                        class="appearance-none pr-10 py-3"
                    >
                        <option value="">All Genres</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->slug }}" @selected(request('genre') === $genre->slug)>{{ $genre->name }}</option>
                        @endforeach
                    </x-form-input>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-white/30 group-focus-within:text-[#f5af19] transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="relative group">
                    <x-form-input
                        name="language"
                        id="language-select"
                        :label="false"
                        class="appearance-none pr-10 py-3"
                    >
                        <option value="">All Languages</option>
                        @foreach($languages as $lang)
                            <option value="{{ $lang }}" @selected(request('language') === $lang)>{{ $lang }}</option>
                        @endforeach
                    </x-form-input>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-white/30 group-focus-within:text-[#f5af19] transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="relative group">
                    <x-form-input
                        name="status"
                        id="status-select"
                        :label="false"
                        class="appearance-none pr-10 py-3"
                    >
                        <option value="">All</option>
                        <option value="now_showing" @selected(request('status') === 'now_showing')>Now Showing</option>
                        <option value="coming_soon" @selected(request('status') === 'coming_soon')>Coming Soon</option>
                    </x-form-input>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-white/30 group-focus-within:text-[#f5af19] transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="relative group">
                    <x-form-input
                        name="premiere_date"
                        id="date-filter"
                        :label="false"
                        value="{{ request('premiere_date') }}"
                        class="pr-10 py-3"
                        placeholder="2024-12-31"
                    />
                    @if(request('premiere_date'))
                        <button type="button" onclick="document.getElementById('date-filter').value = ''; this.form.submit()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/40 hover:text-white/60 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>

                @if(request()->anyFilled(['search', 'genre', 'language', 'status', 'premiere_date']))
                    <div class="flex items-end">
                        <a href="{{ route('movies.index') }}" class="inline-flex items-center px-4 py-3 border border-white/20 rounded-lg shadow-sm text-sm font-medium text-white/70 bg-white/5 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#f5af19] focus:ring-offset-[#0a0a0a] transition-all duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear Filters
                        </a>
                    </div>
                @endif
            </form>

            <div id="loading" class="text-center py-6 hidden">
                <div class="inline-flex items-center justify-center">
                    <svg class="animate-spin h-8 w-8 text-[#f5af19] mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-white/60 font-medium">Loading movies...</span>
                </div>
            </div>

            <div id="movie-grid" class="mt-8">
                @include('movies.partials.movie-grid')
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.body.addEventListener('htmx:beforeSwap', function(evt) {
                if (evt.detail.target.id === 'movie-grid') {
                }
            });
        </script>
    @endpush
</x-app-layout>
