<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Suivi</p>
                <h2 class="text-2xl font-bold text-slate-900">Élèves</h2>
                <p class="mt-1 text-sm text-slate-600">Recherche, filtrage par classe et suivi de la moyenne.</p>
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                <a href="{{ route('students.create') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Ajouter un élève</a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="grid gap-4 md:grid-cols-4">
                <input name="search" value="{{ request('search') }}" placeholder="Rechercher un élève" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                <select name="class_id" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected((string) request('class_id') === (string) $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">Tous les statuts</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="flex gap-3">
                    <button class="rounded-xl bg-cyan-400 px-4 py-3 text-sm font-semibold text-slate-950">Filtrer</button>
                    <a href="{{ route('students.index') }}" class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700">Réinitialiser</a>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Élève</th>
                            <th class="px-4 py-3">Classe</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Moyenne</th>
                            <th class="px-4 py-3">Mention</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $student)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-slate-900 text-sm font-bold text-white">
                                            @if($student->photo_path)
                                                <img src="{{ asset('storage/'.$student->photo_path) }}" alt="Photo" class="h-full w-full object-cover">
                                            @else
                                                {{ strtoupper(substr($student->first_name, 0, 1).substr($student->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $student->full_name }}</div>
                                            <div class="text-xs text-slate-500">{{ $student->gender }} • {{ optional($student->date_of_birth)->format('d/m/Y') ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $student->schoolClass?->name ?? 'Sans classe' }}</td>
                                <td class="px-4 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $student->status === 'actif' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($student->status) }}</span></td>
                                <td class="px-4 py-4 font-semibold text-slate-900">{{ $student->averageLabel() }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $student->mention() }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('students.show', $student) }}" class="rounded-full border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-700">Voir</a>
                                        <a href="{{ route('students.bulletin', $student) }}" class="rounded-full border border-cyan-300 px-3 py-1 text-xs font-semibold text-cyan-700">Bulletin</a>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                                            <a href="{{ route('students.edit', $student) }}" class="rounded-full border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-700">Modifier</a>
                                            <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Archiver cet élève ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-full border border-rose-300 px-3 py-1 text-xs font-semibold text-rose-700">Archiver</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Aucun élève trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-4">{{ $students->links() }}</div>
        </section>
    </div>
</x-app-layout>
