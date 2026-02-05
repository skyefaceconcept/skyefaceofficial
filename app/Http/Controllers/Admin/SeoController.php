<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        // Avoid eager loading the polymorphic `seoable` to prevent errors when a
        // sentinel value (e.g. 'site') is stored in `seoable_type` which is not a
        // real model class. We'll show the type/id in the UI instead of resolving
        // the relation for the site default.
        $items = SeoMeta::paginate(25);
        return view('admin.seo.index', compact('items'));
    }

    public function edit(SeoMeta $seo)
    {
        return view('admin.seo.edit', compact('seo'));
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
            'title' => 'nullable|string|max:255',
            'meta_description' => 'required|string|max:160',
        ]);

        // Ensure meta description is trimmed and within length limits
        $data['meta_description'] = trim(mb_substr($data['meta_description'], 0, 160));

        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO updated');
    }
}
