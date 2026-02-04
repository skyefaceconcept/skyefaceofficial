<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use App\Services\AIWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    /**
     * Generate SEO content for an existing SeoMeta row using AI and return JSON
     */
    public function generateAI(Request $request, SeoMeta $seo, AIWriter $ai)
    {
        $context = (string) $request->input('context', '');
        $tone = (string) $request->input('tone', 'natural');

        // Determine slug or identifier
        $pageSlug = $seo->page_slug ?? ($seo->seoable_type === 'site' ? 'site' : ($seo->seoable_type . '#' . $seo->seoable_id));

        \Log::debug('Admin/SeoController: generateAI request', ['page' => $pageSlug, 'context_length' => strlen($context), 'tone' => $tone]);

        if (env('AI_PROVIDER') === 'openai' && ! env('AI_API_KEY')) {
            return response()->json(['success' => false, 'message' => 'AI provider not configured. Set AI_PROVIDER and AI_API_KEY in .env.'], 422);
        }

        $usePageOnly = filter_var($request->input('use_page_only'), FILTER_VALIDATE_BOOLEAN);
        \Log::debug('Admin/SeoController: generateAI use_page_only', ['page' => $pageSlug, 'use_page_only' => $usePageOnly]);

        // Build parts depending on the toggle
        $parts = [];
        if (! $usePageOnly && $context) {
            $parts[] = "User context: " . $context;
        }

        if ($seo->title) {
            $parts[] = "Existing title: " . $seo->title;
        }
        if ($seo->meta_description) {
            $parts[] = "Existing meta description: " . $seo->meta_description;
        }
        if ($seo->json_ld) {
            $parts[] = "Existing json_ld: " . $seo->json_ld;
        }
        if (config('app.name')) {
            $parts[] = "Site: " . config('app.name');
        }

        // If the SeoMeta is attached to a model with content, include that too (title, body, excerpt)
        try {
            $seoable = $seo->seoable; // morphTo may return null or a model
            if ($seoable) {
                foreach (['title','name','content','body','excerpt','summary','description'] as $field) {
                    if (isset($seoable->{$field}) && $seoable->{$field}) {
                        $parts[] = "Page {$field}: " . (is_string($seoable->{$field}) ? substr($seoable->{$field}, 0, 1500) : json_encode($seoable->{$field}));
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::debug('Admin/SeoController: could not read seoable model', ['error' => $e->getMessage()]);
        }

        $enrichedContext = trim(implode("\n", array_filter($parts)));

        // If user opted to use page content only and we don't have rich model fields, try fetching the live page HTML
        if ($usePageOnly && empty($enrichedContext)) {
            try {
                $url = rtrim(config('app.url',''), '/') . '/' . ltrim($pageSlug, '/');
                \Log::debug('Admin/SeoController: fetching page HTML fallback', ['page' => $pageSlug, 'url' => $url]);
                $resp = Http::timeout(10)->get($url);
                if ($resp->ok()) {
                    // strip tags and limit size
                    $text = strip_tags($resp->body());
                    $enrichedContext = substr(preg_replace('/\s+/', ' ', $text), 0, 3000);
                    \Log::debug('Admin/SeoController: fetched page HTML length', ['len' => strlen($enrichedContext)]);
                }
            } catch (\Throwable $e) {
                \Log::debug('Admin/SeoController: failed to fetch page HTML', ['error' => $e->getMessage()]);
            }
        }

        // If user asked to use page content only but we still have nothing, return helpful message
        if ($usePageOnly && empty($enrichedContext)) {
            \Log::warning('Admin/SeoController: use_page_only set but no page content found', ['page' => $pageSlug]);
            return response()->json(['success' => false, 'message' => 'No page content found to use. Uncheck "Use page content only" or provide a context.'], 422);
        }

        \Log::debug('Admin/SeoController: generateAI enriched context', ['page' => $pageSlug, 'enriched_length' => strlen($enrichedContext), 'sample' => substr($enrichedContext,0,400), 'parts' => count($parts)]);

        try {
            $result = $ai->generateForPage((string) $pageSlug, $enrichedContext, $tone);
        } catch (\TypeError $te) {
            \Log::error('Admin/SeoController: AI generation type error', ['error' => $te->getMessage(), 'page' => $pageSlug, 'context_type' => gettype($enrichedContext)]);
            return response()->json(['success' => false, 'message' => 'Invalid input provided to AI generator.'], 500);
        } catch (\RuntimeException $re) {
            // Surface OpenAI billing/quota errors to the admin immediately
            \Log::warning('Admin/SeoController: AI runtime error', ['message' => $re->getMessage(), 'page' => $pageSlug]);
            return response()->json(['success' => false, 'message' => $re->getMessage()], 402);
        } catch (\Throwable $e) {
            \Log::error('Admin/SeoController: AI generation failed', ['error' => $e->getMessage(), 'page' => $pageSlug]);
            return response()->json(['success' => false, 'message' => 'AI generation failed. See server logs.'], 500);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    /**
     * Fetch the live page and extract title/meta/h1/paragraphs/json-ld for better AI context
     */
    public function fetchPageContent(Request $request, SeoMeta $seo)
    {
        $pageSlug = $seo->page_slug ?? ($seo->seoable_type === 'site' ? 'site' : ($seo->seoable_type . '#' . $seo->seoable_id));
        $url = rtrim(config('app.url',''), '/') . '/' . ltrim((string) $pageSlug, '/');

        \Log::debug('Admin/SeoController: fetchPageContent', ['page' => $pageSlug, 'url' => $url]);

        try {
            $resp = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
            if (! $resp->ok()) {
                return response()->json(['success' => false, 'message' => 'Could not fetch page (HTTP '.$resp->status().')'], 422);
            }

            $html = $resp->body();
            $data = $this->parseHtmlForSeo($html);

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            \Log::warning('Admin/SeoController: fetchPageContent failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to fetch page: '.$e->getMessage()], 500);
        }
    }

    public function fetchPagePageContent(Request $request, string $slug)
    {
        $url = rtrim(config('app.url',''), '/') . '/' . ltrim($slug, '/');
        \Log::debug('Admin/SeoController: fetchPagePageContent', ['slug' => $slug, 'url' => $url]);

        try {
            $resp = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
            if (! $resp->ok()) {
                return response()->json(['success' => false, 'message' => 'Could not fetch page (HTTP '.$resp->status().')'], 422);
            }

            $html = $resp->body();
            $data = $this->parseHtmlForSeo($html);

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            \Log::warning('Admin/SeoController: fetchPagePageContent failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to fetch page: '.$e->getMessage()], 500);
        }
    }

    /**
     * Return a preview of the system + user prompt that will be sent to the AI
     */
    public function previewPrompt(Request $request, SeoMeta $seo, AIWriter $ai)
    {
        $context = (string) $request->input('context', '');
        $tone = (string) $request->input('tone', 'natural');
        $pageSlug = $seo->page_slug ?? ($seo->seoable_type === 'site' ? 'site' : ($seo->seoable_type . '#' . $seo->seoable_id));

        $messages = $ai->buildMessages((string)$pageSlug, $context, $tone, false);
        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function previewPromptPage(Request $request, string $slug, AIWriter $ai)
    {
        $context = (string) $request->input('context', '');
        $tone = (string) $request->input('tone', 'natural');

        $messages = $ai->buildMessages($slug, $context, $tone, false);
        return response()->json(['success' => true, 'messages' => $messages]);
    }

    /**
     * Generate SEO content for a page slug
     */
    public function generateAIPage(Request $request, string $slug, AIWriter $ai)
    {
        $context = (string) $request->input('context', '');
        $tone = (string) $request->input('tone', 'natural');

        \Log::debug('Admin/SeoController: generateAIPage request', ['slug' => $slug, 'context_length' => strlen($context), 'tone' => $tone]);

        if (env('AI_PROVIDER') === 'openai' && ! env('AI_API_KEY')) {
            return response()->json(['success' => false, 'message' => 'AI provider not configured. Set AI_PROVIDER and AI_API_KEY in .env.'], 422);
        }

        // If a SeoMeta exists for this page, include its fields for richer context
        $pageSeo = \App\Models\SeoMeta::where('seoable_type','page')->where('page_slug',$slug)->first();
        $usePageOnly = filter_var($request->input('use_page_only'), FILTER_VALIDATE_BOOLEAN);
        \Log::debug('Admin/SeoController: generateAIPage use_page_only', ['slug' => $slug, 'use_page_only' => $usePageOnly]);

        $parts = [];
        if (! $usePageOnly && $context) {
            $parts[] = "User context: " . $context;
        }
        if ($pageSeo) {
            if ($pageSeo->title) $parts[] = "Existing title: " . $pageSeo->title;
            if ($pageSeo->meta_description) $parts[] = "Existing meta description: " . $pageSeo->meta_description;
            if ($pageSeo->json_ld) $parts[] = "Existing json_ld: " . $pageSeo->json_ld;
        }
        if (config('app.name')) {
            $parts[] = "Site: " . config('app.name');
        }

        // Try to discover a page model by slug (common model names)
        $foundModel = null;
        $candidates = ['App\\Models\\Page', 'App\\Models\\Post', 'App\\Models\\Article', 'App\\Models\\Service', 'App\\Models\\Product'];
        foreach ($candidates as $class) {
            if (class_exists($class)) {
                try {
                    $m = $class::where('slug', $slug)->orWhere('page_slug', $slug)->first();
                    if ($m) {
                        $foundModel = $m; break;
                    }
                } catch (\Throwable $e) {
                    // ignore query errors
                }
            }
        }

        if ($foundModel) {
            foreach (['title','name','content','body','excerpt','summary','description'] as $field) {
                if (isset($foundModel->{$field}) && $foundModel->{$field}) {
                    $parts[] = "Page {$field}: " . (is_string($foundModel->{$field}) ? substr($foundModel->{$field}, 0, 1500) : json_encode($foundModel->{$field}));
                }
            }
            \Log::debug('Admin/SeoController: generateAIPage found model', ['class' => get_class($foundModel), 'id' => $foundModel->getKey()]);
        } else {
            \Log::debug('Admin/SeoController: generateAIPage no model found for slug', ['slug' => $slug]);
        }

        $enrichedContext = trim(implode("\n", array_filter($parts)));

        // If user opted to use page content only and we don't have rich model fields, try fetching the live page HTML
        if ($usePageOnly && empty($enrichedContext)) {
            try {
                $url = rtrim(config('app.url',''), '/') . '/' . ltrim($slug, '/');
                \Log::debug('Admin/SeoController: fetching page HTML fallback', ['slug' => $slug, 'url' => $url]);
                $resp = Http::timeout(10)->get($url);
                if ($resp->ok()) {
                    $text = strip_tags($resp->body());
                    $enrichedContext = substr(preg_replace('/\s+/', ' ', $text), 0, 3000);
                    \Log::debug('Admin/SeoController: fetched page HTML length', ['len' => strlen($enrichedContext)]);
                }
            } catch (\Throwable $e) {
                \Log::debug('Admin/SeoController: failed to fetch page HTML', ['error' => $e->getMessage()]);
            }
        }

        \Log::debug('Admin/SeoController: generateAIPage enriched context', ['slug' => $slug, 'enriched_length' => strlen($enrichedContext), 'sample' => substr($enrichedContext,0,400), 'parts' => count($parts)]);

        try {
            $result = $ai->generateForPage($slug, $enrichedContext, $tone);
        } catch (\TypeError $te) {
            \Log::error('Admin/SeoController: AI generation type error', ['error' => $te->getMessage(), 'slug' => $slug, 'context_type' => gettype($enrichedContext)]);
            return response()->json(['success' => false, 'message' => 'Invalid input provided to AI generator.'], 500);
        } catch (\Throwable $e) {
            \Log::error('Admin/SeoController: AI generation failed', ['error' => $e->getMessage(), 'slug' => $slug]);
            return response()->json(['success' => false, 'message' => 'AI generation failed. See server logs.'], 500);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

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
            'meta_description' => 'nullable|string|max:2000',
            'canonical' => 'nullable|url|max:255',
            'noindex' => 'nullable|boolean',
            'nofollow' => 'nullable|boolean',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:2000',
            'og_image' => 'nullable|string|max:255',
            'json_ld' => 'nullable|string',
        ]);

        $data['noindex'] = $request->has('noindex');
        $data['nofollow'] = $request->has('nofollow');

        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO updated');
    }
}
