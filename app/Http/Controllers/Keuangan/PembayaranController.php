<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
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
            'verifier_id' => auth()->id(),
            'verified_at' => now(),
        ]);

        $payment->invoice->update([
            'is_accepted' => true,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'reject_reason' => 'required|string',
        ]);

        $payment->update([
            'status' => 'ditolak',
            'reject_reason' => $request->reject_reason,
            'verifier_id' => auth()->id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran ditolak.');
    }
}
