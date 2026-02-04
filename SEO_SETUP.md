SEO setup summary

What's included:
- Installed: artesaos/seotools (meta + social + json-ld), spatie/laravel-sitemap (sitemap generator), spatie/robots-txt (robots generator)
- Migration and model to store per-page SEO (`seo_meta` table + `App\Models\SeoMeta`)
- `App\Traits\HasSeo` trait to attach SEO to any Eloquent model
- `App\Services\SeoService` to apply SEO values to SEOTools facades
- Admin UI: `admin/seo` (index, edit, update)
- Artisan command: `php artisan sitemap:generate` (scheduled daily)
- Dynamic `robots.txt` route at `/robots.txt`

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
- An experimental AI generator is available from the Admin UI on the SEO edit page.
- To enable AI integration, set these environment variables in your `.env` (no key is committed):

  - `AI_PROVIDER=openai`
  - `AI_API_KEY=sk-...` (your OpenAI API key)
  - `AI_MODEL=gpt-3.5-turbo` (optional; defaults to `gpt-3.5-turbo`)

- The admin 'Edit SEO' page contains a **Generate with AI** button which will call the configured provider and return a JSON payload you can preview and apply to the form.

Next improvements (I can implement):
- Bulk SEO editor / CSV import-export
- A UI for assigning SEO to specific pages (select model & id) with search
- Automatic crawl & audit (broken links, Lighthouse integration)
- Google Search Console integration for submitting sitemaps and fetching index data

Would you like me to implement any of the Next improvements now?
