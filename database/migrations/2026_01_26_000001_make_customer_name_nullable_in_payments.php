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
            // Make customer_name nullable
            if (Schema::hasColumn('payments', 'customer_name')) {
                $table->string('customer_name')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'customer_name')) {
                $table->string('customer_name')->change();
            }
        });
    }
};
