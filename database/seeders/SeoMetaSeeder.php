<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoMeta;

class SeoMetaSeeder extends Seeder
{
    public function run()
    {
        SeoMeta::firstOrCreate(
            ['seoable_id' => 0, 'seoable_type' => 'site'],
            ['title' => config('app.name'), 'meta_description' => config('app.name') . ' - Official site']
        );
    }
}
