<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        // Show site-default first then pages alphabetically
        $items = SeoMeta::orderBy('is_site_default', 'desc')->orderBy('page_slug')->paginate(25);
        return view('admin.seo.index', compact('items'));
    }

    public function create()
    {
        $pages = $this->availablePages();
        return view('admin.seo.create', compact('pages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'page_slug' => 'nullable|string|max:191|unique:seo_meta,page_slug',
            'title' => 'nullable|string|max:191',
            'meta_description' => 'required|string|max:160',
            'is_site_default' => 'sometimes|boolean',
        ]);

        if (! empty($data['is_site_default'])) {
            SeoMeta::where('is_site_default', true)->update(['is_site_default' => false]);
            $data['is_site_default'] = true;
            $data['page_slug'] = null;
        } else {
            $data['is_site_default'] = false;
        }

        $seo = SeoMeta::create($data);

        // Dispatch ping job delayed by 5 minutes
        \App\Jobs\PingSearchEngines::dispatch(url('/sitemap.xml'))->delay(now()->addMinutes(5));

        return redirect()->route('admin.seo.index')->with('success', 'SEO entry created. Search engines will be notified shortly.');
    }

    public function edit(SeoMeta $seo)
    {
        $pages = $this->availablePages();
        return view('admin.seo.edit', compact('seo', 'pages'));
    }

    /**
     * Edit a static page's SEO by slug. Creates a record if not present.
     */
    public function editPage(string $slug)
    {
        $seo = SeoMeta::firstOrCreate(
            ['seoable_type' => 'page', 'page_slug' => $slug],
            ['title' => null, 'meta_description' => null]
        );

        return view('admin.seo.edit', compact('seo'));
    }

    public function updatePage(Request $request, string $slug)
    {
        $seo = SeoMeta::firstOrCreate(['seoable_type' => 'page', 'page_slug' => $slug]);
        return $this->update($request, $seo);
    }

    /**
     * Collect available public pages (slugs) from web routes for convenience in admin.
     */
    protected function availablePages(): array
    {
        $pages = [];
        foreach (\Route::getRoutes() as $route) {
            try {
                // only public GET routes
                $methods = $route->methods();
            } catch (\Throwable $e) {
                continue;
            }
            if (! in_array('GET', $methods)) continue;

            $uri = $route->uri();
            // skip admin & api & parameterized routes
            if (str_starts_with($uri, 'admin') || str_starts_with($uri, 'api') || str_contains($uri, '{')) continue;

            $slug = trim($uri, '/');
            $slug = $slug === '' ? 'home' : $slug;

            // human label: route name or uri
            $label = $route->getName() ?: ($slug === 'home' ? 'Home' : ucfirst(str_replace('-', ' ', $slug)));

            $pages[$slug] = $label;
        }

        // ensure unique and sorted
        ksort($pages);
        return $pages;
    }

    // AI generation, remote page fetching and preview endpoints removed to simplify SEO features. AIWriter-based methods were intentionally deleted to keep meta description management deterministic and auditable.

    protected function parseHtmlForSeo(string $html): array
    {
        $data = [
            'title' => null,
            'meta_description' => null,
            'canonical' => null,
            'h1' => null,
            'paragraphs' => [],
            'json_ld' => null,
        ];

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        if (! @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'))) {
            // return whatever we could parse
            libxml_clear_errors();
            return $data;
        }
        $xpath = new \DOMXPath($dom);

        // title
        $titleNode = $xpath->query('//title')->item(0);
        if ($titleNode) $data['title'] = trim($titleNode->textContent);

        // meta description
        $metaDesc = $xpath->query('//meta[@name="description"]')->item(0);
        if ($metaDesc && $metaDesc->hasAttribute('content')) $data['meta_description'] = trim($metaDesc->getAttribute('content'));

        // canonical link
        $canonical = $xpath->query('//link[@rel="canonical"]')->item(0);
        if ($canonical && $canonical->hasAttribute('href')) $data['canonical'] = trim($canonical->getAttribute('href'));

        // first h1
        $h1 = $xpath->query('//h1')->item(0);
        if ($h1) $data['h1'] = trim(preg_replace('/\s+/', ' ', $h1->textContent));

        // first few paragraphs
        $ps = $xpath->query('//p');
        $count = 0;
        foreach ($ps as $p) {
            $txt = trim(preg_replace('/\s+/', ' ', $p->textContent));
            if ($txt) {
                $data['paragraphs'][] = $txt;
                $count++;
                if ($count >= 5) break;
            }
        }

        // JSON-LD scripts
        $scripts = $xpath->query('//script[@type="application/ld+json"]');
        if ($scripts && $scripts->length) {
            $jsons = [];
            foreach ($scripts as $s) {
                $c = trim($s->textContent);
                if ($c) $jsons[] = $c;
            }
            if ($jsons) $data['json_ld'] = implode("\n", $jsons);
        }

        libxml_clear_errors();
        return $data;
    }

    public function update(Request $request, SeoMeta $seo)
    {
        $data = $request->validate([
            'page_slug' => 'nullable|string|max:191|unique:seo_meta,page_slug,' . $seo->id,
            'title' => 'nullable|string|max:191',
            'meta_description' => 'required|string|max:160',
            'is_site_default' => 'sometimes|boolean',
        ]);

        if (! empty($data['is_site_default'])) {
            SeoMeta::where('is_site_default', true)->update(['is_site_default' => false]);
            $data['is_site_default'] = true;
            $data['page_slug'] = null;
        } else {
            $data['is_site_default'] = false;
        }

        // Ensure meta description is trimmed and within length limits
        $data['meta_description'] = trim(mb_substr($data['meta_description'], 0, 160));

        $seo->update($data);

        \App\Jobs\PingSearchEngines::dispatch(url('/sitemap.xml'))->delay(now()->addMinutes(5));

        return redirect()->route('admin.seo.index')->with('success', 'SEO updated');
    }

    public function destroy(SeoMeta $seo)
    {
        $seo->delete();

        \App\Jobs\PingSearchEngines::dispatch(url('/sitemap.xml'))->delay(now()->addMinutes(5));

        return redirect()->route('admin.seo.index')->with('success', 'SEO entry deleted');
    }
}
