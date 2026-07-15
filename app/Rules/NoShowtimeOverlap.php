<?php

namespace App\Rules;

use App\Models\Showtime;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoShowtimeOverlap implements ValidationRule
{
    public function __construct(
        private int $hallId,
        private ?int $excludeShowtimeId = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $startTime = request('start_time');
        $endTime = request('end_time');

        if (!$startTime || !$endTime) {
            return;
        }

        $query = Showtime::where('hall_id', $this->hallId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        if ($this->excludeShowtimeId) {
            $query->where('id', '!=', $this->excludeShowtimeId);
        }

        if ($query->exists()) {
            $fail('A showtime already exists in this hall during the specified time period.');
        }
    }
}
