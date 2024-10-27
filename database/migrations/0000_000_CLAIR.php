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
        Schema::create('clair', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // Tipo di attività (C, L, A, I, R)
            $table->string('method');  // Metodo specifico
            $table->text('details');  // Dettagli dell’attività eseguita
            $table->json('data')->nullable();  // Dati aggiuntivi per il metodo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clair');
    }
};
