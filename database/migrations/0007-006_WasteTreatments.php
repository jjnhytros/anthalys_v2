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
        Schema::create('waste_treatments', function (Blueprint $table) {
            $table->id();
            $table->string('waste_type'); // Tipo di rifiuto (Organico, Plastica, ecc.)
            $table->string('treatment_type'); // Tipo di trattamento (Compostaggio, Riciclo, ecc.)
            $table->decimal('output_quantity', 8, 2); // Quantità di risorsa prodotta dal trattamento
            $table->string('output_resource'); // Risorsa prodotta (es. Compost, Materiali Riciclati)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_treatments');
    }
};
