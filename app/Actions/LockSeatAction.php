<?php

namespace App\Actions;

use App\Models\Seat;
use App\Models\Showtime;
use App\Services\SeatLockService;

class LockSeatAction
{
    public function __construct(private SeatLockService $seatLockService) {}

    public function execute(Showtime $showtime, Seat $seat, int $userId): bool
    {
        if (!$seat->is_active) {
            return false;
        }

        return $this->seatLockService->lock($showtime, $seat, $userId);
    }
}
