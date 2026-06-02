<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Student::query()->with(['schoolClass', 'grades.subject']);

        if ($user->isTeacher()) {
            $classIds = SchoolClass::where('teacher_id', $user->id)->pluck('id');
            $query->whereIn('school_class_id', $classIds);
        }

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('parent_phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->string('status')->trim()->toString()) {
            $query->where('status', $status);
        }

        if ($classId = $request->input('class_id')) {
            $query->where('school_class_id', $classId);
        }

        $students = $query->orderBy('last_name')->paginate(10)->withQueryString();
        $classesQuery = SchoolClass::orderBy('name');

        if ($user->isTeacher()) {
            $classesQuery->whereIn('id', SchoolClass::where('teacher_id', $user->id)->select('id'));
        }

        $classes = $classesQuery->get();

        return view('students.index', [
            'students' => $students,
            'classes' => $classes,
            'statusOptions' => [
                'actif' => 'Actif',
                'archivé' => 'Archivé',
            ],
        ]);
    }

    public function create(Request $request)
    {
        $this->authorizeWrite($request);

        return view('students.create', [
            'classes' => $this->availableClasses($request->user()),
            'statusOptions' => [
                'actif' => 'Actif',
                'archivé' => 'Archivé',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeWrite($request);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Masculin,Féminin',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'parent_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'social_status' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'status' => 'required|in:actif,archivé',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Élève créé avec succès.');
    }

    public function show(Request $request, Student $student)
    {
        $this->authorizeView($request, $student);

        $student->load(['schoolClass.teacher', 'grades.subject', 'grades.teacher']);

        return view('students.show', [
            'student' => $student,
            'average' => $student->averageScore(),
            'mention' => $student->mention(),
        ]);
    }

    public function edit(Request $request, Student $student)
    {
        $this->authorizeWrite($request);
        $this->authorizeView($request, $student);

        return view('students.edit', [
            'student' => $student,
            'classes' => $this->availableClasses($request->user()),
            'statusOptions' => [
                'actif' => 'Actif',
                'archivé' => 'Archivé',
            ],
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $this->authorizeWrite($request);
        $this->authorizeView($request, $student);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Masculin,Féminin',
            'school_class_id' => 'nullable|exists:school_classes,id',
            'parent_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'social_status' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'status' => 'required|in:actif,archivé',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }

            $validated['photo_path'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($validated);

        return redirect()->route('students.show', $student)->with('success', 'Élève mis à jour.');
    }

    public function destroy(Request $request, Student $student)
    {
        $this->authorizeWrite($request);
        $this->authorizeView($request, $student);

        $student->update([
            'status' => 'archivé',
            'archived_at' => now(),
        ]);

        return redirect()->route('students.index')->with('success', 'Élève archivé.');
    }

    private function authorizeWrite(Request $request): void
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isTeacher(), 403);
    }

    private function authorizeView(Request $request, Student $student): void
    {
        $user = $request->user();

        if ($user->isTeacher()) {
            $classIds = SchoolClass::where('teacher_id', $user->id)->pluck('id');

            abort_unless($student->school_class_id && $classIds->contains($student->school_class_id), 403);
        }
    }

    private function availableClasses($user)
    {
        $query = SchoolClass::orderBy('name');

        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }

        return $query->get();
    }
}
