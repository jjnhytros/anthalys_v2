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
        Schema::create('population_migrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_district_id')->constrained('districts')->cascadeOnDelete();
            $table->foreignId('to_district_id')->constrained('districts')->cascadeOnDelete();
            $table->integer('migrants_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('population_migrations');
    }
};
