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
        // Ensure table exists before altering indexes
        if (!Schema::hasTable('portfolios')) {
            return;
        }

        Schema::table('portfolios', function (Blueprint $table) {
            // Add indexes if they don't exist
            if (!Schema::hasIndex('portfolios', 'portfolios_status_index')) {
                $table->index('status');
            }
            if (!Schema::hasIndex('portfolios', 'portfolios_category_index')) {
                $table->index('category');
            }
            if (!Schema::hasIndex('portfolios', 'portfolios_view_count_index')) {
                $table->index('view_count');
            }
            if (!Schema::hasIndex('portfolios', 'portfolios_created_at_index')) {
                $table->index('created_at');
            }
            // Full text search indexes
            if (!Schema::hasIndex('portfolios', 'portfolios_title_description_fulltext')) {
                $table->fulltext(['title', 'description']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            // Drop indexes
            if (Schema::hasIndex('portfolios', 'portfolios_status_index')) {
                $table->dropIndex('portfolios_status_index');
            }
            if (Schema::hasIndex('portfolios', 'portfolios_category_index')) {
                $table->dropIndex('portfolios_category_index');
            }
            if (Schema::hasIndex('portfolios', 'portfolios_view_count_index')) {
                $table->dropIndex('portfolios_view_count_index');
            }
            if (Schema::hasIndex('portfolios', 'portfolios_created_at_index')) {
                $table->dropIndex('portfolios_created_at_index');
            }
            if (Schema::hasIndex('portfolios', 'portfolios_title_description_fulltext')) {
                $table->dropIndex('portfolios_title_description_fulltext');
            }
        });
    }
};
