<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreatedNotification extends Notification
{
    use Queueable;

    protected \App\Models\Invoice $invoice;
    protected \App\Models\User $user;
    protected array $settings;

    public function __construct(\App\Models\Invoice $invoice, \App\Models\User $user, array $settings)
    {
        $this->invoice = $invoice;
        $this->user = $user;
        $this->settings = $settings;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $invoiceUrl = url('/member/premium/pembayaran/' . $this->invoice->id);

        return (new MailMessage)
            ->subject('Tagihan Pembayaran Premium - ' . $this->invoice->number)
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Pesanan paket premium Anda telah berhasil dibuat. Berikut adalah rincian tagihan Anda:')
            ->line('Nomor Invoice: ' . $this->invoice->number)
            ->line('Jumlah: Rp ' . number_format($this->invoice->amount, 0, ',', '.'))
            ->line('Atas Nama Rekening Tujuan: ' . $this->settings['bank_account_name'])
            ->line('Nomor Rekening Tujuan: ' . $this->settings['bank_account_number'])
            ->line('Nama Bank Tujuan: ' . $this->settings['bank_name'])
            ->line('Batas Waktu Pembayaran: ' . \Carbon\Carbon::parse($this->invoice->due_date)->format('d/m/Y, H:i'))
            ->action('Lihat Halaman Invoice', $invoiceUrl)
            ->line('Silakan lakukan transfer sesuai jumlah di atas dan unggah bukti pembayaran melalui tautan tersebut sebelum batas waktu berakhir.');
    }
}
