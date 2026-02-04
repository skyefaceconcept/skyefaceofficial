<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Edit SEO (ID: <?php echo e($seo->id); ?>)</h1>

    <form method="POST" action="<?php echo e(route('admin.seo.update', $seo)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" value="<?php echo e(old('title', $seo->title)); ?>" />
        </div>

        <div class="mb-3">
            <label class="form-label">Meta description</label>
            <textarea name="meta_description" class="form-control" rows="3"><?php echo e(old('meta_description', $seo->meta_description)); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Canonical</label>
            <input name="canonical" class="form-control" value="<?php echo e(old('canonical', $seo->canonical)); ?>" />
        </div>

        <div class="form-check mb-3">
            <label class="form-check-label"><input type="checkbox" name="noindex" class="form-check-input" <?php echo e($seo->noindex ? 'checked' : ''); ?>> No index</label>
        </div>

        <div class="form-check mb-3">
            <label class="form-check-label"><input type="checkbox" name="nofollow" class="form-check-input" <?php echo e($seo->nofollow ? 'checked' : ''); ?>> No follow</label>
        </div>

        <div class="mb-3">
            <label class="form-label">JSON-LD (raw)</label>
            <textarea name="json_ld" class="form-control" rows="6"><?php echo e(old('json_ld', $seo->json_ld)); ?></textarea>
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
            <span id="generate-ai-loading" style="display:none; margin-left:10px;">Generating…</span>
            <small class="form-text text-muted">Requires an API key in <code>AI_PROVIDER</code> / <code>AI_API_KEY</code> in your <code>.env</code>. Generated content is sent to the configured AI provider.</small>
        </div>

        <div id="ai-preview" style="display:none; margin-bottom:16px;">
            <h5>AI generated preview</h5>
            <pre id="ai-preview-json" style="background:#f8f9fa; padding:10px; border-radius:4px; white-space:pre-wrap;"></pre>
<button type="button" id="ai-apply-btn" class="btn btn-success mt-2">Apply generated to form</button>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
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
            <?php if($seo->page_slug): ?>
                url = "<?php echo e(route('admin.seo.generateAIPage', $seo->page_slug)); ?>";
            <?php else: ?>
                url = "<?php echo e(route('admin.seo.generateAI', $seo)); ?>";
            <?php endif; ?>

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/seo/edit.blade.php ENDPATH**/ ?>