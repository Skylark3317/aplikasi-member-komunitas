<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PertanyaanController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with(['submitter', 'messages' => function($q) {
            $q->latest();
        }])->latest()->get();

        return Inertia::render('Petugas/Pertanyaan/Index', [
            'conversations' => $conversations
        ]);
    }

    public function show(Conversation $conversation)
    {
        $conversation->load(['submitter', 'messages.sender']);
        
        return Inertia::render('Petugas/Pertanyaan/Show', [
            'conversation' => $conversation
        ]);
    }

    public function reply(Request $request, Conversation $conversation)
    {
        if ($conversation->is_closed) {
            return back()->with('error', 'Tiket sudah ditutup.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Balasan terkirim.');
    }
}
