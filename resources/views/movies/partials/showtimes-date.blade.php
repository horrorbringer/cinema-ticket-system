<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
    @forelse($showtimes as $showtime)
        <a href="{{ route('showtimes.seats', $showtime) }}"
           class="group relative bg-white/[0.03] border border-white/10 rounded-xl p-4 hover:border-[#f5af19]/40 hover:bg-white/[0.06] transition-all duration-300 text-center">
            <div class="text-lg font-bold text-white group-hover:text-[#f5af19] transition-colors">
                {{ $showtime->start_time->format('h:i A') }}
            </div>
            <div class="text-xs text-white/40 mt-1">{{ $showtime->end_time->format('h:i A') }}</div>
            <div class="text-xs text-white/50 mt-2">{{ $showtime->hall?->name ?? 'Main Hall' }}</div>
            <div class="mt-3 text-sm font-semibold text-[#f5af19]">
                ${{ number_format($showtime->base_price, 2) }}
            </div>
            <div class="absolute inset-0 rounded-xl ring-1 ring-inset ring-transparent group-hover:ring-[#f5af19]/20 transition-all pointer-events-none"></div>
        </a>
    @empty
        <div class="col-span-full text-center py-8 text-white/40">
            No showtimes available for this date.
        </div>
    @endforelse
</div>
