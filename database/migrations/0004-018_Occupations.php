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
        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('salary', 12, 2);
            $table->integer('stress_level')->default(5); // Stress standard associato
            $table->integer('min_reputation')->default(0);
            $table->integer('min_other_occupation_level')->default(0);

            $table->foreignId('min_required_skill_id')->nullable()->constrained('skills')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupations');
    }
};
