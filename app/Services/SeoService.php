<?php

namespace App\Services;

use Artesaos\SEOTools\Facades\SEOTools as SEOToolsFacade;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;

use Illuminate\Http\Request;

class SeoService
{
    /**
     * Apply SEO data to the SEOTools facades. (legacy/compatibility)
     */
    public function apply(array $data = []): void
    {
        // Keep minimal compatibility: set meta description only when SEOTools present
        $description = $data['meta_description'] ?? config('app.description', '');
        if (class_exists('\Artesaos\SEOTools\Facades\SEOMeta')) {
            \Artesaos\SEOTools\Facades\SEOMeta::setDescription($description);
        }
    }

    /**
     * Resolve a meta description for the current request.
     * Priority: page slug -> site default -> config
     */
    public function getDescriptionForRequest(Request $request): string
    {
        $slug = trim($request->path(), '/');
        $slug = $slug === '' ? 'home' : $slug;

        // Try page-specific SEO row
        $pageSeo = \App\Models\SeoMeta::where('page_slug', $slug)->first();
        if ($pageSeo && $pageSeo->meta_description) {
            return trim(mb_substr($pageSeo->meta_description, 0, 160));
        }

        // Site-wide default
        $siteSeo = \App\Models\SeoMeta::where('is_site_default', true)->first();
        if ($siteSeo && $siteSeo->meta_description) {
            return trim(mb_substr($siteSeo->meta_description, 0, 160));
        }

        // Fallback to config
        return config('app.description', '');
    }
}
