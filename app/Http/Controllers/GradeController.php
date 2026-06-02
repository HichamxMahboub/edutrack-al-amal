<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Grade::with(['student.schoolClass', 'subject', 'teacher']);

        if ($user->isTeacher()) {
            $query->whereHas('student.schoolClass', fn ($builder) => $builder->where('teacher_id', $user->id));
        }

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }

        if ($subjectId = $request->input('subject_id')) {
            $query->where('subject_id', $subjectId);
        }

        if ($semester = $request->string('semester')->trim()->toString()) {
            $query->where('semester', $semester);
        }

        if ($classId = $request->input('class_id')) {
            $query->whereHas('student', fn ($builder) => $builder->where('school_class_id', $classId));
        }

        $grades = $query->latest()->paginate(10)->withQueryString();

        $classes = SchoolClass::orderBy('name')->get();
        $studentsQuery = Student::with('schoolClass')->orderBy('last_name');

        if ($user->isTeacher()) {
            $classIds = SchoolClass::where('teacher_id', $user->id)->pluck('id');
            $studentsQuery->whereIn('school_class_id', $classIds);
        }

        $students = $studentsQuery->get();
        $subjects = Subject::orderBy('name')->get();

        return view('grades.index', compact('grades', 'classes', 'students', 'subjects'));
    }

    public function create(Request $request)
    {
        $this->authorizeWrite($request);

        $user = $request->user();
        $classes = SchoolClass::orderBy('name')->get();

        $studentsQuery = Student::with('schoolClass')->orderBy('last_name');
        if ($user->isTeacher()) {
            $classIds = SchoolClass::where('teacher_id', $user->id)->pluck('id');
            $studentsQuery->whereIn('school_class_id', $classIds);
        }

        $students = $studentsQuery->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', UserRole::Enseignant->value)->orderBy('name')->get();

        return view('grades.create', compact('students', 'subjects', 'classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $this->authorizeWrite($request);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:20',
            'coefficient' => 'required|numeric|min:0.5|max:10',
            'semester' => 'required|string|max:10',
            'observation' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $user = $request->user();
        $validated['teacher_id'] = $user->isTeacher() ? $user->id : ($validated['teacher_id'] ?? null);

        $grade = Grade::create($validated);

        return redirect()->route('grades.show', $grade)->with('success', 'Note enregistrée.');
    }

    public function show(Request $request, Grade $grade)
    {
        $this->authorizeView($request, $grade);

        $grade->load(['student.schoolClass', 'subject', 'teacher']);

        return view('grades.show', compact('grade'));
    }

    public function edit(Request $request, Grade $grade)
    {
        $this->authorizeWrite($request);
        $this->authorizeView($request, $grade);

        $user = $request->user();

        $students = Student::with('schoolClass')->orderBy('last_name')->get();
        if ($user->isTeacher()) {
            $classIds = SchoolClass::where('teacher_id', $user->id)->pluck('id');
            $students = $students->whereIn('school_class_id', $classIds);
        }

        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', UserRole::Enseignant->value)->orderBy('name')->get();

        return view('grades.edit', compact('grade', 'students', 'subjects', 'teachers'));
    }

    public function update(Request $request, Grade $grade)
    {
        $this->authorizeWrite($request);
        $this->authorizeView($request, $grade);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'required|numeric|min:0|max:20',
            'coefficient' => 'required|numeric|min:0.5|max:10',
            'semester' => 'required|string|max:10',
            'observation' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $user = $request->user();
        $validated['teacher_id'] = $user->isTeacher() ? $user->id : ($validated['teacher_id'] ?? null);

        $grade->update($validated);

        return redirect()->route('grades.show', $grade)->with('success', 'Note mise à jour.');
    }

    public function destroy(Request $request, Grade $grade)
    {
        $this->authorizeWrite($request);
        $this->authorizeView($request, $grade);

        $grade->delete();

        return redirect()->route('grades.index')->with('success', 'Note supprimée.');
    }

    private function authorizeWrite(Request $request): void
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isTeacher(), 403);
    }

    private function authorizeView(Request $request, Grade $grade): void
    {
        $user = $request->user();

        if ($user->isTeacher()) {
            $grade->loadMissing('student.schoolClass');
            abort_unless($grade->student?->schoolClass?->teacher_id === $user->id, 403);
        }
    }
}
