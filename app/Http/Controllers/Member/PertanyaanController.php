<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PertanyaanController extends Controller
{
    /**
     * Menampilkan daftar pertanyaan milik member (langsung redirect ke room chat).
     */
    public function index()
    {
        // if (!auth()->user()->isPremium()) {
        //     abort(403, 'Akses ditolak. Fitur ini hanya untuk member premium.');
        // }

        $conversation = Conversation::firstOrCreate([
            'submitter_id' => auth()->id(),
        ]);

        return redirect()->route('member.pertanyaan.show', $conversation->id);
    }

    /**
     * Menampilkan percakapan Q&A (Database SQL)
     */
    public function show(Conversation $conversation)
    {
        // if (!auth()->user()->isPremium()) {
        //     abort(403, 'Akses ditolak. Fitur ini hanya untuk member premium.');
        // }

        if ($conversation->submitter_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Mark incoming messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversation->load(['submitter', 'messages.sender']);

        return Inertia::render('Member/Pertanyaan/Show', [
            'conversation' => $conversation
        ]);
    }

    /**
     * Menyimpan balasan chat member langsung ke database SQL
     */
    public function reply(Request $request, Conversation $conversation)
    {
        if (!auth()->user()->isPremium()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk member premium.');
        }

        if ($conversation->submitter_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => auth()->id(),
            'content'         => $request->content,
            'is_read'         => false,
        ]);

        return back()->with('success', 'Balasan dikirim.');
    }
}
