<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hall_id' => \App\Models\Hall::factory(),
            'seat_type_id' => \App\Models\SeatType::factory(),
            'row' => fake()->randomLetter(),
            'number' => fake()->numberBetween(1, 20),
            'label' => fake()->randomLetter() . fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
