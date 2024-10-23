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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('quantity', 12, 2)->default(0); // Quantità totale
            $table->decimal('produced', 12, 2)->default(0); // Quantità prodotta
            $table->decimal('consumed', 12, 2)->default(0); // Quantità consumata
            $table->string('unit');
            $table->decimal('daily_production', 12, 2)->default(0); // Produzione giornaliera
            $table->decimal('optimized_production', 12, 2)->default(0);
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('agricultural_resources', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome della risorsa (es. Grano, Ortaggi)
            $table->decimal('quantity', 12, 2)->default(0); // Quantità disponibile
            $table->decimal('daily_production', 12, 2)->default(0); // Produzione giornaliera
            $table->decimal('daily_consumption', 12, 2)->default(0);
            $table->decimal('water_consumption', 12, 2)->default(0); // Consumo di acqua
            $table->decimal('energy_consumption', 12, 2)->default(0); // Consumo di energia
            $table->foreignId('district_id')->constrained()->cascadeOnDelete(); // Collegamento al distretto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agricultural_resources');
        Schema::dropIfExists('resources');
    }
};
