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
            // Add payment fields if they don't exist
            if (!Schema::hasColumn('repairs', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('cost_actual');
            }
            if (!Schema::hasColumn('repairs', 'payment_verified_at')) {
                $table->timestamp('payment_verified_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('repairs', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_verified_at');
            }
            if (!Schema::hasColumn('repairs', 'payment_processor')) {
                $table->string('payment_processor')->nullable()->after('payment_reference');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            // Drop payment fields if they exist
            if (Schema::hasColumn('repairs', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('repairs', 'payment_verified_at')) {
                $table->dropColumn('payment_verified_at');
            }
            if (Schema::hasColumn('repairs', 'payment_reference')) {
                $table->dropColumn('payment_reference');
            }
            if (Schema::hasColumn('repairs', 'payment_processor')) {
                $table->dropColumn('payment_processor');
            }
        });
    }
};
