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
        Schema::create('warehouse_reports', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_energy_used', 10, 2); // Consumo energetico totale del mese
            $table->integer('recyclable_orders'); // Ordini con imballaggi riciclabili
            $table->integer('non_recyclable_orders'); // Ordini non riciclabili
            $table->decimal('waste_generated', 10, 2); // Rifiuti generati in kg
            $table->integer('sold_quantity')->default(0); // Quantità venduta
            $table->integer('restocked_quantity')->default(0); // Quantità rifornita
            $table->decimal('revenue', 10, 2)->default(0.00); // Entrate generate dalle vendite
            $table->date('report_month'); // Mese del report
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_reports');
    }
};
