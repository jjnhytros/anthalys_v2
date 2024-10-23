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
        Schema::create('recycling_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained()->onDelete('cascade');
            $table->string('resource_type'); // Tipo di risorsa riciclata (es. plastica, carta)
            $table->decimal('quantity', 12, 2); // Quantità di risorsa riciclata
            $table->dateTime('recycled_at'); // Data dell'attività di riciclo
            $table->decimal('bonus', 12, 2); // Bonus ricevuto per il riciclo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recycling_activities');
    }
};
