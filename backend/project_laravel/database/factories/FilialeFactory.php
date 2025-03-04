<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Filiale>
 */
class FilialeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "codice" => fake()->bothify("????????"),
            "indirizzo" => fake()->bothify("????????"),
            "città" => fake()->bothify("????????"),
            "cap" => fake()->bothify("????????"),
        ];
    }
}
