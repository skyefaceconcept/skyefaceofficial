<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml using Spatie\Sitemap';

    public function handle()
    {
        $this->info('Generating sitemap...');
        SitemapGenerator::create(config('app.url'))->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated at public/sitemap.xml');
        return 0;
    }
}
