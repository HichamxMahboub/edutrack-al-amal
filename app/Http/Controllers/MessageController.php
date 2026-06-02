<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $messages = Message::with(['sender', 'recipient'])
            ->where('recipient_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('messages.inbox', [
            'messages' => $messages,
            'unreadCount' => Message::where('recipient_id', $user->id)->whereNull('read_at')->count(),
        ]);
    }

    public function sent(Request $request)
    {
        $user = $request->user();

        $messages = Message::with(['sender', 'recipient'])
            ->where('sender_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('messages.sent', compact('messages'));
    }

    public function create(Request $request)
    {
        return view('messages.create', [
            'recipients' => User::where('id', '!=', $request->user()->id)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
        ]);

        return redirect()->route('messages.show', $message)->with('success', 'Message envoyé.');
    }

    public function show(Request $request, Message $message)
    {
        $this->authorizeAccess($request, $message);

        if ($message->recipient_id === $request->user()->id) {
            $message->markAsRead();
        }

        $message->load(['sender', 'recipient']);

        return view('messages.show', compact('message'));
    }

    public function destroy(Request $request, Message $message)
    {
        $this->authorizeAccess($request, $message);

        $message->delete();

        return redirect()->route('messages.index')->with('success', 'Message supprimé.');
    }

    public function markAsRead(Request $request, Message $message)
    {
        $this->authorizeAccess($request, $message);

        if ($message->recipient_id === $request->user()->id) {
            $message->markAsRead();
        }

        return back()->with('success', 'Message marqué comme lu.');
    }

    private function authorizeAccess(Request $request, Message $message): void
    {
        abort_unless($message->sender_id === $request->user()->id || $message->recipient_id === $request->user()->id, 403);
    }
}
