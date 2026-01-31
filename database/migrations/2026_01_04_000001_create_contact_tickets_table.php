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
        Schema::create('contact_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // e.g., TICKET-001, TICKET-002
            $table->string('user_email');
            $table->string('user_name');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->enum('status', ['open', 'pending_reply', 'closed'])->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->dateTime('last_reply_date')->nullable();
            $table->dateTime('auto_closed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('assigned_to');
            $table->index('user_email');
            $table->index('last_reply_date');

            // Foreign key
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_tickets');
    }
};
