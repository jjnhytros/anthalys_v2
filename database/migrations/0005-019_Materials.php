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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome del materiale, es. "Acciaio"
            $table->text('composition')->nullable(); // Descrizione della composizione
            $table->integer('durability')->default(100); // Percentuale di resistenza, 100 = nuovo
            $table->decimal('density', 12, 3)->nullable(); // Densità del materiale
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
