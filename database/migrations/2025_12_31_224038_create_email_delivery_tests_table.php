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
        Schema::create('email_delivery_tests', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->index(); // Gmail, Outlook, Yahoo, etc.
            $table->string('test_email'); // test@gmail.com, test@live.com, test@yahoo.com
            $table->string('status')->default('pending'); // pending, sent, delivered, failed, bounced
            $table->text('error_message')->nullable(); // SMTP error or delivery failure reason
            $table->integer('response_code')->nullable(); // SMTP response code (250, 550, etc.)
            $table->timestamp('last_tested_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_delivery_tests');
    }
};
