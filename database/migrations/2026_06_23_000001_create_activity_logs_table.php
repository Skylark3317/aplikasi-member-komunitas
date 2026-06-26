<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');          // e.g. "Buat Akun", "Hapus Akun", "Ubah Pengaturan"
            $table->string('target_type')->nullable(); // e.g. "User", "Setting", "Profil"
            $table->unsignedBigInteger('target_id')->nullable(); // id of affected record
            $table->string('target_label')->nullable(); // human-readable label, e.g. user name
            $table->json('metadata')->nullable(); // extra context
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
