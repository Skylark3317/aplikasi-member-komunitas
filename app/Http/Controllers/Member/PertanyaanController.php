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
     * Mengecek apakah user aktif memiliki benefit "Tanya Jawab dengan Admin".
     */
    private function canAccessChat(): bool
    {
        $user    = auth()->user();
        $profile = $user->memberProfile()->with('plan')->first();

        if (!$profile || $profile->status !== 'active' || now()->gte($profile->expire_date)) {
            return false;
        }

        return $profile->hasBenefit('Tanya Jawab dengan Admin');
    }

    /**
     * Menampilkan daftar pertanyaan milik member (langsung redirect ke room chat).
     */
    public function index()
    {
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
        if ($conversation->submitter_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $canAccessChat = $this->canAccessChat();

        if ($canAccessChat) {
            // Mark incoming messages as read only if user has access
            Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', auth()->id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        $conversation->load(['submitter', 'messages.sender']);

        return Inertia::render('Member/Pertanyaan/Show', [
            'conversation'  => $conversation,
            'canAccessChat' => $canAccessChat,
        ]);
    }

    /**
     * Menyimpan balasan chat member langsung ke database SQL
     */
    public function reply(Request $request, Conversation $conversation)
    {
        if (!$this->canAccessChat()) {
            abort(403, 'Akses ditolak. Fitur Tanya Jawab dengan Admin hanya tersedia untuk paket yang menyertakan benefit ini.');
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
