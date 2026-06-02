<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Grade;
use App\Models\Message;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();
        $classCount = SchoolClass::count();
        $teacherCount = User::where('role', UserRole::Enseignant->value)->count();
        $encadrantCount = User::where('role', UserRole::Encadrant->value)->count();
        $messageCount = Message::count();
        $unreadMessages = Message::whereNull('read_at')->count();
        $archivedStudents = Student::where('status', 'archivé')->count();

        $globalStats = Grade::query()
            ->selectRaw('SUM(score * coefficient) as weighted_total, SUM(coefficient) as coefficient_total')
            ->first();

        $globalAverage = $globalStats && $globalStats->coefficient_total
            ? round($globalStats->weighted_total / $globalStats->coefficient_total, 2)
            : null;

        $students = Student::with('grades')->get();
        $successRate = $studentCount
            ? round($students->filter(fn (Student $student) => ($student->averageScore() ?? 0) >= 10)->count() / $studentCount * 100, 1)
            : 0;

        $studentsByClass = SchoolClass::withCount('students')->orderBy('name')->get();
        $studentsWithoutClass = Student::whereNull('school_class_id')->count();

        $mentionLabels = ['Très bien', 'Bien', 'Assez bien', 'Passable', 'Insuffisant', 'Non évalué'];
        $mentionDistribution = collect($mentionLabels)->mapWithKeys(function (string $label) use ($students) {
            return [$label => $students->filter(fn (Student $student) => $student->mention() === $label)->count()];
        });

        $averagesBySemester = Grade::query()
            ->select('semester', DB::raw('SUM(score * coefficient) as weighted_total'), DB::raw('SUM(coefficient) as coefficient_total'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->get()
            ->map(function ($row) {
                $average = $row->coefficient_total ? round($row->weighted_total / $row->coefficient_total, 2) : 0;

                return [
                    'semester' => $row->semester,
                    'average' => $average,
                ];
            });

        $followUpIndicators = [
            ['label' => 'Taux de présence (simulé)', 'value' => 92, 'trend' => '+2%'],
            ['label' => 'Progression moyenne', 'value' => 68, 'trend' => '+4%'],
            ['label' => 'Participation encadrants', 'value' => 81, 'trend' => '+1%'],
            ['label' => 'Élèves sans classe', 'value' => $studentsWithoutClass, 'trend' => 'suivi'],
            ['label' => 'Messages non lus', 'value' => $unreadMessages, 'trend' => 'actions'],
            ['label' => 'Élèves archivés', 'value' => $archivedStudents, 'trend' => 'historique'],
        ];

        return view('dashboard', [
            'studentCount' => $studentCount,
            'classCount' => $classCount,
            'teacherCount' => $teacherCount,
            'encadrantCount' => $encadrantCount,
            'messageCount' => $messageCount,
            'unreadMessages' => $unreadMessages,
            'globalAverage' => $globalAverage,
            'successRate' => $successRate,
            'studentsByClass' => $studentsByClass,
            'mentionDistribution' => $mentionDistribution,
            'averagesBySemester' => $averagesBySemester,
            'followUpIndicators' => $followUpIndicators,
            'archivedStudents' => $archivedStudents,
        ]);
    }
}
