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
     * Menampilkan daftar tiket pertanyaan milik member.
     */
    public function index()
    {
        $conversations = Conversation::with(['submitter', 'messages' => function($q) {
            $q->latest();
        }])
        ->where('submitter_id', auth()->id())
        ->latest()
        ->get();

        return Inertia::render('Member/Pertanyaan/Index', [
            'conversations' => $conversations
        ]);
    }

    /**
     * Menampilkan form untuk membuat pertanyaan baru.
     */
    public function create()
    {
        if (!auth()->user()->isPremium()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk member premium.');
        }

        return Inertia::render('Member/Pertanyaan/Create');
    }

    /**
     * Menyimpan pertanyaan baru ke database.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isPremium()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk member premium.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $ticketCount = Conversation::count() + 101;
        $ticketNumber = 'TKT-' . str_pad($ticketCount, 4, '0', STR_PAD_LEFT);

        $conversation = Conversation::create([
            'submitter_id'  => auth()->id(),
            'ticket_number' => $ticketNumber,
            'is_closed'     => false,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => auth()->id(),
            'content'         => $request->content,
        ]);

        return redirect()->route('member.pertanyaan.show', $conversation->id)
            ->with('success', 'Pertanyaan berhasil diajukan.');
    }

    /**
     * Menampilkan percakapan Q&A (Database SQL)
     */
    public function show(Conversation $conversation)
    {
        if (!auth()->user()->isPremium()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk member premium.');
        }

        if ($conversation->submitter_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

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

        if ($conversation->is_closed) {
            return back()->with('error', 'Tiket sudah ditutup.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => auth()->id(),
            'content'         => $request->content,
        ]);

        return back()->with('success', 'Balasan dikirim.');
    }

    /**
     * Menyelesaikan pertanyaan di database SQL
     */
    public function close(Conversation $conversation)
    {
        if (!auth()->user()->isPremium()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk member premium.');
        }

        if ($conversation->submitter_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($conversation->is_closed) {
            return back()->with('error', 'Pertanyaan sudah diselesaikan sebelumnya.');
        }

        $conversation->update([
            'is_closed' => true
        ]);

        return back()->with('success', 'Pertanyaan ini telah ditandai sebagai selesai.');
    }
}
