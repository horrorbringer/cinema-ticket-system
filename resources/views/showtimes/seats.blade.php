<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('movies.show', $showtime->movie) }}" class="text-white/50 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Select Seats — {{ $showtime->movie->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl p-6 mb-6">
                <div class="text-center mb-6">
                    <p class="text-sm text-white/50">{{ $showtime->movie->title }}</p>
                    <p class="font-semibold text-white">{{ $hall->name }}</p>
                    <p class="text-sm text-white/50">{{ $showtime->start_time->format('l, F j, Y \a\t h:i A') }}</p>
                </div>

                <div class="text-center mb-8">
                    <div class="w-3/4 h-2 bg-white/10 rounded mx-auto"></div>
                    <p class="text-xs text-white/40 mt-1">Screen</p>
                </div>

                <div id="seat-map" class="flex flex-col items-center gap-2 mb-6">
                    @foreach($seats as $row => $rowSeats)
                        <div class="flex items-center gap-2">
                            <span class="w-6 text-xs text-white/40 font-medium text-right">{{ $row }}</span>
                            <div class="flex gap-1.5">
                                @foreach($rowSeats as $seat)
                                    @php
                                        $isLocked = in_array($seat->id, $lockedSeats);
                                    @endphp
                                    <button type="button"
                                            class="seat-btn w-8 h-8 rounded-t-lg text-xs font-medium transition-colors
                                                @if(!$seat->is_active) bg-white/10 text-white/20 cursor-not-allowed
                                                @elseif($isLocked) bg-red-500/60 text-white cursor-not-allowed
                                                @else bg-emerald-500/60 hover:bg-emerald-500 text-white cursor-pointer
                                                @endif"
                                            data-seat-id="{{ $seat->id }}"
                                            data-label="{{ $seat->label }}"
                                            data-type="{{ $seat->seatType->name }}"
                                            data-price="{{ $showtime->base_price * $seat->seatType->price_multiplier }}"
                                            data-locked="{{ $isLocked ? 'true' : 'false' }}"
                                            @if(!$seat->is_active || $isLocked) disabled @endif>
                                        {{ $seat->number }}
                                    </button>
                                @endforeach
                            </div>
                            <span class="w-6 text-xs text-white/40 font-medium">{{ $row }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-center gap-6 mb-6 text-xs text-white/50">
                    <div class="flex items-center gap-1">
                        <span class="w-4 h-4 rounded bg-emerald-500/60 inline-block"></span>
                        Available
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-4 h-4 rounded bg-[#f5af19] inline-block"></span>
                        Selected
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-4 h-4 rounded bg-red-500/60 inline-block"></span>
                        Taken
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-4 h-4 rounded bg-white/10 inline-block"></span>
                        Disabled
                    </div>
                </div>

                <div id="booking-summary" class="border-t border-white/10 pt-4">
                    <div class="text-center text-white/50 py-4">
                        Select seats to see booking summary
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectedSeats = new Map();
                const showtimeId = {{ $showtime->id }};
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                function getSeatButton(seatId) {
                    return document.querySelector(`.seat-btn[data-seat-id="${seatId}"]`);
                }

                function markLocked(seatId) {
                    const btn = getSeatButton(seatId);
                    if (!btn || btn.dataset.selected === 'true') return;
                    btn.dataset.locked = 'true';
                    btn.disabled = true;
                    btn.classList.remove('bg-emerald-500/60', 'hover:bg-emerald-500', 'bg-[#f5af19]', 'hover:bg-[#e09e00]');
                    btn.classList.add('bg-red-500/60', 'cursor-not-allowed');
                }

                function markAvailable(seatId) {
                    const btn = getSeatButton(seatId);
                    if (!btn || btn.dataset.selected === 'true') return;
                    btn.dataset.locked = 'false';
                    btn.disabled = false;
                    btn.classList.remove('bg-red-500/60', 'bg-[#f5af19]', 'hover:bg-[#e09e00]', 'cursor-not-allowed');
                    btn.classList.add('bg-emerald-500/60', 'hover:bg-emerald-500', 'cursor-pointer');
                }

                function toggleSeat(btn) {
                    const id = btn.dataset.seatId;
                    const label = btn.dataset.label;
                    const price = parseFloat(btn.dataset.price);

                    if (selectedSeats.has(id)) {
                        selectedSeats.delete(id);
                        btn.dataset.selected = 'false';
                        btn.classList.remove('bg-[#f5af19]', 'hover:bg-[#e09e00]');
                        btn.classList.add('bg-emerald-500/60', 'hover:bg-emerald-500');

                        @auth
                        fetch('{{ route('seats.release', ['showtime' => $showtime, 'seat' => '__SEAT__']) }}'.replace('__SEAT__', id), {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken }
                        });
                        @endauth
                    } else {
                        selectedSeats.set(id, { label, price });
                        btn.dataset.selected = 'true';
                        btn.classList.remove('bg-emerald-500/60', 'hover:bg-emerald-500');
                        btn.classList.add('bg-[#f5af19]', 'hover:bg-[#e09e00]');

                        @auth
                        fetch('{{ route('seats.lock', ['showtime' => $showtime, 'seat' => '__SEAT__']) }}'.replace('__SEAT__', id), {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken }
                        });
                        @endauth
                    }

                    updateSummary();
                }

                document.querySelectorAll('.seat-btn:not([disabled])').forEach(btn => {
                    btn.addEventListener('click', () => toggleSeat(btn));
                });

                @auth
                if (window.Echo) {
                    window.Echo.channel(`showtime.${showtimeId}`)
                        .listen('.seat.locked', (e) => {
                            markLocked(e.seatId);
                            if (selectedSeats.has(e.seatId)) {
                                selectedSeats.delete(e.seatId);
                                updateSummary();
                            }
                        })
                        .listen('.seat.unlocked', (e) => {
                            markAvailable(e.seatId);
                        });
                }
                @endauth

                function updateSummary() {
                    const summary = document.getElementById('booking-summary');
                    if (selectedSeats.size === 0) {
                        summary.innerHTML = `<div class="text-center text-white/50 py-4">Select seats to see booking summary</div>`;
                        return;
                    }

                    let total = 0;
                    const labels = [];
                    selectedSeats.forEach((data) => {
                        labels.push(data.label);
                        total += data.price;
                    });

                    const seatIds = Array.from(selectedSeats.keys()).join(',');

                    summary.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-white/50">Selected Seats</p>
                                <p class="font-semibold text-lg text-white">${labels.join(', ')}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-white/50">Total</p>
                                <p class="font-bold text-2xl text-[#f5af19]">$${total.toFixed(2)}</p>
                                <p class="text-xs text-white/40">${selectedSeats.size} seat(s)</p>
                            </div>
                        </div>
                        <div class="mt-4 text-right">
                            @auth
                                <form action="{{ route('bookings.checkout', $showtime) }}" method="GET" class="inline">
                                    ${Array.from(selectedSeats.keys()).map(id => `<input type="hidden" name="seats[]" value="${id}">`).join('')}
                                    <button type="submit"
                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white rounded-lg hover:from-[#d42d02] hover:to-[#e09e00] font-semibold transition-all">
                                        Proceed to Checkout
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#f12711] to-[#f5af19] text-white rounded-lg hover:from-[#d42d02] hover:to-[#e09e00] font-semibold transition-all">
                                    Login to Book
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    `;
                }
            });
        </script>
    @endpush
</x-app-layout>
