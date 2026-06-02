@php($grade = $grade ?? null)
@php($isEdit = $grade !== null)
<form method="POST" action="{{ $isEdit ? route('grades.update', $grade) : route('grades.store') }}" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif
    <div class="grid gap-4 md:grid-cols-2">
        <label class="block"><span class="text-sm font-semibold text-slate-700">Élève</span><select name="student_id" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>@foreach($students as $student)<option value="{{ $student->id }}" @selected((string) old('student_id', $grade?->student_id ?? '') === (string) $student->id)>{{ $student->full_name }} ({{ $student->schoolClass?->name ?? 'Sans classe' }})</option>@endforeach</select></label>
        <label class="block"><span class="text-sm font-semibold text-slate-700">Matière</span><select name="subject_id" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>@foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected((string) old('subject_id', $grade?->subject_id ?? '') === (string) $subject->id)>{{ $subject->name }}</option>@endforeach</select></label>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <label class="block"><span class="text-sm font-semibold text-slate-700">Note /20</span><input type="number" step="0.01" min="0" max="20" name="score" value="{{ old('score', $grade?->score ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required></label>
        <label class="block"><span class="text-sm font-semibold text-slate-700">Coefficient</span><input type="number" step="0.1" min="0.5" max="10" name="coefficient" value="{{ old('coefficient', $grade?->coefficient ?? 1) }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required></label>
        <label class="block"><span class="text-sm font-semibold text-slate-700">Semestre</span><input name="semester" value="{{ old('semester', $grade?->semester ?? 'S1') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required></label>
    </div>
    <label class="block"><span class="text-sm font-semibold text-slate-700">Observation</span><textarea name="observation" rows="4" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('observation', $grade?->observation ?? '') }}</textarea></label>
    @if(auth()->user()->isAdmin())
        <label class="block"><span class="text-sm font-semibold text-slate-700">Enseignant</span><select name="teacher_id" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500"><option value="">Aucun</option>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}" @selected((string) old('teacher_id', $grade?->teacher_id ?? '') === (string) $teacher->id)>{{ $teacher->name }}</option>@endforeach</select></label>
    @endif
    <div class="flex items-center gap-3"><button class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">{{ $isEdit ? 'Mettre à jour' : 'Enregistrer la note' }}</button><a href="{{ route('grades.index') }}" class="rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700">Annuler</a></div>
</form>
