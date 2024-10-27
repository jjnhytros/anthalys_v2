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
        Schema::create('emergency_plans', function (Blueprint $table) {
            $table->id();
            $table->string('resource_name');
            $table->integer('threshold'); // Soglia di crisi
            $table->integer('reserve_quantity'); // Quantità di riserva
            $table->boolean('limit_usage')->default(false); // Limitazione dell'uso
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_plans');
    }
};
