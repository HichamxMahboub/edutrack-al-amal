<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Pilotage</p>
                <h2 class="text-2xl font-bold text-slate-900">Dashboard EduTrack Al Amal</h2>
                <p class="mt-1 text-sm text-slate-600">Vue de synthèse pour le suivi scolaire, social et pédagogique.</p>
            </div>
            <a href="{{ route('transformation-digitale') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Voir la transformation digitale</a>
        </div>
    </x-slot>

    @php
        $studentsByClassLabels = $studentsByClass->map(fn($class) => $class->name)->values();
        $studentsByClassValues = $studentsByClass->map(fn($class) => $class->students_count)->values();
        $mentionLabels = $mentionDistribution->keys()->values();
        $mentionValues = $mentionDistribution->values();
        $semesterLabels = $averagesBySemester->pluck('semester')->values();
        $semesterValues = $averagesBySemester->pluck('average')->values();
    @endphp

    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Élèves', 'value' => $studentCount, 'hint' => 'Dossiers actifs et archivés', 'tone' => 'bg-cyan-50 text-cyan-900'],
                ['label' => 'Classes', 'value' => $classCount, 'hint' => 'Unités de suivi', 'tone' => 'bg-blue-50 text-blue-900'],
                ['label' => 'Enseignants', 'value' => $teacherCount, 'hint' => 'Corps enseignant', 'tone' => 'bg-emerald-50 text-emerald-900'],
                ['label' => 'Messages', 'value' => $messageCount, 'hint' => $unreadMessages.' non lus', 'tone' => 'bg-amber-50 text-amber-900'],
            ] as $card)
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-sm font-semibold text-slate-500">{{ $card['label'] }}</div>
                    <div class="mt-2 text-3xl font-bold text-slate-900">{{ $card['value'] }}</div>
                    <div class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $card['tone'] }}">{{ $card['hint'] }}</div>
                </article>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Suivi pédagogique</h3>
                        <p class="text-sm text-slate-600">Moyenne générale, réussite et distribution des mentions.</p>
                    </div>
                    <div class="rounded-full bg-cyan-50 px-3 py-1 text-sm font-semibold text-cyan-800">Moyenne globale: {{ $globalAverage ?? 'N/A' }}</div>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-500">Taux de réussite</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900">{{ $successRate }}%</div>
                        <div class="mt-2 text-sm text-slate-600">Élèves ayant une moyenne >= 10/20.</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-500">Encadrants</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900">{{ $encadrantCount }}</div>
                        <div class="mt-2 text-sm text-slate-600">Accompagnement social et suivi de proximité.</div>
                    </div>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ($followUpIndicators as $indicator)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-sm font-semibold text-slate-500">{{ $indicator['label'] }}</div>
                            <div class="mt-2 flex items-end justify-between">
                                <div class="text-2xl font-bold text-slate-900">{{ $indicator['value'] }}</div>
                                <div class="text-xs font-semibold text-cyan-700">{{ $indicator['trend'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Indicateurs clés</h3>
                <div class="mt-4 space-y-4">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-500">Élèves archivés</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $archivedStudents }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-500">Messages non lus</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $unreadMessages }}</div>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-500">Classes sans élèves</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $studentsByClass->where('students_count', 0)->count() }}</div>
                    </div>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
                <h3 class="text-lg font-bold text-slate-900">Répartition analytique</h3>
                <div class="mt-5 grid gap-6 lg:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4 lg:col-span-1">
                        <div class="mb-4 text-sm font-semibold text-slate-500">Élèves par classe</div>
                        <canvas id="studentsByClassChart" height="220"></canvas>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 lg:col-span-1">
                        <div class="mb-4 text-sm font-semibold text-slate-500">Mentions</div>
                        <canvas id="mentionsChart" height="220"></canvas>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 lg:col-span-1">
                        <div class="mb-4 text-sm font-semibold text-slate-500">Évolution par semestre</div>
                        <canvas id="semesterChart" height="220"></canvas>
                    </div>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Lecture stratégique</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Le tableau de bord relie les données scolaires à la décision managériale : progression des élèves,
                    qualité de suivi, charge de travail, et coordination entre enseignants et encadrants.
                </p>
                <div class="mt-4 space-y-3">
                    <div class="rounded-2xl bg-cyan-50 px-4 py-3 text-sm font-medium text-cyan-900">Digitalisation des processus</div>
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">Pilotage par indicateurs</div>
                    <div class="rounded-2xl bg-blue-50 px-4 py-3 text-sm font-medium text-blue-900">Sécurisation des données</div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm font-medium text-slate-800">Conduite du changement</div>
                </div>
            </article>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const studentsByClassCtx = document.getElementById('studentsByClassChart');
                const mentionsCtx = document.getElementById('mentionsChart');
                const semesterCtx = document.getElementById('semesterChart');

                if (studentsByClassCtx && window.Chart) {
                    new window.Chart(studentsByClassCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($studentsByClassLabels),
                            datasets: [{
                                label: 'Élèves',
                                data: @json($studentsByClassValues),
                                backgroundColor: '#06b6d4',
                                borderRadius: 10,
                            }],
                        },
                        options: { responsive: true, plugins: { legend: { display: false } } },
                    });
                }

                if (mentionsCtx && window.Chart) {
                    new window.Chart(mentionsCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($mentionLabels),
                            datasets: [{
                                data: @json($mentionValues),
                                backgroundColor: ['#0f172a', '#0891b2', '#14b8a6', '#22c55e', '#f59e0b', '#ef4444'],
                            }],
                        },
                        options: { responsive: true },
                    });
                }

                if (semesterCtx && window.Chart) {
                    new window.Chart(semesterCtx, {
                        type: 'line',
                        data: {
                            labels: @json($semesterLabels),
                            datasets: [{
                                label: 'Moyenne',
                                data: @json($semesterValues),
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                                tension: 0.3,
                                fill: true,
                            }],
                        },
                        options: { responsive: true, plugins: { legend: { display: false } } },
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
