<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->bothify('Classe ?#'),
            'level' => fake()->randomElement(['Primaire', 'Collège', 'Lycée']),
            'school_year' => fake()->randomElement(['2024-2025', '2025-2026', '2026-2027']),
            'description' => fake()->optional()->sentence(12),
            'teacher_id' => null,
        ];
    }
}
