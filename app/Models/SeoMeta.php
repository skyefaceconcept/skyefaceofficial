<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'title', 'meta_description', 'meta_keywords', 'canonical', 'noindex', 'nofollow',
        'og_title', 'og_description', 'og_image',
        'twitter_title', 'twitter_description', 'twitter_image',
        'json_ld'
    ];

    protected $casts = [
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
    ];

    public function seoable()
    {
        return $this->morphTo();
    }
}
