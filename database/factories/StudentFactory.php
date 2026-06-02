<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'date_of_birth' => fake()->dateTimeBetween('-18 years', '-6 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(['Masculin', 'Féminin']),
            'school_class_id' => null,
            'parent_phone' => fake()->optional()->phoneNumber(),
            'address' => fake()->optional()->address(),
            'social_status' => fake()->optional()->sentence(10),
            'medical_notes' => fake()->optional()->sentence(10),
            'status' => 'actif',
            'archived_at' => null,
            'photo_path' => null,
        ];
    }
}
