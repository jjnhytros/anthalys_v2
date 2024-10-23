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
        Schema::create('infrastructure_maintenance_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('infrastructure_id')->constrained('infrastructures')->onDelete('cascade');
            $table->dateTime('maintained_at'); // Data della manutenzione
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastructure_maintenance_histories');
    }
};
