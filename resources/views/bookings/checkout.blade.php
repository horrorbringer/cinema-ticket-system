<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Checkout</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $showtime->movie->title }}</h3>
                    <p class="text-sm text-white/50 mb-1">{{ $showtime->hall->name }}</p>
                    <p class="text-sm text-white/50 mb-6">{{ $showtime->start_time->format('l, F j, Y \a\t h:i A') }}</p>

                    <form action="{{ route('bookings.store', $showtime) }}" method="POST">
                        @csrf
                        <table class="w-full mb-6">
                            <thead>
                                <tr class="border-b border-white/10 text-left text-sm text-white/50">
                                    <th class="pb-2">Seat</th>
                                    <th class="pb-2">Type</th>
                                    <th class="pb-2 text-right">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seats as $seat)
                                    <tr class="border-b border-white/10">
                                        <td class="py-2 text-white/80">{{ $seat->label }}</td>
                                        <td class="py-2 text-white/60">{{ $seat->seatType->name }}</td>
                                        <td class="py-2 text-right text-white/80">${{ number_format($showtime->base_price * $seat->seatType->price_multiplier, 2) }}</td>
                                    </tr>
                                    <input type="hidden" name="seats[]" value="{{ $seat->id }}">
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-semibold text-lg text-white">
                                    <td colspan="2" class="pt-4">Total</td>
                                    <td class="pt-4 text-right">${{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('showtimes.seats', $showtime) }}" class="text-[#f5af19] hover:text-[#e09e00]">
                                &larr; Change Seats
                            </a>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white rounded-lg hover:from-[#d42d02] hover:to-[#e09e00] font-semibold transition-all">
                                Lock Seats &amp; Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
