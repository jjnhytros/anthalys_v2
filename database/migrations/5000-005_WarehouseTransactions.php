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
        Schema::create('warehouse_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('warehouse')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('citizens')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('citizens')->nullOnDelete();
            $table->integer('quantity');
            $table->string('transaction_type'); // "supply" or "purchase"
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_transactions');
    }
};
