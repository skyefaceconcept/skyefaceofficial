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
                $table->string('company_name')->nullable();
            }
            if (!Schema::hasColumn('brandings', 'cac_number')) {
                $table->string('cac_number')->nullable();
            }
            if (!Schema::hasColumn('brandings', 'rc_number')) {
                $table->string('rc_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop columns only if they exist to support older Laravel versions
        if (Schema::hasColumn('brandings', 'company_name') || Schema::hasColumn('brandings', 'cac_number') || Schema::hasColumn('brandings', 'rc_number')) {
            Schema::table('brandings', function (Blueprint $table) {
                if (Schema::hasColumn('brandings', 'company_name')) {
                    $table->dropColumn('company_name');
                }
                if (Schema::hasColumn('brandings', 'cac_number')) {
                    $table->dropColumn('cac_number');
                }
                if (Schema::hasColumn('brandings', 'rc_number')) {
                    $table->dropColumn('rc_number');
                }
            });
        }
    }
};
