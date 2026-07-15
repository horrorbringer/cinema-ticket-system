<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Movie $movie, Request $request)
    {
        $request->user()->bookings()
            ->whereHas('showtime', fn($q) => $q->where('movie_id', $movie->id))
            ->where('status', 'confirmed')
            ->firstOr(fn() => abort(403, 'You must have a confirmed booking to review this movie.'));

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);

        $existing = Review::where('movie_id', $movie->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->update($data);
        } else {
            Review::create([
                'movie_id' => $movie->id,
                'user_id' => $request->user()->id,
                'rating' => $data['rating'],
                'review' => $data['review'],
                'approved' => false,
            ]);
        }

        return back()->with('success', 'Review submitted! It will appear after moderation.');
    }
}
