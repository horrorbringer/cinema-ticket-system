<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">My Bookings</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl overflow-hidden divide-y divide-white/10">
                @forelse($bookings as $booking)
                    <a href="{{ route('bookings.show', $booking) }}" class="block p-4 hover:bg-white/5 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="font-semibold text-white">{{ $booking->showtime->movie->title }}</p>
                                    <p class="text-sm text-white/50">
                                        {{ $booking->showtime->start_time->format('M j, Y h:i A') }}
                                        &middot; {{ $booking->showtime->hall->name }}
                                    </p>
                                    <p class="text-sm text-white/40">
                                        Seats: {{ $booking->items->pluck('seat.label')->join(', ') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($booking->status === 'confirmed') bg-green-500/20 text-green-400
                                    @elseif($booking->status === 'pending') bg-yellow-500/20 text-yellow-400
                                    @elseif($booking->status === 'refunded') bg-purple-500/20 text-purple-400
                                    @else bg-red-500/20 text-red-400 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                <p class="text-sm font-semibold text-white mt-1">${{ number_format($booking->total_amount, 2) }}</p>
                                <p class="text-xs text-white/40">{{ $booking->booking_number }}</p>
                            </div>
                        </div>
                        @if($booking->status === 'confirmed')
                            <div class="mt-2">
                                <span class="text-sm text-[#f5af19]">Download Tickets &rarr;</span>
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="p-12 text-center text-white/50">
                        <svg class="w-16 h-16 mx-auto text-white/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <p class="text-lg">No bookings yet</p>
                        <a href="{{ route('movies.index') }}" class="text-[#f5af19] hover:text-[#e09e00] mt-2 inline-block">
                            Browse Movies &rarr;
                        </a>
                    </div>
                @endforelse
            </div>

            @if(method_exists($bookings, 'hasPages') && $bookings->hasPages())
                <div class="mt-6" id="bookings-pagination">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
