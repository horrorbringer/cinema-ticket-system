<?php

namespace App\Http\Controllers;

use App\Actions\LockSeatAction;
use App\Actions\UnlockSeatAction;
use App\Models\Seat;
use App\Models\Showtime;
use App\Services\SeatLockService;
use Illuminate\Http\Request;

class SeatLockController extends Controller
{
    public function lock(Showtime $showtime, Seat $seat, Request $request, LockSeatAction $lockSeat)
    {
        $locked = $lockSeat->execute($showtime, $seat, $request->user()->id);

        return response()->json([
            'success' => $locked,
            'message' => $locked ? 'Seat locked' : 'Seat is already locked',
        ]);
    }

    public function release(Showtime $showtime, Seat $seat, Request $request, UnlockSeatAction $unlockSeat, SeatLockService $seatLockService)
    {
        $holderId = $seatLockService->getLockHolder($showtime, $seat);

        if ($holderId !== null && $holderId !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Seat is locked by another user'], 403);
        }

        $unlockSeat->execute($showtime, $seat);

        return response()->json(['success' => true, 'message' => 'Seat released']);
    }

    public function status(Showtime $showtime, SeatLockService $seatLockService)
    {
        $lockedSeats = $seatLockService->getLockedSeats($showtime);

        return response()->json(['locked' => $lockedSeats]);
    }
}
