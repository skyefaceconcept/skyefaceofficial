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
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'invoice_number')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            // Add invoice_number as a unique, indexed column
            if (! Schema::hasColumn('orders', 'invoice_number')) {
                $table->string('invoice_number')->after('id')->unique()->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('invoice_number');
        });
    }
};
