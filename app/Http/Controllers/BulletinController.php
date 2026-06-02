<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
    public function show(Request $request, Student $student)
    {
        $this->authorizeAccess($request, $student);

        $student->load(['schoolClass.teacher', 'grades.subject', 'grades.teacher']);

        return view('bulletins.show', [
            'student' => $student,
            'average' => $student->averageScore(),
            'mention' => $student->mention(),
        ]);
    }

    public function pdf(Request $request, Student $student)
    {
        $this->authorizeAccess($request, $student);

        $student->load(['schoolClass.teacher', 'grades.subject', 'grades.teacher']);

        $pdf = Pdf::loadView('bulletins.pdf', [
            'student' => $student,
            'average' => $student->averageScore(),
            'mention' => $student->mention(),
        ])->setPaper('a4');

        return $pdf->download('bulletin-'.$student->id.'.pdf');
    }

    private function authorizeAccess(Request $request, Student $student): void
    {
        $user = $request->user();

        if ($user->isTeacher()) {
            $classIds = SchoolClass::where('teacher_id', $user->id)->pluck('id');
            abort_unless($student->school_class_id && $classIds->contains($student->school_class_id), 403);
        }
    }
}
