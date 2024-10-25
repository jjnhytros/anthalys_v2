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
        Schema::create('production_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade'); // Collegamento alla fattoria
            $table->decimal('total_crop_yield', 8, 2); // Resa totale delle colture
            $table->decimal('total_animal_yield', 8, 2); // Resa totale degli animali
            $table->decimal('vertical_farming_yield', 8, 2); // Resa dalla coltivazione verticale
            $table->string('report_period'); // Periodo del report (settimanale/mensile)
            $table->string('type')->default('monthly'); // Tipo di report (settimanale, mensile)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_reports');
    }
};
