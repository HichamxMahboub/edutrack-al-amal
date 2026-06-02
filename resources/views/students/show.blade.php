<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Dossier élève</p>
                <h2 class="text-2xl font-bold text-slate-900">{{ $student->full_name }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('students.bulletin.pdf', $student) }}" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Exporter PDF</a>
                @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
                    <a href="{{ route('students.edit', $student) }}" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Modifier</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-5">
                    <div class="text-sm font-semibold text-slate-500">Moyenne générale</div>
                    <div class="mt-2 text-3xl font-bold text-slate-900">{{ $average !== null ? number_format($average, 2) : 'N/A' }}</div>
                    <div class="mt-2 text-sm text-slate-600">Mention: {{ $mention }}</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-5">
                    <div class="text-sm font-semibold text-slate-500">Classe</div>
                    <div class="mt-2 text-2xl font-bold text-slate-900">{{ $student->schoolClass?->name ?? 'Sans classe' }}</div>
                    <div class="mt-2 text-sm text-slate-600">{{ $student->schoolClass?->level ?? 'Niveau non défini' }}</div>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Matière</th>
                            <th class="px-4 py-3 text-left">Note</th>
                            <th class="px-4 py-3 text-left">Coef.</th>
                            <th class="px-4 py-3 text-left">Semestre</th>
                            <th class="px-4 py-3 text-left">Enseignant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($student->grades as $grade)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $grade->subject?->name }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $grade->score, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $grade->coefficient, 2) }}</td>
                                <td class="px-4 py-3">{{ $grade->semester }}</td>
                                <td class="px-4 py-3">{{ $grade->teacher?->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucune note disponible.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="space-y-6">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Informations</h3>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Date de naissance</dt><dd class="font-medium text-slate-900">{{ optional($student->date_of_birth)->format('d/m/Y') ?? 'N/A' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Genre</dt><dd class="font-medium text-slate-900">{{ $student->gender }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Téléphone parent</dt><dd class="font-medium text-slate-900">{{ $student->parent_phone ?? 'N/A' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Statut</dt><dd class="font-medium text-slate-900">{{ ucfirst($student->status) }}</dd></div>
                </dl>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Suivi social</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $student->social_status ?: 'Aucune information sociale saisie.' }}</p>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $student->medical_notes ?: 'Aucune note médicale saisie.' }}</p>
            </section>
        </aside>
    </div>
</x-app-layout>
