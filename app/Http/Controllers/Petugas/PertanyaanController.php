<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class PertanyaanController extends Controller
{
    /**
     * Menampilkan daftar semua pertanyaan.
     */
    public function index()
    {
        $conversations = Conversation::with(['submitter', 'messages' => function($q) {
            $q->latest();
        }])->latest()->get();

        // Menyisipkan balasan terakhir dari cache Redis (jika ada) ke dalam tampilan index
        foreach ($conversations as $conversation) {
            $rawCached = Redis::lrange("pertanyaan:conversation:{$conversation->id}:messages", 0, -1);
            if (!empty($rawCached)) {
                $latestCached = json_decode(end($rawCached), true);
                if ($latestCached) {
                    $sender = \App\Models\User::find($latestCached['sender_id']);
                    
                    // Buat model dummy untuk dirender di list index
                    $dummyMessage = new Message([
                        'conversation_id' => $conversation->id,
                        'sender_id' => $latestCached['sender_id'],
                        'content' => $latestCached['content'] . ' (Di-cache)',
                        'created_at' => $latestCached['timestamp'],
                    ]);
                    
                    if ($sender) {
                        $dummyMessage->setRelation('sender', $sender);
                    }
                    
                    $conversation->setRelation('messages', collect([$dummyMessage]));
                }
            }
        }

        return Inertia::render('Petugas/Pertanyaan/Index', [
            'conversations' => $conversations
        ]);
    }

    /**
     * Menampilkan detail pertanyaan (Menggabungkan Database SQL + Cache Redis)
     */
    public function show(Conversation $conversation)
    {
        $conversation->load(['submitter', 'messages.sender']);
        
        // Ambil semua balasan/pesan dari cache Redis
        $rawCached = Redis::lrange("pertanyaan:conversation:{$conversation->id}:messages", 0, -1);
        
        $cachedMessages = array_map(function ($msg) use ($conversation) {
            $data = json_decode($msg, true);
            $sender = \App\Models\User::find($data['sender_id']);
            
            return [
                'id' => 'cache-' . $data['timestamp'],
                'conversation_id' => $conversation->id,
                'sender_id' => $data['sender_id'],
                'content' => $data['content'],
                'created_at' => $data['timestamp'],
                'sender' => $sender ? [
                    'id' => $sender->id,
                    'name' => $sender->name,
                    'role' => $sender->role
                ] : null
            ];
        }, $rawCached);

        // Gabungkan data pesan dari SQL Database dengan data dari Redis Cache
        $dbMessages = $conversation->messages;
        $allMessages = $dbMessages->concat($cachedMessages);
        
        // Set relation messages agar dibaca transparan oleh komponen Vue
        $conversation->setRelation('messages', $allMessages);

        return Inertia::render('Petugas/Pertanyaan/Show', [
            'conversation' => $conversation
        ]);
    }

    /**
     * Menyimpan balasan chat dari member/petugas ke dalam Redis terlebih dahulu
     */
    public function reply(Request $request, Conversation $conversation)
    {
        if ($conversation->is_closed) {
            return back()->with('error', 'Tiket sudah ditutup.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        // Simpan data balasan ke Redis list
        $payload = [
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'content' => e($request->content), // XSS Protection
            'timestamp' => now()->toIso8601String()
        ];

        Redis::rpush("pertanyaan:conversation:{$conversation->id}:messages", json_encode($payload));
        
        // Refresh TTL: Sesi cache berlaku selama 7 hari
        Redis::expire("pertanyaan:conversation:{$conversation->id}:messages", 604800);

        return back()->with('success', 'Balasan dikirim ke cache Redis.');
    }

    /**
     * Menyelesaikan pertanyaan dan menyinkronkan seluruh pesan dari cache Redis ke SQL Database
     */
    public function close(Conversation $conversation)
    {
        if ($conversation->is_closed) {
            return back()->with('error', 'Pertanyaan sudah diselesaikan sebelumnya.');
        }

        // Ambil seluruh pesan dari cache Redis
        $rawCached = Redis::lrange("pertanyaan:conversation:{$conversation->id}:messages", 0, -1);

        DB::transaction(function () use ($conversation, $rawCached) {
            if (!empty($rawCached)) {
                $messagesPayload = [];
                foreach ($rawCached as $msg) {
                    $data = json_decode($msg, true);
                    $messagesPayload[] = [
                        'conversation_id' => $conversation->id,
                        'sender_id' => $data['sender_id'],
                        'content' => $data['content'],
                        'created_at' => \Illuminate\Support\Carbon::parse($data['timestamp'])->toDateTimeString(),
                        'updated_at' => now(),
                    ];
                }

                // Masukkan semua pesan sekaligus (Bulk Insert)
                Message::insert($messagesPayload);
            }

            // Tandai tiket selesai di database SQL
            $conversation->update([
                'is_closed' => true
            ]);

            // Bersihkan memori cache Redis karena data sudah aman di database SQL
            Redis::del("pertanyaan:conversation:{$conversation->id}:messages");
        });

        return back()->with('success', 'Pertanyaan selesai. Semua balasan telah berhasil disimpan ke database!');
    }
}
