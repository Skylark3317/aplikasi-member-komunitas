<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;

class DummyPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        // Check if there are invoices, if not create some
        $invoices = Invoice::all();
        if ($invoices->isEmpty()) {
            for ($i = 1; $i <= 20; $i++) {
                Invoice::create([
                    'user_id' => $users->random()->id,
                    'number' => 'INV-' . time() . '-' . $i,
                    'amount' => rand(50000, 500000),
                    'is_accepted' => true,
                    'due_date' => Carbon::now()->addDays(7),
                ]);
            }
            $invoices = Invoice::all();
        }

        $statuses = ['menunggu', 'diverifikasi', 'ditolak'];
        $banks = ['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI'];

        for ($i = 1; $i <= 15; $i++) {
            $invoice = $invoices->random();
            $status = $statuses[array_rand($statuses)];
            
            Payment::create([
                'invoice_id' => $invoice->id,
                'payer_id' => $invoice->user_id,
                'payment_proof_url' => 'dummy_proof_' . $i . '.jpg',
                'account_holder_name' => 'John Doe ' . $i,
                'account_number' => '123456789' . $i,
                'account_bank_name' => $banks[array_rand($banks)],
                'amount' => $invoice->amount,
                'date' => Carbon::now()->subDays(rand(1, 30)),
                'status' => $status,
                'reject_reason' => $status === 'ditolak' ? 'Bukti transfer tidak jelas' : null,
                'verified_at' => $status === 'diverifikasi' ? Carbon::now() : null,
            ]);
        }
    }
}
