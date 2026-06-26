<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['payer', 'invoice'])
            ->latest();

        if ($request->search) {
            $query->whereHas('invoice', function($q) use ($request) {
                $q->where('number', 'like', '%' . $request->search . '%');
            })->orWhereHas('payer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        if ($request->from) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('date', '<=', $request->to);
        }

        $payments = $query->paginate(10)->withQueryString();

        return Inertia::render('Bendahara/Pembayaran/Index', [
            'payments' => $payments,
            'filters' => $request->only(['search', 'status', 'from', 'to']),
        ]);
    }

    public function show(Payment $payment)
    {
        $payment->load(['payer', 'invoice']);
        return Inertia::render('Bendahara/Pembayaran/Show', [
            'payment' => $payment,
        ]);
    }

    public function verify(Payment $payment)
    {
        $payment->update([
            'status' => 'diverifikasi',
            'verifier_id' => Auth::id(),
            'verified_at' => now(),
        ]);

        $payment->invoice->update([
            'is_accepted' => true,
        ]);

        // Auto-activate or extend MemberProfile
        $payer = $payment->payer;
        $plan  = $payment->invoice->plan;

        if ($plan) {
            // Paket terkait invoice → pakai durasi paket (bisa lifetime)
            $expiry = $plan->computeExpiry();
        } else {
            // Invoice lama tanpa paket → fallback ke setting global
            $durationMonths = (int) \App\Models\Setting::get('membership_duration', 12);
            $expiry = now()->addMonths($durationMonths);
        }

        \App\Models\MemberProfile::updateOrCreate(
            ['member_id' => $payer->id],
            [
                'status'      => 'active',
                'expire_date' => $expiry ?? now()->addYears(100), // lifetime
                'plan_id'     => $plan?->id,
                'address'     => '-',
            ]
        );

        // Send email notification to member
        try {
            $payer->notify(new \App\Notifications\PaymentApprovedNotification($payer, $payment));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email verifikasi pembayaran: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi dan member diaktifkan.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'reject_reason' => 'required|string',
        ]);

        $payment->update([
            'status' => 'ditolak',
            'reject_reason' => $request->reject_reason,
            'verifier_id' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran ditolak.');
    }
}
