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
        Schema::create('market_products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome del prodotto
            $table->integer('quantity'); // Quantità disponibile
            $table->decimal('purchase_price', 12, 2)->after('price')->nullable(); // Prezzo di acquisto
            $table->decimal('price', 12, 2); // Prezzo del prodotto
            $table->integer('demand')->default(0); // Richiesta iniziale di prodotti
            $table->integer('min_stock_level')->default(value: 12); // Livello minimo di scorte
            $table->integer('reorder_amount')->default(60);   // Quantità di riordino

            $table->foreignId('market_id')->constrained()->cascadeOnDelete(); // Collegamento al mercato locale
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete(); // Collegamento alla fattoria
            $table->foreignId('stall_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('farms')->nullOnDelete(); // Collegamento alla fattoria locale

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_products');
    }
};
