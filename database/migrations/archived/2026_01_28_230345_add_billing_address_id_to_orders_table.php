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
        // Already merged into create_orders_table — no-op if column exists
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'billing_address_id')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'billing_address_id')) {
                $table->unsignedBigInteger('billing_address_id')->nullable()->index();
                // FK will be created later when billing_addresses exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safely remove foreign key and column if present
        if (Schema::hasColumn('orders', 'billing_address_id')) {
            Schema::table('orders', function (Blueprint $table) {
                // Attempt to drop foreign key for billing_address_id
                try {
                    $table->dropForeign(['billing_address_id']);
                } catch (\Exception $e) {
                    // ignore if foreign key doesn't exist
                }
                $table->dropColumn('billing_address_id');
            });
        }
    }
};
