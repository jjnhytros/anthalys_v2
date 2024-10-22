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
        Schema::create('resource_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_district_id')->constrained('districts')->onDelete('cascade');
            $table->foreignId('target_district_id')->constrained('districts')->onDelete('cascade');
            $table->string('resource_name');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_transfers');
    }
};
