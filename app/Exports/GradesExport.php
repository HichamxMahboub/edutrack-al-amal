<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GradesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Grade::query()
            ->with(['student.schoolClass', 'subject', 'teacher'])
            ->get()
            ->map(fn (Grade $grade) => [
                'student' => $grade->student?->full_name,
                'class' => $grade->student?->schoolClass?->name,
                'subject' => $grade->subject?->name,
                'score' => $grade->score,
                'coefficient' => $grade->coefficient,
                'semester' => $grade->semester,
                'teacher' => $grade->teacher?->name,
                'observation' => $grade->observation,
            ]);
    }

    public function headings(): array
    {
        return ['student', 'class', 'subject', 'score', 'coefficient', 'semester', 'teacher', 'observation'];
    }
}
