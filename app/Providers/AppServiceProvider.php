<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        \Illuminate\Validation\Rules\Password::defaults(function () {
            return \Illuminate\Validation\Rules\Password::min(8)
                ->mixedCase()
                ->numbers();
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $senderName = Setting::get('email_sender_name') ?: config('app.name');
            $senderEmail = Setting::get('email_sender_address') ?: config('mail.from.address');
            return (new MailMessage)
                ->from($senderEmail, $senderName)
                ->subject(Lang::get('Verify your email address'))
                ->line(Lang::get('Please click the button below to verify your email address.'))
                ->action(Lang::get('Verify Email Address'), $url)
                ->line(Lang::get('If you did not create an account, no further action is required.'));
        });
    }
}
