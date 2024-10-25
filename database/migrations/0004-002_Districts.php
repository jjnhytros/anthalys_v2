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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->nullable()->constrained('citizens')->nullOnDelete();
            $table->string('name');
            $table->integer('population')->nullable();
            $table->string('type')->default('Residenziale');
            $table->decimal('area', 8, 2); // Area in km^2
            $table->text('description')->nullable();
            $table->decimal('soil_health', 5, 2)->default(1.0); // 1.0 = Salute massima, 0.0 = completamente degradata
            $table->integer('compost_available')->default(0); // Quantità di compost disponibile
            $table->integer('fertilizer_available')->default(0); // Quantità di fertilizzante naturale disponibile
            $table->decimal('energy', 12, 2)->default(0)->after('population');  // Energia disponibile
            $table->decimal('water', 12, 2)->default(0)->after('energy');       // Acqua disponibile
            $table->decimal('food', 12, 2)->default(0)->after('water');         // Cibo disponibile
            $table->decimal('energy_threshold', 12, 2)->default(100);  // Soglia critica per l'energia
            $table->decimal('water_threshold', 12, 2)->default(100);   // Soglia critica per l'acqua
            $table->decimal('food_threshold', 12, 2)->default(100);    // Soglia critica per il cibo
            $table->decimal('infrastructure_efficiency', 5, 2)->default(1.0); // Efficienza delle infrastrutture (1.0 = 100%)
            $table->decimal('technology_level', 5, 2)->default(1.0); // Livello tecnologico (1.0 = standard)
            $table->decimal('tax_rate', 5, 2)->nullable()->default(0); // Colonna per il tasso di tassazione
            $table->boolean('auto_sufficient')->nullable()->default(false); // Colonna per l’autosufficienza

            $table->foreignId('city_id')->constrained()->cascadeOnDelete(); // Collegamento alla città
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
