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
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('show_menu_offer_image');
            $table->string('cac_number')->nullable()->after('company_name');
            $table->string('rc_number')->nullable()->after('cac_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safely drop columns only if they exist
        if (Schema::hasColumn('company_settings', 'company_name') || Schema::hasColumn('company_settings', 'cac_number') || Schema::hasColumn('company_settings', 'rc_number')) {
            Schema::table('company_settings', function (Blueprint $table) {
                if (Schema::hasColumn('company_settings', 'company_name')) {
                    $table->dropColumn('company_name');
                }
                if (Schema::hasColumn('company_settings', 'cac_number')) {
                    $table->dropColumn('cac_number');
                }
                if (Schema::hasColumn('company_settings', 'rc_number')) {
                    $table->dropColumn('rc_number');
                }
            });
        }
    }
};
