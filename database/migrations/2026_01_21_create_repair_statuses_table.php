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
        Schema::create('repair_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_id')->constrained('repairs')->onDelete('cascade');
            $table->string('status');
            $table->text('description')->nullable();
            $table->integer('stage')->default(1); // 1-6 representing stages
            $table->timestamp('estimated_completion')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable(); // Admin who updated the status
            $table->timestamps();

            // Indexes
            $table->index('repair_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_statuses');
    }
};
