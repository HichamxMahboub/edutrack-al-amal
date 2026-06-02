<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Student::query()
            ->with('schoolClass')
            ->get()
            ->map(fn (Student $student) => [
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'gender' => $student->gender,
                'date_of_birth' => optional($student->date_of_birth)->format('Y-m-d'),
                'class' => $student->schoolClass?->name,
                'parent_phone' => $student->parent_phone,
                'address' => $student->address,
                'status' => $student->status,
            ]);
    }

    public function headings(): array
    {
        return ['first_name', 'last_name', 'gender', 'date_of_birth', 'class', 'parent_phone', 'address', 'status'];
    }
}
