<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class SeatLockReleased implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public int $showtimeId,
        public int $seatId,
        public string $label,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("showtime.{$this->showtimeId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'seat.unlocked';
    }
}
