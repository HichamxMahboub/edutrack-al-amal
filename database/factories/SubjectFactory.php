<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Mathématiques',
                'Français',
                'Sciences',
                'Histoire-Géographie',
                'Anglais',
                'Informatique',
            ]),
            'code' => fake()->optional()->bothify('SUB-###'),
            'description' => fake()->optional()->sentence(8),
            'default_coefficient' => fake()->randomElement([1, 1.5, 2]),
        ];
    }
}
