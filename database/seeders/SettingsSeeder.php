<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'community_name'        => 'Aplikasi Member Komunitas',
            'community_logo'        => null,
            'email'                 => 'amk@mail.com',
            'phone'                 => '081234567890',
            'address'               => 'Jl. Merdeka No. 123, Jakarta, Indonesia',
            'social_x'              => 'https://x.com/amk',
            'social_facebook'       => 'https://facebook.com/amk',
            'social_linkedin'       => 'https://linkedin.com/amk',
            'social_skype'          => 'https://skype.com/amk',
            'social_instagram'      => 'https://instagram.com/amk',
            'social_youtube'        => 'https://youtube.com/amk',
            'bank_account_name'     => 'AMK',
            'bank_account_number'   => '000000001111',
            'bank_name'             => 'Bank BRI',
            'membership_fee'        => '50000',
            'membership_duration'   => '12',
            'invoice_countdown'     => '24',
            'primary_color'         => '#007FFF',
            'surface_color'         => '#E5F2FF',
            'bg_image'              => null,
            'hero_title'            => 'Bangun Koneksi dan Tumbuh Bersama',
            'hero_description'      => 'Terhubung dengan individu dari berbagai latar belakang, berbagi ide, dan membangun kolaborasi dalam komunitas inklusif untuk berkembang bersama serta menciptakan dampak nyata.',
            'about_image'           => null,
            'about_title'           => 'Tentang',
            'about_description'     => 'Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung dalam lingkungan yang positif dan kolaboratif.',
            'stat_member_aktif'     => '1919',
            'stat_member_pasif'     => '99',
            'stat_member_company'   => '217',
            'stat_member_personal'  => '1801',
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['key' => $key, 'value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
