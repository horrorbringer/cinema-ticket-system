<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        Movie::factory()->create(['status' => 'now_showing']);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
