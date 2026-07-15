<x-app-layout>
    @php
        $backdropUrl = $movie->poster
            ? (str_starts_with($movie->poster, 'http') ? $movie->poster : asset('storage/' . $movie->poster))
            : 'https://picsum.photos/seed/' . $movie->id . '/1200/600';
        $isLoggedIn = auth()->check();
        $userReview = $isLoggedIn ? $movie->approvedReviews->where('user_id', auth()->id())->first() : null;
        $dateKeys = array_keys($showtimesByDate->toArray());
        $initialDate = $dateKeys[0] ?? null;
    @endphp

    {{-- Hero Section with Backdrop --}}
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-[#0a0a0a]/80 to-transparent z-10"></div>
        <div class="absolute inset-0 opacity-30">
            <img src="{{ $backdropUrl }}" alt=""
                 class="w-full h-full object-cover blur-sm scale-110"
                 onerror="this.style.display='none'">
        </div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 text-white/50 hover:text-white transition-colors mb-6 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Movies
            </a>

            <div class="md:flex gap-8 items-start">
                {{-- Poster --}}
                <div class="w-48 md:w-64 flex-shrink-0 mx-auto md:mx-0 mb-6 md:mb-0">
                    <div class="aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl shadow-black/50 border border-white/10">
                        <img src="{{ $backdropUrl }}" alt="{{ $movie->title }}"
                             class="w-full h-full object-cover"
                             onerror="this.outerHTML = `<div class='w-full h-full bg-gradient-to-br from-white/5 to-white/10 flex items-center justify-center'><svg class='w-16 h-16 text-white/20' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z'/></svg></div>`">
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-wrap items-center gap-2 justify-center md:justify-start mb-2">
                        @if($movie->status === 'now_showing')
                            <span class="px-3 py-1 text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30 rounded-full">Now Showing</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold bg-[#f5af19]/20 text-[#f5af19] border border-[#f5af19]/30 rounded-full">Coming {{ $movie->release_date?->format('M d, Y') ? $movie->release_date->format('M d, Y') : 'Soon' }}</span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-5xl font-bold text-white mb-3">{{ $movie->title }}</h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-white/60 mb-4 justify-center md:justify-start">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $movie->duration }} min
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $movie->language }}
                        </span>
                        @if($movie->release_date)
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $movie->release_date->format('M d, Y') }}
                            </span>
                        @endif
                    </div>

                    {{-- Genres --}}
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start mb-4">
                        @foreach($movie->genres as $genre)
                            <span class="px-3 py-1 text-xs bg-white/5 text-white/60 border border-white/10 rounded-full">{{ $genre->name }}</span>
                        @endforeach
                    </div>

                    {{-- Rating --}}
                    @if($movie->rating > 0)
                        <div class="flex items-center gap-3 justify-center md:justify-start mb-4">
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($movie->rating / 2) ? 'text-[#f5af19]' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-white/60 text-sm"><span class="text-white font-semibold">{{ number_format($movie->rating, 1) }}</span>/10</span>
                            @if($averageRating)
                                <span class="text-white/40 text-sm">· Avg {{ number_format($averageRating, 1) }}/5 from reviews</span>
                            @endif
                        </div>
                    @endif

                    {{-- Description --}}
                    <p class="text-white/60 leading-relaxed max-w-2xl mx-auto md:mx-0 mb-6">{{ $movie->description }}</p>

                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center gap-3 justify-center md:justify-start">
                        @if($movie->trailer)
                            <button onclick="openTrailer('{{ $movie->trailer }}')"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 border border-white/10 text-white rounded-xl hover:bg-white/20 transition-all text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                Watch Trailer
                            </button>
                        @endif
                        @auth
                            <a href="{{ route('bookings.history') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 text-white/70 rounded-xl hover:bg-white/10 hover:text-white transition-all text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                My Bookings
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10 pb-24 md:pb-8">

        {{-- Showtimes --}}
        <div id="showtimes">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-[#f5af19]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Showtimes
            </h2>

            @if(count($showtimesByDate) > 0)
                {{-- Date tabs carousel --}}
                <div class="flex gap-2 overflow-x-auto pb-2 mb-6 scrollbar-thin snap-x snap-mandatory" id="date-tabs" style="scrollbar-width: thin;">
                    @foreach($showtimesByDate as $date => $showtimes)
                        @php $dt = \Carbon\Carbon::parse($date); @endphp
                        <button hx-get="{{ route('movies.showtimes-date', ['movie' => $movie, 'date' => $date]) }}"
                                hx-target="#showtimes-grid"
                                hx-indicator="#showtimes-loading"
                                hx-swap="innerHTML"
                                @class([
                                    'flex-shrink-0 px-4 py-3 rounded-xl text-sm font-medium border transition-all duration-200 text-center min-w-[80px] snap-start',
                                    'bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white shadow-lg shadow-[#f12711]/20 border-transparent' => $loop->first,
                                    'bg-white/5 text-white/60 hover:bg-white/10 border-white/10' => !$loop->first,
                                ])>
                            <div class="text-xs opacity-70">{{ $dt->format('D') }}</div>
                            <div class="text-lg font-bold">{{ $dt->format('d') }}</div>
                            <div class="text-xs opacity-70">{{ $dt->format('M') }}</div>
                        </button>
                    @endforeach
                </div>

                {{-- Showtimes grid --}}
                <div id="showtimes-loading" class="hidden text-center py-4">
                    <svg class="animate-spin h-6 w-6 text-[#f5af19] mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <div id="showtimes-grid">
                    @if($initialDate)
                        @php
                            $initialShowtimes = $showtimesByDate[$initialDate] ?? collect();
                        @endphp
                        @include('movies.partials.showtimes-date', ['showtimes' => $initialShowtimes])
                    @endif
                </div>

                <script>
                    document.body.addEventListener('htmx:beforeSwap', function(evt) {
                        if (evt.detail.target.id === 'showtimes-grid') {
                            document.querySelectorAll('#date-tabs button').forEach(function(btn) {
                                btn.classList.remove('bg-gradient-to-r', 'from-[#f12711]', 'to-[#f5af19]', 'text-white', 'shadow-lg', 'shadow-[#f12711]/20', 'border-transparent');
                                btn.classList.add('bg-white/5', 'text-white/60', 'hover:bg-white/10', 'border-white/10');
                            });
                            if (evt.detail.elt) {
                                evt.detail.elt.classList.remove('bg-white/5', 'text-white/60', 'hover:bg-white/10', 'border-white/10');
                                evt.detail.elt.classList.add('bg-gradient-to-r', 'from-[#f12711]', 'to-[#f5af19]', 'text-white', 'shadow-lg', 'shadow-[#f12711]/20', 'border-transparent');
                            }
                        }
                    });
                </script>
            @else
                <div class="bg-white/[0.03] border border-white/10 rounded-xl p-8 text-center">
                    <svg class="w-12 h-12 text-white/20 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-white/60">No showtimes available yet. Check back soon!</p>
                </div>
            @endif
        </div>

        {{-- More Like This – carousel --}}
        <div id="related-movies">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#f5af19]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                    </svg>
                    More Like This
                </h2>
            </div>

            <div hx-get="{{ route('movies.related', $movie) }}"
                 hx-trigger="load"
                 hx-swap="innerHTML"
                 hx-indicator="#related-loading"
                 id="related-container">
                <div id="related-loading" class="flex gap-4 overflow-x-auto pb-4">
                    @for($i = 0; $i < 5; $i++)
                        <div class="flex-shrink-0 w-44 animate-pulse">
                            <div class="bg-white/5 rounded-xl overflow-hidden">
                                <div class="aspect-[2/3] bg-white/10"></div>
                                <div class="p-4 space-y-2">
                                    <div class="h-3 bg-white/10 rounded w-3/4"></div>
                                    <div class="h-2 bg-white/10 rounded w-1/2"></div>
                                    <div class="h-8 bg-white/10 rounded-lg mt-3"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Reviews --}}
        <div id="reviews">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-[#f5af19]" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Reviews
                @if($movie->approvedReviews->count() > 0)
                    <span class="text-sm font-normal text-white/40">({{ $movie->approvedReviews->count() }})</span>
                @endif
            </h2>

            @auth
                <div class="bg-white/[0.03] border border-white/10 rounded-xl p-6 mb-6">
                    <h3 class="text-white font-medium mb-4">{{ $userReview ? 'Edit your review' : 'Write a review' }}</h3>
                    <form action="{{ route('reviews.store', $movie) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-white/60 mb-2">Rating</label>
                            <div class="flex gap-1" id="star-rating" data-rating="{{ $userReview->rating ?? 0 }}">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" data-value="{{ $i }}"
                                            class="star-btn w-8 h-8 hover:scale-110 transition-transform focus:outline-none {{ $i <= ($userReview->rating ?? 0) ? 'text-[#f5af19]' : 'text-white/20' }}">
                                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                @endfor
                                <input type="hidden" name="rating" id="rating-input" value="{{ $userReview->rating ?? 0 }}">
                            </div>
                        </div>
                        <div>
                            <label for="review" class="block text-sm text-white/60 mb-2">Your Review (optional)</label>
                            <textarea name="review" id="review" rows="3"
                                      class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-[#f5af19]/50 focus:border-[#f5af19]/50 transition-all resize-none"
                                      placeholder="Share your thoughts...">{{ old('review', $userReview->review ?? '') }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white font-medium rounded-xl hover:from-[#d42d02] hover:to-[#e09e00] transition-all text-sm shadow-lg shadow-[#f12711]/20">
                                {{ $userReview ? 'Update Review' : 'Submit Review' }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white/[0.03] border border-white/10 rounded-xl p-6 mb-6 text-center">
                    <p class="text-white/60 text-sm">
                        <a href="{{ route('login') }}" class="text-[#f5af19] hover:underline">Sign in</a> to leave a review.
                    </p>
                </div>
            @endauth

            @if($movie->approvedReviews->count() > 0)
                <div class="space-y-4">
                    @foreach($movie->approvedReviews as $review)
                        <div class="bg-white/[0.03] border border-white/10 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#f12711] to-[#f5af19] flex items-center justify-center text-sm font-bold text-white">
                                        {{ strtoupper(substr($review->user?->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $review->user?->name ?? 'Anonymous' }}</p>
                                        <p class="text-xs text-white/40">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-[#f5af19]' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            @if($review->review)
                                <p class="text-white/70 text-sm leading-relaxed">{{ $review->review }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white/[0.03] border border-white/10 rounded-xl p-8 text-center">
                    <p class="text-white/50 text-sm">No reviews yet. Be the first to review!</p>
                </div>
            @endif
        </div>

    </div>

    {{-- Trailer Modal --}}
    <div id="trailer-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeTrailer()"></div>
        <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-4xl bg-black rounded-2xl overflow-hidden shadow-2xl shadow-black/60 border border-white/10">
                <div class="flex justify-end p-2">
                    <button onclick="closeTrailer()" class="text-white/50 hover:text-white transition-colors p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="aspect-video" id="trailer-embed"></div>
            </div>
        </div>
    </div>

    {{-- Sticky Mobile CTA --}}
    @if(count($showtimesByDate) > 0)
        <div class="fixed bottom-0 left-0 right-0 z-30 bg-[#0a0a0a]/95 backdrop-blur-lg border-t border-white/10 p-4 md:hidden">
            <a href="#showtimes"
               class="block w-full text-center py-3 bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white font-medium rounded-xl shadow-lg shadow-[#f12711]/20">
                View Showtimes
            </a>
        </div>
    @endif

    @push('scripts')
        <script>
            // Trailer modal
            function openTrailer(url) {
                const modal = document.getElementById('trailer-modal');
                const embed = document.getElementById('trailer-embed');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                if (url.includes('youtube.com') || url.includes('youtu.be')) {
                    const id = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]+)/)?.[1];
                    if (id) {
                        embed.innerHTML = `<iframe class="w-full h-full" src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0" frameborder="0" allowfullscreen allow="autoplay"></iframe>`;
                    } else {
                        embed.innerHTML = `<div class="w-full h-full flex items-center justify-center text-white/60">Unable to load trailer</div>`;
                    }
                } else {
                    embed.innerHTML = `<a href="${url}" target="_blank" class="w-full h-full flex items-center justify-center text-white bg-white/5">Open Trailer</a>`;
                }
            }

            function closeTrailer() {
                const modal = document.getElementById('trailer-modal');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                const embed = document.getElementById('trailer-embed');
                embed.innerHTML = '';
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeTrailer();
            });

            // Star rating (no Alpine)
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.star-btn');
                if (!btn) return;
                const container = document.getElementById('star-rating');
                if (!container) return;
                const rating = parseInt(btn.dataset.value);
                document.getElementById('rating-input').value = rating;
                container.querySelectorAll('.star-btn').forEach(function(sb) {
                    const val = parseInt(sb.dataset.value);
                    sb.classList.toggle('text-[#f5af19]', val <= rating);
                    sb.classList.toggle('text-white/20', val > rating);
                });
            });
        </script>
    @endpush
</x-app-layout>
