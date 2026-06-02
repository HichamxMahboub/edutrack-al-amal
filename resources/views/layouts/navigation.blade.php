@php($user = auth()->user())

<div class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col overflow-y-auto border-r border-slate-800 bg-slate-950 px-5 py-6 text-slate-100 shadow-2xl shadow-slate-900/30">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-400 text-sm font-bold text-slate-950">EA</span>
            <span>
                <span class="block text-base font-semibold">EduTrack Al Amal</span>
                <span class="block text-xs text-slate-300">{{ $user?->roleLabel ?? 'Espace connecté' }}</span>
            </span>
        </a>

        <nav class="mt-8 space-y-1 text-sm font-medium">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Dashboard</a>
            <a href="{{ route('students.index') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('students.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Élèves</a>
            <a href="{{ route('classes.index') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('classes.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Classes</a>
            @if($user?->isAdmin())
                <a href="{{ route('staff.index') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('staff.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Enseignants / Encadrants</a>
            @endif
            <a href="{{ route('grades.index') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('grades.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Notes</a>
            <a href="{{ route('students.index') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('students.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Bulletins</a>
            <a href="{{ route('messages.index') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('messages.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Messages</a>
            @if($user?->isAdmin())
                <a href="{{ route('import-export.index') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('import-export.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Import / Export</a>
            @endif
            <a href="{{ route('transformation-digitale') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('transformation-digitale') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Transformation digitale</a>
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between rounded-xl px-4 py-3 transition {{ request()->routeIs('profile.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">Profil</a>
        </nav>

        <div class="mt-auto rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
            <div class="font-semibold text-white">{{ $user?->name }}</div>
            <div class="mt-1">{{ $user?->email }}</div>
            <div class="mt-3 flex items-center gap-2">
                <span class="rounded-full bg-cyan-400/15 px-3 py-1 text-xs font-semibold text-cyan-200">{{ $user?->roleLabel }}</span>
                <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold text-slate-200 transition hover:border-rose-300 hover:text-rose-200">Déconnexion</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="lg:hidden">
    <div class="border-b border-slate-200 bg-white px-4 py-4 shadow-sm">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-400 text-sm font-bold text-slate-950">EA</span>
                <span class="text-sm font-semibold text-slate-900">EduTrack Al Amal</span>
            </a>
            <button type="button" @click="sidebarOpen = !sidebarOpen" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700">Menu</button>
        </div>
    </div>

    <div x-show="sidebarOpen" x-transition class="border-b border-slate-200 bg-slate-950 px-4 py-4 text-slate-100" style="display:none;">
        <nav class="space-y-1 text-sm font-medium">
            <a href="{{ route('dashboard') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Dashboard</a>
            <a href="{{ route('students.index') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('students.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Élèves</a>
            <a href="{{ route('classes.index') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('classes.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Classes</a>
            @if($user?->isAdmin())
                <a href="{{ route('staff.index') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('staff.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Enseignants / Encadrants</a>
                <a href="{{ route('import-export.index') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('import-export.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Import / Export</a>
            @endif
            <a href="{{ route('grades.index') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('grades.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Notes</a>
            <a href="{{ route('messages.index') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('messages.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Messages</a>
            <a href="{{ route('transformation-digitale') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('transformation-digitale') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Transformation digitale</a>
            <a href="{{ route('profile.edit') }}" class="block rounded-xl px-4 py-3 {{ request()->routeIs('profile.*') ? 'bg-cyan-400 text-slate-950' : 'text-slate-200' }}">Profil</a>
        </nav>
    </div>
</div>
