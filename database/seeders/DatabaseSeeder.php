<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create a site default SEO entry (seoable_type = 'site')
        \App\Models\SeoMeta::firstOrCreate([
            'seoable_id' => 0,
            'seoable_type' => 'site'
        ], [
            'title' => config('app.name'),
            'meta_description' => config('app.name') . ' - Official site',
        ]);
    }
}
