<?php

namespace App\Services;

use App\Models\Showtime;

class ShowtimeService
{
    public function getShowtimesForMovie(int $movieId)
    {
        return Showtime::with('hall')
            ->where('movie_id', $movieId)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($s) => $s->start_time->format('Y-m-d'));
    }

    public function checkOverlap(int $hallId, string $startTime, string $endTime, ?int $excludeId = null): bool
    {
        $query = Showtime::where('hall_id', $hallId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
