<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Grade>
 */
class GradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'teacher_id' => null,
            'score' => fake()->randomFloat(2, 5, 20),
            'coefficient' => fake()->randomElement([1, 1.5, 2]),
            'semester' => fake()->randomElement(['S1', 'S2']),
            'observation' => fake()->optional()->sentence(10),
        ];
    }
}
