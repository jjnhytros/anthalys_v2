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
        Schema::create('drones', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo di drone (monitoraggio, multisensore, ecc.)
            $table->decimal('battery_level', 5, 2); // Livello della batteria del drone
            $table->string('status'); // Stato del drone (attivo, inattivo, in manutenzione)
            $table->foreignId('farm_id')->nullable()->constrained()->nullOnDelete(); // Collegamento alla fattoria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drones');
    }
};
