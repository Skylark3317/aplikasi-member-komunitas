<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * STF — Pengujian fitur Petugas (Staff)
 *
 * TC STF-01 s/d STF-14
 */
class PetugasTest extends TestCase
{
    use RefreshDatabase;

    private function makePetugas(): User
    {
        return User::factory()->create([
            'role'              => 'staff',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    private function makeMember(): User
    {
        return User::factory()->create([
            'role'              => 'member',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // STF-01: Lihat daftar chat masuk dari member
    // ──────────────────────────────────────────────────────────
    public function test_STF01_petugas_dapat_melihat_daftar_chat_member(): void
    {
        $petugas = $this->makePetugas();

        $response = $this->actingAs($petugas)->get('/petugas/pertanyaan');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // STF-02: Petugas dapat melihat detail chat dari member
    // ──────────────────────────────────────────────────────────
    public function test_STF02_petugas_dapat_membuka_detail_chat(): void
    {
        $petugas      = $this->makePetugas();
        $member       = $this->makeMember();
        $conversation = Conversation::create(['submitter_id' => $member->id]);

        $response = $this->actingAs($petugas)
            ->get("/petugas/pertanyaan/{$conversation->id}");

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // STF-02b: Petugas dapat membalas chat dari member
    // ──────────────────────────────────────────────────────────
    public function test_STF02b_petugas_dapat_membalas_chat_member(): void
    {
        $petugas      = $this->makePetugas();
        $member       = $this->makeMember();
        $conversation = Conversation::create(['submitter_id' => $member->id]);

        $response = $this->actingAs($petugas)
            ->post("/petugas/pertanyaan/{$conversation->id}/balas", [
                'content' => 'Ini balasan dari petugas untuk member.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'content'         => 'Ini balasan dari petugas untuk member.',
            'sender_id'       => $petugas->id,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // STF-03: Lihat daftar konten (Video/Ebook)
    // ──────────────────────────────────────────────────────────
    public function test_STF03_petugas_dapat_melihat_daftar_konten(): void
    {
        $petugas  = $this->makePetugas();
        $response = $this->actingAs($petugas)->get('/petugas/konten');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // STF-04: Halaman tambah konten dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_STF04_petugas_dapat_membuka_form_tambah_konten(): void
    {
        $petugas  = $this->makePetugas();
        $response = $this->actingAs($petugas)->get('/petugas/konten/buat');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // STF-05: Tambah konten Video baru berhasil
    // ──────────────────────────────────────────────────────────
    public function test_STF05_petugas_dapat_menambah_konten_video(): void
    {
        Storage::fake('public');
        $petugas = $this->makePetugas();
        $file    = UploadedFile::fake()->create('video.mp4', 1000, 'video/mp4');
        $thumb   = UploadedFile::fake()->image('thumb.jpg', 400, 300)->size(100);

        $response = $this->actingAs($petugas)->post('/petugas/konten', [
            'title'         => 'Video Tutorial PHP',
            'type'          => 'video',
            'file'          => $file,
            'thumbnail'     => $thumb,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contents', [
            'title'       => 'Video Tutorial PHP',
            'type'        => 'video',
            'uploader_id' => $petugas->id,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // STF-06: Tambah konten Ebook baru berhasil
    // ──────────────────────────────────────────────────────────
    public function test_STF06_petugas_dapat_menambah_konten_ebook(): void
    {
        Storage::fake('public');
        $petugas = $this->makePetugas();
        $file    = UploadedFile::fake()->create('ebook.pdf', 500, 'application/pdf');
        $thumb   = UploadedFile::fake()->image('thumb.jpg', 400, 300)->size(100);

        $response = $this->actingAs($petugas)->post('/petugas/konten', [
            'title'     => 'Ebook Laravel Dasar',
            'type'      => 'ebook',
            'file'      => $file,
            'thumbnail' => $thumb,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contents', [
            'title' => 'Ebook Laravel Dasar',
            'type'  => 'ebook',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // STF-07 (Negative): Tambah konten tanpa judul → validasi error
    // ──────────────────────────────────────────────────────────
    public function test_STF07_tambah_konten_tanpa_judul_gagal_validasi(): void
    {
        Storage::fake('public');
        $petugas = $this->makePetugas();
        $file    = UploadedFile::fake()->create('video.mp4', 500, 'video/mp4');

        $response = $this->actingAs($petugas)->post('/petugas/konten', [
            'title' => '',
            'type'  => 'video',
            'file'  => $file,
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    // ──────────────────────────────────────────────────────────
    // STF-08: Edit konten yang sudah ada
    // ──────────────────────────────────────────────────────────
    public function test_STF08_petugas_dapat_membuka_form_edit_konten(): void
    {
        $petugas = $this->makePetugas();
        $konten  = Content::create([
            'uploader_id' => $petugas->id,
            'title'       => 'Konten Lama',
            'type'        => 'video',
            'file_url'    => 'contents/video_lama.mp4',
        ]);

        $response = $this->actingAs($petugas)
            ->get("/petugas/konten/{$konten->id}/edit");

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // STF-09: Hapus konten berhasil
    // ──────────────────────────────────────────────────────────
    public function test_STF09_petugas_dapat_menghapus_konten(): void
    {
        Storage::fake('public');
        $petugas = $this->makePetugas();
        $konten  = Content::create([
            'uploader_id' => $petugas->id,
            'title'       => 'Konten Yang Akan Dihapus',
            'type'        => 'ebook',
            'file_url'    => 'contents/ebook_test.pdf',
        ]);

        $response = $this->actingAs($petugas)
            ->delete("/petugas/konten/{$konten->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('contents', ['id' => $konten->id]);
    }

    // ──────────────────────────────────────────────────────────
    // STF-10: Lihat daftar blog
    // ──────────────────────────────────────────────────────────
    public function test_STF10_petugas_dapat_melihat_daftar_blog(): void
    {
        $petugas  = $this->makePetugas();
        $response = $this->actingAs($petugas)->get('/petugas/blog');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // STF-11: Halaman buat blog dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_STF11_petugas_dapat_membuka_form_buat_blog(): void
    {
        $petugas  = $this->makePetugas();
        $response = $this->actingAs($petugas)->get('/petugas/blog/buat');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // STF-12: Buat artikel blog baru berhasil
    // ──────────────────────────────────────────────────────────
    public function test_STF12_petugas_dapat_membuat_artikel_blog_baru(): void
    {
        $petugas = $this->makePetugas();

        $response = $this->actingAs($petugas)->post('/petugas/blog', [
            'title'        => 'Judul Artikel Test',
            'excerpt'      => 'Ringkasan artikel test.',
            'content'      => '<p>Isi konten artikel lengkap.</p>',
            'published_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Judul Artikel Test']);
    }

    // ──────────────────────────────────────────────────────────
    // STF-13 (Negative): Buat blog tanpa judul → validasi error
    // ──────────────────────────────────────────────────────────
    public function test_STF13_buat_blog_tanpa_judul_gagal_validasi(): void
    {
        $petugas = $this->makePetugas();

        $response = $this->actingAs($petugas)->post('/petugas/blog', [
            'title'   => '',
            'content' => '<p>Isi ada tapi judul kosong.</p>',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    // ──────────────────────────────────────────────────────────
    // STF-14: Hapus artikel blog berhasil
    // ──────────────────────────────────────────────────────────
    public function test_STF14_petugas_dapat_menghapus_artikel_blog(): void
    {
        $petugas = $this->makePetugas();
        $post    = Post::create([
            'title'        => 'Artikel Yang Akan Dihapus',
            'slug'         => 'artikel-yang-akan-dihapus',
            'excerpt'      => 'Ringkasan.',
            'content'      => '<p>Isi artikel.</p>',
            'author_id'    => $petugas->id,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($petugas)
            ->delete("/petugas/blog/{$post->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    // ──────────────────────────────────────────────────────────
    // STF-15: Edit dan update profil Petugas berhasil
    // ──────────────────────────────────────────────────────────
    public function test_STF15_petugas_dapat_mengupdate_profil_pribadi(): void
    {
        $petugas = $this->makePetugas();

        $response = $this->actingAs($petugas)->patch('/petugas/profil', [
            'name'      => 'Nama Petugas Baru',
            'telephone' => '08199988877',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id'   => $petugas->id,
            'name' => 'Nama Petugas Baru',
        ]);
    }
}
