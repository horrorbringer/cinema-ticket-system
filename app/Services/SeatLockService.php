<?php

namespace App\Services;

use App\Events\SeatLockCreated;
use App\Events\SeatLockReleased;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Support\Facades\Redis;

class SeatLockService
{
    private function key(Showtime $showtime, Seat $seat): string
    {
        return "seat_lock:{$showtime->id}:{$seat->id}";
    }

    public function lock(Showtime $showtime, Seat $seat, int $userId): bool
    {
        $key = $this->key($showtime, $seat);

        $locked = Redis::set($key, json_encode([
            'user_id' => $userId,
            'seat_id' => $seat->id,
            'label' => $seat->label,
            'showtime_id' => $showtime->id,
            'locked_at' => now()->toDateTimeString(),
        ]), 'EX', 300, 'NX');

        if ($locked) {
            broadcast(new SeatLockCreated($showtime->id, $seat->id, $seat->label));
            return true;
        }

        return false;
    }

    public function release(Showtime $showtime, Seat $seat): void
    {
        $key = $this->key($showtime, $seat);
        Redis::del($key);

        broadcast(new SeatLockReleased($showtime->id, $seat->id, $seat->label));
    }

    public function isLocked(Showtime $showtime, Seat $seat): bool
    {
        return Redis::exists($this->key($showtime, $seat));
    }

    public function getLockedSeats(Showtime $showtime): array
    {
        $keys = Redis::keys("seat_lock:{$showtime->id}:*");
        $seats = [];

        foreach ($keys as $key) {
            $data = Redis::get($key);
            if ($data) {
                $seats[] = json_decode($data, true);
            }
        }

        return $seats;
    }

    public function getLockHolder(Showtime $showtime, Seat $seat): ?int
    {
        $data = Redis::get($this->key($showtime, $seat));
        if (!$data) return null;

        return json_decode($data, true)['user_id'] ?? null;
    }

    public function extendLock(Showtime $showtime, Seat $seat): bool
    {
        $key = $this->key($showtime, $seat);
        return (bool) Redis::expire($key, 300);
    }
}
