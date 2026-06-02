<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row): ?Student
    {
        $className = trim((string) ($row['class'] ?? $row['school_class'] ?? ''));
        $classId = null;

        if ($className !== '') {
            $classId = SchoolClass::firstOrCreate(
                ['name' => $className],
                [
                    'level' => $row['level'] ?? 'Collège',
                    'school_year' => $row['school_year'] ?? '2025-2026',
                    'description' => 'Classe créée via import.',
                ]
            )->id;
        }

        return new Student([
            'first_name' => $row['first_name'] ?? Str::title((string) ($row['prenom'] ?? '')),
            'last_name' => $row['last_name'] ?? Str::title((string) ($row['nom'] ?? '')),
            'date_of_birth' => !empty($row['date_of_birth']) ? Carbon::parse($row['date_of_birth'])->format('Y-m-d') : null,
            'gender' => $row['gender'] ?? 'Masculin',
            'school_class_id' => $classId,
            'parent_phone' => $row['parent_phone'] ?? null,
            'address' => $row['address'] ?? null,
            'social_status' => $row['social_status'] ?? null,
            'medical_notes' => $row['medical_notes'] ?? null,
            'status' => $row['status'] ?? 'actif',
        ]);
    }
}
