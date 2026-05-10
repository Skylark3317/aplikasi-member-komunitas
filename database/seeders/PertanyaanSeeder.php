<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class PertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        $members = User::where('role', 'member')->get();
        $staff = User::where('role', 'staff')->first();

        if ($members->isEmpty() || !$staff) {
            return;
        }

        $questions = [
            [
                'question' => 'Apakah data anggota aman di dalam aplikasi?',
                'replies' => [
                    ['sender' => 'staff', 'text' => 'Halo! Tentu saja, kami menggunakan enkripsi standar industri untuk melindungi semua data sensitif anggota.'],
                    ['sender' => 'member', 'text' => 'Terima kasih informasinya.'],
                ],
                'status' => false, // open
            ],
            [
                'question' => 'Bagaimana cara mendaftar sebagai member komunitas?',
                'replies' => [],
                'status' => false,
            ],
            [
                'question' => 'Bagaimana cara memperbarui data profil saya?',
                'replies' => [
                    ['sender' => 'staff', 'text' => 'Masuk ke akun Anda, lalu buka menu Profil. Pilih Edit Profil, kemudian ubah data yang diinginkan seperti foto, alamat, atau nomor kontak. Setelah selesai klik Simpan.'],
                    ['sender' => 'member', 'text' => 'Terima kasih atas jawabannya!'],
                    ['sender' => 'staff', 'text' => 'Sama-sama. Senang bisa membantu!'],
                ],
                'status' => true, // closed
            ],
            [
                'question' => 'Kapan event tahunan komunitas diadakan?',
                'replies' => [
                    ['sender' => 'staff', 'text' => 'Untuk tahun ini, event tahunan direncanakan pada bulan Desember. Pantau terus halaman Blog untuk pengumuman resminya.'],
                ],
                'status' => false,
            ],
        ];

        foreach ($questions as $index => $qData) {
            $member = $members->random();
            
            $conversation = Conversation::create([
                'submitter_id' => $member->id,
                'ticket_number' => 'TKT-' . str_pad($index + 101, 4, '0', STR_PAD_LEFT),
                'is_closed' => $qData['status'],
                'created_at' => now()->subDays(rand(1, 10))->subHours(rand(1, 23)),
            ]);

            // First message (the question)
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $member->id,
                'content' => $qData['question'],
                'created_at' => $conversation->created_at,
            ]);

            // Replies
            $lastTime = $conversation->created_at;
            foreach ($qData['replies'] as $reply) {
                $senderId = ($reply['sender'] === 'staff') ? $staff->id : $member->id;
                $lastTime = $lastTime->addMinutes(rand(5, 60));
                
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $senderId,
                    'content' => $reply['text'],
                    'created_at' => $lastTime,
                ]);
            }
        }
    }
}
