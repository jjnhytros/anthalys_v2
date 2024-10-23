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
        Schema::create('waste_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome del tipo di rifiuto (es. Plastica, Vetro)
            $table->string('container_color'); // Colore del contenitore (es. Giallo, Verde)
            $table->string('description'); // Descrizione del tipo di rifiuto
            $table->timestamps();
        });

        Schema::create('waste_treatments', function (Blueprint $table) {
            $table->id();
            $table->string('waste_type'); // Tipo di rifiuto (Organico, Plastica, ecc.)
            $table->string('treatment_type'); // Tipo di trattamento (Compostaggio, Riciclo, ecc.)
            $table->decimal('output_quantity', 8, 2); // Quantità di risorsa prodotta dal trattamento
            $table->string('output_resource'); // Risorsa prodotta (es. Compost, Materiali Riciclati)
            $table->timestamps();
        });

        Schema::create('auto_waste_disposers', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo di smaltitore (es. Compostatore, Mini-Riciclatore)
            $table->decimal('efficiency', 5, 2); // Efficienza di riduzione rifiuti (percentuale)
            $table->foreignId('citizen_id')->constrained('citizens')->onDelete('cascade'); // Collega lo smaltitore alla famiglia/cittadino
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_waste_disposers');
        Schema::dropIfExists('waste_treatments');
        Schema::dropIfExists('waste_types');
    }
};
