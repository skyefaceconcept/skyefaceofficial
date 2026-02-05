<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>SEO Entries</h1>

    <div class="mb-4">
        <h4>Static Pages</h4>
        <p>Quick links for common static pages. Click Edit to create or update SEO for a specific page.</p>
        <?php
            $staticPages = ['home' => '/', 'about' => '/about', 'services' => '/services', 'contact' => '/contact', 'shop' => '/shop'];
        ?>
        <div class="list-group mb-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staticPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('admin.seo.editPage', $slug)); ?>" class="list-group-item list-group-item-action"><?php echo e(ucfirst($slug)); ?> — <?php echo e($path); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Page</th>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($item->id); ?></td>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seoable_type === 'site'): ?>
                <td>Site default</td>
                <?php elseif($item->seoable_type === 'page' && $item->page_slug): ?>
                <td>Page: <?php echo e($item->page_slug); ?></td>
                <?php else: ?>
                <td><?php echo e(class_basename($item->seoable_type) . '#' . $item->seoable_id); ?></td>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <td><?php echo e(Str::limit($item->title, 60)); ?></td>
                <td><?php echo e(Str::limit($item->meta_description, 80)); ?></td>
                <td><a href="<?php echo e(route('admin.seo.edit', $item)); ?>" class="btn btn-sm btn-primary">Edit</a></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    <?php echo e($items->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/seo/index.blade.php ENDPATH**/ ?>