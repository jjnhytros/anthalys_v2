<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->id();
            $table->integer('floor')->comment('Da 0 a -144 per la gestione del magazzino');
            $table->string('product_type'); // Alimentari, abbigliamento, ecc.
            $table->integer('quantity')->default(0); // Quantità di prodotti per tipo
            $table->decimal('energy_usage', 12, 2)->default(0); // Consumo energetico totale in kWh
            $table->integer('min_quantity')->default(10)->after('quantity'); // Quantità minima per il riordino
            $table->integer('reorder_quantity')->default(50)->after('min_quantity'); // Quantità da riordinare automaticamente

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse');
    }
};
