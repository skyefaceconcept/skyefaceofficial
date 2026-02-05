<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Create SEO Entry</h1>

    <form method="POST" action="<?php echo e(route('admin.seo.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
            <label class="form-label">Page slug (leave blank for site default)</label>
            <input name="page_slug" class="form-control" value="<?php echo e(old('page_slug')); ?>" />
        </div>

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" value="<?php echo e(old('title')); ?>" />
        </div>

        <div class="mb-3">
            <label class="form-label">Meta description</label>
            <textarea name="meta_description" class="form-control" rows="3"><?php echo e(old('meta_description')); ?></textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_site_default" value="1" class="form-check-input" id="is_site_default">
            <label class="form-check-label" for="is_site_default">Set as site default (overrides other defaults)</label>
        </div>

        <button class="btn btn-primary">Create</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/seo/create.blade.php ENDPATH**/ ?>