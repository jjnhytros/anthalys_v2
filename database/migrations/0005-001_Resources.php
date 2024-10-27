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
            $table->decimal('price', 12, 2)->default(1.0);

            $table->decimal('quantity', 12, 2)->default(0); // Quantità totale
            $table->decimal('produced', 12, 2)->default(0); // Quantità prodotta
            $table->decimal('consumed', 12, 2)->default(0); // Quantità consumata
            $table->string('unit');
            $table->decimal('available_quantity', 12, 2)->default(0); // Quantità disponibile
            $table->decimal('minimum_required', 12, 2)->default(value: 12); // Quantità minima richiesta
            $table->decimal('daily_consumption', 12, 2)->default(1); // Consumo giornaliero
            $table->decimal('daily_production', 12, 2)->default(0); // Produzione giornaliera
            $table->decimal('optimized_production', 12, 2)->default(0);
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->decimal('surplus_limit', 12, 2)->default(1000); // Limite oltre il quale è considerato surplus
            $table->decimal('deficit_limit', 12, 2)->default(200);  // Limite sotto il quale è considerato deficit
            $table->integer('priority')->default(1); // Priorità delle risorse: 1 = alta, 3 = bassa
            $table->integer('availability')->default(100); // Percentuale di disponibilità

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
