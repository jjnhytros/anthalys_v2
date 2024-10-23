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
        Schema::create('citizens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_employed')->default(false); // Indica se il cittadino è lavorativamente attivo
            $table->decimal('income', 15, 2)->default(0);   // Reddito del cittadino
            $table->decimal('cash', 12, 2)->default(0);     // Aggiungi il campo 'cash'
            $table->decimal('salary', 15, 2)->default(0);   // Stipendio del cittadino
            $table->decimal('taxes_due', 12, 2)->default(0); // Tasse da pagare
            $table->decimal('hours_worked', 12, 2)->default(0); // Ore lavorate totali
            $table->boolean('is_working')->default(true); // Stato di lavoro (attivo o in pausa)
            $table->integer('bonus_points')->nullable()->default(0);
            $table->integer('recycling_points')->default(0); // Punti di riciclo

            $table->foreignId('district_id')->nullable()->constrained('districts')->cascadeOnDelete();
            $table->foreignId('residential_building_id')->nullable()->constrained('buildings')->cascadeOnDelete(); // Collega alla casa (residenziale)
            $table->foreignId('work_building_id')->nullable()->constrained('buildings')->cascadeOnDelete(); // Collega al luogo di lavoro (commerciale/industriale)
            $table->foreignId('city_id')->constrained()->cascadeOnDelete(); // Collega alla città
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizens');
    }
};
