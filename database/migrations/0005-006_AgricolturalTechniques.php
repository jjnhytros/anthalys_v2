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
        Schema::create('agricultural_techniques', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('efficiency_boost', 5, 2); // Es. 1.15 per il 15% di boost
            $table->decimal('sustainability_level', 5, 2); // Es. 1.10 per un aumento del 10% della sostenibilità
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agricultural_techniques');
    }
};
