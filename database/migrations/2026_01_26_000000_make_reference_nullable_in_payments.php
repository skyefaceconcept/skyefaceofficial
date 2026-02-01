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
        // Make reference nullable and drop the unique constraint if it exists
        $hasIndex = \Illuminate\Support\Facades\DB::select("SHOW INDEX FROM payments WHERE Column_name = 'reference'");
        if (! empty($hasIndex)) {
            try {
                Schema::table('payments', function (Blueprint $table) {
                    $table->dropIndex(['reference']);
                });
            } catch (\Exception $e) {
                // ignore if we couldn't drop the index
            }
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->string('reference')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change column back to NOT NULL first
        Schema::table('payments', function (Blueprint $table) {
            $table->string('reference')->change();
        });

        // Add unique index only if it does not already exist
        $exists = \Illuminate\Support\Facades\DB::select("SHOW INDEX FROM payments WHERE Column_name = 'reference' AND Key_name = 'payments_reference_unique'");
        if (empty($exists)) {
            Schema::table('payments', function (Blueprint $table) {
                $table->unique('reference');
            });
        }
    }
};
