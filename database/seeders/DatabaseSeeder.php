<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Payment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Settings ─────────────────────────────────────────────
        $this->call(SettingsSeeder::class);

        // ── Super Admin ──────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'superadmin@amk.com'],
            [
                'name'              => 'Met Slamet',
                'password'          => Hash::make('Password123'),
                'role'              => 'super_admin',
                'telephone'         => '081234567890',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // ── Users ──────────────────────────────────────────────
        $ketua = User::updateOrCreate(
            ['email' => 'ketua@amk.com'],
            [
                'name'              => 'Jo Bejo',
                'password'          => Hash::make('Password123'),
                'role'              => 'leader',
                'telephone'         => '081200000001',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // ── Petugas (Staff) ──────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'staff@amk.com'],
            [
                'name'              => 'Gus Agus',
                'password'          => Hash::make('Password123'),
                'role'              => 'staff',
                'telephone'         => '081200000002',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // ── Keuangan ──────────────────────────────────
        $keuangan = User::updateOrCreate(
            ['email' => 'keuangan@amk.com'],
            [
                'name'              => 'Jo Paijo',
                'password'          => Hash::make('Password123'),
                'role'              => 'finance',
                'telephone'         => '081200000003',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $members = [
            ['name' => 'Nem Painem',  'email' => 'nem@amk.com'],
            ['name' => 'Siti Rahayu', 'email' => 'siti@amk.com'],
            ['name' => 'Budi Santoso','email' => 'budi@amk.com'],
        ];

        $memberModels = [];
        foreach ($members as $m) {
            $memberModels[] = User::updateOrCreate(
                ['email' => $m['email']],
                [
                    'name'              => $m['name'],
                    'password'          => Hash::make('Password123'),
                    'role'              => 'member',
                    'telephone'         => '082991919192811',
                    'email_verified_at' => now(),
                ]
            );
        }

        // ── Categories ─────────────────────────────────────────
        $catSemua  = Category::updateOrCreate(['slug' => 'semua'], ['name' => 'Semua']);
        $catBerita = Category::updateOrCreate(['slug' => 'berita'], ['name' => 'Berita']);
        $catAcara  = Category::updateOrCreate(['slug' => 'acara'], ['name' => 'Acara']);

        // ── Posts ──────────────────────────────────────────────
        $posts = [
            [
                'title'        => 'Kolaborasi Komunitas Dorong Inovasi Digital Lokal',
                'slug'         => 'kolaborasi-komunitas-dorong-inovasi-digital-lokal',
                'excerpt'      => 'Sinergi antar anggota komunitas terbukti menjadi kunci lahirnya berbagai solusi digital yang relevan dengan kebutuhan masyarakat lokal.',
                'content'      => '<p>Perkembangan teknologi digital tidak selalu lahir dari perusahaan besar atau pusat inovasi global. Di berbagai daerah, justru komunitas lokal menjadi motor penggerak munculnya solusi digital yang relevan, inklusif, dan berdampak langsung bagi masyarakat sekitar.</p><p>Kolaborasi antar anggota komunitas—dengan latar belakang yang beragam—telah membuka jalan bagi terciptanya inovasi yang berakar pada kebutuhan nyata di lapangan.</p><p>Di banyak kota dan daerah, komunitas teknologi, kreatif, hingga sosial mulai saling terhubung dan berbagi sumber daya. Mereka tidak hanya bertukar ide, tetapi juga membangun proyek bersama. Mulai dari aplikasi layanan publik sederhana, platform pemasaran UMKM, hingga sistem informasi desa—semuanya lahir dari semangat gotong royong digital.</p><p>Salah satu kekuatan utama dari kolaborasi komunitas adalah pemahaman kontekstual yang kuat. Anggota komunitas biasanya adalah bagian dari masyarakat itu sendiri, sehingga mereka memahami masalah yang dihadapi secara langsung. Hal ini membuat solusi yang dihasilkan lebih tepat guna dibandingkan pendekatan top-down yang sering kali kurang sesuai dengan kondisi lokal.</p>',
                'category_id'  => $catBerita->id,
                'published_at' => now()->subDays(0),
            ],
            [
                'title'        => 'Generasi Muda Semakin Aktif dalam Kegiatan Sosial',
                'slug'         => 'generasi-muda-semakin-aktif-dalam-kegiatan-sosial',
                'excerpt'      => 'Tren keterlibatan anak muda dalam berbagai gerakan sosial terus meningkat.',
                'content'      => '<p>Tren keterlibatan anak muda dalam berbagai gerakan sosial terus meningkat. Dari kegiatan lingkungan hingga pemberdayaan masyarakat, generasi Z dan milenial membuktikan bahwa perubahan bisa dimulai dari aksi nyata.</p><p>Data menunjukkan bahwa partisipasi pemuda dalam kegiatan sukarela meningkat signifikan dalam tiga tahun terakhir. Mereka tidak hanya hadir sebagai peserta, tetapi juga sebagai penggagas dan pemimpin perubahan.</p>',
                'category_id'  => $catBerita->id,
                'published_at' => now()->subDays(1),
            ],
            [
                'title'        => 'Pemanfaatan Teknologi Tingkatkan Produktivitas UMKM',
                'slug'         => 'pemanfaatan-teknologi-tingkatkan-produktivitas-umkm',
                'excerpt'      => 'Adopsi teknologi digital membantu pelaku UMKM meningkatkan efisiensi operasional dan memperluas jangkauan pasar.',
                'content'      => '<p>Transformasi digital kini menjadi kebutuhan bagi pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) dalam menghadapi persaingan pasar yang semakin ketat. Dengan memanfaatkan teknologi seperti marketplace, media sosial, dan sistem pembayaran digital, UMKM mampu menjangkau konsumen lebih luas.</p><p>Tidak hanya itu, penggunaan aplikasi pencatatan keuangan dan manajemen stok juga membantu pelaku usaha dalam mengelola bisnis secara lebih efisien dan terstruktur.</p><p>Berbagai pelatihan dan pendampingan dari komunitas maupun pemerintah turut mendorong percepatan adopsi teknologi ini. Hasilnya, banyak UMKM yang mengalami peningkatan omzet serta daya saing di pasar lokal maupun nasional.</p>',
                'category_id'  => $catBerita->id,
                'published_at' => now()->subDays(2),
            ],
            [
                'title'        => 'Pentingnya Literasi Digital di Era Informasi',
                'slug'         => 'pentingnya-literasi-digital-di-era-informasi',
                'excerpt'      => 'Kemampuan memahami dan menyaring informasi digital menjadi keterampilan penting di tengah arus informasi yang cepat.',
                'content'      => '<p>Di era informasi yang serba cepat, literasi digital menjadi keterampilan dasar yang harus dimiliki oleh setiap individu. Kemampuan untuk memahami, mengevaluasi, dan menggunakan informasi secara bijak sangat penting untuk menghindari penyebaran hoaks.</p><p>Berbagai komunitas dan lembaga pendidikan kini активно menggalakkan program literasi digital melalui workshop, seminar, dan kampanye online. Tujuannya adalah meningkatkan kesadaran masyarakat dalam menggunakan teknologi secara bertanggung jawab.</p><p>Dengan literasi digital yang baik, masyarakat tidak hanya menjadi konsumen informasi, tetapi juga mampu berkontribusi secara positif dalam ekosistem digital yang sehat.</p>',
                'category_id'  => $catBerita->id,
                'published_at' => now()->subDays(3),
            ],
            [
                'title'        => 'Workshop Pengembangan Diri Diminati Banyak Peserta',
                'slug'         => 'workshop-pengembangan-diri-diminati-banyak-peserta',
                'excerpt'      => 'Antusiasme masyarakat terhadap kegiatan pelatihan dan pengembangan diri semakin tinggi.',
                'content'      => '<p>Antusiasme masyarakat terhadap kegiatan pelatihan dan pengembangan diri semakin tinggi. Workshop yang mengangkat tema produktivitas, mindset, dan keterampilan hidup ini berhasil menarik ratusan peserta dari berbagai latar belakang.</p><p>Para fasilitator berpengalaman berbagi ilmu tentang manajemen waktu, kecerdasan emosional, dan strategi mencapai tujuan hidup. Peserta pulang dengan bekal baru yang siap diaplikasikan dalam kehidupan sehari-hari.</p>',
                'category_id'  => $catAcara->id,
                'published_at' => now()->subDays(4),
            ],
            [
                'title'        => 'Komunitas Lokal Gelar Event Kreatif Tahunan',
                'slug'         => 'komunitas-lokal-gelar-event-kreatif-tahunan',
                'excerpt'      => 'Ajang tahunan yang dinantikan kembali hadir, menghadirkan ruang ekspresi bagi para seniman, pelaku budaya, dan kreator lokal.',
                'content'      => '<p>Ajang tahunan yang dinantikan kembali hadir, menghadirkan ruang ekspresi bagi para seniman, pelaku budaya, dan kreator lokal. Event ini menjadi bukti nyata bahwa kreativitas komunitas mampu menyatukan banyak pihak.</p><p>Ratusan karya dipamerkan dalam berbagai medium: lukisan, instalasi, pertunjukan musik, dan pameran foto. Pengunjung dari berbagai daerah datang untuk menyaksikan keberagaman ekspresi budaya yang kaya.</p>',
                'category_id'  => $catAcara->id,
                'published_at' => now()->subDays(12),
            ],
            [
                'title'        => 'Pentingnya Jejaring dalam Dunia Profesional Modern',
                'slug'         => 'pentingnya-jejaring-dalam-dunia-profesional-modern',
                'excerpt'      => 'Di era kompetisi global, membangun relasi yang kuat bukan lagi sekadar pilihan—melainkan keharusan.',
                'content'      => '<p>Di era kompetisi global, membangun relasi yang kuat bukan lagi sekadar pilihan—melainkan keharusan. Artikel ini mengulas strategi efektif membangun jejaring profesional yang bermakna dan berkelanjutan.</p><p>Networking yang baik bukan hanya soal mengumpulkan kontak, melainkan membangun hubungan yang saling menguntungkan dan dilandasi kepercayaan. Mulailah dengan memberikan nilai kepada orang lain sebelum mengharapkan sesuatu kembali.</p>',
                'category_id'  => $catBerita->id,
                'published_at' => now()->subDays(43),
            ],
            [
                'title'        => 'Komunitas Gelar Workshop Digital Untuk Pemula',
                'slug'         => 'komunitas-gelar-workshop-digital-untuk-pemula',
                'excerpt'      => 'Tidak perlu latar belakang teknis untuk mulai belajar dunia digital.',
                'content'      => '<p>Tidak perlu latar belakang teknis untuk mulai belajar dunia digital. Workshop ramah pemula ini dirancang khusus untuk memperkenalkan keterampilan digital dasar kepada masyarakat yang ingin meningkatkan kompetensinya.</p><p>Materi meliputi penggunaan aplikasi produktivitas, pengenalan media sosial untuk bisnis, dan keamanan digital dasar. Semua dibawakan dengan bahasa yang mudah dipahami oleh siapa saja.</p>',
                'category_id'  => $catAcara->id,
                'published_at' => now()->subDays(65),
            ],
            [
                'title'        => 'Seminar Nasional Buka Wawasan Tentang Tren Teknologi Terkini',
                'slug'         => 'seminar-nasional-buka-wawasan-tentang-tren-teknologi-terkini',
                'excerpt'      => 'Para pakar dan praktisi berkumpul dalam seminar nasional untuk berbagi pandangan tentang perkembangan AI, otomasi, dan transformasi digital.',
                'content'      => '<p>Para pakar dan praktisi berkumpul dalam seminar nasional untuk berbagi pandangan tentang perkembangan AI, otomasi, dan transformasi digital. Wawasan baru siap membuka perspektif Anda tentang masa depan teknologi.</p><p>Seminar ini menghadirkan pembicara dari berbagai industri, mulai dari startup teknologi, perusahaan multinasional, hingga akademisi terkemuka. Diskusi berlangsung dinamis dengan pertanyaan-pertanyaan tajam dari peserta.</p>',
                'category_id'  => $catAcara->id,
                'published_at' => now()->subDays(124),
            ],
            [
                'title'        => 'Event Kolaborasi Hadirkan Berbagai Kegiatan Inspiratif',
                'slug'         => 'event-kolaborasi-hadirkan-berbagai-kegiatan-inspiratif',
                'excerpt'      => 'Dalam satu panggung yang sama, puluhan komunitas bersatu untuk menghadirkan rangkaian kegiatan yang memotivasi dan menginspirasi.',
                'content'      => '<p>Dalam satu panggung yang sama, puluhan komunitas bersatu untuk menghadirkan rangkaian kegiatan yang memotivasi dan menginspirasi. Dari talk show hingga pameran karya, setiap sudut event ini menyimpan cerita yang layak untuk diikuti.</p><p>Kolaborasi antar komunitas ini membuktikan bahwa ketika berbagai kelompok bersatu dengan visi yang sama, hasilnya jauh melampaui apa yang bisa dicapai secara individual.</p>',
                'category_id'  => $catAcara->id,
                'published_at' => now()->subDays(174),
            ],
        ];

        foreach ($posts as $postData) {
            Post::create(array_merge($postData, ['author_id' => $memberModels[0]->id]));
        }

        // ── Invoices & Payments ───────────────────────────────────────────
        $invoiceStatuses = [true, false];
        $invoiceIndex = 1;
        foreach ($memberModels as $member) {
            for ($i = 1; $i <= 5; $i++) {
                $invoice = \App\Models\Invoice::create([
                    'user_id' => $member->id,
                    'number' => 'INV' . str_pad($invoiceIndex++, 5, '0', STR_PAD_LEFT),
                    'amount' => 50000,
                    'due_date' => now()->addDays(rand(1, 30)),
                    'is_accepted' => $invoiceStatuses[array_rand($invoiceStatuses)],
                ]);

                // Create a payment for some invoices
                if (rand(0, 1)) {
                    $status = ['menunggu', 'diverifikasi', 'ditolak'][rand(0, 2)];
                    
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'payer_id' => $member->id,
                        'verifier_id' => $status === 'menunggu' ? null : $keuangan->id,
                        'payment_proof_url' => 'https://via.placeholder.com/300x400?text=Bukti+Pembayaran',
                        'account_holder_name' => $member->name,
                        'account_number' => '000000000000',
                        'account_bank_name' => 'Bank BRI',
                        'amount' => 50000,
                        'date' => now()->subDays(rand(1, 10)),
                        'status' => $status,
                        'verified_at' => $status === 'menunggu' ? null : now(),
                    ]);

                    // If payment is verified, update invoice
                    if ($status === 'diverifikasi') {
                        $invoice->update(['is_accepted' => true]);
                    }
                }
            }
        }

        // ── Dummy Content ──────────────────────────────────────
        $this->call(DummyContentSeeder::class);
        $this->call(PertanyaanSeeder::class);
    }
}
