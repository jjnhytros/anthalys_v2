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
        Schema::create('infrastructures', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome dell'infrastruttura
            $table->string('type'); // Tipo di infrastruttura (es: strada, rete elettrica, ponte)
            $table->decimal('length', 8, 2)->nullable(); // Lunghezza in km (se applicabile)
            $table->foreignId('district_id')->constrained()->onDelete('cascade'); // Collegamento al distretto
            $table->decimal('capacity', 12, 2)->default(100); // Capacità massima dell'infrastruttura
            $table->decimal('efficiency', 5, 2)->default(1.00); // Efficienza, 1.00 rappresenta il 100% di efficienza
            $table->decimal('condition', 5, 2)->default(1.00); // 1.00 rappresenta il 100% di efficienza
            $table->decimal('co2_emissions', 12, 2)->default(0); // Emissioni di CO₂ in tonnellate
            $table->decimal('energy_consumption', 12, 2)->default(0); // Consumo energetico in kWh
            $table->decimal('water_consumption', 12, 2)->default(0); // Consumo di acqua in litri
            $table->decimal('biodiversity_impact', 12, 2)->default(0); // Impatto sulla biodiversità, scala da 0 a 1

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructures');
    }
};
