

<?php $__env->startSection('title', 'Page Impressions Statistics'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-box mr-2"></i>Page Impressions Statistics
            </h1>
            <p class="text-muted">Overview statistics for the selected period (<?php echo e($days ?? '30'); ?> days)</p>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('admin.analytics.page-impressions.index')); ?>" class="btn btn-sm btn-outline-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-uppercase text-muted small">Total Impressions</div>
                    <h3 class="mt-2"><?php echo e(number_format($totalImpressions ?? 0)); ?></h3>
                    <div class="text-muted small">Period: <?php echo e(isset($startDate) && isset($endDate) ? $startDate->format('Y-m-d') . ' → ' . $endDate->format('Y-m-d') : '—'); ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-uppercase text-muted small">Unique Visitors</div>
                    <h3 class="mt-2"><?php echo e(number_format($uniqueVisitors ?? 0)); ?></h3>
                    <div class="text-muted small">Unique IPs seen</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-uppercase text-muted small">Average Time (s)</div>
                    <h3 class="mt-2"><?php echo e($avgTime ?? 0); ?></h3>
                    <div class="text-muted small">Average session time in seconds</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 font-weight-bold">Notes</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">This view provides summary-level statistics. For detailed breakdowns, use the Dashboard, Device Analytics, Browser Analytics and Visitors sections.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/analytics/page-impressions/statistics.blade.php ENDPATH**/ ?>