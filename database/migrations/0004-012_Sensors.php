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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo di sensore (es. Umidità, Temperatura, Nutrienti)
            $table->decimal('value', 8, 2); // Valore letto dal sensore
            $table->foreignId('greenhouse_id')->constrained()->onDelete('cascade'); // Collegamento alla serra
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
