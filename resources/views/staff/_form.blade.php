@php($staffMember = $staffMember ?? null)
@php($isEdit = $staffMember !== null)
<form method="POST" action="{{ $isEdit ? route('staff.update', $staffMember) : route('staff.store') }}" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <label class="block"><span class="text-sm font-semibold text-slate-700">Nom</span><input name="name" value="{{ old('name', $staffMember?->name ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required></label>
        <label class="block"><span class="text-sm font-semibold text-slate-700">Email</span><input type="email" name="email" value="{{ old('email', $staffMember?->email ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required></label>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <label class="block"><span class="text-sm font-semibold text-slate-700">Rôle</span><select name="role" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>@foreach($roles as $value => $label)<option value="{{ $value }}" @selected(old('role', $staffMember?->role->value ?? $staffMember?->role ?? 'enseignant') === $value)>{{ $label }}</option>@endforeach</select></label>
        <label class="block"><span class="text-sm font-semibold text-slate-700">Téléphone</span><input name="phone" value="{{ old('phone', $staffMember?->phone ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500"></label>
        <label class="block"><span class="text-sm font-semibold text-slate-700">Mot de passe</span><input type="password" name="password" placeholder="{{ $isEdit ? 'Laisser vide pour conserver' : 'password' }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500"></label>
    </div>
    <label class="block"><span class="text-sm font-semibold text-slate-700">Spécialité</span><input name="specialty" value="{{ old('specialty', $staffMember?->specialty ?? '') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500"></label>
    <label class="block"><span class="text-sm font-semibold text-slate-700">Classes affectées</span><select name="class_ids[]" multiple class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500">@foreach($classes as $class)<option value="{{ $class->id }}" @selected(collect(old('class_ids', ($staffMember?->schoolClasses?->pluck('id')->all() ?? [])))->contains($class->id))>{{ $class->name }}</option>@endforeach</select></label>
    <div class="flex items-center gap-3"><button class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">{{ $isEdit ? 'Mettre à jour' : 'Créer le membre' }}</button><a href="{{ route('staff.index') }}" class="rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700">Annuler</a></div>
</form>
