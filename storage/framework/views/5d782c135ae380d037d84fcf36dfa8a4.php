

<?php $__env->startSection('title', 'Browser Analytics'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-globe mr-2"></i>Browser Analytics</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('admin.analytics.page-impressions.index')); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h6 class="m-0 font-weight-bold">Top Browsers</h6>
        </div>
        <div class="card-body">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($browserStats) && $browserStats->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Browser</th>
                                <th class="text-right">Impressions</th>
                                <th class="text-right">Unique Visitors</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $browserStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($b->browser); ?></td>
                                    <td class="text-right"><?php echo e(number_format($b->impressions ?? $b->total ?? 0)); ?></td>
                                    <td class="text-right"><?php echo e(number_format($b->unique_visitors ?? 0)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-muted">No browser data available.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/analytics/page-impressions/browsers.blade.php ENDPATH**/ ?>