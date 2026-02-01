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
        // Already merged into create_orders_table — no-op if license_duration exists
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'license_duration')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'license_duration')) {
                $table->enum('license_duration', ['6months', '1year', '2years'])->after('status')->default('1year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'license_duration')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('license_duration');
            });
        }
    }
};
