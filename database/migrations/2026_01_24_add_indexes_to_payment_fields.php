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
        Schema::table('repairs', function (Blueprint $table) {
            // Add indexes for payment lookups
            if (!Schema::hasColumn('repairs', 'payment_reference')) {
                // Column doesn't exist, skip
            } else {
                // Add index to payment_reference if it doesn't exist
                try {
                    $table->index('payment_reference');
                } catch (\Exception $e) {
                    // Index might already exist, skip
                }
            }

            if (!Schema::hasColumn('repairs', 'payment_id')) {
                // Column doesn't exist, skip
            } else {
                // Add index to payment_id if it doesn't exist
                try {
                    $table->index('payment_id');
                } catch (\Exception $e) {
                    // Index might already exist, skip
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safely skip rollback to avoid issues with foreign key constraints
        // The indexes don't hurt if left in place
    }
};
