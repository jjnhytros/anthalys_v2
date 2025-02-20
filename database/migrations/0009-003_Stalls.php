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
        Schema::create('stalls', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome della bancarella
            $table->text('description')->nullable(); // Descrizione della bancarella
            $table->foreignId('market_id')->constrained()->cascadeOnDelete(); // Collegamento al mercato
            $table->foreignId('owner_id')->constrained('citizens')->cascadeOnDelete(); // Proprietario della bancarella (cittadino)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stalls');
    }
};
