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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->enum('sender_type', ['client', 'admin']); // Who sent the message
            $table->unsignedBigInteger('sender_id')->nullable(); // If admin, reference to user_id
            $table->longText('message');
            $table->json('attachments')->nullable(); // Store file paths if attachments are added
            $table->timestamps();

            // Foreign key
            $table->foreign('ticket_id')
                  ->references('id')
                  ->on('contact_tickets')
                  ->onDelete('cascade');

            // Indexes
            $table->index('ticket_id');
            $table->index('sender_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
