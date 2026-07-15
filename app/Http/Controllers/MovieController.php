<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('genres', 'showtimes')->where('status', '!=', 'ended');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', fn ($q) => $q->where('slug', $request->genre));
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('premiere_date')) {
            $query->where('release_date', $request->premiere_date);
        }

        $sort = $request->get('sort', 'latest');
        $queryOrdered = match ($sort) {
            'rating' => $query->orderBy('rating', 'desc'),
            'title' => $query->orderBy('title'),
            'release_date' => $query->orderBy('release_date', 'desc'),
            'duration' => $query->orderBy('duration'),
            default => $query->latest(),
        };

        $allMovies = $queryOrdered->get();

        $nowShowing = $allMovies->where('status', 'now_showing');
        $comingSoon = $allMovies->where('status', 'coming_soon');
        $total = $allMovies->count();

        if ($request->header('HX-Request')) {
            return view('movies.partials.movie-grid', compact('nowShowing', 'comingSoon', 'total'));
        }

        $genres = \App\Models\Genre::all();
        $languages = Movie::distinct()->pluck('language');
        $featured = Movie::with('genres')
            ->where('status', 'now_showing')
            ->orderBy('rating', 'desc')
            ->first();

        return view('movies.index', compact('nowShowing', 'comingSoon', 'total', 'genres', 'languages', 'featured'));
    }

    public function show(Movie $movie)
    {
        $movie->load('genres', 'showtimes.hall', 'approvedReviews.user');

        $showtimesByDate = $movie->showtimes()
            ->with('hall')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($showtime) => $showtime->start_time->format('Y-m-d'));

        $averageRating = $movie->averageRating();

        return view('movies.show', compact('movie', 'showtimesByDate', 'averageRating'));
    }

    public function showtimesDate(Movie $movie, string $date)
    {
        $showtimes = $movie->showtimes()
            ->with('hall')
            ->whereDate('start_time', $date)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get();

        if (!request()->header('HX-Request')) {
            abort(404);
        }

        return view('movies.partials.showtimes-date', compact('showtimes'));
    }

    public function related(Movie $movie)
    {
        $genreIds = $movie->genres->pluck('id');

        $related = Movie::with('genres', 'showtimes')
            ->where('id', '!=', $movie->id)
            ->where('status', $movie->status)
            ->whereHas('genres', fn ($q) => $q->whereIn('genres.id', $genreIds))
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return view('movies.partials.related-carousel', compact('related'));
    }
}
