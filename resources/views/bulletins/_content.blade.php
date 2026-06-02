<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">EduTrack Al Amal</div>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Bulletin scolaire</h1>
        </div>
        @if(!($pdf ?? false))
            <a href="{{ route('students.bulletin.pdf', $student) }}" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Exporter PDF</a>
        @endif
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-slate-50 p-4"><div class="text-xs font-semibold text-slate-500">Élève</div><div class="mt-1 font-bold text-slate-900">{{ $student->full_name }}</div></div>
        <div class="rounded-2xl bg-slate-50 p-4"><div class="text-xs font-semibold text-slate-500">Classe</div><div class="mt-1 font-bold text-slate-900">{{ $student->schoolClass?->name ?? 'N/A' }}</div></div>
        <div class="rounded-2xl bg-slate-50 p-4"><div class="text-xs font-semibold text-slate-500">Moyenne</div><div class="mt-1 font-bold text-slate-900">{{ $average !== null ? number_format($average, 2) : 'N/A' }}</div></div>
        <div class="rounded-2xl bg-slate-50 p-4"><div class="text-xs font-semibold text-slate-500">Mention</div><div class="mt-1 font-bold text-slate-900">{{ $mention }}</div></div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-600"><tr><th class="px-4 py-3 text-left">Matière</th><th class="px-4 py-3 text-left">Note</th><th class="px-4 py-3 text-left">Coef.</th><th class="px-4 py-3 text-left">Semestre</th><th class="px-4 py-3 text-left">Observation</th></tr></thead>
            <tbody class="divide-y divide-slate-100">@forelse($student->grades as $grade)<tr><td class="px-4 py-3 font-medium text-slate-900">{{ $grade->subject?->name }}</td><td class="px-4 py-3">{{ number_format((float) $grade->score, 2) }}</td><td class="px-4 py-3">{{ number_format((float) $grade->coefficient, 2) }}</td><td class="px-4 py-3">{{ $grade->semester }}</td><td class="px-4 py-3">{{ $grade->observation ?? 'N/A' }}</td></tr>@empty<tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucune note.</td></tr>@endforelse</tbody>
        </table>
    </div>

    <div class="mt-6 rounded-2xl bg-slate-950 p-5 text-slate-100">
        <div class="text-sm font-semibold text-cyan-300">Observation générale</div>
        <p class="mt-2 text-sm leading-7 text-slate-300">Le bulletin présente une lecture synthétique de la performance de l’élève et permet un export PDF propre pour la soutenance et l’archivage.</p>
    </div>
</div>
