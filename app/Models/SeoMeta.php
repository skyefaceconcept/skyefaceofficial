<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'page_slug',
        'title',
        'meta_description',
        'is_site_default',
    ];

    protected $casts = [
        'is_site_default' => 'boolean',
    ];

    public static function siteDefault(): ?self
    {
        return static::where('is_site_default', true)->first();
    }
}
