@php($student = $student ?? null)
@php($isEdit = $student !== null)
<form method="POST" action="{{ $isEdit ? route('students.update', $student) : route('students.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Prénom</span>
            <input name="first_name" value="{{ old('first_name', $student?->first_name ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
        </label>
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Nom</span>
            <input name="last_name" value="{{ old('last_name', $student?->last_name ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
        </label>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Date de naissance</span>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($student?->date_of_birth)->format('Y-m-d')) }}" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
        </label>
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Genre</span>
            <select name="gender" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                @foreach (['Masculin', 'Féminin'] as $gender)
                    <option value="{{ $gender }}" @selected(old('gender', $student?->gender ?? '') === $gender)>{{ $gender }}</option>
                @endforeach
            </select>
        </label>
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Statut</span>
            <select name="status" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                @foreach($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $student?->status ?? 'actif') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Classe</span>
            <select name="school_class_id" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                <option value="">Aucune</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected((string) old('school_class_id', $student?->school_class_id ?? '') === (string) $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Téléphone parent</span>
            <input name="parent_phone" value="{{ old('parent_phone', $student?->parent_phone ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
        </label>
    </div>

    <label class="block">
        <span class="text-sm font-semibold text-slate-700">Adresse</span>
        <input name="address" value="{{ old('address', $student?->address ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
    </label>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Situation sociale</span>
            <textarea name="social_status" rows="4" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('social_status', $student?->social_status ?? '') }}</textarea>
        </label>
        <label class="block">
            <span class="text-sm font-semibold text-slate-700">Notes médicales</span>
            <textarea name="medical_notes" rows="4" class="mt-2 w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('medical_notes', $student?->medical_notes ?? '') }}</textarea>
        </label>
    </div>

    <label class="block">
        <span class="text-sm font-semibold text-slate-700">Photo</span>
        <input type="file" name="photo" accept="image/*" class="mt-2 block w-full rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm shadow-sm file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800">
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">{{ $isEdit ? 'Mettre à jour' : 'Créer l'élève' }}</button>
        <a href="{{ route('students.index') }}" class="rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400">Annuler</a>
    </div>
</form>
