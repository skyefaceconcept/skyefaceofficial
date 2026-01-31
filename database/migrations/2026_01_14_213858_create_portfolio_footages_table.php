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
        Schema::create('portfolio_footages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained('portfolios')->onDelete('cascade');
            $table->enum('type', ['photo', 'video'])->default('photo');
            $table->string('media_path'); // path to photo or video
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('portfolio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_footages');
    }
};
