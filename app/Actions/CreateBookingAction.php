<?php

namespace App\Actions;

use App\Models\Showtime;
use App\Models\User;
use App\Services\BookingService;
use App\Services\SeatLockService;

class CreateBookingAction
{
    public function __construct(
        private BookingService $bookingService,
        private SeatLockService $seatLockService,
    ) {}

    public function execute(User $user, Showtime $showtime, array $seatIds): array
    {
        $seatData = [];

        foreach ($seatIds as $seatId) {
            $seat = $showtime->hall->seats()->findOrFail($seatId);

            if (!$this->seatLockService->isLocked($showtime, $seat)) {
                return ['success' => false, 'message' => "Seat {$seat->label} is no longer available."];
            }

            $holderId = $this->seatLockService->getLockHolder($showtime, $seat);
            if ($holderId !== $user->id) {
                return ['success' => false, 'message' => "Seat {$seat->label} is locked by another user."];
            }

            $seatData[] = [
                'seat_id' => $seat->id,
                'label' => $seat->label,
                'price' => $showtime->base_price * $seat->seatType->price_multiplier,
            ];
        }

        $booking = $this->bookingService->createPendingBooking($user, $showtime, $seatData);

        return ['success' => true, 'booking' => $booking];
    }
}
