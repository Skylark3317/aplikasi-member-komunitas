<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * KTA — Pengujian fitur Ketua (Leader)
 *
 * TC KTA-01 s/d KTA-06
 */
class KetuaTest extends TestCase
{
    use RefreshDatabase;

    private function makeKetua(): User
    {
        return User::factory()->create([
            'role'              => 'leader',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // KTA-01: Halaman statistik dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_KTA01_ketua_dapat_melihat_halaman_statistik(): void
    {
        $ketua    = $this->makeKetua();
        $response = $this->actingAs($ketua)->get('/ketua/statistik');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // KTA-02: Detail laporan keuangan dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_KTA02_ketua_dapat_melihat_detail_laporan_keuangan(): void
    {
        $ketua    = $this->makeKetua();
        $response = $this->actingAs($ketua)
            ->get('/ketua/statistik/detail/keuangan');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // KTA-03: Detail laporan member dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_KTA03_ketua_dapat_melihat_detail_laporan_member(): void
    {
        $ketua    = $this->makeKetua();
        $response = $this->actingAs($ketua)
            ->get('/ketua/statistik/detail/member');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // KTA-04: Export laporan keuangan berhasil menghasilkan file
    // ──────────────────────────────────────────────────────────
    public function test_KTA04_ketua_dapat_mengeksport_laporan_keuangan(): void
    {
        $ketua    = $this->makeKetua();
        $response = $this->actingAs($ketua)
            ->get('/ketua/statistik/detail/keuangan/export');

        // Harus mengembalikan file download (200 atau 302 redirect ke download)
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }

    // ──────────────────────────────────────────────────────────
    // KTA-05: Export laporan member berhasil menghasilkan file
    // ──────────────────────────────────────────────────────────
    public function test_KTA05_ketua_dapat_mengeksport_laporan_member(): void
    {
        $ketua    = $this->makeKetua();
        $response = $this->actingAs($ketua)
            ->get('/ketua/statistik/detail/member/export');

        $this->assertContains($response->getStatusCode(), [200, 302]);
    }

    // ──────────────────────────────────────────────────────────
    // KTA-06: Update profil Ketua berhasil
    // ──────────────────────────────────────────────────────────
    public function test_KTA06_ketua_dapat_mengupdate_profil_pribadi(): void
    {
        $ketua = $this->makeKetua();

        $response = $this->actingAs($ketua)->patch('/ketua/profil', [
            'name'      => 'Ketua Baru Update',
            'telephone' => '08200112233',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id'   => $ketua->id,
            'name' => 'Ketua Baru Update',
        ]);
    }
}
