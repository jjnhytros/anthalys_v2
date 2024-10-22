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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo di evento (tempesta, epidemia, ecc.)
            $table->string('description'); // Descrizione dell'effetto
            $table->decimal('impact', 5, 2); // Impatto in termini di percentuale
            $table->boolean('active')->default(true); // Se l'evento è attivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
