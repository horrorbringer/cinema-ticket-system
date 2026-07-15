<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">Booking #{{ $booking->booking_number }}</h2>
            <a href="{{ route('bookings.history') }}" class="text-sm text-[#f5af19] hover:text-[#e09e00]">&larr; Back to Bookings</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $booking->showtime->movie->title }}</h3>
                            <p class="text-white/50">{{ $booking->showtime->hall->name }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($booking->status === 'confirmed') bg-green-500/20 text-green-400
                            @elseif($booking->status === 'pending') bg-yellow-500/20 text-yellow-400
                            @elseif($booking->status === 'refunded') bg-purple-500/20 text-purple-400
                            @else bg-red-500/20 text-red-400 @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="bg-white/5 rounded-xl p-6 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-white/50">Date</p>
                                <p class="font-semibold text-white">{{ $booking->showtime->start_time->format('l, F j, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/50">Time</p>
                                <p class="font-semibold text-white">{{ $booking->showtime->start_time->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/50">Seats ({{ $booking->items->count() }})</p>
                                <p class="font-semibold text-white">{{ $booking->items->pluck('seat.label')->join(', ') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/50">Total</p>
                                <p class="font-semibold text-[#f5af19]">${{ number_format($booking->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    @if($booking->payment)
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h4 class="font-semibold text-white mb-3">Payment</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-white/50">Transaction ID</p>
                                    <p class="font-semibold text-sm text-white/80">{{ $booking->payment->transaction_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-white/50">Status</p>
                                    <p class="font-semibold
                                        @if($booking->payment->status === 'paid') text-green-400
                                        @elseif($booking->payment->status === 'refunded') text-purple-400
                                        @else text-red-400 @endif">
                                        {{ ucfirst($booking->payment->status) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($booking->tickets->isNotEmpty())
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h4 class="font-semibold text-white mb-3">Tickets</h4>
                            <div class="space-y-2">
                                @foreach($booking->tickets as $ticket)
                                    <div class="flex items-center justify-between py-2 border-b border-white/10 last:border-0">
                                        <div>
                                            <p class="font-semibold text-white">{{ $ticket->ticket_number }}</p>
                                            <p class="text-sm text-white/50">
                                                Status: <span class="
                                                    @if($ticket->status === 'active') text-green-400
                                                    @else text-red-400 @endif">{{ ucfirst($ticket->status) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <div class="space-x-3">
                            @if($booking->status === 'confirmed')
                                <a href="{{ route('bookings.tickets', $booking) }}"
                                   class="px-4 py-2 bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white rounded-lg hover:from-[#d42d02] hover:to-[#e09e00] font-semibold inline-flex items-center transition-all">
                                    Download Tickets
                                </a>
                            @endif
                        </div>

                        @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->showtime->start_time->isFuture())
                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                                  onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 font-semibold transition-colors">
                                    Cancel Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
