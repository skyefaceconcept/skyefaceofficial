<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PingSearchEngines implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $sitemapUrl;

    public function __construct(string $sitemapUrl)
    {
        $this->sitemapUrl = $sitemapUrl;
    }

    public function handle(): void
    {
        try {
            $encoded = urlencode($this->sitemapUrl);
            $google = 'https://www.google.com/ping?sitemap=' . $encoded;
            $bing = 'https://www.bing.com/ping?sitemap=' . $encoded;

            Log::info('PingSearchEngines: pinging search engines', ['sitemap' => $this->sitemapUrl]);

            $g = Http::get($google);
            Log::info('PingSearchEngines: google response', ['status' => $g->status()]);

            $b = Http::get($bing);
            Log::info('PingSearchEngines: bing response', ['status' => $b->status()]);

            // Optionally, write a cached copy of the sitemap to public/ for crawlers that expect a file
            try {
                $sitemapXml = app(\App\Http\Controllers\SitemapController::class)->generateXml();
                file_put_contents(public_path('sitemap.xml'), $sitemapXml);
            } catch (\Throwable $e) {
                Log::warning('PingSearchEngines: failed to write sitemap file', ['error' => $e->getMessage()]);
            }
        } catch (\Throwable $e) {
            Log::error('PingSearchEngines: job failed', ['error' => $e->getMessage()]);
        }
    }
}
