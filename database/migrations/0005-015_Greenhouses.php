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
        Schema::create('greenhouses', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo di serra (es. Verticale, Idroponica)
            $table->string('energy_source'); // Fonte di energia (solare, geotermica)
            $table->decimal('yield_multiplier', 4, 2)->default(1.0); // Moltiplicatore di resa
            $table->decimal('space_efficiency', 4, 2)->default(1.0); // Moltiplicatore per la gestione dello spazio

            $table->foreignId('farm_id')->constrained()->cascadeOnDelete(); // Collegamento alla fattoria

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('greenhouses');
    }
};
