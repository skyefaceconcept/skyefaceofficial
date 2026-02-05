SEO setup summary

What's included:
- **Simplified description manager** using `App\Models\SeoMeta` for per-page descriptions
- Migration and model to store per-page SEO (`seo_meta` table + `App\Models\SeoMeta`)
- `App\Traits\HasSeo` trait to attach SEO to any Eloquent model
- `App\Services\SeoService` (lightweight) to resolve a single meta description for pages and site default
- Admin UI: `admin/seo` (index, edit, update). The edit form is intentionally minimal: **title** and **meta_description** only
- Artisan command: `php artisan sitemap:generate` (scheduled daily) (unchanged)

How to attach SEO to a model
1. Add `use App\Traits\HasSeo;` to your model and `use HasSeo;` in the class.
2. Create or update SEO data: `$model->updateSeo(['title' => 'My Page', 'meta_description' => 'desc', ...]);`
3. In the controller that displays the page, apply the SEO before returning the view:

```php
$seoService = app(\App\Services\SeoService::class);
$seoService->apply($model->getSeoData([
    'title' => 'Fallback Title',
    'meta_description' => 'Fallback description',
]));
```

Manage SEO from Admin
- Go to Admin → Settings → SEO to edit entries.
- Entries are polymorphic (attached to a model) or a site default entry exists with `seoable_type = 'site'` and `seoable_id = 0`.

Sitemap & robots
- Run `php artisan sitemap:generate` to generate `public/sitemap.xml`.
- `robots.txt` is available at `/robots.txt` and references the sitemap.

AI-assisted generation
- Removed: AI-assisted generation and remote page fetching have been disabled to avoid non-deterministic metadata changes. If you need AI assistance in future it will be re-introduced as an opt-in, clearly flagged feature.

Next improvements (I can implement):
- Bulk SEO editor / CSV import-export
- A UI for assigning SEO to specific pages (select model & id) with search
- Automatic crawl & audit (broken links, Lighthouse integration)
- Google Search Console integration for submitting sitemaps and fetching index data

Would you like me to implement any of the Next improvements now?
