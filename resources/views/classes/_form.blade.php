@php($class = $class ?? null)
@php($isEdit = $class !== null)
<form method="POST" action="{{ $isEdit ? route('classes.update', $class) : route('classes.store') }}" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Nom</span>
            <input name="name" value="{{ old('name', $class?->name ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>
        </label>
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Niveau</span>
            <input name="level" value="{{ old('level', $class?->level ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>
        </label>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Année scolaire</span>
            <input name="school_year" value="{{ old('school_year', $class?->school_year ?? '2025-2026') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>
        </label>
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Enseignant responsable</span>
            <select name="teacher_id" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                <option value="">Aucun</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected((string) old('teacher_id', $class?->teacher_id ?? '') === (string) $teacher->id)>{{ $teacher->name }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <label class="block">
        <span class="text-sm font-semibold text-slate-700">Description</span>
        <textarea name="description" rows="5" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('description', $class?->description ?? '') }}</textarea>
    </label>

    <div class="flex items-center gap-3">
        <button class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">{{ $isEdit ? 'Mettre à jour' : 'Créer la classe' }}</button>
        <a href="{{ route('classes.index') }}" class="rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700">Annuler</a>
    </div>
</form>
