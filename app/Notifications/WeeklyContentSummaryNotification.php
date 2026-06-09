<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyContentSummaryNotification extends Notification
{
    use Queueable;

    protected $contents;

    public function __construct($contents)
    {
        $this->contents = $contents;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Ringkasan Konten Premium Minggu Ini - ' . config('app.name'))
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Berikut adalah ringkasan konten premium terbaru yang diunggah oleh tim Petugas kami dalam 7 hari terakhir:');

        foreach ($this->contents as $content) {
            $message->line('- ' . $content->title . ' (' . ucfirst($content->type) . ')');
        }

        $message->action('Lihat Semua Konten', url('/member/konten'))
            ->line('Jangan lewatkan materi-materi terbaru kami untuk mendukung Anda!');

        return $message;
    }
}
