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

        $chatHistories = [
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Apakah data anggota aman di dalam aplikasi? Saya agak khawatir data personal bocor.'],
                    ['sender' => 'staff', 'text' => 'Halo! Tentu saja aman. Kami menggunakan enkripsi standar industri (SSL/TLS) dan mengamankan password menggunakan hash bcrypt.'],
                    ['sender' => 'member', 'text' => 'Terima kasih informasinya, sangat melegakan.'],
                    ['sender' => 'staff', 'text' => 'Sama-sama. Jika ada pertanyaan lain seputar keamanan, silakan hubungi kami lagi.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Bagaimana cara memperbarui data profil saya seperti alamat dan no telepon?'],
                    ['sender' => 'staff', 'text' => 'Halo! Silakan masuk ke akun Anda, lalu buka menu Profil di sudut kanan bawah/samping. Klik tombol "Edit Profil", ubah datanya, lalu klik "Simpan Changes".'],
                    ['sender' => 'member', 'text' => 'Baik, akan saya coba sekarang.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Kapan event tahunan komunitas diadakan?'],
                    ['sender' => 'staff', 'text' => 'Halo! Untuk tahun ini, event tahunan direncanakan pada bulan Desember. Pantau terus halaman Blog kami untuk pengumuman resminya ya!'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Halo min, saya baru gabung premium. Bagaimana cara mengakses video tutorial di dashboard?'],
                    ['sender' => 'staff', 'text' => 'Selamat bergabung! Anda cukup pergi ke menu Konten. Di sana Anda akan melihat daftar Video dan E-Book yang sudah terbuka secara otomatis.'],
                    ['sender' => 'member', 'text' => 'Oh iya sudah kelihatan semua videonya. Terima kasih ya.'],
                    ['sender' => 'staff', 'text' => 'Sama-sama! Selamat belajar.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Apakah ada rekomendasi e-book untuk pemula yang ingin belajar pemrograman web?'],
                    ['sender' => 'staff', 'text' => 'Halo! Kami merekomendasikan e-book "Dasar Pemrograman Web Modern" yang ada di menu Konten. E-book tersebut membahas HTML, CSS, dan Javascript dari dasar.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Saya sudah transfer pembayaran premium tapi statusnya masih menunggu verifikasi. Tolong dibantu.'],
                    ['sender' => 'staff', 'text' => 'Halo! Mohon maaf atas keterlambatannya. Verifikasi pembayaran dilakukan maksimal 1x24 jam oleh bagian keuangan. Silakan ditunggu atau kirim bukti bayar yang jelas.'],
                    ['sender' => 'member', 'text' => 'Sudah saya upload bukti transfernya min. Terima kasih.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Di mana saya bisa melihat riwayat tagihan dan invoice pembayaran saya?'],
                    ['sender' => 'staff', 'text' => 'Halo! Anda bisa masuk ke menu Pembayaran. Di sana terdapat daftar lengkap invoice lama beserta status pembayarannya.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Min, apakah bisa mengganti email terdaftar di akun komunitas?'],
                    ['sender' => 'staff', 'text' => 'Halo! Untuk alasan keamanan, pergantian email harus melalui verifikasi admin. Silakan kirimkan email lama, email baru, dan foto kartu identitas Anda ke cs@amk.com.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Apa saja keuntungan utama menjadi member premium dibandingkan member biasa?'],
                    ['sender' => 'staff', 'text' => 'Halo! Dengan menjadi member premium, Anda mendapat akses tak terbatas ke seluruh konten video tutorial, e-book eksklusif, serta fitur tanya jawab langsung dengan petugas.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Apakah aplikasi member komunitas ini sudah tersedia di Play Store?'],
                    ['sender' => 'staff', 'text' => 'Halo! Saat ini aplikasi kami berbasis web responsif, jadi Anda bisa mengaksesnya dengan nyaman lewat browser HP. Versi Play Store sedang dalam tahap pengembangan.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Apakah pembayaran premium bisa di-refund jika saya salah transfer nominal?'],
                    ['sender' => 'staff', 'text' => 'Halo! Untuk kelebihan transfer nominal, silakan hubungi bagian keuangan untuk proses pengembalian dana selisihnya.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Saya ingin menyumbang artikel untuk dipublish di blog komunitas. Bagaimana caranya?'],
                    ['sender' => 'staff', 'text' => 'Halo! Menarik sekali. Silakan kirim draft artikel Anda dalam format Markdown atau Word ke email redaksi@amk.com. Tim kami akan melakukan kurasi.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Bagaimana cara reset password jika saya lupa password akun saya?'],
                    ['sender' => 'staff', 'text' => 'Halo! Anda bisa klik tombol "Lupa Password" di halaman login. Sistem akan mengirimkan link reset password ke email terdaftar Anda.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Kapan jadwal webinar online terdekat untuk anggota komunitas?'],
                    ['sender' => 'staff', 'text' => 'Halo! Webinar terdekat bertema "Membangun Karir di Dunia IT" akan diadakan pada tanggal 15 bulan depan. Detail pendaftaran akan kami publish di blog minggu ini.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Apakah sesama anggota komunitas bisa saling berkirim pesan secara privat?'],
                    ['sender' => 'staff', 'text' => 'Halo! Saat ini fitur chat privat antar anggota belum tersedia demi menjaga kenyamanan bersama. Anda bisa berinteraksi di forum komunitas atau grup Telegram resmi.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Apakah ada kode diskon khusus untuk mahasiswa yang ingin daftar premium?'],
                    ['sender' => 'staff', 'text' => 'Halo! Saat ini tarif premium flat Rp50.000 untuk semua kalangan. Namun kami sering mengadakan promo potongan harga pada hari-hari besar nasional.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Apakah komunitas ini sering mengadakan pertemuan offline (kopi darat)?'],
                    ['sender' => 'staff', 'text' => 'Halo! Ya, kami rutin mengadakan kopi darat regional setiap 3 bulan sekali. Informasi gathering regional akan dibagikan lewat newsletter email.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Saya ingin bertanya tentang kurikulum materi pembelajaran web di sini. Apakah ada update berkala?'],
                    ['sender' => 'staff', 'text' => 'Halo! Tentu saja. Tim kurikulum kami selalu memperbarui materi setiap bulan agar tetap relevan dengan industri saat ini.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Bagaimana cara memperpanjang masa aktif premium jika sudah habis?'],
                    ['sender' => 'staff', 'text' => 'Halo! Ketika masa aktif habis, Anda akan mendapat invoice baru di menu Pembayaran. Anda cukup melakukan pembayaran seperti biasa untuk perpanjangan otomatis.'],
                ]
            ],
            [
                'messages' => [
                    ['sender' => 'member', 'text' => 'Di mana saya bisa membaca syarat dan ketentuan penggunaan aplikasi ini?'],
                    ['sender' => 'staff', 'text' => 'Halo! Syarat dan ketentuan lengkap dapat Anda baca di link "Terms of Service" yang terletak pada bagian footer halaman utama.'],
                ]
            ]
        ];

        foreach ($chatHistories as $index => $history) {
            if ($index >= $members->count()) {
                break;
            }
            $member = $members[$index];

            $conversation = Conversation::create([
                'submitter_id' => $member->id,
                'created_at' => now()->subDays(rand(1, 10))->subHours(rand(1, 23)),
            ]);

            $lastTime = $conversation->created_at;
            foreach ($history['messages'] as $msgData) {
                $senderId = ($msgData['sender'] === 'staff') ? $staff->id : $member->id;
                $lastTime = $lastTime->copy()->addMinutes(rand(5, 60));

                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $senderId,
                    'content' => $msgData['text'],
                    'is_read' => true,
                    'created_at' => $lastTime,
                ]);
            }
        }
    }
}
