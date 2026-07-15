<?php

namespace App\Services;

use App\Models\Movie;

class MovieService
{
    public function getNowShowing()
    {
        return Movie::with('genres')
            ->where('status', 'now_showing')
            ->latest()
            ->paginate(12);
    }

    public function getComingSoon()
    {
        return Movie::with('genres')
            ->where('status', 'coming_soon')
            ->latest()
            ->paginate(12);
    }

    public function search(string $query)
    {
        return Movie::with('genres')
            ->where('title', 'like', "%{$query}%")
            ->where('status', '!=', 'ended')
            ->latest()
            ->paginate(12);
    }
}
