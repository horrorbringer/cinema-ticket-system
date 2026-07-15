<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Custom Export</h3>
            <form wire:submit="export" class="space-y-4">
                {{ $this->form }}

                <div class="flex justify-end">
                    <x-filament::button type="submit" color="primary">
                        Export to Excel
                    </x-filament::button>
                </div>
            </form>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Summary</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $totalRevenue = \App\Models\Payment::where('status', 'paid')->sum('amount');
                    $totalBookings = \App\Models\Booking::count();
                    $paidBookings = \App\Models\Booking::where('status', 'confirmed')->count();
                    $todayRevenue = \App\Models\Payment::where('status', 'paid')->whereDate('created_at', today())->sum('amount');
                @endphp
                <div class="bg-white/5 rounded-lg p-4">
                    <p class="text-sm text-white/50">Total Revenue</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-white/5 rounded-lg p-4">
                    <p class="text-sm text-white/50">Total Bookings</p>
                    <p class="text-2xl font-bold text-white">{{ $totalBookings }}</p>
                </div>
                <div class="bg-white/5 rounded-lg p-4">
                    <p class="text-sm text-white/50">Paid Bookings</p>
                    <p class="text-2xl font-bold text-white">{{ $paidBookings }}</p>
                </div>
                <div class="bg-white/5 rounded-lg p-4">
                    <p class="text-sm text-white/50">Today's Revenue</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($todayRevenue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
