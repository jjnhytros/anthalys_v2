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
        Schema::create('online_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained()->cascadeOnDelete(); // Collegamento al cittadino
            $table->foreignId('product_id')->constrained('market_products')->cascadeOnDelete(); // Collegamento al prodotto del mercato
            $table->integer('quantity'); // Quantità ordinata
            $table->string('status')->default('pending'); // Stato dell'ordine
            $table->string('packaging_type')->default('standard'); // Tipo di imballaggio
            $table->boolean('is_recyclable')->default(true); // Specifica se è riciclabile

            $table->timestamp('confirmed_at')->nullable(); // Tempo di conferma
            $table->timestamp('canceled_at')->nullable(); // Tempo di cancellazione
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_orders');
    }
};
