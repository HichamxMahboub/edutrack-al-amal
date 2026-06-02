<?php

namespace App\Exports;

use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatisticsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            ['metric' => 'Total élèves', 'value' => Student::count()],
            ['metric' => 'Total classes', 'value' => SchoolClass::count()],
            ['metric' => 'Total notes', 'value' => Grade::count()],
            ['metric' => 'Moyenne générale', 'value' => $this->globalAverage()],
            ['metric' => 'Taux de réussite', 'value' => $this->successRate()],
        ]);
    }

    public function headings(): array
    {
        return ['metric', 'value'];
    }

    private function globalAverage(): string
    {
        $stats = Grade::query()
            ->selectRaw('SUM(score * coefficient) as weighted_total, SUM(coefficient) as coefficient_total')
            ->first();

        if (!$stats || !$stats->coefficient_total) {
            return '0.00';
        }

        return number_format($stats->weighted_total / $stats->coefficient_total, 2);
    }

    private function successRate(): string
    {
        $students = Student::with('grades')->get();
        $total = $students->count();

        if ($total === 0) {
            return '0%';
        }

        $success = $students->filter(fn (Student $student) => ($student->averageScore() ?? 0) >= 10)->count();

        return number_format(($success / $total) * 100, 1) . '%';
    }
}
