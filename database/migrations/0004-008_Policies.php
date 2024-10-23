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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome della politica, es. "Aliquota fiscale"
            $table->string('type'); // Tipo di politica: 'tax', 'subsidy', 'regulation'
            $table->decimal('rate', 5, 2); // Aliquota fiscale o percentuale di sussidio
            $table->text('description')->nullable(); // Descrizione della politica
            $table->boolean('active')->default(true); // Stato della politica (attiva o no)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
