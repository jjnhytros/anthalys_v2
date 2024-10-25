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
        Schema::create('citizen_careers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained()->cascadeOnDelete();
            $table->foreignId('occupation_id')->constrained('occupations')->cascadeOnDelete();
            $table->integer('level')->default(1);
            $table->integer('reputation')->default(0); // Reputazione accumulata
            $table->integer('experience')->default(0); // Esperienza accumulata per promozioni
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizen_careers');
    }
};
