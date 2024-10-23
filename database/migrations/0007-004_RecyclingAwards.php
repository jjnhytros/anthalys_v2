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
        Schema::create('recycling_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained()->onDelete('cascade');
            $table->string('award_type'); // Tipo di premio (es. "Cittadino Sostenibile")
            $table->integer('year'); // Anno in cui viene assegnato il premio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recycling_awards');
    }
};
