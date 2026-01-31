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
        Schema::table('brandings', function (Blueprint $table) {
            if (!Schema::hasColumn('brandings', 'company_name')) {
                $table->string('company_name')->nullable()->after('name_logo');
            }
            if (!Schema::hasColumn('brandings', 'cac_number')) {
                $table->string('cac_number')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('brandings', 'rc_number')) {
                $table->string('rc_number')->nullable()->after('cac_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brandings', function (Blueprint $table) {
            if (Schema::hasColumn('brandings', 'rc_number')) {
                $table->dropColumn('rc_number');
            }
            if (Schema::hasColumn('brandings', 'cac_number')) {
                $table->dropColumn('cac_number');
            }
            if (Schema::hasColumn('brandings', 'company_name')) {
                $table->dropColumn('company_name');
            }
        });
    }
};