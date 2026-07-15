<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\SeatLockService;
use Illuminate\Console\Command;

class CleanExpiredBookings extends Command
{
    protected $signature = 'bookings:clean-expired';
    protected $description = 'Cancel expired pending bookings and release seat locks';

    public function handle(SeatLockService $seatLockService): int
    {
        $expired = Booking::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expired as $booking) {
            $booking->load('items.seat');
            foreach ($booking->items as $item) {
                $seatLockService->release($booking->showtime, $item->seat);
            }
            $booking->update(['status' => 'cancelled']);
            $count++;
        }

        $this->info("Cancelled {$count} expired booking(s).");

        return self::SUCCESS;
    }
}
