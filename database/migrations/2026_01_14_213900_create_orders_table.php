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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained('portfolios')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('NGN');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('transaction_reference')->nullable()->unique();
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['portfolio_id', 'status']);
            $table->index('customer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
