<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeoMeta;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        $xml = $this->generateXml();
        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Generate sitemap XML string used by the public route and by PingSearchEngines job.
     */
    public function generateXml(): string
    {
        $items = SeoMeta::orderBy('page_slug')->get();

        $base = rtrim(config('app.url', url('/')), '/');

        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Always include homepage
        $xml[] = "  <url>\n    <loc>{$base}/</loc>\n  </url>";

        foreach ($items as $item) {
            if ($item->is_site_default) continue; // site default not a page URL
            $slug = $item->page_slug ?? '';
            $loc = $base . '/' . ltrim($slug, '/');
            $xml[] = "  <url>\n    <loc>{$loc}</loc>\n  </url>";
        }

        $xml[] = '</urlset>';

        return implode("\n", $xml);
    }
}
