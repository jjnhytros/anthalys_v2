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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('start_day'); // Giorno di inizio della stagione (es: 1 per il giorno 1 dell'anno)
            $table->integer('end_day');   // Giorno di fine della stagione (es: 72 per il giorno 72 dell'anno)
            $table->decimal('impact_factor', 5, 2); // Fattore di impatto della stagione sulla produzione agricola
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
