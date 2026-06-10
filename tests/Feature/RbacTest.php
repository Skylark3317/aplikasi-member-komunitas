<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * RBAC — Pengujian Role-Based Access Control
 *
 * TC RBAC-01 s/d RBAC-08
 * Memastikan setiap role tidak dapat mengakses URL role lain.
 */
class RbacTest extends TestCase
{
    use RefreshDatabase;

    private function makeMember(): User
    {
        return User::factory()->create([
            'role' => 'member', 'is_active' => true, 'email_verified_at' => now(),
        ]);
    }

    private function makePetugas(): User
    {
        return User::factory()->create([
            'role' => 'staff', 'is_active' => true, 'email_verified_at' => now(),
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // RBAC-01: Member mencoba akses URL Super Admin → ditolak
    // ──────────────────────────────────────────────────────────
    public function test_RBAC01_member_tidak_bisa_akses_halaman_admin(): void
    {
        $response = $this->actingAs($this->makeMember())
            ->get('/superadmin/kelol-akun');

        $response->assertStatus(403);
    }

    // ──────────────────────────────────────────────────────────
    // RBAC-02: Member mencoba akses URL Bendahara → ditolak
    // ──────────────────────────────────────────────────────────
    public function test_RBAC02_member_tidak_bisa_akses_halaman_keuangan(): void
    {
        $response = $this->actingAs($this->makeMember())
            ->get('/keuangan/pembayaran');

        $response->assertStatus(403);
    }

    // ──────────────────────────────────────────────────────────
    // RBAC-03: Member mencoba akses URL Petugas → ditolak
    // ──────────────────────────────────────────────────────────
    public function test_RBAC03_member_tidak_bisa_akses_halaman_petugas(): void
    {
        $response = $this->actingAs($this->makeMember())
            ->get('/petugas/konten');

        $response->assertStatus(403);
    }

    // ──────────────────────────────────────────────────────────
    // RBAC-04: Member mencoba akses URL Ketua → ditolak
    // ──────────────────────────────────────────────────────────
    public function test_RBAC04_member_tidak_bisa_akses_halaman_ketua(): void
    {
        $response = $this->actingAs($this->makeMember())
            ->get('/ketua/statistik');

        $response->assertStatus(403);
    }

    // ──────────────────────────────────────────────────────────
    // RBAC-05: Petugas mencoba akses URL Bendahara → ditolak
    // ──────────────────────────────────────────────────────────
    public function test_RBAC05_petugas_tidak_bisa_akses_halaman_keuangan(): void
    {
        $response = $this->actingAs($this->makePetugas())
            ->get('/keuangan/pembayaran');

        $response->assertStatus(403);
    }

    // ──────────────────────────────────────────────────────────
    // RBAC-06: Petugas mencoba akses URL Admin → ditolak
    // ──────────────────────────────────────────────────────────
    public function test_RBAC06_petugas_tidak_bisa_akses_halaman_admin(): void
    {
        $response = $this->actingAs($this->makePetugas())
            ->get('/superadmin/kelol-akun');

        $response->assertStatus(403);
    }

    // ──────────────────────────────────────────────────────────
    // RBAC-07: Guest (belum login) akses URL Member → redirect login
    // ──────────────────────────────────────────────────────────
    public function test_RBAC07_guest_tidak_bisa_akses_halaman_member(): void
    {
        $response = $this->get('/member/profil');

        $response->assertRedirect('/login');
    }

    // ──────────────────────────────────────────────────────────
    // RBAC-08: Guest akses URL Admin → redirect login
    // ──────────────────────────────────────────────────────────
    public function test_RBAC08_guest_tidak_bisa_akses_halaman_admin(): void
    {
        $response = $this->get('/superadmin/kelol-akun');

        $response->assertRedirect('/login');
    }
}
