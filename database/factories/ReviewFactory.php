<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected static ?string $model = \App\Models\Review::class;

    public function definition(): array
    {
        return [
            'movie_id' => Movie::factory(),
            'user_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'review' => $this->faker->paragraph(),
            'approved' => $this->faker->boolean(70),
        ];
    }
}
