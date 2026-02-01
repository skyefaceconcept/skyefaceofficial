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
        // Add notes column to repair_statuses table only if the table exists
        if (Schema::hasTable('repair_statuses') && ! Schema::hasColumn('repair_statuses', 'notes')) {
            Schema::table('repair_statuses', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('repair_statuses') && Schema::hasColumn('repair_statuses', 'notes')) {
            Schema::table('repair_statuses', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }
    }
};
