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
        Schema::create('auto_waste_disposers', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo di smaltitore (es. Compostatore, Mini-Riciclatore)
            $table->decimal('efficiency', 5, 2); // Efficienza di riduzione rifiuti (percentuale)
            $table->foreignId('citizen_id')->constrained('citizens')->onDelete('cascade'); // Collega lo smaltitore alla famiglia/cittadino
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_waste_disposers');
    }
};
