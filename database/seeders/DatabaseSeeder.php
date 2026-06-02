<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Grade;
use App\Models\Message;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin EduTrack',
            'email' => 'admin@edutrack.test',
            'role' => UserRole::Admin,
            'phone' => '0600000001',
            'specialty' => 'Direction',
        ]);

        $leadTeacher = User::factory()->create([
            'name' => 'Enseignant Démo',
            'email' => 'enseignant@edutrack.test',
            'role' => UserRole::Enseignant,
            'phone' => '0600000002',
            'specialty' => 'Mathématiques',
        ]);

        $leadMentor = User::factory()->create([
            'name' => 'Encadrant Démo',
            'email' => 'encadrant@edutrack.test',
            'role' => UserRole::Encadrant,
            'phone' => '0600000003',
            'specialty' => 'Accompagnement social',
        ]);

        $teachers = User::factory()->count(4)->create([
            'role' => UserRole::Enseignant,
        ])->push($leadTeacher)->values();

        $mentors = User::factory()->count(2)->create([
            'role' => UserRole::Encadrant,
        ])->push($leadMentor)->values();

        $allStaff = $teachers->concat($mentors)->push($admin)->values();

        $subjects = collect([
            ['name' => 'Mathématiques', 'code' => 'MATH', 'default_coefficient' => 2],
            ['name' => 'Français', 'code' => 'FR', 'default_coefficient' => 2],
            ['name' => 'Sciences', 'code' => 'SCI', 'default_coefficient' => 1.5],
            ['name' => 'Histoire-Géographie', 'code' => 'HG', 'default_coefficient' => 1],
            ['name' => 'Anglais', 'code' => 'ANG', 'default_coefficient' => 1.5],
            ['name' => 'Informatique', 'code' => 'INFO', 'default_coefficient' => 1],
            ['name' => 'Éducation Islamique', 'code' => 'ISL', 'default_coefficient' => 1],
            ['name' => 'Physique-Chimie', 'code' => 'PC', 'default_coefficient' => 1.5],
            ['name' => 'SVT', 'code' => 'SVT', 'default_coefficient' => 1.5],
            ['name' => 'Arts Plastiques', 'code' => 'ART', 'default_coefficient' => 1],
            ['name' => 'Éducation Physique', 'code' => 'EPS', 'default_coefficient' => 1],
            ['name' => 'Arabe', 'code' => 'ARB', 'default_coefficient' => 2],
        ])->map(fn (array $data) => Subject::create($data));

        $classes = collect([
            ['name' => '6A', 'level' => 'Collège', 'school_year' => '2025-2026'],
            ['name' => '6B', 'level' => 'Collège', 'school_year' => '2025-2026'],
            ['name' => '7A', 'level' => 'Collège', 'school_year' => '2025-2026'],
            ['name' => '8A', 'level' => 'Collège', 'school_year' => '2025-2026'],
            ['name' => '1S', 'level' => 'Lycée', 'school_year' => '2025-2026'],
            ['name' => '2S', 'level' => 'Lycée', 'school_year' => '2025-2026'],
        ])->map(function (array $data, int $index) use ($allStaff) {
            return SchoolClass::create([
                ...$data,
                'description' => 'Classe pilote EduTrack Al Amal',
                'teacher_id' => $allStaff[$index % $allStaff->count()]->id,
            ]);
        });

        $students = collect();
        foreach (range(1, 60) as $index) {
            $student = Student::factory()->make([
                'school_class_id' => $classes[($index - 1) % $classes->count()]->id,
                'status' => $index > 50 ? 'archivé' : 'actif',
                'archived_at' => $index > 50 ? now()->subDays(rand(5, 90)) : null,
            ]);
            $student->save();
            $students->push($student);
        }

        $scorePool = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18];
        foreach ($students as $student) {
            $studentSubjects = $subjects->shuffle()->take(6);
            foreach ($studentSubjects as $subject) {
                foreach (['S1', 'S2'] as $semester) {
                    Grade::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'teacher_id' => $allStaff->random()->id,
                        'score' => Arr::random($scorePool),
                        'coefficient' => $subject->default_coefficient,
                        'semester' => $semester,
                        'observation' => Arr::random([
                            'Bonne progression.',
                            'Résultats stables.',
                            'Peut mieux faire.',
                            'Excellent travail.',
                            'Participation régulière.',
                            'Travail sérieux.',
                        ]),
                    ]);
                }
            }
        }

        collect(range(1, 24))->each(function () use ($allStaff) {
            $sender = $allStaff->random();
            $recipient = $allStaff->where('id', '!=', $sender->id)->random();

            Message::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'subject' => Arr::random([
                    'Suivi pédagogique',
                    'Point de coordination',
                    'Remontée terrain',
                    'Bilan de semaine',
                    'Planning de classe',
                ]),
                'body' => 'Message de démonstration pour illustrer la messagerie interne et la circulation des informations.',
                'read_at' => fake()->optional(0.6)->dateTimeThisMonth(),
            ]);
        });
    }
}
