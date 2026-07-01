<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPaymentSubmittedNotification extends Notification
{
    use Queueable;

    protected \App\Models\Payment $payment;
    protected \App\Models\User $payer;

    public function __construct(\App\Models\Payment $payment)
    {
        $this->payment = $payment;
        $this->payer = $payment->payer;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = url('/keuangan/pembayaran/' . $this->payment->id);

        return (new MailMessage)
            ->subject('Bukti Pembayaran Baru Dikirim - ' . $this->payment->invoice->number)
            ->greeting('Halo, Tim Keuangan!')
            ->line('Member ' . $this->payer->name . ' telah mengunggah bukti pembayaran baru untuk diverifikasi.')
            ->line('Nomor Invoice: ' . $this->payment->invoice->number)
            ->line('Nama Paket: ' . ($this->payment->invoice->plan->name ?? '-'))
            ->line('Jumlah Pembayaran: Rp ' . number_format($this->payment->amount, 0, ',', '.'))
            ->line('Atas Nama Pengirim: ' . $this->payment->account_holder_name)
            ->line('Nomor Rekening Pengirim: ' . $this->payment->account_number)
            ->line('Bank Pengirim: ' . $this->payment->account_bank_name)
            ->line('Bukti Pembayaran: ' . url($this->payment->payment_proof_url))
            ->line('Tanggal Transfer: ' . $this->payment->date->format('d/m/Y'))
            ->action('Verifikasi Pembayaran', $verificationUrl)
            ->line('Silakan tinjau bukti pembayaran tersebut di atas untuk melakukan persetujuan (acc) atau penolakan.');
    }
}
