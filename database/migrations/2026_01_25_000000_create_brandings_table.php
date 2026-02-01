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
        Schema::create('brandings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('name_logo')->nullable();

            // Added merged fields
            $table->boolean('show_menu_offer_image')->default(true);
            $table->string('company_name')->nullable();
            $table->string('cac_number')->nullable();
            $table->string('rc_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brandings');
    }
};
