<?php

namespace App\Services;

use Artesaos\SEOTools\Facades\SEOTools as SEOToolsFacade;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;

class SeoService
{
    /**
     * Apply SEO data to the SEOTools facades.
     * Accepts an array with possible keys like: title, meta_description, canonical, noindex, nofollow,
     * og_title, og_description, og_image, twitter_title, twitter_description, twitter_image, json_ld
     */
    public function apply(array $data = []): void
    {
        $title = $data['title'] ?? config('app.name');
        $description = $data['meta_description'] ?? config('app.description', '');

        SEOToolsFacade::setTitle($title);
        SEOMeta::setDescription($description);

        if (! empty($data['canonical'])) {
            SEOMeta::setCanonical($data['canonical']);
        }

        if (! empty($data['noindex']) || ! empty($data['nofollow'])) {
            $robots = [];
            if (! empty($data['noindex'])) $robots[] = 'noindex';
            if (! empty($data['nofollow'])) $robots[] = 'nofollow';
            SEOMeta::addMeta('robots', implode(',', $robots), 'name');
        }

        // Open Graph
        if (! empty($data['og_title'])) OpenGraph::setTitle($data['og_title']);
        if (! empty($data['og_description'])) OpenGraph::setDescription($data['og_description']);
        if (! empty($data['og_image'])) OpenGraph::addImage($data['og_image']);

        // Twitter
        if (! empty($data['twitter_title'])) TwitterCard::addValue('title', $data['twitter_title']);
        if (! empty($data['twitter_description'])) TwitterCard::addValue('description', $data['twitter_description']);
        if (! empty($data['twitter_image'])) TwitterCard::addValue('image', $data['twitter_image']);

        // Json-LD
        if (! empty($data['json_ld'])) {
            try {
                $json = is_array($data['json_ld']) ? $data['json_ld'] : json_decode($data['json_ld'], true);
                if ($json) JsonLd::addValue('@graph', $json);
            } catch (\Throwable $e) {
                // ignore invalid json
            }
        }
    }
}
