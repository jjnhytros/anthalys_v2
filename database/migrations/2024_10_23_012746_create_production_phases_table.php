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
        Schema::create('production_phases', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // es. 'Fermentazione', 'Maturazione'
            $table->text('description')->nullable();
            $table->integer('duration'); // In giorni
            $table->timestamps();
        });

        Schema::create('alcoholic_production_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alcoholic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('production_phase_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alcoholic_production_phases');
        Schema::dropIfExists('production_phases');
    }
};
