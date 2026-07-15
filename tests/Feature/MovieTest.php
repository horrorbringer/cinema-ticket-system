<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_movies_index_shows_movies(): void
    {
        $movie = Movie::factory()->create([
            'title' => 'Test Movie',
            'status' => 'now_showing',
        ]);
        $genre = Genre::factory()->create(['name' => 'Action']);
        $movie->genres()->attach($genre);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Test Movie');
    }

    public function test_movie_detail_shows_showtimes(): void
    {
        $movie = Movie::factory()->create([
            'title' => 'Test Movie',
            'status' => 'now_showing',
        ]);

        $response = $this->get('/movies/' . $movie->id);

        $response->assertStatus(200);
        $response->assertSee('Test Movie');
    }

    public function test_hidden_movie_not_shown(): void
    {
        Movie::factory()->create([
            'title' => 'Hidden Movie',
            'status' => 'ended',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Hidden Movie');
    }
}
