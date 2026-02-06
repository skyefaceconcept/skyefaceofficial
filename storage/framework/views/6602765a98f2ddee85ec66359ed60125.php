

<?php $__env->startSection('title', 'Device Analytics'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-devices mr-2"></i>Device Analytics</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('admin.analytics.page-impressions.index')); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Device Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold">Breakdown</h6>
                </div>
                <div class="card-body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($deviceStats) && $deviceStats->count() > 0): ?>
                        <ul class="list-group">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $deviceStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo e(ucfirst($stat->device_type)); ?>

                                    <span class="badge badge-primary"><?php echo e(number_format($stat->impressions ?? $stat->total ?? 0)); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-muted">No device data available.</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    <?php
        $labels = isset($deviceStats) ? $deviceStats->pluck('device_type')->map(fn($d) => ucfirst($d))->toArray() : [];
        $data = isset($deviceStats) ? $deviceStats->pluck('impressions')->toArray() : (isset($deviceStats) ? $deviceStats->pluck('total')->toArray() : []);
    ?>
    const ctx = document.getElementById('deviceChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{ data: <?php echo json_encode($data); ?>, backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e'] }]
            },
            options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/analytics/page-impressions/devices.blade.php ENDPATH**/ ?>