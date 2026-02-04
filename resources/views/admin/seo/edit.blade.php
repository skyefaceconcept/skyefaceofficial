@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1>Edit SEO (ID: {{ $seo->id }})</h1>

    <form method="POST" action="{{ route('admin.seo.update', $seo) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" value="{{ old('title', $seo->title) }}" />
        </div>

        <div class="mb-3">
            <label class="form-label">Meta description</label>
            <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $seo->meta_description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Canonical</label>
            <input name="canonical" class="form-control" value="{{ old('canonical', $seo->canonical) }}" />
        </div>

        <div class="form-check mb-3">
            <label class="form-check-label"><input type="checkbox" name="noindex" class="form-check-input" {{ $seo->noindex ? 'checked' : '' }}> No index</label>
        </div>

        <div class="form-check mb-3">
            <label class="form-check-label"><input type="checkbox" name="nofollow" class="form-check-input" {{ $seo->nofollow ? 'checked' : '' }}> No follow</label>
        </div>

        <div class="mb-3">
            <label class="form-label">JSON-LD (raw)</label>
            <textarea name="json_ld" class="form-control" rows="6">{{ old('json_ld', $seo->json_ld) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">AI context (optional)</label>
            <input type="text" id="ai-context" class="form-control" placeholder="E.g., 'Services include phone repair, battery replacement'" />
        </div>
        <div class="mb-3">
            <label class="form-label">Tone</label>
            <select id="ai-tone" class="form-control">
                <option value="natural">Natural</option>
                <option value="professional">Professional</option>
                <option value="friendly">Friendly</option>
                <option value="persuasive">Persuasive</option>
            </select>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" id="ai-use-content-only" class="form-check-input">
            <label class="form-check-label" for="ai-use-content-only">Use page content only (ignore manual context)</label>
        </div>

        <div class="mb-3">
            <button type="button" id="generate-ai-btn" class="btn btn-outline-secondary">Generate with AI</button>
            <button type="button" id="fetch-page-btn" class="btn btn-outline-info ms-2">Fetch page content</button>
            <button type="button" id="preview-prompt-btn" class="btn btn-outline-dark ms-2">Preview prompt</button>
            <span id="generate-ai-loading" style="display:none; margin-left:10px;">Generating…</span>
            <small class="form-text text-muted">Requires an API key in <code>AI_PROVIDER</code> / <code>AI_API_KEY</code> in your <code>.env</code>. Generated content is sent to the configured AI provider.</small>
        </div>

        <div id="fetch-result" style="display:none; margin-bottom:16px; background:#fffbea; padding:10px; border-radius:4px;">
            <h6>Fetched page properties</h6>
            <div id="fetched-fields"></div>
        </div>

        <div id="prompt-preview" style="display:none; margin-bottom:16px; background:#eef7ff; padding:10px; border-radius:4px;">
            <h6>Prompt Preview (system + user)</h6>
            <pre id="prompt-preview-json" style="background:#f8f9fa; padding:10px; border-radius:4px; white-space:pre-wrap;"></pre>
        </div>

        <div id="ai-preview" style="display:none; margin-bottom:16px;">
            <h5>AI generated preview</h5>
            <pre id="ai-preview-json" style="background:#f8f9fa; padding:10px; border-radius:4px; white-space:pre-wrap;"></pre>
<button type="button" id="ai-apply-btn" class="btn btn-success mt-2">Apply generated to form</button>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>

@push('scripts')
<script>
    (function(){
        const btn = document.getElementById('generate-ai-btn');
        const loading = document.getElementById('generate-ai-loading');
        const preview = document.getElementById('ai-preview');
        const previewJson = document.getElementById('ai-preview-json');
        const applyBtn = document.getElementById('ai-apply-btn');

        // Toggle: disable manual context when 'use content only' is checked
        const useOnlyCheckbox = document.getElementById('ai-use-content-only');
        if (useOnlyCheckbox) {
            useOnlyCheckbox.addEventListener('change', function(){
                const ctx = document.getElementById('ai-context');
                if (ctx) {
                    ctx.disabled = this.checked;
                }
            });
        }

        btn && btn.addEventListener('click', function(e){
            e.preventDefault();
            loading.style.display = 'inline';
            btn.disabled = true;
            preview.style.display = 'none';

            const context = document.getElementById('ai-context').value || '';
            const tone = document.getElementById('ai-tone').value || 'natural';
            const usePageOnly = document.getElementById('ai-use-content-only').checked || false;

            // Determine endpoint: page slug or seo id
            let url;
            @if($seo->page_slug)
                url = "{{ route('admin.seo.generateAIPage', $seo->page_slug) }}";
            @else
                url = "{{ route('admin.seo.generateAI', $seo) }}";
            @endif

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({context: context, tone: tone, use_page_only: usePageOnly})
            }).then(r => r.json()).then(data => {
                loading.style.display = 'none';
                btn.disabled = false;
                if (data?.success && data.data) {
                    previewJson.textContent = JSON.stringify(data.data, null, 2);
                    preview.style.display = 'block';

                    // store current generated in memory
                    window.__generatedSeo = data.data;
                } else {
                    previewJson.textContent = data?.message || 'No data returned or an error occurred';
                    preview.style.display = 'block';
                }
            }).catch(err => {
                loading.style.display = 'none';
                btn.disabled = false;
                previewJson.textContent = 'Error generating: ' + (err.message || err);
                preview.style.display = 'block';
            });
        });

        // Fetch page content
        const fetchBtn = document.getElementById('fetch-page-btn');
        const fetchResult = document.getElementById('fetch-result');
        const fetchedFields = document.getElementById('fetched-fields');
        fetchBtn && fetchBtn.addEventListener('click', function(e){
            e.preventDefault();
            fetchResult.style.display = 'none';
            fetchBtn.disabled = true;

            let url;
            @if($seo->page_slug)
                url = "{{ route('admin.seo.fetchPagePage', $seo->page_slug) }}";
            @else
                url = "{{ route('admin.seo.fetchPage', $seo) }}";
            @endif

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            }).then(r => r.json()).then(data => {
                fetchBtn.disabled = false;
                if (data?.success && data.data) {
                    fetchResult.style.display = 'block';
                    const d = data.data;
                    fetchedFields.innerHTML = '';
                    if (d.title) {
                        fetchedFields.innerHTML += '<div><strong>Title:</strong> ' + d.title + '</div>';
                        document.querySelector('input[name="title"]').value = d.title;
                    }
                    if (d.meta_description) {
                        fetchedFields.innerHTML += '<div><strong>Meta Description:</strong> ' + d.meta_description + '</div>';
                        document.querySelector('textarea[name="meta_description"]').value = d.meta_description;
                    }
                    if (d.canonical) {
                        fetchedFields.innerHTML += '<div><strong>Canonical:</strong> ' + d.canonical + '</div>';
                        document.querySelector('input[name="canonical"]').value = d.canonical;
                    }
                    if (d.h1) fetchedFields.innerHTML += '<div><strong>H1:</strong> ' + d.h1 + '</div>';
                    if (d.paragraphs) fetchedFields.innerHTML += '<div><strong>Excerpt:</strong> ' + d.paragraphs.join('\n\n') + '</div>';
                    if (d.json_ld) {
                        fetchedFields.innerHTML += '<div><strong>JSON-LD:</strong> <pre style="white-space:pre-wrap;">' + d.json_ld + '</pre></div>';
                        document.querySelector('textarea[name="json_ld"]').value = d.json_ld;
                    }

                    // Populate AI context with a short excerpt for convenience
                    const excerpt = d.paragraphs ? d.paragraphs.slice(0,2).join('\n\n') : (d.title || '');
                    if (excerpt) document.getElementById('ai-context').value = excerpt;
                } else {
                    fetchedFields.innerHTML = '<div class="text-danger">' + (data?.message || 'Failed to fetch') + '</div>';
                    fetchResult.style.display = 'block';
                }
            }).catch(err => {
                fetchBtn.disabled = false;
                fetchedFields.innerHTML = '<div class="text-danger">Fetch error: ' + (err.message || err) + '</div>';
                fetchResult.style.display = 'block';
            });
        });

        // Prompt preview
        const previewBtn = document.getElementById('preview-prompt-btn');
        const promptPreview = document.getElementById('prompt-preview');
        const promptPreviewJson = document.getElementById('prompt-preview-json');
        previewBtn && previewBtn.addEventListener('click', function(e){
            e.preventDefault();
            previewBtn.disabled = true;
            promptPreview.style.display = 'none';

            const context = document.getElementById('ai-context').value || '';
            const tone = document.getElementById('ai-tone').value || 'natural';

            let url;
            @if($seo->page_slug)
                url = "{{ route('admin.seo.previewPromptPage', $seo->page_slug) }}";
            @else
                url = "{{ route('admin.seo.previewPrompt', $seo) }}";
            @endif

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({context: context, tone: tone})
            }).then(r => r.json()).then(data => {
                previewBtn.disabled = false;
                if (data?.success && data.messages) {
                    promptPreviewJson.textContent = JSON.stringify(data.messages, null, 2);
                    promptPreview.style.display = 'block';
                } else {
                    promptPreviewJson.textContent = data?.message || 'No prompt available';
                    promptPreview.style.display = 'block';
                }
            }).catch(err => {
                previewBtn.disabled = false;
                promptPreviewJson.textContent = 'Error fetching prompt: ' + (err.message || err);
                promptPreview.style.display = 'block';
            });
        });

        applyBtn && applyBtn.addEventListener('click', function(e){
            e.preventDefault();
            const g = window.__generatedSeo || {};
            if (!g) return;
            if (g.title) document.querySelector('input[name="title"]').value = g.title;
            if (g.meta_description) document.querySelector('textarea[name="meta_description"]').value = g.meta_description;
            if (g.meta_keywords) document.querySelector('input[name="meta_keywords"]').value = g.meta_keywords;
            if (g.og_title) document.querySelector('input[name="og_title"]').value = g.og_title;
            if (g.og_description) document.querySelector('textarea[name="og_description"]').value = g.og_description;
            if (g.twitter_title) document.querySelector('input[name="twitter_title"]').value = g.twitter_title;
            if (g.twitter_description) document.querySelector('textarea[name="twitter_description"]').value = g.twitter_description;
            if (g.json_ld) document.querySelector('textarea[name="json_ld"]').value = (typeof g.json_ld === 'string') ? g.json_ld : JSON.stringify(g.json_ld);
        });
    })();
</script>
@endpush
@endsection
