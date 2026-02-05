

<?php $__env->startSection('title', 'Migrations'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Migrations</h1>
      </div>
    </div>
  </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
<div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
<div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="card">
  <div class="card-body">
    <p class="small text-muted">Manage migration files: see which have run, install pending ones, refresh changed migrations, or rollback recent batches. Be careful — running migrations via the UI affects your database.</p>

    <div class="mb-3">
      <form action="<?php echo e(route('admin.settings.migrations.run')); ?>" method="POST" class="d-inline">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="run-all" />
        <button class="btn btn-success" onclick="return confirm('Run all pending migrations? This will modify your database.')">Run All Pending</button>
      </form>

      <form action="<?php echo e(route('admin.settings.migrations.rollback')); ?>" method="POST" class="d-inline">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="steps" value="1" />
        <button class="btn btn-warning" onclick="return confirm('Rollback the last migration batch?')">Rollback Last Batch</button>
      </form>
    </div>

    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>Filename</th>
          <th>Status</th>
          <th>Batch</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $migrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td><?php echo e($m['filename']); ?></td>
          <td>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m['status'] === 'ran'): ?>
              <span class="badge badge-success">Ran</span>
            <?php else: ?>
              <span class="badge badge-secondary">Pending</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </td>
          <td><?php echo e($m['batch'] ?? '—'); ?></td>
          <td class="text-right">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m['status'] === 'pending'): ?>
              <form action="<?php echo e(route('admin.settings.migrations.run')); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="run-file" />
                <input type="hidden" name="file" value="<?php echo e($m['filename']); ?>" />
                <button class="btn btn-sm btn-success">Run</button>
              </form>
            <?php else: ?>
              <form action="<?php echo e(route('admin.settings.migrations.refresh')); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="file" value="<?php echo e($m['filename']); ?>" />
                <button class="btn btn-sm btn-info" onclick="return confirm('Refresh (rollback & re-run) this migration file?')">Refresh</button>
              </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </tbody>
    </table>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('migration_output')): ?>
      <hr>
      <h6>Command Output</h6>
      <pre style="white-space: pre-wrap; background: #0b1220; color: #cfe8ff; padding: 12px; border-radius: 6px;"><?php echo e(session('migration_output')); ?></pre>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/settings/migrations.blade.php ENDPATH**/ ?>