<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya seed bila tabel kosong
        if (MembershipPlan::exists()) {
            return;
        }

        // Konversi nilai settings lama (membership_fee / membership_duration) menjadi paket default.
        $fee      = (float) Setting::get('membership_fee', 50000);
        $duration = (int) Setting::get('membership_duration', 12);

        $plans = [
            [
                'name'           => 'Membership Bulanan',
                'description'    => 'Akses penuh ke seluruh konten pembelajaran eksklusif selama satu bulan.',
                'price'          => 10000,
                'duration'       => 1,
                'duration_unit'  => 'month',
                'is_lifetime'    => false,
                'features'       => [
                    'Akses Halaman Tanya Jawab Petugas Ahli',
                    'Nonton Video Pembelajaran Eksklusif',
                ],
                'is_recommended' => false,
                'is_active'      => true,
                'sort_order'     => 1,
            ],
            [
                'name'           => 'Membership Tahunan',
                'description'    => 'Akses penuh tanpa batas ke semua layanan & konten pembelajaran berkualitas tinggi selama satu tahun.',
                'price'          => $fee,
                'duration'       => $duration,
                'duration_unit'  => 'month',
                'is_lifetime'    => false,
                'features'       => [
                    'Akses Halaman Tanya Jawab Petugas Ahli',
                    'Nonton Puluhan Video Pembelajaran Eksklusif',
                    'Download E-Book & Modul Praktik Terstruktur',
                ],
                'is_recommended' => true,
                'is_active'      => true,
                'sort_order'     => 2,
            ],
            [
                'name'           => 'Membership Seumur Hidup',
                'description'    => 'Bergabung sekali, nikmati seluruh layanan premium selamanya tanpa batas waktu.',
                'price'          => $fee * 5,
                'duration'       => 0,
                'duration_unit'  => 'year',
                'is_lifetime'    => true,
                'features'       => [
                    'Semua keuntungan Membership Tahunan',
                    'Akses seumur hidup tanpa perlu perpanjang',
                    'Prioritas dukungan dari petugas ahli',
                ],
                'is_recommended' => false,
                'is_active'      => true,
                'sort_order'     => 3,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::create($plan);
        }
    }
}
