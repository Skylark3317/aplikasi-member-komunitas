<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PUB — Pengujian halaman publik (Guest / tanpa login)
 *
 * TC PUB-01 s/d PUB-05
 */
class PublicPageTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────
    // PUB-01: Landing Page (/) dapat diakses guest
    // ──────────────────────────────────────────────────────────
    public function test_PUB01_landing_page_dapat_diakses_tanpa_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // PUB-02: Halaman daftar Blog publik dapat diakses
    // ──────────────────────────────────────────────────────────
    public function test_PUB02_halaman_daftar_blog_dapat_diakses_tanpa_login(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // PUB-03: Search blog publik berjalan
    // ──────────────────────────────────────────────────────────
    public function test_PUB03_pencarian_blog_publik_berfungsi(): void
    {
        $response = $this->get('/blog/search?q=komunitas');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // PUB-04: Halaman detail artikel blog dapat dibuka via slug
    // ──────────────────────────────────────────────────────────
    public function test_PUB04_detail_artikel_blog_dapat_dibuka(): void
    {
        $author = User::factory()->create(['role' => 'staff']);
        $post   = Post::create([
            'title'        => 'Artikel Test Publik',
            'slug'         => 'artikel-test-publik',
            'excerpt'      => 'Ringkasan artikel test.',
            'content'      => '<p>Isi artikel test publik yang bisa dibaca semua orang.</p>',
            'author_id'    => $author->id,
            'published_at' => now(),
        ]);

        $response = $this->get("/blog/{$post->slug}");

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // PUB-05: Artikel yang belum dipublish tidak tampil di publik
    // ──────────────────────────────────────────────────────────
    public function test_PUB05_artikel_draft_tidak_tampil_di_publik(): void
    {
        $author = User::factory()->create(['role' => 'staff']);
        $post   = Post::create([
            'title'        => 'Artikel Draft Rahasia',
            'slug'         => 'artikel-draft-rahasia',
            'excerpt'      => 'Draft.',
            'content'      => '<p>Isi draft.</p>',
            'author_id'    => $author->id,
            'published_at' => null, // belum dipublish
        ]);

        $response = $this->get("/blog/{$post->slug}");

        // Harus 404 atau redirect, bukan 200
        $this->assertContains($response->getStatusCode(), [404, 302]);
    }
}
