<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * ADM — Pengujian fitur Super Admin
 *
 * TC ADM-01 s/d ADM-22
 * Mencakup: Kelola Akun (CRUD, toggle status) dan
 * Pengaturan Sistem (5 tab: Identitas, Kontak, Keanggotaan,
 * Kartu Member, Landing Page + Live Preview).
 */
class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create([
            'role'              => 'super_admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    // ══════════════════════════════════════════════════════════
    //  KELOLA AKUN
    // ══════════════════════════════════════════════════════════

    // ──────────────────────────────────────────────────────────
    // ADM-01: Lihat daftar semua akun
    // ──────────────────────────────────────────────────────────
    public function test_ADM01_superadmin_dapat_melihat_daftar_akun(): void
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/superadmin/kelol-akun');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-02: Lihat detail akun pengguna lain
    // ──────────────────────────────────────────────────────────
    public function test_ADM02_superadmin_dapat_melihat_detail_akun(): void
    {
        $admin  = $this->makeAdmin();
        $target = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($admin)
            ->get("/superadmin/kelol-akun/{$target->id}");

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-03: Form buat akun baru dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_ADM03_superadmin_dapat_membuka_form_buat_akun(): void
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/superadmin/kelol-akun/buat');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-04: Buat akun pengurus baru berhasil
    // ──────────────────────────────────────────────────────────
    public function test_ADM04_superadmin_dapat_membuat_akun_pengurus_baru(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/superadmin/kelol-akun', [
            'name'                  => 'Petugas Baru Test',
            'email'                 => 'petugasbaru@test.com',
            'telephone'             => '08123456789',
            'role'                  => 'staff',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'petugasbaru@test.com',
            'role'  => 'staff',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-05 (Negative): Buat akun dengan email duplikat → error
    // ──────────────────────────────────────────────────────────
    public function test_ADM05_buat_akun_dengan_email_duplikat_gagal(): void
    {
        $admin  = $this->makeAdmin();
        $exists = User::factory()->create(['email' => 'duplikat@test.com']);

        $response = $this->actingAs($admin)->post('/superadmin/kelol-akun', [
            'name'                  => 'Siapapun',
            'email'                 => 'duplikat@test.com',
            'telephone'             => '08123456789',
            'role'                  => 'staff',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-06 (Negative): Buat akun dengan field kosong → error validasi
    // ──────────────────────────────────────────────────────────
    public function test_ADM06_buat_akun_dengan_field_kosong_gagal_validasi(): void
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->post('/superadmin/kelol-akun', []);

        $response->assertSessionHasErrors(['name', 'email', 'role', 'password']);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-07: Toggle Status → nonaktifkan akun
    // ──────────────────────────────────────────────────────────
    public function test_ADM07_superadmin_dapat_menonaktifkan_akun(): void
    {
        $admin  = $this->makeAdmin();
        $target = User::factory()->create(['role' => 'staff', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->patch("/superadmin/kelol-akun/{$target->id}/toggle-status");

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id'        => $target->id,
            'is_active' => false,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-08: Akun nonaktif tidak dapat login
    // ──────────────────────────────────────────────────────────
    public function test_ADM08_akun_nonaktif_tidak_dapat_login(): void
    {
        // Buat user nonaktif
        $inactive = User::factory()->create([
            'role'              => 'staff',
            'is_active'         => false,
            'email_verified_at' => now(),
        ]);

        $this->post('/login', [
            'email'    => $inactive->email,
            'password' => 'password',
        ]);

        // Harus tetap sebagai guest (tidak terotentikasi)
        $this->assertGuest();
    }

    // ──────────────────────────────────────────────────────────
    // ADM-09: Toggle Status → aktifkan kembali akun yang nonaktif
    // ──────────────────────────────────────────────────────────
    public function test_ADM09_superadmin_dapat_mengaktifkan_kembali_akun(): void
    {
        $admin  = $this->makeAdmin();
        $target = User::factory()->create(['role' => 'staff', 'is_active' => false]);

        $response = $this->actingAs($admin)
            ->patch("/superadmin/kelol-akun/{$target->id}/toggle-status");

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id'        => $target->id,
            'is_active' => true,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-10: Hapus akun pengguna berhasil
    // ──────────────────────────────────────────────────────────
    public function test_ADM10_superadmin_dapat_menghapus_akun(): void
    {
        $admin  = $this->makeAdmin();
        $target = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($admin)
            ->delete("/superadmin/kelol-akun/{$target->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-11: Halaman Pengaturan dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_ADM11_superadmin_dapat_membuka_halaman_pengaturan(): void
    {
        $admin    = $this->makeAdmin();
        $response = $this->actingAs($admin)->get('/superadmin/pengaturan');

        $response->assertStatus(200);
    }

    // ══════════════════════════════════════════════════════════
    //  PENGATURAN SISTEM — Tab Identitas
    // ══════════════════════════════════════════════════════════

    // ──────────────────────────────────────────────────────────
    // ADM-12: Simpan pengaturan Identitas (nama komunitas)
    // ──────────────────────────────────────────────────────────
    public function test_ADM12_superadmin_dapat_mengubah_nama_komunitas(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'community_name' => 'Komunitas Baru Test',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key'   => 'community_name',
            'value' => 'Komunitas Baru Test',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-13: Upload logo komunitas format valid
    // ──────────────────────────────────────────────────────────
    public function test_ADM13_superadmin_dapat_upload_logo_komunitas(): void
    {
        Storage::fake('public');
        $admin = $this->makeAdmin();
        $logo  = UploadedFile::fake()->image('logo.jpg', 200, 200)->size(50);

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'community_name' => 'Komunitas Test',
            'logo'           => $logo,
        ]);

        $response->assertRedirect();
    }

    // ──────────────────────────────────────────────────────────
    // ADM-14 (Negative): Upload logo > 1MB → validasi error
    // ──────────────────────────────────────────────────────────
    public function test_ADM14_upload_logo_terlalu_besar_gagal_validasi(): void
    {
        Storage::fake('public');
        $admin   = $this->makeAdmin();
        $bigLogo = UploadedFile::fake()->image('logo_besar.jpg')->size(2048); // 2MB

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'logo' => $bigLogo,
        ]);

        $response->assertSessionHasErrors(['logo']);
    }

    // ══════════════════════════════════════════════════════════
    //  PENGATURAN SISTEM — Tab Kontak & Sosial
    // ══════════════════════════════════════════════════════════

    // ──────────────────────────────────────────────────────────
    // ADM-15: Simpan pengaturan kontak & media sosial
    // ──────────────────────────────────────────────────────────
    public function test_ADM15_superadmin_dapat_mengubah_kontak_dan_sosial(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'email'            => 'kontak@komunitas.com',
            'phone'            => '081234567890',
            'address'          => 'Jl. Komunitas No. 1',
            'social_instagram' => 'https://instagram.com/komunitas',
            'social_youtube'   => 'https://youtube.com/komunitas',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key'   => 'email',
            'value' => 'kontak@komunitas.com',
        ]);
    }

    // ══════════════════════════════════════════════════════════
    //  PENGATURAN SISTEM — Tab Keanggotaan
    // ══════════════════════════════════════════════════════════

    // ──────────────────────────────────────────────────────────
    // ADM-16: Simpan pengaturan biaya & rekening bank
    // ──────────────────────────────────────────────────────────
    public function test_ADM16_superadmin_dapat_mengubah_biaya_dan_rekening(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'membership_fee'      => 200000,
            'membership_duration' => 12,
            'invoice_countdown'   => 48,
            'bank_account_name'   => 'Bendahara AMK',
            'bank_account_number' => '0987654321',
            'bank_name'           => 'Bank BCA',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key'   => 'membership_fee',
            'value' => '200000',
        ]);
        $this->assertDatabaseHas('settings', [
            'key'   => 'bank_name',
            'value' => 'Bank BCA',
        ]);
    }

    // ══════════════════════════════════════════════════════════
    //  PENGATURAN SISTEM — Tab Kartu Member
    // ══════════════════════════════════════════════════════════

    // ──────────────────────────────────────────────────────────
    // ADM-17: Upload gambar latar kartu member berhasil
    // ──────────────────────────────────────────────────────────
    public function test_ADM17_superadmin_dapat_upload_background_kartu_member(): void
    {
        Storage::fake('public');
        $admin   = $this->makeAdmin();
        $cardBg  = UploadedFile::fake()->image('card_bg.jpg', 856, 540)->size(200);

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'card_background' => $cardBg,
        ]);

        $response->assertRedirect();
    }

    // ──────────────────────────────────────────────────────────
    // ADM-18 (Negative): Upload background kartu > 1MB → error
    // ──────────────────────────────────────────────────────────
    public function test_ADM18_upload_background_kartu_terlalu_besar_gagal(): void
    {
        Storage::fake('public');
        $admin  = $this->makeAdmin();
        $bigBg  = UploadedFile::fake()->image('card_bg_besar.jpg')->size(2048);

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'card_background' => $bigBg,
        ]);

        $response->assertSessionHasErrors(['card_background']);
    }

    // ══════════════════════════════════════════════════════════
    //  PENGATURAN SISTEM — Tab Landing Page
    // ══════════════════════════════════════════════════════════

    // ──────────────────────────────────────────────────────────
    // ADM-19: Simpan pengaturan warna primer & permukaan
    // ──────────────────────────────────────────────────────────
    public function test_ADM19_superadmin_dapat_mengubah_warna_utama(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'primary_color' => '#4f46e5',
            'surface_color' => '#1e1b4b',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key'   => 'primary_color',
            'value' => '#4f46e5',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-20: Simpan pengaturan Hero Section Landing Page
    // ──────────────────────────────────────────────────────────
    public function test_ADM20_superadmin_dapat_mengubah_hero_section(): void
    {
        Storage::fake('public');
        $admin   = $this->makeAdmin();
        $heroBg  = UploadedFile::fake()->image('hero_bg.jpg', 1920, 1080)->size(500);

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'hero_title'       => 'Selamat Datang di Komunitas Kami',
            'hero_description' => 'Deskripsi hero yang baru dan informatif.',
            'bg_image'         => $heroBg,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key'   => 'hero_title',
            'value' => 'Selamat Datang di Komunitas Kami',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-21: Simpan pengaturan About Section Landing Page
    // ──────────────────────────────────────────────────────────
    public function test_ADM21_superadmin_dapat_mengubah_about_section(): void
    {
        Storage::fake('public');
        $admin      = $this->makeAdmin();
        $aboutImage = UploadedFile::fake()->image('about.jpg', 800, 600)->size(300);

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'about_title'       => 'Tentang Komunitas Kami',
            'about_description' => 'Kami adalah komunitas yang berdedikasi.',
            'about_image'       => $aboutImage,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key'   => 'about_title',
            'value' => 'Tentang Komunitas Kami',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-22: Simpan statistik member di Landing Page
    // ──────────────────────────────────────────────────────────
    public function test_ADM22_superadmin_dapat_mengubah_statistik_member_landing(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'stat_member_aktif'    => 120,
            'stat_member_pasif'    => 45,
            'stat_member_company'  => 30,
            'stat_member_personal' => 90,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key'   => 'stat_member_aktif',
            'value' => '120',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-23: Update profil Super Admin berhasil
    // ──────────────────────────────────────────────────────────
    public function test_ADM23_superadmin_dapat_mengupdate_profil_pribadi(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->patch('/superadmin/profil', [
            'name'      => 'Super Admin Baru',
            'telephone' => '08100011122',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id'   => $admin->id,
            'name' => 'Super Admin Baru',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-24: Simpan pengaturan Template Surat (CV) berhasil
    // ──────────────────────────────────────────────────────────
    public function test_ADM24_superadmin_dapat_mengubah_template_surat_keanggotaan(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'cv_introduction' => 'Teks pembuka kustom',
            'cv_closing'      => 'Teks penutup kustom',
            'cv_city'         => 'Surakarta',
            'cv_signer_title' => 'Ketua Umum',
            'cv_signer_name'  => 'Ketua AMK',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key'   => 'cv_introduction',
            'value' => 'Teks pembuka kustom',
        ]);
        $this->assertDatabaseHas('settings', [
            'key'   => 'cv_closing',
            'value' => 'Teks penutup kustom',
        ]);
        $this->assertDatabaseHas('settings', [
            'key'   => 'cv_city',
            'value' => 'Surakarta',
        ]);
        $this->assertDatabaseHas('settings', [
            'key'   => 'cv_signer_title',
            'value' => 'Ketua Umum',
        ]);
        $this->assertDatabaseHas('settings', [
            'key'   => 'cv_signer_name',
            'value' => 'Ketua AMK',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // ADM-25: Upload tanda tangan ketua berhasil
    // ──────────────────────────────────────────────────────────
    public function test_ADM25_superadmin_dapat_upload_tanda_tangan_ketua(): void
    {
        Storage::fake('public');
        $admin = $this->makeAdmin();
        $signature = UploadedFile::fake()->image('signature.png', 100, 100)->size(50);

        $response = $this->actingAs($admin)->post('/superadmin/pengaturan', [
            'cv_signature_image' => $signature,
        ]);

        $response->assertRedirect();
    }
}
