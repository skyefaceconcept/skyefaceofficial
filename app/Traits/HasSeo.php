<?php

namespace App\Traits;

use App\Models\SeoMeta;

trait HasSeo
{
    public function seoMeta()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * Get the merged SEO array (model SEO falls back to site defaults)
     */
    public function getSeoData(array $defaults = []) : array
    {
        $meta = $this->seoMeta ? $this->seoMeta->toArray() : [];
        return array_merge($defaults, $meta);
    }

    public function updateSeo(array $data)
    {
        if ($this->seoMeta) {
            return $this->seoMeta()->update($data);
        }

        return $this->seoMeta()->create($data);
    }
}
