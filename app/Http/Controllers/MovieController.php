<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::with('genres');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'ended');
        }

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
            $premiereDate = $request->premiere_date;
            $query->where('release_date', $premiereDate);
        }

        $movies = $query->latest()->paginate(12);

        if ($request->wantsJson() || $request->ajax() || $request->header('HX-Request')) {
            return view('movies.partials.movie-grid', compact('movies'));
        }

        $genres = \App\Models\Genre::all();
        $languages = Movie::distinct()->pluck('language');

        return view('movies.index', compact('movies', 'genres', 'languages'));
    }

    public function show(Movie $movie)
    {
        $movie->load('genres', 'showtimes.hall');

        $showtimesByDate = $movie->showtimes()
            ->with('hall')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($showtime) => $showtime->start_time->format('Y-m-d'));

        return view('movies.show', compact('movie', 'showtimesByDate'));
    }
}
