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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('population')->nullable();
            $table->string('type')->default('Residenziale');
            $table->decimal('area', 8, 2); // Area in km^2
            $table->text('description')->nullable();
            $table->foreignId('city_id')->constrained()->onDelete('cascade'); // Collegamento alla città
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
