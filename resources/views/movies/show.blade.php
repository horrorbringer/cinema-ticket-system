<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('movies.index') }}" class="text-white/50 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ $movie->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/3">
                        @php
                            $posterUrl = $movie->poster
                                ? (str_starts_with($movie->poster, 'http') ? $movie->poster : asset('storage/' . $movie->poster))
                                : 'https://picsum.photos/seed/' . $movie->id . '/400/600';
                        @endphp
                            <img src="{{ $posterUrl }}" alt="{{ $movie->title }}" class="w-full h-full object-cover"
                                 loading="lazy"
                                 onerror="this.parentElement.innerHTML = `<div class='w-full h-96 bg-white/5 flex items-center justify-center'><svg class='w-24 h-24 text-white/20' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z'/></svg></div>`">
                    </div>
                    <div class="p-8 md:w-2/3">
                        <h1 class="text-3xl font-bold text-white mb-4">{{ $movie->title }}</h1>

                        <div class="flex flex-wrap gap-4 text-sm text-white/60 mb-4">
                            <span class="px-3 py-1 bg-[#f5af19]/20 text-[#f5af19] rounded-full">{{ $movie->duration }} min</span>
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full">{{ $movie->language }}</span>
                            @foreach($movie->genres as $genre)
                                <span class="px-3 py-1 bg-white/10 text-white/70 rounded-full">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        @if($movie->rating > 0)
                            <div class="flex items-center mb-4">
                                <svg class="w-5 h-5 text-[#f5af19]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="ml-1 text-lg font-semibold text-white">{{ $movie->rating }}/10</span>
                            </div>
                        @endif

                        <p class="text-white/60 mb-6">{{ $movie->description }}</p>

                        @if($movie->trailer)
                            <a href="{{ $movie->trailer }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600/80 text-white rounded-lg hover:bg-red-600 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                Watch Trailer
                            </a>
                        @endif

                        @if(count($showtimesByDate) > 0)
                            <div class="mt-8 border-t border-white/10 pt-6">
                                <h3 class="text-xl font-semibold text-white mb-4">Showtimes</h3>
                                @foreach($showtimesByDate as $date => $showtimes)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-white/50 mb-2">
                                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                        </h4>
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($showtimes as $showtime)
                                                <a href="{{ route('showtimes.seats', $showtime) }}"
                                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#f12711]/80 to-[#f5af19]/80 text-white rounded-lg hover:from-[#f12711] hover:to-[#f5af19] text-sm transition-all">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $showtime->start_time->format('h:i A') }}
                                                    <span class="ml-2 text-white/60">${{ number_format($showtime->base_price, 2) }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-8 border-t border-white/10 pt-6">
                                <p class="text-white/50">No showtimes available yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
