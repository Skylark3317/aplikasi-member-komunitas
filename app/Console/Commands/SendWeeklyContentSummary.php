<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;
use App\Models\User;
use App\Notifications\WeeklyContentSummaryNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendWeeklyContentSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:weekly-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email ringkasan konten mingguan ke member premium';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = Carbon::now()->subDays(7)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $contents = Content::whereBetween('created_at', [$startDate, $endDate])->get();

        if ($contents->isEmpty()) {
            $this->info('Tidak ada konten baru minggu ini.');
            return;
        }

        // Ambil semua member
        $members = User::where('role', 'member')->get();

        $count = 0;
        foreach ($members as $member) {
            try {
                // Pastikan member adalah premium dan aktif masa tenggangnya
                if ($member->isPremium() && $member->membershipStatus() === 'active') {
                    $member->notify(new WeeklyContentSummaryNotification($contents));
                    $count++;
                }
            } catch (\Exception $e) {
                Log::error('Gagal mengirim ringkasan konten ke ' . $member->email . ': ' . $e->getMessage());
            }
        }

        $this->info("Berhasil mengirim ringkasan konten mingguan ke $count member.");
    }
}
