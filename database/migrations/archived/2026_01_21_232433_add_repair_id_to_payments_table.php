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
        // Already merged into create_payments_table — no-op if column exists
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'repair_id')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'repair_id')) {
                $table->unsignedBigInteger('repair_id')->nullable()->index();
                // add FK later when repairs table exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('payments', 'repair_id')) {
            Schema::table('payments', function (Blueprint $table) {
                try {
                    $table->dropForeign(['repair_id']);
                } catch (\Exception $e) {
                    // ignore if foreign key does not exist
                }
                $table->dropColumn('repair_id');
            });
        }
    }
};
