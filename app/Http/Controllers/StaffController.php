<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $role = $request->string('role')->trim()->toString();

        $query = User::query()->whereIn('role', [UserRole::Enseignant->value, UserRole::Encadrant->value]);

        if ($role) {
            $query->where('role', $role);
        }

        $staff = $query->withCount('schoolClasses')->orderBy('name')->paginate(10)->withQueryString();

        return view('staff.index', compact('staff', 'role'));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);

        return view('staff.create', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'roles' => [
                UserRole::Enseignant->value => UserRole::Enseignant->label(),
                UserRole::Encadrant->value => UserRole::Encadrant->label(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:enseignant,encadrant',
            'phone' => 'nullable|string|max:50',
            'specialty' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:school_classes,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'specialty' => $validated['specialty'] ?? null,
            'password' => Hash::make($validated['password'] ?? 'password'),
        ]);

        $this->syncClassesForTeacher($user, $validated['class_ids'] ?? []);

        return redirect()->route('staff.show', $user)->with('success', 'Membre ajouté.');
    }

    public function show(Request $request, User $staff)
    {
        $this->authorizeAdmin($request);

        $staff->load('schoolClasses.students');

        return view('staff.show', ['staffMember' => $staff]);
    }

    public function edit(Request $request, User $staff)
    {
        $this->authorizeAdmin($request);

        $staff->load('schoolClasses');

        return view('staff.edit', [
            'staffMember' => $staff,
            'classes' => SchoolClass::orderBy('name')->get(),
            'roles' => [
                UserRole::Enseignant->value => UserRole::Enseignant->label(),
                UserRole::Encadrant->value => UserRole::Encadrant->label(),
            ],
        ]);
    }

    public function update(Request $request, User $staff)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$staff->id,
            'role' => 'required|in:enseignant,encadrant',
            'phone' => 'nullable|string|max:50',
            'specialty' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:school_classes,id',
        ]);

        $staff->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'specialty' => $validated['specialty'] ?? null,
        ]);

        if (!empty($validated['password'])) {
            $staff->update(['password' => Hash::make($validated['password'])]);
        }

        $this->syncClassesForTeacher($staff, $validated['class_ids'] ?? []);

        if ($validated['role'] === UserRole::Encadrant->value) {
            SchoolClass::where('teacher_id', $staff->id)->update(['teacher_id' => null]);
        }

        return redirect()->route('staff.show', $staff)->with('success', 'Membre mis à jour.');
    }

    public function destroy(Request $request, User $staff)
    {
        $this->authorizeAdmin($request);

        if ($staff->isAdmin()) {
            return redirect()->route('staff.index')->with('error', 'Impossible de supprimer un administrateur.');
        }

        SchoolClass::where('teacher_id', $staff->id)->update(['teacher_id' => null]);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Membre supprimé.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()->isAdmin(), 403);
    }

    private function syncClassesForTeacher(User $user, array $classIds): void
    {
        if ($user->hasRole(UserRole::Enseignant)) {
            SchoolClass::where('teacher_id', $user->id)
                ->whereNotIn('id', $classIds)
                ->update(['teacher_id' => null]);

            if ($classIds !== []) {
                SchoolClass::whereIn('id', $classIds)->update(['teacher_id' => $user->id]);
            }
        } else {
            SchoolClass::where('teacher_id', $user->id)->update(['teacher_id' => null]);
        }
    }
}
