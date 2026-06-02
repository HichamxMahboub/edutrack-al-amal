@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="max-w-4xl space-y-6">
            <div class="inline-flex rounded-full border border-cyan-300/30 bg-cyan-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-cyan-100">Transformation digitale</div>
            <h1 class="text-4xl font-black tracking-tight text-white sm:text-6xl">EduTrack Al Amal comme cas d'école</h1>
            <p class="text-lg leading-8 text-slate-200">Cette page présente le projet selon les notions de transformation digitale du cours, avec un diagnostic, une stratégie, des axes de transformation et une logique de pilotage.</p>
        </div>

        <div class="mt-10 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                ['title' => 'Définition', 'text' => 'Transformation des activités scolaires par le numérique pour gagner en efficacité, traçabilité et pilotage.'],
                ['title' => 'Diagnostic', 'text' => 'Processus manuels, données dispersées, difficulté de consolidation et faible visibilité en temps réel.'],
                ['title' => 'Problématique', 'text' => 'Comment structurer un système simple, sécurisé et utile aux acteurs éducatifs ?'],
                ['title' => 'Vision', 'text' => 'Un système unifié centré sur l’élève, la donnée et la coordination interne.'],
                ['title' => 'Préalables', 'text' => 'Stratégie, leadership, organisation, client interne et protection des données.'],
                ['title' => 'Axe service', 'text' => 'Bulletins, messagerie, import/export et tableaux de bord pour servir le terrain.'],
            ] as $item)
                <article class="rounded-3xl border border-white/10 bg-white/10 p-5 text-slate-100 shadow-lg backdrop-blur">
                    <div class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-300">{{ $item['title'] }}</div>
                    <p class="mt-3 text-sm leading-6 text-slate-200">{{ $item['text'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-slate-50 py-16 text-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold">Axes de transformation</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ([
                            'Expérience utilisateur' => 'Interface claire, navigation rapide et accès par rôle.',
                            'Processus internes' => 'Saisie, archivage, filtrage et génération de bulletin centralisés.',
                            'Modèle de service' => 'Service plus réactif grâce à des données partagées.',
                            'Nouveaux rôles' => 'Administrateur, enseignant et encadrant avec responsabilités distinctes.'
                        ] as $title => $text)
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="font-semibold text-slate-900">{{ $title }}</div>
                                <div class="mt-1 text-sm leading-6 text-slate-600">{{ $text }}</div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold">Gestion de la data</h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            'Collecte', 'Stockage', 'Traitement', 'Visualisation', 'Décision', 'Qualité'
                        ] as $step)
                            <div class="rounded-2xl bg-cyan-50 p-4 text-cyan-900">{{ $step }}</div>
                        @endforeach
                    </div>
                    <div class="mt-6 rounded-2xl bg-slate-950 p-5 text-slate-100">
                        <div class="text-sm font-semibold text-cyan-300">Sécurité et confidentialité</div>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Accès par rôle, séparation des responsabilités, protection du compte, et conservation des données dans une base SQLite locale pour la démonstration.</p>
                    </div>
                </article>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold">Déploiement progressif et changement</h2>
                    <div class="mt-4 space-y-3">
                        @foreach ([
                            'Phase 1 - cadrage et diagnostic',
                            'Phase 2 - digitalisation des modules clés',
                            'Phase 3 - appropriation par les équipes',
                            'Phase 4 - pilotage et amélioration continue'
                        ] as $phase)
                            <div class="rounded-2xl border-l-4 border-cyan-400 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">{{ $phase }}</div>
                        @endforeach
                    </div>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-bold">Risques et maîtrise</h2>
                    <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                        <p>• Résistance au changement: accompagnement et formation.</p>
                        <p>• Données incohérentes: validation et standardisation.</p>
                        <p>• Usage limité: dashboard et gains visibles rapidement.</p>
                        <p>• Sécurité: contrôle d’accès et journalisation minimale.</p>
                    </div>
                </article>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-bold">Maturité digitale et perspectives</h2>
                <p class="mt-3 max-w-4xl text-sm leading-7 text-slate-600">EduTrack Al Amal situe l'association dans une trajectoire de maturité digitale intermédiaire: les processus sont déjà outillés, la donnée devient exploitable, et les prochaines étapes peuvent porter sur des alertes automatiques, des exports avancés, et des tableaux de bord encore plus fins.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    @foreach (['Suivi à 360°', 'Tableaux de bord', 'Bulletins PDF', 'Messagerie interne', 'Import / Export'] as $tag)
                        <span class="rounded-full bg-cyan-50 px-4 py-2 text-sm font-semibold text-cyan-800">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
