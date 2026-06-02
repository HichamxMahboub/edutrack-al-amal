@php($isEdit = false)
<form method="POST" action="{{ route('messages.store') }}" class="space-y-6">
    @csrf
    <label class="block"><span class="text-sm font-semibold text-slate-700">Destinataire</span><select name="recipient_id" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>@foreach($recipients as $recipient)<option value="{{ $recipient->id }}">{{ $recipient->name }} — {{ $recipient->roleLabel }}</option>@endforeach</select></label>
    <label class="block"><span class="text-sm font-semibold text-slate-700">Sujet</span><input name="subject" value="{{ old('subject') }}" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required></label>
    <label class="block"><span class="text-sm font-semibold text-slate-700">Message</span><textarea name="body" rows="8" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>{{ old('body') }}</textarea></label>
    <div class="flex items-center gap-3"><button class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Envoyer</button><a href="{{ route('messages.index') }}" class="rounded-full border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700">Annuler</a></div>
</form>
