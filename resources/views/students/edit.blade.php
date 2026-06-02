<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Élèves</p>
            <h2 class="text-2xl font-bold text-slate-900">Modifier {{ $student->full_name }}</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        @include('students._form')
    </div>
</x-app-layout>
