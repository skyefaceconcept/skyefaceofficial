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
        // If indexes are already present or columns missing, do nothing
        if (! Schema::hasTable('repairs')) {
            return;
        }

        // if both indexes exist already, early return
        $hasPaymentReferenceIndex = false;
        $hasPaymentIdIndex = false;
        try {
            // Some DB drivers may not implement hasIndex; these checks may be best-effort
            $hasPaymentReferenceIndex = Schema::hasIndex('repairs', 'repairs_payment_reference_index');
            $hasPaymentIdIndex = Schema::hasIndex('repairs', 'repairs_payment_id_index');
        } catch (\Throwable $t) {
            // ignore and continue
        }

        if ($hasPaymentReferenceIndex && $hasPaymentIdIndex) {
            return;
        }

        Schema::table('repairs', function (Blueprint $table) {
            // Add indexes for payment lookups
            if (Schema::hasColumn('repairs', 'payment_reference') && !isset($hasPaymentReferenceIndex)) {
                try {
                    $table->index('payment_reference');
                } catch (\Exception $e) {
                    // Index might already exist or be invalid; skip
                }
            }

            if (Schema::hasColumn('repairs', 'payment_id') && !isset($hasPaymentIdIndex)) {
                try {
                    $table->index('payment_id');
                } catch (\Exception $e) {
                    // Index might already exist or be invalid; skip
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
