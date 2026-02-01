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
        // Only add columns when table exists and column does not already exist
        if (!Schema::hasTable('portfolios')) {
            return;
        }

        if (!Schema::hasColumn('portfolios', 'festive_discount_enabled')) {
            Schema::table('portfolios', function (Blueprint $table) {
                $table->boolean('festive_discount_enabled')->default(false)->after('status');
            });
        }

        if (!Schema::hasColumn('portfolios', 'festive_discount_percentage')) {
            Schema::table('portfolios', function (Blueprint $table) {
                $table->decimal('festive_discount_percentage', 5, 2)->default(0)->after('festive_discount_enabled');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['festive_discount_enabled', 'festive_discount_percentage']);
        });
    }
};
