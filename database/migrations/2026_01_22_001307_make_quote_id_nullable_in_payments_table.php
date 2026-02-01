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
        // Already covered by the payments create migration (quote_id is nullable by default). No-op.
        if (Schema::hasTable('payments')) {
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip reverting to avoid NULL issues during rollback
    }
};
