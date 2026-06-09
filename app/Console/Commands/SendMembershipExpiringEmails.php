<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\MembershipExpiringNotification;
use Carbon\Carbon;

class SendMembershipExpiringEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:membership-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email notifikasi ke member premium yang masa aktifnya akan habis dalam 3 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = Carbon::today()->addDays(3);

        $users = User::where('role', 'member')
            ->whereHas('memberProfile', function ($query) use ($targetDate) {
                $query->whereDate('expire_date', $targetDate->toDateString())
                      ->where('status', 'active');
            })
            ->get();

        $count = 0;
        foreach ($users as $user) {
            $user->notify(new MembershipExpiringNotification(3));
            $count++;
        }

        $this->info("Berhasil mengirim notifikasi expiring ke {$count} member.");
    }
}
