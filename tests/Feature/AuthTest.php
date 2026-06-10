<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * AUTH — Pengujian Login, Logout, dan Autentikasi
 *
 * TC AUTH-01 s/d AUTH-09
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────────────────
    // AUTH-01: Login Member berhasil → diarahkan ke dashboard
    // ──────────────────────────────────────────────────────────
    public function test_AUTH01_member_dapat_login_dengan_kredensial_valid(): void
    {
        $member = User::factory()->create([
            'role'     => 'member',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email'    => $member->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect();
    }

    // ──────────────────────────────────────────────────────────
    // AUTH-02: Login password salah → ditolak
    // ──────────────────────────────────────────────────────────
    public function test_AUTH02_login_ditolak_jika_password_salah(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password-salah-banget',
        ]);

        $this->assertGuest();
    }

    // ──────────────────────────────────────────────────────────
    // AUTH-03: Login email tidak terdaftar → ditolak
    // ──────────────────────────────────────────────────────────
    public function test_AUTH03_login_ditolak_jika_email_tidak_terdaftar(): void
    {
        $this->post('/login', [
            'email'    => 'emailtidakada@contoh.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    // ──────────────────────────────────────────────────────────
    // AUTH-04: Login field kosong → validasi error
    // ──────────────────────────────────────────────────────────
    public function test_AUTH04_login_gagal_jika_field_kosong(): void
    {
        $response = $this->post('/login', [
            'email'    => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    // ──────────────────────────────────────────────────────────
    // AUTH-05 ~ AUTH-08: Setiap role diarahkan ke URL yang benar
    // ──────────────────────────────────────────────────────────
    public function test_AUTH05_super_admin_login_dan_dapat_akses_dashboard_admin(): void
    {
        $admin = User::factory()->create([
            'role'     => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/superadmin/kelol-akun');
        $response->assertStatus(200);
    }

    public function test_AUTH06_ketua_dapat_akses_halaman_statistik(): void
    {
        $ketua = User::factory()->create([
            'role'     => 'leader',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($ketua)->get('/ketua/statistik');
        $response->assertStatus(200);
    }

    public function test_AUTH07_petugas_dapat_akses_halaman_konten(): void
    {
        $petugas = User::factory()->create([
            'role'     => 'staff',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($petugas)->get('/petugas/konten');
        $response->assertStatus(200);
    }

    public function test_AUTH08_bendahara_dapat_akses_halaman_pembayaran(): void
    {
        $bendahara = User::factory()->create([
            'role'     => 'finance',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($bendahara)->get('/keuangan/pembayaran');
        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // AUTH-09: Logout berhasil
    // ──────────────────────────────────────────────────────────
    public function test_AUTH09_pengguna_dapat_logout(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
