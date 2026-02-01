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
        // If the payments table already exists (partial run), skip create to avoid duplicate-table errors
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();

                // Relationship ids (create as plain columns here; constraints added in later migrations when referenced tables exist)
                $table->unsignedBigInteger('quote_id')->nullable()->index();
                $table->unsignedBigInteger('repair_id')->nullable()->index();
                $table->unsignedBigInteger('order_id')->nullable()->index();

                // Monetary fields
                $table->decimal('amount', 12, 2);
                $table->string('currency')->default('USD');
                $table->string('status')->default('pending'); // pending, completed, failed, cancelled

                // Transaction identification
                $table->string('transaction_id')->nullable()->unique();
                $table->string('transaction_reference')->nullable();
                $table->string('reference')->nullable();

                // Customer info
                $table->string('customer_email');
                $table->string('customer_name')->nullable();

                // Payment metadata
                $table->string('payment_method')->nullable();
                $table->string('payment_source')->nullable();
                $table->json('response_data')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                // Indexes
                $table->index(['quote_id', 'status']);
                $table->index('reference');
            });
        } // end if (! Schema::hasTable('payments'))
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
