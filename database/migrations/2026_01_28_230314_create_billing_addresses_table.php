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
        Schema::create('billing_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('country')->default('Nigeria');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            // Add index for quick lookups
            $table->index('customer_email');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_addresses');
    }
};
