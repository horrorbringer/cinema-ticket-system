<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HallFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word() . ' Hall',
            'capacity' => fake()->numberBetween(50, 300),
            'is_active' => true,
        ];
    }
}
