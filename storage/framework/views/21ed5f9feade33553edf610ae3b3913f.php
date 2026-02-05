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

        <button class="btn btn-primary">Save</button>
    </form>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/seo/edit.blade.php ENDPATH**/ ?>