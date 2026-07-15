<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(80, 180),
            'poster' => null,
            'release_date' => fake()->date(),
            'language' => fake()->randomElement(['English', 'French', 'Spanish']),
            'status' => 'now_showing',
        ];
    }
}
