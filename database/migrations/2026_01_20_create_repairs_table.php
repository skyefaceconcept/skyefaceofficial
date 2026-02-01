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
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('device_type');
            $table->string('device_brand');
            $table->string('device_model');
            $table->text('issue_description');
            $table->enum('urgency', ['Normal', 'Express', 'Urgent'])->default('Normal');
            $table->string('status')->default('Received'); // Received, Diagnosed, In Progress, Quality Check, Ready for Pickup, Completed
            $table->timestamp('estimated_completion')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('cost_estimate', 10, 2)->nullable();
            $table->decimal('cost_actual', 10, 2)->nullable();

            // Payment related fields (merged from add_payment_fields_to_repairs_table)
            $table->string('payment_status')->default('pending')->after('cost_actual');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_status');
            $table->string('payment_reference')->nullable()->after('payment_verified_at');
            $table->string('payment_processor')->nullable()->after('payment_reference');
            $table->unsignedBigInteger('payment_id')->nullable()->after('payment_processor');

            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('invoice_number');
            $table->index('customer_email');
            $table->index('status');
            $table->index('created_at');
            // Indexes for payment lookup
            try {
                $table->index('payment_reference');
            } catch (\Exception $e) {
                // ignore if DB doesn't support or index exists
            }
            try {
                $table->index('payment_id');
            } catch (\Exception $e) {
                // ignore
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
