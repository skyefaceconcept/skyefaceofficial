<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Edit SEO (ID: <?php echo e($seo->id); ?>)</h1>
        <a href="<?php echo e(route('admin.seo.index')); ?>" class="btn btn-light">Back</a>
    </div>

    <form method="POST" action="<?php echo e(route('admin.seo.update', $seo)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row">
            <div class="col-md-7">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Choose page <small class="text-muted">(or select Custom to enter your own slug)</small></label>
                            <select id="page_select" class="form-control mb-2">
                                <option value="">-- (none) select page --</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($slug); ?>" <?php if(old('page_slug', $seo->page_slug) == $slug): ?> selected <?php endif; ?>><?php echo e($label); ?> (<?php echo e($slug == 'home' ? '/' : '/'.$slug); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <option value="__custom">Custom slug...</option>
                            </select>
                            <input name="page_slug" id="page_slug" class="form-control" value="<?php echo e(old('page_slug', $seo->page_slug)); ?>" placeholder="e.g. about" <?php echo e($seo->is_site_default ? 'disabled' : ''); ?> />
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_site_default" value="1" class="form-check-input" id="is_site_default" <?php echo e($seo->is_site_default ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_site_default">Set as site default (will clear other defaults)</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input name="title" id="seo_title" class="form-control" value="<?php echo e(old('title', $seo->title)); ?>" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="4"><?php echo e(old('meta_description', $seo->meta_description)); ?></textarea>
                            <small class="form-text text-muted"><span id="desc_count"><?php echo e(strlen(old('meta_description', $seo->meta_description ?? ''))); ?></span>/160 characters</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary">Save</button>
                            <a href="<?php echo e(route('admin.seo.index')); ?>" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Live SEO Preview</h6>
                        <div class="border p-3 rounded">
                            <h5 id="preview_title" class="mb-1"><?php echo e(old('title', $seo->title) ?: 'Title will appear here'); ?></h5>
                            <div class="text-primary mb-2"><?php echo e(url('/')); ?> / <small id="preview_slug"><?php echo e(old('page_slug', $seo->page_slug) ?: ($seo->is_site_default ? 'Site default' : 'slug')); ?></small></div>
                            <p id="preview_description" class="text-muted mb-0"><?php echo e(old('meta_description', $seo->meta_description) ?: 'Meta description will show here (160 chars recommended).'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body small text-muted">
                        <p class="mb-1"><strong>Tips</strong></p>
                        <ul class="mb-0 pl-3">
                            <li>Keep meta descriptions under 160 characters.</li>
                            <li>Titles should be concise (50-60 chars).</li>
                            <li>Check site default if you want one global description.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const titleEl = document.getElementById('seo_title');
        const descEl = document.getElementById('meta_description');
        const slugEl = document.getElementById('page_slug');
        const previewTitle = document.getElementById('preview_title');
        const previewDesc = document.getElementById('preview_description');
        const previewSlug = document.getElementById('preview_slug');
        const descCount = document.getElementById('desc_count');
        const siteDefault = document.getElementById('is_site_default');

        function updatePreview(){
            previewTitle.textContent = titleEl.value || 'Title will appear here';
            previewDesc.textContent = descEl.value || 'Meta description will show here (160 chars recommended).';
            previewSlug.textContent = slugEl.value || (siteDefault.checked ? 'Site default' : 'slug');
            descCount.textContent = (descEl.value || '').length;
        }

        function toggleSlug(){
            if(siteDefault.checked){
                slugEl.setAttribute('disabled', 'disabled');
            } else {
                slugEl.removeAttribute('disabled');
            }
        }

        titleEl && titleEl.addEventListener('input', updatePreview);
        descEl && descEl.addEventListener('input', updatePreview);
        slugEl && slugEl.addEventListener('input', updatePreview);
        siteDefault && siteDefault.addEventListener('change', function(){ toggleSlug(); updatePreview(); });

        const pageSelect = document.getElementById('page_select');
        function onPageSelect(){
            if(!pageSelect) return;
            const val = pageSelect.value;
            if(val === '__custom'){
                slugEl.value = '';
                slugEl.removeAttribute('disabled');
                slugEl.focus();
            } else if(val === ''){
                slugEl.value = '';
                slugEl.removeAttribute('disabled');
            } else {
                slugEl.value = val;
                slugEl.setAttribute('disabled','disabled');
            }
            updatePreview();
        }

        pageSelect && pageSelect.addEventListener('change', onPageSelect);

        // initialize selector based on current slug
        if(slugEl.value){
            const foundOption = Array.from(pageSelect.options).find(o => o.value === slugEl.value);
            if(foundOption) pageSelect.value = foundOption.value;
            else pageSelect.value = '__custom';
            onPageSelect();
        }

        updatePreview();
        toggleSlug();
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/seo/edit.blade.php ENDPATH**/ ?>