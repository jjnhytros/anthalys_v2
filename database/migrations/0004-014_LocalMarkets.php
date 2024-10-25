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
        Schema::create('local_markets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome del mercato
            $table->string('location'); // Posizione del mercato
            $table->foreignId('city_id')->constrained()->cascadeOnDelete(); // Collegamento alla città
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_markets');
    }
};
