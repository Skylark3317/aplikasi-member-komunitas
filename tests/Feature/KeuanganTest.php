<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\MemberProfile;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * FIN — Pengujian fitur Keuangan/Bendahara
 *
 * TC FIN-01 s/d FIN-08
 */
class KeuanganTest extends TestCase
{
    use RefreshDatabase;

    private function makeBendahara(): User
    {
        return User::factory()->create([
            'role'              => 'finance',
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

    /** Helper: Buat invoice + payment siap verifikasi */
    private function makePaymentPending(User $member): array
    {
        Storage::fake('public');
        $invoice = Invoice::create([
            'user_id'  => $member->id,
            'number'   => 'INV-FIN-' . rand(1000, 9999),
            'amount'   => 150000,
            'due_date' => now()->addHours(24),
        ]);
        $payment = Payment::create([
            'invoice_id'          => $invoice->id,
            'payer_id'            => $member->id,
            'payment_proof_url'   => 'payments/bukti_test.jpg',
            'account_holder_name' => 'Budi Test',
            'account_number'      => '1234567890',
            'account_bank_name'   => 'Bank Test',
            'amount'              => 150000,
            'date'                => now(),
            'status'              => 'menunggu',
        ]);
        return [$invoice, $payment];
    }

    // ──────────────────────────────────────────────────────────
    // FIN-01: Lihat halaman daftar pembayaran
    // ──────────────────────────────────────────────────────────
    public function test_FIN01_bendahara_dapat_melihat_daftar_pembayaran(): void
    {
        $bendahara = $this->makeBendahara();
        $response  = $this->actingAs($bendahara)->get('/keuangan/pembayaran');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // FIN-02: Filter daftar pembayaran berdasarkan status
    // ──────────────────────────────────────────────────────────
    public function test_FIN02_bendahara_dapat_memfilter_pembayaran_berdasar_status(): void
    {
        $bendahara = $this->makeBendahara();
        $response  = $this->actingAs($bendahara)
            ->get('/keuangan/pembayaran?status=menunggu');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // FIN-03: Cari pembayaran berdasarkan nama member
    // ──────────────────────────────────────────────────────────
    public function test_FIN03_bendahara_dapat_mencari_pembayaran(): void
    {
        $bendahara = $this->makeBendahara();
        $response  = $this->actingAs($bendahara)
            ->get('/keuangan/pembayaran?search=budi');

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // FIN-04: Lihat detail pembayaran
    // ──────────────────────────────────────────────────────────
    public function test_FIN04_bendahara_dapat_melihat_detail_pembayaran(): void
    {
        $bendahara            = $this->makeBendahara();
        $member               = $this->makeMember();
        [$invoice, $payment]  = $this->makePaymentPending($member);

        $response = $this->actingAs($bendahara)
            ->get("/keuangan/pembayaran/{$payment->id}");

        $response->assertStatus(200);
    }

    // ──────────────────────────────────────────────────────────
    // FIN-05: Verifikasi (setujui) pembayaran → member jadi aktif
    // ──────────────────────────────────────────────────────────
    public function test_FIN05_bendahara_dapat_memverifikasi_pembayaran(): void
    {
        $bendahara            = $this->makeBendahara();
        $member               = $this->makeMember();
        [$invoice, $payment]  = $this->makePaymentPending($member);

        $response = $this->actingAs($bendahara)
            ->post("/keuangan/pembayaran/{$payment->id}/verify");

        $response->assertRedirect();

        // Status payment berubah jadi diverifikasi
        $this->assertDatabaseHas('payments', [
            'id'     => $payment->id,
            'status' => 'diverifikasi',
        ]);

        // Status invoice is_accepted = true
        $this->assertDatabaseHas('invoices', [
            'id'          => $invoice->id,
            'is_accepted' => true,
        ]);

        // MemberProfile member jadi aktif
        $this->assertDatabaseHas('member_profiles', [
            'member_id' => $member->id,
            'status'    => 'active',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // FIN-06: Tolak pembayaran dengan alasan → berhasil
    // ──────────────────────────────────────────────────────────
    public function test_FIN06_bendahara_dapat_menolak_pembayaran_dengan_alasan(): void
    {
        $bendahara            = $this->makeBendahara();
        $member               = $this->makeMember();
        [$invoice, $payment]  = $this->makePaymentPending($member);

        $response = $this->actingAs($bendahara)
            ->post("/keuangan/pembayaran/{$payment->id}/reject", [
                'reject_reason' => 'Gambar bukti transfer tidak terbaca / buram.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'id'            => $payment->id,
            'status'        => 'ditolak',
            'reject_reason' => 'Gambar bukti transfer tidak terbaca / buram.',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // FIN-07 (Negative): Tolak pembayaran TANPA alasan → validasi error
    // ──────────────────────────────────────────────────────────
    public function test_FIN07_penolakan_gagal_jika_alasan_kosong(): void
    {
        $bendahara            = $this->makeBendahara();
        $member               = $this->makeMember();
        [$invoice, $payment]  = $this->makePaymentPending($member);

        $response = $this->actingAs($bendahara)
            ->post("/keuangan/pembayaran/{$payment->id}/reject", [
                'reject_reason' => '',
            ]);

        $response->assertSessionHasErrors(['reject_reason']);

        // Status payment TIDAK berubah
        $this->assertDatabaseHas('payments', [
            'id'     => $payment->id,
            'status' => 'menunggu',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // FIN-08: Edit dan update profil Bendahara berhasil
    // ──────────────────────────────────────────────────────────
    public function test_FIN08_bendahara_dapat_mengupdate_profil_pribadi(): void
    {
        $bendahara = $this->makeBendahara();

        $response = $this->actingAs($bendahara)->patch('/keuangan/profil', [
            'name'      => 'Bendahara Baru',
            'telephone' => '08112233445',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id'   => $bendahara->id,
            'name' => 'Bendahara Baru',
        ]);
    }
}
