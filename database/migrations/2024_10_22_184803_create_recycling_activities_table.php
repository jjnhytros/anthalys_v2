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

        Schema::create('district_recycling_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->string('resource_type'); // Tipo di risorsa (es. plastica)
            $table->decimal('target_quantity', 12, 2); // Obiettivo di quantità
            $table->decimal('current_quantity', 12, 2)->default(0); // Quantità attuale riciclata
            $table->timestamps();
        });

        Schema::create('recycling_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained()->onDelete('cascade');
            $table->foreignId('waste_type_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 8, 2); // Quantità di rifiuto riciclato
            $table->timestamps(); // Timestamp per tenere traccia del progresso nel tempo
        });

        Schema::create('recycling_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_id')->constrained()->onDelete('cascade');
            $table->string('award_type'); // Tipo di premio (es. "Cittadino Sostenibile")
            $table->integer('year'); // Anno in cui viene assegnato il premio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recycling_awards');
        Schema::dropIfExists('recycling_progress');
        Schema::dropIfExists('district_recycling_goals');
        Schema::dropIfExists('recycling_activities');
    }
};
