<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Payment</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6">
                    <div class="text-center mb-8">
                        <svg class="w-16 h-16 text-[#f5af19] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-white mb-2">Complete Payment</h3>
                        <p class="text-white/50">Booking: {{ $booking->booking_number }}</p>
                    </div>

                    <div class="bg-white/5 rounded-xl p-4 mb-6">
                        <div class="flex justify-between mb-2">
                            <span class="text-white/50">Movie</span>
                            <span class="font-semibold text-white">{{ $booking->showtime->movie->title }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-white/50">Seats</span>
                            <span class="font-semibold text-white">{{ $booking->items->pluck('seat.label')->join(', ') }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-white/50">Date</span>
                            <span class="font-semibold text-white">{{ $booking->showtime->start_time->format('M j, Y h:i A') }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-white/10">
                            <span class="text-lg font-bold text-white">Total</span>
                            <span class="text-lg font-bold text-[#f5af19]">${{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-4 mb-6">
                        <p class="text-sm text-yellow-300">
                            <strong>Mock Payment:</strong> This is a simulated payment gateway.
                            Clicking "Pay Now" will process a mock payment (90% success rate).
                        </p>
                    </div>

                    <form action="{{ route('bookings.process-payment', $booking) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 font-bold text-lg transition-all">
                            Pay ${{ number_format($booking->total_amount, 2) }}
                        </button>
                    </form>

                    <p class="text-center text-xs text-white/40 mt-4">
                        Your seats are locked for 10 minutes. Complete payment before they expire.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
