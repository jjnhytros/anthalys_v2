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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('citizens')->cascadeOnDelete(); // Collega il mittente (cittadino)
            $table->foreignId('recipient_id')->constrained('citizens')->cascadeOnDelete(); // Collega il destinatario (cittadino)
            $table->string('subject');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->string('type')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_message')->default(true); // Booleano per identificare se è un messaggio
            $table->boolean('is_notification')->default(false); // Booleano per notifiche
            $table->boolean('is_email')->default(false); // Booleano per email
            $table->boolean('is_archived')->default(false); // Booleano per archiviazione
            $table->enum('status', ['sent', 'unread', 'read', 'archived'])->default('unread');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
