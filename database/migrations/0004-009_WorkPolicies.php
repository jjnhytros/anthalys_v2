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
        Schema::create('work_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('work_hours_per_day'); // Durata della giornata lavorativa
            $table->integer('work_days_per_week'); // Giorni lavorativi a settimana
            $table->integer('work_months_per_year'); // Mesi di lavoro all'anno
            $table->integer('vacation_days'); // Giorni di vacanza all'anno
            $table->integer('sick_leave_days'); // Giorni di malattia
            $table->integer('maternity_leave_days'); // Giorni di congedo maternità/paternità
            $table->json('tax_brackets'); // Fasce di tassazione in formato JSON
            $table->json('pension_conditions'); // Condizioni pensionistiche in formato JSON
            $table->integer('healthcare_for_low_income'); // Soglia di reddito per l'assistenza sanitaria gratuita
            $table->json('benefits'); // Benefici per i cittadini (es. sicurezza sul lavoro)
            $table->timestamps(); // Timestamp per created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_policies');
    }
};
