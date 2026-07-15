<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Services\SeatLockService;

class ShowtimeController extends Controller
{
    public function seats(Showtime $showtime, SeatLockService $seatLockService)
    {
        $showtime->load('movie', 'hall.seats.seatType');

        $hall = $showtime->hall;
        $seats = $hall->seats->groupBy('row');

        $lockedSeats = collect($seatLockService->getLockedSeats($showtime))
            ->pluck('seat_id')
            ->toArray();

        return view('showtimes.seats', compact('showtime', 'hall', 'seats', 'lockedSeats'));
    }
}
