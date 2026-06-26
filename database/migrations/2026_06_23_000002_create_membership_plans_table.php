<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedInteger('duration')->default(1);          // jumlah satuan
            $table->enum('duration_unit', ['day', 'month', 'year'])->default('month');
            $table->boolean('is_lifetime')->default(false);           // masa aktif tak terbatas
            $table->json('features')->nullable();                     // daftar fitur (bullet)
            $table->boolean('is_recommended')->default(false);        // badge "Direkomendasikan"
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
