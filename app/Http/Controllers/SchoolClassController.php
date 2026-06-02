<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = SchoolClass::with(['teacher'])->withCount('students');

        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->id);
        }

        $classes = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('classes.index', compact('classes'));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);

        return view('classes.create', [
            'teachers' => User::where('role', UserRole::Enseignant->value)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'school_year' => 'required|string|max:20',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $class = SchoolClass::create($validated);

        return redirect()->route('classes.show', $class)->with('success', 'Classe créée.');
    }

    public function show(Request $request, SchoolClass $class)
    {
        $this->authorizeView($request, $class);

        $class->load(['teacher', 'students.grades.subject']);
        $class->loadCount('students');

        return view('classes.show', [
            'class' => $class,
            'average' => $class->averageScore(),
        ]);
    }

    public function edit(Request $request, SchoolClass $class)
    {
        $this->authorizeAdmin($request);

        return view('classes.edit', [
            'class' => $class,
            'teachers' => User::where('role', UserRole::Enseignant->value)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'school_year' => 'required|string|max:20',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $class->update($validated);

        return redirect()->route('classes.show', $class)->with('success', 'Classe mise à jour.');
    }

    public function destroy(Request $request, SchoolClass $class)
    {
        $this->authorizeAdmin($request);

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Classe supprimée.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->isAdmin(), 403);
    }

    private function authorizeView(Request $request, SchoolClass $class): void
    {
        $user = $request->user();

        if ($user->isTeacher()) {
            abort_unless($class->teacher_id === $user->id, 403);
        }
    }
}
