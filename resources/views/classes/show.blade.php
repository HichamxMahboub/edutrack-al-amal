<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Classe</p>
                <h2 class="text-2xl font-bold text-slate-900">{{ $class->name }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('grades.index', ['class_id' => $class->id]) }}" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Voir les notes</a>
                @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                    <a href="{{ route('students.create') }}" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Ajouter un élève</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-slate-50 p-4"><div class="text-sm text-slate-500">Enseignant</div><div class="mt-1 font-semibold text-slate-900">{{ $class->teacher?->name ?? 'Aucun' }}</div></div>
                <div class="rounded-2xl bg-slate-50 p-4"><div class="text-sm text-slate-500">Élèves</div><div class="mt-1 font-semibold text-slate-900">{{ $class->students_count }}</div></div>
                <div class="rounded-2xl bg-slate-50 p-4"><div class="text-sm text-slate-500">Moyenne</div><div class="mt-1 font-semibold text-slate-900">{{ $average !== null ? number_format($average, 2) : 'N/A' }}</div></div>
            </div>
            <p class="mt-6 text-sm leading-7 text-slate-600">{{ $class->description ?: 'Aucune description renseignée.' }}</p>
        </section>

        <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900">Statistiques</h3>
            <div class="mt-4 space-y-3">
                <div class="rounded-2xl bg-cyan-50 p-4 text-cyan-900">Moyenne de classe: {{ $class->averageLabel() }}</div>
                <div class="rounded-2xl bg-emerald-50 p-4 text-emerald-900">Élèves actifs: {{ $class->students->where('status', 'actif')->count() }}</div>
                <div class="rounded-2xl bg-slate-50 p-4 text-slate-800">Archivés: {{ $class->students->where('status', 'archivé')->count() }}</div>
            </div>
        </aside>
    </div>

    <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Élève</th>
                    <th class="px-4 py-3 text-left">Moyenne</th>
                    <th class="px-4 py-3 text-left">Mention</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($class->students as $student)
                    <tr>
                        <td class="px-4 py-4 font-semibold text-slate-900">{{ $student->full_name }}</td>
                        <td class="px-4 py-4">{{ $student->averageLabel() }}</td>
                        <td class="px-4 py-4">{{ $student->mention() }}</td>
                        <td class="px-4 py-4"><a href="{{ route('students.show', $student) }}" class="rounded-full border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-700">Fiche élève</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Aucun élève dans cette classe.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-app-layout>
