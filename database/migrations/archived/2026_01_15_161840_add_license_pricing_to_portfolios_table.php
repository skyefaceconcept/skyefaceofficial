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
        // Only add license pricing columns if table exists and columns are missing
        if (! Schema::hasTable('portfolios')) {
            return;
        }

        if (! Schema::hasColumn('portfolios', 'price_6months')) {
            Schema::table('portfolios', function (Blueprint $table) {
                $table->decimal('price_6months', 10, 2)->after('price')->nullable();
            });
        }
        if (! Schema::hasColumn('portfolios', 'price_1year')) {
            Schema::table('portfolios', function (Blueprint $table) {
                $table->decimal('price_1year', 10, 2)->after('price_6months')->nullable();
            });
        }
        if (! Schema::hasColumn('portfolios', 'price_2years')) {
            Schema::table('portfolios', function (Blueprint $table) {
                $table->decimal('price_2years', 10, 2)->after('price_1year')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['price_6months', 'price_1year', 'price_2years']);
        });
    }
};
