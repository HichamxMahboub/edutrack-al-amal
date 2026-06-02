<x-app-layout>
    <x-slot name="header"><div><p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-700">Bulletin</p><h2 class="text-2xl font-bold text-slate-900">{{ $student->full_name }}</h2></div></x-slot>
    @include('bulletins._content', ['pdf' => false])
</x-app-layout>
