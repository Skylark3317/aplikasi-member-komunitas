<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->string('member_number')->nullable()->unique()->after('member_id');
        });

        // Populate existing member profiles with the new format (DDMMYYYY + 3-digit sequence)
        $profiles = DB::table('member_profiles')->orderBy('id')->get();

        foreach ($profiles as $profile) {
            $date = \Carbon\Carbon::parse($profile->created_at);
            $prefix = $date->format('dmY');

            // Find how many profiles with the same prefix we have updated so far
            $existingCount = DB::table('member_profiles')
                ->where('member_number', 'like', $prefix . '%')
                ->count();

            $nextSeq = $existingCount + 1;
            $memberNumber = $prefix . $nextSeq;

            DB::table('member_profiles')
                ->where('id', $profile->id)
                ->update(['member_number' => $memberNumber]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->dropColumn('member_number');
        });
    }
};
