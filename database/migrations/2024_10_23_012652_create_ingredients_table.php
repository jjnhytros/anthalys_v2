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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // es. 'Orzo', 'Luppolo'
            $table->decimal('quantity', 8, 2); // Quantità disponibile
            $table->string('unit'); // es. 'kg'
            $table->timestamps();
        });

        Schema::create('alcoholic_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alcoholic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity_used', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alcoholic_ingredients');
        Schema::dropIfExists('ingredients');
    }
};
