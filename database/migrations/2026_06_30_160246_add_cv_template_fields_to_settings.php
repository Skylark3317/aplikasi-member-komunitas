<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $newSettings = [
            'cv_community_name' => 'Aplikasi Member Komunitas',
            'cv_website'        => 'www.komunitasamk.com',
            'cv_letter_title'   => 'Surat Keterangan Keanggotaan Premium',
        ];

        foreach ($newSettings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['key' => $key, 'value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'cv_community_name',
            'cv_website',
            'cv_letter_title',
        ])->delete();
    }
};
