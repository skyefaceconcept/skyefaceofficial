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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('quotes')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('USD');
            $table->string('status')->default('pending'); // pending, completed, failed, cancelled
            $table->string('transaction_id')->nullable()->unique();
            $table->string('reference')->unique();
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('payment_method')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['quote_id', 'status']);
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
