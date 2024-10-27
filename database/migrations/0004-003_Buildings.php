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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // Es: residenziale, commerciale, industriale, scuola, ospedale, ecc.
            $table->integer('floors'); // Numero di piani
            $table->decimal('height', 5, 2); // Altezza in metri
            $table->decimal('energy_consumption', 12, 2)->default(0); // Consumo energetico
            $table->decimal('water_consumption', 12, 2)->default(0); // Consumo idrico
            $table->decimal('food_consumption', 12, 2)->default(0); // Consumo di cibo
            $table->integer('capacity')->default(0); // Capacità per studenti o pazienti
            $table->decimal('service_quality', 5, 2)->default(1.0); // Qualità del servizio, 1.0 come base
            $table->integer('administrative_capacity')->default(0); // Capacità amministrativa
            $table->decimal('tax_contribution', 8, 2)->default(0); // Contributo fiscale potenziale
            $table->integer('cultural_capacity')->default(0); // Capacità culturale, ad esempio per spettatori o visitatori
            $table->decimal('tourism_attraction', 5, 2)->default(1.0); // Capacità di attrazione turistica
            $table->decimal('event_income', 10, 2)->default(0); // Guadagno generato dagli eventi culturali
            $table->integer('transport_capacity')->default(0); // Capacità di trasporto per mezzi pubblici
            $table->decimal('energy_output', 12, 2)->default(0); // Produzione energetica o output per gli edifici di servizio energia
            $table->integer('recreation_capacity')->default(0); // Capacità ricreativa per parchi, palestre, ecc.
            $table->decimal('wellbeing_boost', 5, 2)->default(1.0); // Incremento al benessere della popolazione
            $table->integer('communication_capacity')->default(0); // Capacità comunicativa per centri IT
            $table->decimal('technology_boost', 5, 2)->default(1.0); // Incremento tecnologico
            $table->integer('waste_capacity')->default(0); // Capacità di gestione dei rifiuti
            $table->decimal('recycling_efficiency', 5, 2)->default(1.0); // Efficienza di riciclo
            $table->integer('emergency_capacity')->default(0); // Capacità di risposta per emergenze
            $table->decimal('safety_boost', 5, 2)->default(1.0); // Incremento della sicurezza
            $table->boolean('low_income_support')->default(false); // Edifici per reddito basso
            $table->boolean('elderly_support')->default(false); // Strutture per anziani
            $table->integer('research_capacity')->default(0); // Capacità di ricerca
            $table->decimal('innovation_boost', 5, 2)->default(1.0); // Incremento dell'innovazione

            $table->foreignId('district_id')->constrained()->cascadeOnDelete(); // Relazione con il distretto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
