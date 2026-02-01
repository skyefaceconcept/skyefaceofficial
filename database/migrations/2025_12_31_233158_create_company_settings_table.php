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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable(); // Path to company logo
            $table->string('favicon')->nullable(); // Path to favicon
            $table->string('name_logo')->nullable();
            // Company info fields (merged from add_company_info_to_company_settings)
            $table->string('company_name')->nullable();
            $table->string('cac_number')->nullable();
            $table->string('rc_number')->nullable();
            $table->boolean('show_menu_offer_image')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
