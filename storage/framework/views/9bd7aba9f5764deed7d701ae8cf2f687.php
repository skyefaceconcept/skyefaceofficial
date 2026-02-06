

<?php $__env->startSection('title', 'Top Visitors'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-friends mr-2"></i>Top Visitors</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('admin.analytics.page-impressions.index')); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white">
            <h6 class="m-0 font-weight-bold">Visitors</h6>
        </div>
        <div class="card-body">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($topVisitors) && $topVisitors->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th class="text-right">Impressions</th>
                                <th class="text-right">Last Seen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $topVisitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><code><?php echo e($v->ip_address); ?></code></td>
                                    <td><?php echo e(ucfirst($v->device_type)); ?></td>
                                    <td class="text-right"><?php echo e(number_format($v->impressions ?? 0)); ?></td>
                                    <td class="text-right"><?php echo e(optional($v->last_seen)->format('Y-m-d H:i') ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-muted">No visitor data available.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/analytics/page-impressions/visitors.blade.php ENDPATH**/ ?>