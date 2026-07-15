<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Booking Confirmed</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 text-center">
                    <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-green-400 mb-2">Payment Successful!</h3>
                    <p class="text-white/50 mb-6">Booking #{{ $booking->booking_number }}</p>

                    <div class="bg-white/5 rounded-xl p-6 text-left mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-white/50">Movie</p>
                                <p class="font-semibold text-white">{{ $booking->showtime->movie->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/50">Hall</p>
                                <p class="font-semibold text-white">{{ $booking->showtime->hall->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/50">Date</p>
                                <p class="font-semibold text-white">{{ $booking->showtime->start_time->format('l, F j, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/50">Time</p>
                                <p class="font-semibold text-white">{{ $booking->showtime->start_time->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/50">Seats</p>
                                <p class="font-semibold text-white">{{ $booking->items->pluck('seat.label')->join(', ') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-white/50">Total Paid</p>
                                <p class="font-semibold text-[#f5af19]">${{ number_format($booking->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center gap-4">
                        <a href="{{ route('bookings.tickets', $booking) }}"
                           class="px-6 py-3 bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white rounded-lg hover:from-[#d42d02] hover:to-[#e09e00] font-semibold inline-flex items-center transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Tickets (PDF)
                        </a>
                        <a href="{{ route('bookings.history') }}"
                           class="px-6 py-3 border border-white/20 rounded-lg hover:bg-white/10 font-semibold text-white/70 hover:text-white transition-all">
                            View My Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
