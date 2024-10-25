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
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->nullable()->constrained()->nullOnDelete(); // Collegamento alla fattoria
            $table->foreignId('drone_id')->nullable()->constrained()->nullOnDelete(); // Collegamento al drone
            $table->decimal('temperature', 5, 2)->nullable(); // Temperatura rilevata
            $table->decimal('humidity', 5, 2)->nullable(); // Umidità rilevata
            $table->decimal('crop_health', 5, 2)->nullable(); // Stato di salute delle colture
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
