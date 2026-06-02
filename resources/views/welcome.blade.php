@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
            <div class="space-y-6">
                <div class="inline-flex rounded-full border border-cyan-300/30 bg-cyan-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-cyan-100">Association Al Amal</div>
                <h1 class="max-w-3xl text-4xl font-black tracking-tight text-white sm:text-6xl">EduTrack Al Amal</h1>
                <p class="max-w-2xl text-lg leading-8 text-slate-200">Plateforme digitale de suivi scolaire, social et pédagogique</p>
                <p class="max-w-2xl text-base leading-7 text-slate-300">Un outil de démonstration complet pour centraliser les élèves, les classes, les notes, la messagerie interne et le pilotage par indicateurs dans une logique de transformation digitale mesurable.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('login') }}" class="inline-flex items-center rounded-full bg-cyan-400 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">Connexion</a>
                    <a href="{{ route('transformation-digitale') }}" class="inline-flex items-center rounded-full border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:border-cyan-300">Voir l'approche digitale</a>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold text-white">360°</div>
                        <div class="mt-1 text-sm text-slate-300">Suivi intégré</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold text-white">Data</div>
                        <div class="mt-1 text-sm text-slate-300">Tableaux de bord</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold text-white">Role-based</div>
                        <div class="mt-1 text-sm text-slate-300">Accès sécurisé</div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-2xl shadow-slate-950/30 backdrop-blur">
                <div class="rounded-2xl bg-slate-950 p-6 text-slate-100">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div>
                            <div class="text-sm font-semibold text-cyan-300">Contexte</div>
                            <div class="mt-1 text-xl font-bold">Suivi scolaire modernisé</div>
                        </div>
                        <div class="rounded-full bg-cyan-400 px-3 py-1 text-xs font-bold text-slate-950">Prototype soutenance</div>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-white/5 p-4">
                            <div class="text-sm text-slate-300">Élèves</div>
                            <div class="mt-2 text-2xl font-bold">60+</div>
                            <div class="mt-1 text-xs text-slate-400">Données réalistes de démonstration</div>
                        </div>
                        <div class="rounded-2xl bg-white/5 p-4">
                            <div class="text-sm text-slate-300">Modules</div>
                            <div class="mt-2 text-2xl font-bold">8</div>
                            <div class="mt-1 text-xs text-slate-400">Élèves, classes, notes, messages, bulletins</div>
                        </div>
                        <div class="rounded-2xl bg-white/5 p-4">
                            <div class="text-sm text-slate-300">Sécurité</div>
                            <div class="mt-2 text-2xl font-bold">RBAC</div>
                            <div class="mt-1 text-xs text-slate-400">Accès par rôle</div>
                        </div>
                        <div class="rounded-2xl bg-white/5 p-4">
                            <div class="text-sm text-slate-300">Indicateurs</div>
                            <div class="mt-2 text-2xl font-bold">Charts</div>
                            <div class="mt-1 text-xs text-slate-400">Lecture rapide de la performance</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-16 text-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold">Contexte</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">L'association Al Amal suit des élèves avec des informations pédagogiques, sociales et administratives dispersées. EduTrack centralise ces données et améliore la coordination entre les acteurs internes.</p>
                </article>
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold">Problématique</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Comment digitaliser le suivi scolaire tout en garantissant un accès simple, des données fiables, des tableaux de bord utiles et une sécurité adaptée à un contexte associatif ?</p>
                </article>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    'Gestion des élèves et classes',
                    'Saisie et suivi des notes',
                    'Bulletins PDF et indicateurs',
                    'Messagerie interne et import/export'
                ] as $feature)
                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="h-2 w-16 rounded-full bg-cyan-400"></div>
                        <h3 class="mt-4 text-lg font-semibold">{{ $feature }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Fonctionnalité intégrée pour fluidifier les processus internes et améliorer la lecture des situations.</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold">Avant / Après digitalisation</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-rose-50 p-4">
                            <div class="text-sm font-semibold text-rose-700">Avant</div>
                            <p class="mt-2 text-sm text-rose-900">Données dispersées, suivi manuel, recherche lente, vision limitée.</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 p-4">
                            <div class="text-sm font-semibold text-emerald-700">Après</div>
                            <p class="mt-2 text-sm text-emerald-900">Centralisation, automatisation, indicateurs et accès sécurisé par rôle.</p>
                        </div>
                    </div>
                </article>
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold">Valeur ajoutée</h2>
                    <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                        <li>• Meilleure coordination entre enseignants et encadrants</li>
                        <li>• Décisions appuyées par les données</li>
                        <li>• Historisation des parcours élèves</li>
                        <li>• Démonstration claire des concepts du cours</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 text-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold">Notions du cours appliquées</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    'Diagnostic de l’existant',
                    'Stratégie digitale',
                    'Axes de transformation',
                    'Pilotage par indicateurs',
                    'Sécurité des données',
                    'Conduite du changement'
                ] as $topic)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">{{ $topic }}</div>
                @endforeach
            </div>
            <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-950 p-6 text-slate-100 shadow-xl">
                <div class="text-sm font-semibold text-cyan-300">Impact attendu</div>
                <p class="mt-3 max-w-4xl text-sm leading-7 text-slate-300">Une application démontrable, cohérente avec la transformation digitale, qui montre la maîtrise des données, la structuration des processus et la gouvernance métier dans un contexte associatif réel.</p>
            </div>
        </div>
    </section>
@endsection
