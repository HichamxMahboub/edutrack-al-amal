<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Classes</p>
            <h2 class="text-2xl font-bold text-slate-900">Modifier {{ $class->name }}</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        @include('classes._form')
    </div>
</x-app-layout>
