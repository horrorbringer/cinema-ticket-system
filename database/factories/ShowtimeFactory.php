<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShowtimeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'movie_id' => \App\Models\Movie::factory(),
            'hall_id' => \App\Models\Hall::factory(),
            'start_time' => now()->addDay()->setTime(14, 0),
            'end_time' => now()->addDay()->setTime(16, 0),
            'base_price' => fake()->randomFloat(2, 8, 20),
        ];
    }
}
