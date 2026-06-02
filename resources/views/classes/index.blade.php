<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Organisation</p>
                <h2 class="text-2xl font-bold text-slate-900">Classes</h2>
            </div>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('classes.create') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Nouvelle classe</a>
            @endif
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Classe</th>
                    <th class="px-4 py-3 text-left">Enseignant</th>
                    <th class="px-4 py-3 text-left">Élèves</th>
                    <th class="px-4 py-3 text-left">Moyenne</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($classes as $class)
                    <tr>
                        <td class="px-4 py-4">
                            <div class="font-semibold text-slate-900">{{ $class->name }}</div>
                            <div class="text-xs text-slate-500">{{ $class->level }} • {{ $class->school_year }}</div>
                        </td>
                        <td class="px-4 py-4 text-slate-600">{{ $class->teacher?->name ?? 'Aucun' }}</td>
                        <td class="px-4 py-4 font-semibold text-slate-900">{{ $class->students_count }}</td>
                        <td class="px-4 py-4 font-semibold text-slate-900">{{ $class->averageLabel() }}</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('classes.show', $class) }}" class="rounded-full border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-700">Voir</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('classes.edit', $class) }}" class="rounded-full border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-700">Modifier</a>
                                    <form method="POST" action="{{ route('classes.destroy', $class) }}" onsubmit="return confirm('Supprimer cette classe ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-full border border-rose-300 px-3 py-1 text-xs font-semibold text-rose-700">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucune classe enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-slate-200 bg-slate-50 px-4 py-4">{{ $classes->links() }}</div>
    </div>
</x-app-layout>
