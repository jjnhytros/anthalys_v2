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
        Schema::create('job_market', function (Blueprint $table) {
            $table->id();
            $table->foreignId('occupation_id')->constrained()->cascadeOnDelete();
            $table->integer('demand')->default(0); // Rappresenta la domanda per l'occupazione
            $table->integer('supply')->default(0); // Rappresenta l'offerta di candidati
            $table->decimal('average_salary', 12, 2)->default(0.00); // Stipendio medio calcolato
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_markets');
    }
};
