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
        Schema::create('warehouse_operation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('operation_type'); // Tipo di operazione: Accesso, Rifornimento, Movimento, ecc.
            $table->foreignId('product_id')->nullable()->constrained('market_products')->nullOnDelete(); // Prodotto coinvolto
            $table->integer('quantity')->nullable(); // Quantità coinvolta
            $table->timestamp('operation_time')->useCurrent(); // Tempo dell'operazione
            $table->text('details')->nullable(); // Dettagli dell'operazione
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_operation_logs');
    }
};
