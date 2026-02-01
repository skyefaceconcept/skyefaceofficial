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

            // Invoice number (unique but nullable to avoid early constraint failures)
            $table->string('invoice_number')->nullable()->unique();

            $table->foreignId('portfolio_id')->constrained('portfolios')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('billing_address_id')->nullable()->index();

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();

            // Checkout address fields
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();

            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('NGN');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('transaction_reference')->nullable()->unique();

            // Cart and license
            $table->json('cart_items')->nullable();
            $table->enum('license_duration', ['6months', '1year', '2years'])->default('1year');

            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Indexes
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
