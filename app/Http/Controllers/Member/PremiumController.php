<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\MemberProfile;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PremiumController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $status = $user->membershipStatus();

        if ($status === 'pending_invoice' || $status === 'pending_verification') {
            $latestInvoice = $user->invoices()->latest()->first();
            if ($latestInvoice) {
                return redirect()->route('member.premium.payment_detail', ['invoice' => $latestInvoice->id])
                    ->with('info', 'Anda masih memiliki pesanan yang belum diselesaikan.');
            }
        }

        // Get bank settings
        $settings = [
            'bank_name'           => Setting::get('bank_name', 'Bank BRI'),
            'bank_account_number' => Setting::get('bank_account_number', '000000001111'),
            'bank_account_name'   => Setting::get('bank_account_name', 'AMK'),
            'membership_fee'      => Setting::get('membership_fee', 50000),
        ];

        return Inertia::render('Member/Premium/Index', [
            'settings' => $settings,
            'isPremium' => $user->is_premium,
        ]);
    }

    public function join(Request $request)
    {
        $user = $request->user();

        // Check if there is already a pending invoice
        $status = $user->membershipStatus();
        if ($status === 'pending_invoice' || $status === 'pending_verification') {
            $latestInvoice = $user->invoices()->latest()->first();
            if ($latestInvoice) {
                return redirect()->route('member.premium.payment_detail', ['invoice' => $latestInvoice->id])
                    ->with('info', 'Anda masih memiliki tagihan yang belum selesai.');
            }
        }

        $request->validate([
            'institution' => 'nullable|string|max:255',
            'department'  => 'nullable|string|max:255',
            'address'     => 'nullable|string',
        ]);

        $institution = $request->institution ?: 'AMK';
        $department = $request->department ?: 'Premium Member';
        $address = $request->address ?: 'Online';

        // Create or update MemberProfile
        MemberProfile::updateOrCreate(
            ['member_id' => $user->id],
            [
                'institution' => $institution,
                'department'  => $department,
                'address'     => $address,
                'status'      => 'nonactive',
                'expire_date' => now(), // temporary
            ]
        );

        // Generate Invoice
        $fee = (float) Setting::get('membership_fee', 50000);
        $invoiceNumber = 'INV' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $countdown = (int) Setting::get('invoice_countdown', 24); // default 24 jam

        $invoice = Invoice::create([
            'user_id'     => $user->id,
            'number'      => $invoiceNumber,
            'amount'      => $fee,
            'due_date'    => now()->addHours($countdown),
            'is_accepted' => false,
        ]);

        return redirect()->route('member.premium.payment_detail', ['invoice' => $invoice->id])
            ->with('success', 'Pendaftaran berhasil. Silakan lakukan pembayaran.');
    }

    public function paymentIndex(Request $request)
    {
        $user = $request->user();
        $invoices = $user->invoices()->with('payment')->latest()->get();

        return Inertia::render('Member/Premium/PaymentIndex', [
            'invoices' => $invoices,
        ]);
    }

    public function paymentDetail(Request $request, Invoice $invoice)
    {
        $user = $request->user();

        if ($invoice->user_id !== $user->id) {
            abort(403);
        }

        $payment = $invoice->payment;

        // Determine step-by-step status
        $status = 'none'; // 'none' (belum bayar), 'menunggu' (verifikasi), 'diverifikasi' (aktif), 'ditolak'
        if ($payment) {
            $status = $payment->status; // 'menunggu', 'diverifikasi', 'ditolak'
        }

        $settings = [
            'bank_name'           => Setting::get('bank_name', 'Bank BRI'),
            'bank_account_number' => Setting::get('bank_account_number', '000000001111'),
            'bank_account_name'   => Setting::get('bank_account_name', 'AMK'),
            'membership_fee'      => Setting::get('membership_fee', 50000),
        ];

        return Inertia::render('Member/Premium/Payment', [
            'invoice'  => $invoice,
            'payment'  => $payment,
            'settings' => $settings,
            'status'   => $status,
        ]);
    }

    public function pay(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'invoice_id'          => 'required|exists:invoices,id',
            'account_holder_name' => 'required|string|max:255',
            'account_number'      => 'required|string|max:50',
            'account_bank_name'   => 'required|string|max:100',
            'payment_proof'       => 'required|image|max:1024',
            'payment_date'        => 'required|date',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);

        if ($invoice->user_id !== $user->id) {
            abort(403);
        }

        $proofPath = $request->file('payment_proof')->store('payments', 'public');
        $proofUrl = '/storage/' . $proofPath;

        $payment = Payment::updateOrCreate(
            ['invoice_id' => $invoice->id],
            [
                'payer_id'             => $user->id,
                'payment_proof_url'    => $proofUrl,
                'account_holder_name'  => $request->account_holder_name,
                'account_number'       => $request->account_number,
                'account_bank_name'    => $request->account_bank_name,
                'amount'               => $invoice->amount,
                'date'                 => $request->payment_date,
                'status'               => 'menunggu',
                'reject_reason'        => null,
                'verified_at'          => null,
            ]
        );

        // Fetch all finance (keuangan) users and notify them
        $financeUsers = \App\Models\User::where('role', 'finance')->get();
        foreach ($financeUsers as $financeUser) {
            try {
                $financeUser->notify(new \App\Notifications\NewPaymentSubmittedNotification($payment));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email pemberitahuan pembayaran baru ke keuangan: ' . $e->getMessage());
            }
        }

        return redirect()->route('member.premium.payment_detail', ['invoice' => $invoice->id])
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi.');
    }

    public function cancelInvoice(Request $request, Invoice $invoice)
    {
        $user = $request->user();

        if ($invoice->user_id !== $user->id) {
            abort(403);
        }

        if ($invoice->is_accepted || ($invoice->payment && $invoice->payment->status === 'diverifikasi')) {
            return back()->with('error', 'Invoice ini sudah dibayar dan tidak dapat dibatalkan.');
        }

        if ($invoice->payment) {
            $invoice->payment->delete();
        }
        $invoice->delete();

        return redirect()->route('member.premium.index')->with('success', 'Pesanan Member Premium berhasil dibatalkan.');
    }
}
