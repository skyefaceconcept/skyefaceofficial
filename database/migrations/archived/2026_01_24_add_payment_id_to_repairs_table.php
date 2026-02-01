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
            // Add payment_id column to link repairs to payment records
            if (!Schema::hasColumn('repairs', 'payment_id')) {
                $table->unsignedBigInteger('payment_id')->nullable()->after('payment_processor');
                // Add foreign key only when payments table exists to avoid ordering issues
                if (Schema::hasTable('payments')) {
                    try {
                        $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
                    } catch (\Exception $e) {
                        // ignore FK creation failures (partial migration ordering)
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            if (Schema::hasColumn('repairs', 'payment_id')) {
                $table->dropForeign(['payment_id']);
                $table->dropColumn('payment_id');
            }
        });
    }
};
