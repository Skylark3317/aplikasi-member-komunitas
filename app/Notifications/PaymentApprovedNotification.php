<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    protected \App\Models\User $user;
    protected \App\Models\Payment $payment;

    public function __construct(\App\Models\User $user, \App\Models\Payment $payment)
    {
        $this->user = $user;
        $this->payment = $payment;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $profileUrl = url('/member/profil');

        return (new MailMessage)
            ->subject('Pembayaran Premium Disetujui - ' . config('app.name'))
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Kabar baik! Pembayaran biaya keanggotaan Anda telah berhasil diverifikasi dan disetujui.')
            ->line('Nomor Invoice: ' . $this->payment->invoice->number)
            ->line('Jumlah: Rp ' . number_format($this->payment->amount, 0, ',', '.'))
            ->line('Status Keanggotaan Anda sekarang AKTIF. Anda dapat menikmati semua fitur premium.')
            ->action('Lihat Profil Saya', $profileUrl)
            ->line('Terima kasih telah bergabung dengan komunitas kami!');
    }
}
