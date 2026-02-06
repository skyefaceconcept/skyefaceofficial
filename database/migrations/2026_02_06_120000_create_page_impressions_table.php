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
        Schema::create('page_impressions', function (Blueprint $table) {
            $table->id();
            $table->string('page_url');
            $table->string('page_title')->nullable();
            $table->string('route_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('device_type')->default('desktop'); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->decimal('time_spent_seconds', 10, 2)->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index('page_url');
            $table->index('route_name');
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['page_url', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        // Create a daily summary table for faster dashboard queries
        Schema::create('page_impression_daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('page_url');
            $table->string('page_title')->nullable();
            $table->string('route_name')->nullable();
            $table->unsignedInteger('total_impressions')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->unsignedInteger('mobile_impressions')->default(0);
            $table->unsignedInteger('desktop_impressions')->default(0);
            $table->unsignedInteger('tablet_impressions')->default(0);
            $table->decimal('avg_time_spent_seconds', 10, 2)->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->unique(['date', 'page_url']);
            $table->index('date');
            $table->index('page_url');
            $table->index('route_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_impression_daily_summaries');
        Schema::dropIfExists('page_impressions');
    }
};
