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
        Schema::table('payments', function (Blueprint $table) {
            // Add missing columns for order payments
            if (!Schema::hasColumn('payments', 'order_id')) {
                $table->foreignId('order_id')->nullable()->after('repair_id')->constrained('orders')->onDelete('cascade');
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
