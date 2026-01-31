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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('license_code')->unique();
            $table->string('application_name');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired', 'revoked'])->default('active');
            $table->integer('activation_count')->default(0);
            $table->string('last_activated_ip')->nullable();
            $table->timestamp('last_activated_at')->nullable();
            $table->json('metadata')->nullable(); // Store device info, etc
            $table->timestamps();

            $table->index('license_code');
            $table->index('status');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
