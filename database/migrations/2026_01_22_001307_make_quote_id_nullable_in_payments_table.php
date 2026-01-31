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
            // Make quote_id nullable since payments can be for repairs too
            $table->foreignId('quote_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip reverting to avoid NULL issues during rollback
    }
};
