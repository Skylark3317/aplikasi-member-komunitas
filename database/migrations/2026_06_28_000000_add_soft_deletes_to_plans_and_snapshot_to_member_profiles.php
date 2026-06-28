<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('member_profiles', function (Blueprint $table) {
            $table->json('plan_snapshot')->nullable()->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->dropColumn('plan_snapshot');
        });

        Schema::table('membership_plans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
