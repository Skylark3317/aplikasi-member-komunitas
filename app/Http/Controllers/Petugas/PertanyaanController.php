<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PertanyaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Conversation::with(['submitter', 'messages.sender']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('submitter', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhereHas('messages', function($mq) use ($search) {
                    $mq->where('content', 'like', "%{$search}%");
                });
            });
        }

        $conversations = $query->get()->map(function($c) {
            $messages = $c->messages->sortBy('created_at');
            $lastMessage = $messages->last();
            $unreadCount = $c->messages->where('sender_id', $c->submitter_id)->where('is_read', false)->count();

            return [
                'id' => $c->id,
                'submitter' => [
                    'id' => $c->submitter->id,
                    'name' => $c->submitter->name,
                    'avatar_url' => $c->submitter->avatar_url,
                ],
                'last_message' => $lastMessage ? [
                    'content' => $lastMessage->content,
                    'created_at' => $lastMessage->created_at->toISOString(),
                    'is_read' => $lastMessage->is_read,
                    'sender_id' => $lastMessage->sender_id,
                ] : null,
                'unread_count' => $unreadCount,
                'updated_at' => $c->updated_at->toISOString(),
            ];
        })->sortByDesc('updated_at')->values();

        return Inertia::render('Petugas/Pertanyaan/Index', [
            'conversations' => $conversations,
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Menampilkan detail pertanyaan (Database SQL)
     */
    public function show(Conversation $conversation, Request $request)
    {
        // Mark member's messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', $conversation->submitter_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $query = Conversation::with(['submitter', 'messages.sender']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('submitter', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhereHas('messages', function($mq) use ($search) {
                    $mq->where('content', 'like', "%{$search}%");
                });
            });
        }

        $conversations = $query->get()->map(function($c) use ($conversation) {
            $messages = $c->messages->sortBy('created_at');
            $lastMessage = $messages->last();
            
            // If it is the currently open conversation, unread count is 0 because we just marked them as read above
            $unreadCount = ($c->id === $conversation->id)
                ? 0
                : $c->messages->where('sender_id', $c->submitter_id)->where('is_read', false)->count();

            return [
                'id' => $c->id,
                'submitter' => [
                    'id' => $c->submitter->id,
                    'name' => $c->submitter->name,
                    'avatar_url' => $c->submitter->avatar_url,
                ],
                'last_message' => $lastMessage ? [
                    'content' => $lastMessage->content,
                    'created_at' => $lastMessage->created_at->toISOString(),
                    'is_read' => $lastMessage->is_read,
                    'sender_id' => $lastMessage->sender_id,
                ] : null,
                'unread_count' => $unreadCount,
                'updated_at' => $c->updated_at->toISOString(),
            ];
        })->sortByDesc('updated_at')->values();

        $conversation->load(['submitter', 'messages.sender']);

        return Inertia::render('Petugas/Pertanyaan/Show', [
            'conversations' => $conversations,
            'conversation' => $conversation,
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Menyimpan balasan chat dari member/petugas langsung ke database SQL
     */
    public function reply(Request $request, Conversation $conversation)
    {
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
