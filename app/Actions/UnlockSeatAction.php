<?php

namespace App\Actions;

use App\Models\Seat;
use App\Models\Showtime;
use App\Services\SeatLockService;

class UnlockSeatAction
{
    public function __construct(private SeatLockService $seatLockService) {}

    public function execute(Showtime $showtime, Seat $seat): void
    {
        $this->seatLockService->release($showtime, $seat);
    }
}
