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
        Schema::create('alcoholics', function (Blueprint $table) {
            $table->id();

            // Informazioni generali sulla produzione alcolica
            $table->string('name'); // Nome del prodotto alcolico, es. Birra, Vino
            $table->integer('batch_size'); // Dimensione della produzione in litri o unità
            $table->string('malt_type')->nullable(); // Tipo di malto usato (se applicabile)
            $table->string('hop_type')->nullable(); // Tipo di luppolo (se applicabile)
            $table->string('yeast_type')->nullable(); // Tipo di lievito (se applicabile)
            $table->string('water_source'); // Fonte dell'acqua usata per la produzione

            // Processi di produzione specifici
            $table->string('production_phase'); // Fase della produzione (ammollatura, fermentazione, ecc.)
            $table->integer('fermentation_time'); // Tempo di fermentazione in giorni
            $table->integer('maturation_time'); // Tempo di maturazione in giorni

            // Nuovi campi per la gestione degli ingredienti e della qualità
            $table->string('type')->nullable(); // Tipo di bevanda, es. 'Birra', 'Vino'
            $table->string('ingredient')->nullable(); // Orzo, luppolo, lievito
            $table->string('process_stage')->nullable(); // Ammostamento, fermentazione, ecc.
            $table->integer('quantity')->nullable(); // Quantità prodotta
            $table->decimal('quality', 5, 2)->nullable(); // Qualità della produzione

            // Impatto ambientale
            $table->decimal('environmental_impact', 8, 2); // Impatto ambientale del processo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alcoholics');
    }
};
