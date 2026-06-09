<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Permanently delete member accounts that have been pending deletion for 7+ days
Schedule::command('accounts:purge-expired')->daily();

// Send weekly content summary to active premium members (e.g., every Monday at 09:00)
Schedule::command('email:weekly-summary')->weeklyOn(1, '09:00');

// Send notification to premium members whose membership expires in 3 days (daily at 08:00)
Schedule::command('email:membership-expiring')->dailyAt('08:00');
