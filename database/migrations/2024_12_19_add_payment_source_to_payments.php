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
            // Add payment_source column after payment_method if it doesn't exist
            if (!Schema::hasColumn('payments', 'payment_source')) {
                $table->string('payment_source')->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_source')) {
                $table->dropColumn('payment_source');
            }
        });
    }
};
