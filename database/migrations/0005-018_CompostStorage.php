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
        Schema::create('compost_storage', function (Blueprint $table) {
            $table->id();
            $table->decimal('compostable_material', 12, 2)->default(0); // Materiale compostabile
            $table->decimal('available_compost', 12, 2)->default(0); // Compost finale disponibile
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compost_storages');
    }
};
