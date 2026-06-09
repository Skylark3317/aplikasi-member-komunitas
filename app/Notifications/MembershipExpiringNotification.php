<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct($daysRemaining = 3)
    {
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Peringatan: Masa Aktif Member Premium Akan Habis')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Kami ingin memberitahukan bahwa masa aktif akun premium Anda akan habis dalam ' . $this->daysRemaining . ' hari.')
            ->line('Jangan sampai terlewat! Segera perpanjang paket langganan Anda agar tetap dapat menikmati fasilitas dan akses penuh ke konten premium komunitas kami.')
            ->action('Perpanjang Paket Sekarang', route('member.premium.index'))
            ->line('Terima kasih telah menjadi bagian dari komunitas kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
