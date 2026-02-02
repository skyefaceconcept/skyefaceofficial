<?php $__env->startSection('title', 'Edit Role'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="page-header">
      <h1>Edit Role</h1>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </ul>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'edit_role')): ?>
        <form action="<?php echo e(route('admin.roles.update', $role->slug)); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name', $role->name)); ?>" required>
          </div>

          <div class="form-group mt-2">
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="<?php echo e(old('slug', $role->slug)); ?>">
            <small class="form-text text-muted">Leave empty to auto-generate from the name; must be unique.</small>
          </div>

          <div class="form-group mt-2">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"><?php echo e(old('description', $role->description)); ?></textarea>
          </div>

          <?php
            $assigned = $role->permissions->pluck('id')->toArray();
            $oldPermissions = old('permissions', $assigned);
          ?>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($permissions) && $permissions->count()): ?>
          <div class="form-group mt-3">
            <label>Permissions</label>
            <div class="row">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="col-md-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo e($permission->id); ?>" id="perm_<?php echo e($permission->id); ?>" <?php echo e((is_array($oldPermissions) && in_array($permission->id, $oldPermissions)) ? 'checked' : ''); ?>>
                  <label class="form-check-label" for="perm_<?php echo e($permission->id); ?>"><?php echo e($permission->name); ?> <small class="text-muted">(<?php echo e($permission->slug); ?>)</small></label>
                </div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          <div class="mt-4 d-flex justify-content-between">
            <a href="<?php echo e(route('admin.roles.index')); ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Role</button>
          </div>
        </form>
        <?php else: ?>
        <div class="alert alert-danger">You do not have permission to edit roles.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
    </div>
  </div>
</div>
      <?php $__env->startPush('extra-js'); ?>
      <script>
      function slugify(text) {
        return text.toString().toLowerCase()
          .replace(/\s+/g, '-')
          .replace(/[^a-z0-9\-]/g, '')
          .replace(/--+/g, '-')
          .replace(/^-+/, '')
          .replace(/-+$/, '');
      }

      document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        if (!nameInput || !slugInput) return;

        nameInput.addEventListener('input', function () {
          if (!slugInput.dataset.touched) {
            slugInput.value = slugify(nameInput.value);
          }
        });

        slugInput.addEventListener('input', function () {
          slugInput.dataset.touched = true;
        });
      });
      </script>
      <?php $__env->stopPush(); ?>

      <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/roles/edit.blade.php ENDPATH**/ ?>