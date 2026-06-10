<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Invoice;
use App\Models\MemberProfile;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * MBR — Pengujian fitur Member
 *
 * TC MBR-01 s/d MBR-25
 */
class MemberTest extends TestCase
{
    use RefreshDatabase;

    private function makeMember(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'role'              => 'member',
            'is_active'         => true,
            'email_verified_at' => now(),
        ], $attrs));
    }

    // ──────────────────────────────────────────────────────────
    // MBR-01: Halaman profil dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_MBR01_member_dapat_melihat_halaman_profil(): void
    {
        $member = $this->makeMember();

        $response = $this->actingAs($member)->get('/member/profil');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-02: Halaman edit profil dapat dibuka
    // ──────────────────────────────────────────────────────────
    public function test_MBR02_member_dapat_membuka_halaman_edit_profil(): void
    {
        $member = $this->makeMember();

        $response = $this->actingAs($member)->get('/member/profil/edit');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-03: Update profil berhasil tersimpan
    // ──────────────────────────────────────────────────────────
    public function test_MBR03_member_dapat_mengupdate_profil(): void
    {
        $member = $this->makeMember();

        $response = $this->actingAs($member)->post('/member/profil', [
            'name'        => 'Nama Baru Test',
            'telephone'   => '08123456789',
            'institution' => 'Universitas Test',
            'department'  => 'Teknik Informatika',
            'address'     => 'Jl. Test No. 1',
            'gender'      => 'L',
            'blood_type'  => 'A',
            'last_education' => 'S1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['name' => 'Nama Baru Test']);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-04: Upload avatar format valid
    // ──────────────────────────────────────────────────────────
    public function test_MBR04_member_dapat_upload_avatar_gambar_valid(): void
    {
        Storage::fake('public');
        $member = $this->makeMember();
        $file   = UploadedFile::fake()->image('avatar.jpg', 200, 200)->size(100);

        $response = $this->actingAs($member)->post('/member/profil', [
            'name'   => $member->name,
            'avatar' => $file,
        ]);

        $response->assertRedirect();
    }

    // ──────────────────────────────────────────────────────────
    // MBR-10: Halaman premium tampil dengan data paket
    // ──────────────────────────────────────────────────────────
    public function test_MBR10_member_dapat_melihat_halaman_premium(): void
    {
        $member   = $this->makeMember();
        $response = $this->actingAs($member)->get('/member/premium');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-11: Member bergabung premium → invoice terbuat
    // ──────────────────────────────────────────────────────────
    public function test_MBR11_member_dapat_membuat_invoice_premium(): void
    {
        $member = $this->makeMember();

        // Pastikan tidak ada invoice yang sudah ada
        $this->assertDatabaseMissing('invoices', ['user_id' => $member->id]);

        $response = $this->actingAs($member)->post('/member/premium/gabung');

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', ['user_id' => $member->id]);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-13: Member dapat membatalkan invoice yang pending
    // ──────────────────────────────────────────────────────────
    public function test_MBR13_member_dapat_membatalkan_invoice_pending(): void
    {
        $member  = $this->makeMember();
        $invoice = Invoice::create([
            'user_id'  => $member->id,
            'number'   => 'INV-TEST-001',
            'amount'   => 150000,
            'due_date' => now()->addHours(24),
        ]);

        $response = $this->actingAs($member)
            ->delete("/member/premium/pembayaran/{$invoice->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-14: Member dapat melihat detail invoice
    // ──────────────────────────────────────────────────────────
    public function test_MBR14_member_dapat_melihat_detail_invoice(): void
    {
        $member  = $this->makeMember();
        $invoice = Invoice::create([
            'user_id'  => $member->id,
            'number'   => 'INV-DETAIL-001',
            'amount'   => 150000,
            'due_date' => now()->addHours(24),
        ]);

        $response = $this->actingAs($member)
            ->get("/member/premium/pembayaran/{$invoice->id}");

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-15: Member upload bukti bayar format valid
    // ──────────────────────────────────────────────────────────
    public function test_MBR15_member_dapat_upload_bukti_bayar_gambar_valid(): void
    {
        Storage::fake('public');
        $member  = $this->makeMember();
        $invoice = Invoice::create([
            'user_id'  => $member->id,
            'number'   => 'INV-BAYAR-001',
            'amount'   => 150000,
            'due_date' => now()->addHours(24),
        ]);
        $file = UploadedFile::fake()->image('bukti.jpg')->size(200);

        $response = $this->actingAs($member)->post('/member/premium/bayar', [
            'invoice_id'          => $invoice->id,
            'payment_proof'       => $file,
            'account_holder_name' => 'Budi Test',
            'account_number'      => '123456789',
            'account_bank_name'   => 'Bank Test',
            'amount'              => 150000,
            'date'                => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', ['invoice_id' => $invoice->id]);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-19: Member non-aktif akses konten → status 200 (view dikontrol frontend)
    // ──────────────────────────────────────────────────────────
    public function test_MBR19_member_dapat_mengakses_halaman_konten(): void
    {
        $member   = $this->makeMember();
        $response = $this->actingAs($member)->get('/member/konten');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-21: Member dapat mengakses halaman chat (pertanyaan)
    // ──────────────────────────────────────────────────────────
    public function test_MBR21_member_dapat_membuka_halaman_konsultasi(): void
    {
        $member   = $this->makeMember();
        $response = $this->actingAs($member)->get('/member/pertanyaan');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-22: Member dapat mengirim balasan pada conversation
    // ──────────────────────────────────────────────────────────
    public function test_MBR22_member_dapat_mengirim_pesan_chat(): void
    {
        $member       = $this->makeMember();
        $conversation = Conversation::create(['submitter_id' => $member->id]);

        $response = $this->actingAs($member)
            ->post("/member/pertanyaan/{$conversation->id}/balas", [
                'content' => 'Ini pesan pertanyaan saya kepada petugas.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'content'         => 'Ini pesan pertanyaan saya kepada petugas.',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-23: Member kirim pesan kosong → gagal validasi
    // ──────────────────────────────────────────────────────────
    public function test_MBR23_member_tidak_bisa_kirim_pesan_kosong(): void
    {
        $member       = $this->makeMember();
        $conversation = Conversation::create(['submitter_id' => $member->id]);

        $response = $this->actingAs($member)
            ->post("/member/pertanyaan/{$conversation->id}/balas", [
                'content' => '',
            ]);

        $response->assertSessionHasErrors(['content']);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-24: Member dapat mengajukan request hapus akun
    // ──────────────────────────────────────────────────────────
    public function test_MBR24_member_dapat_mengajukan_hapus_akun(): void
    {
        $member   = $this->makeMember();
        $response = $this->actingAs($member)->post('/member/hapus-akun');

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            // delete_requested_at harus terisi
        ]);
        $member->refresh();
        $this->assertNotNull($member->delete_requested_at);
    }

    // ──────────────────────────────────────────────────────────
    // MBR-25: Member dapat membatalkan request hapus akun
    // ──────────────────────────────────────────────────────────
    public function test_MBR25_member_dapat_membatalkan_request_hapus_akun(): void
    {
        $member = $this->makeMember(['delete_requested_at' => now()]);

        $response = $this->actingAs($member)->delete('/member/hapus-akun');

        $response->assertRedirect();
        $member->refresh();
        $this->assertNull($member->delete_requested_at);
    }
}
