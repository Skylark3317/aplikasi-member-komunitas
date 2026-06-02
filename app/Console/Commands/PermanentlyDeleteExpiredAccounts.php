<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PermanentlyDeleteExpiredAccounts extends Command
{
    protected $signature = 'accounts:purge-expired';
    protected $description = 'Permanently delete member accounts that requested deletion more than 7 days ago';

    public function handle(): void
    {
        $durationMinutes = (int) \App\Models\Setting::get('account_deletion_duration', 10080);
        $threshold = now()->subMinutes($durationMinutes);

        $users = User::whereNotNull('delete_requested_at')
            ->where('delete_requested_at', '<=', $threshold)
            ->get();

        $count = 0;
        foreach ($users as $user) {
            $user->delete();
            $count++;
        }

        $this->info("Deleted {$count} expired account(s).");
    }
}
