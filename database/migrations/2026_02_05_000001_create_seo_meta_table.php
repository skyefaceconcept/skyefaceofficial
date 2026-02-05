<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Be defensive: don't fail if the table already exists (idempotent migration)
        if (! Schema::hasTable('seo_meta')) {
            Schema::create('seo_meta', function (Blueprint $table) {
                $table->id();
                $table->string('page_slug')->nullable()->index();
                $table->string('title')->nullable();
                $table->string('meta_description', 200)->nullable();
                $table->boolean('is_site_default')->default(false)->index();
                $table->timestamps();

                $table->unique(['page_slug']);
            });

            // Create a site default row placeholder (will be editable via admin)
            DB::table('seo_meta')->insert([
                'page_slug' => null,
                'title' => null,
                'meta_description' => config('app.description', ''),
                'is_site_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // If the table exists, ensure there is a site default row so the app has a fallback
            if (! DB::table('seo_meta')->where('is_site_default', true)->exists()) {
                DB::table('seo_meta')->insert([
                    'page_slug' => null,
                    'title' => null,
                    'meta_description' => config('app.description', ''),
                    'is_site_default' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
