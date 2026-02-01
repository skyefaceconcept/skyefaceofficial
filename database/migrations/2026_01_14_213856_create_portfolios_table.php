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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('detailed_description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('category')->default('web'); // web, mobile, design, etc.
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->integer('view_count')->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            // Festive discount fields (merged from add migration)
            $table->boolean('festive_discount_enabled')->default(false);
            $table->decimal('festive_discount_percentage', 5, 2)->default(0);
            $table->timestamps();

            $table->index('category');
            $table->index('status');
            $table->index('view_count');
            $table->index('created_at');
            // Full-text search for fresh installs
            $table->fullText(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
