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
        // Columns already merged into create_payments_table — no-op if present
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'order_id') && Schema::hasColumn('payments', 'transaction_reference')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            // Add missing columns for order payments if they don't exist
            if (!Schema::hasColumn('payments', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->index();
                // add FK later when orders table exists
            }
            if (!Schema::hasColumn('payments', 'processor')) {
                $table->string('processor')->nullable()->after('currency');
            }
            if (!Schema::hasColumn('payments', 'transaction_reference')) {
                $table->string('transaction_reference')->nullable()->after('transaction_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
            if (Schema::hasColumn('payments', 'processor')) {
                $table->dropColumn('processor');
            }
            if (Schema::hasColumn('payments', 'transaction_reference')) {
                $table->dropColumn('transaction_reference');
            }
        });
    }
};
