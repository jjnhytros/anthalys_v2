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
        Schema::create('recycling_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained()->onDelete('cascade');
            $table->foreignId('waste_type_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 8, 2); // Quantità di rifiuto riciclato
            $table->timestamps(); // Timestamp per tenere traccia del progresso nel tempo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recycling_progress');
    }
};
