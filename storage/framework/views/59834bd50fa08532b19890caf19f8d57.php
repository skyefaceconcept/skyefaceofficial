<?php $__env->startSection('title', 'Create Permission'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Create Permission</h1>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Permission Details</h5>
      </div>
      <div class="card-body">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'create_permission')): ?>
        <form action="<?php echo e(route('admin.permissions.store')); ?>" method="POST">
          <?php echo csrf_field(); ?>

          <div class="form-group">
            <label for="name">Permission Name</label>
            <input
              type="text"
              class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="name"
              name="name"
              value="<?php echo e(old('name')); ?>"
              placeholder="e.g., Create User"
              required>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          <div class="form-group">
            <label for="slug">Permission Slug</label>
            <input
              type="text"
              class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="slug"
              name="slug"
              value="<?php echo e(old('slug')); ?>"
              placeholder="e.g., create-user"
              required>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <small class="form-text text-muted">Use lowercase letters, numbers, and hyphens only.</small>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea
              class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="description"
              name="description"
              rows="4"
              placeholder="Brief description of what this permission does..."><?php echo e(old('description')); ?></textarea>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($routes) && $routes->count()): ?>
          <div class="form-group">
            <label for="route_select">Link To Route <span class="text-danger">*</span></label>
            <select id="route_select" class="form-control <?php $__errorArgs = ['route'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
              <option value="">-- Select a named route (required) --</option>
              <?php
                // Group routes by prefix
                $groupedRoutes = [];
                foreach($routes as $r) {
                  $parts = explode('.', $r['name']);
                  $group = count($parts) > 1 ? $parts[0] : 'Other';
                  if(!isset($groupedRoutes[$group])) {
                    $groupedRoutes[$group] = [];
                  }
                  $groupedRoutes[$group][] = $r;
                }
                ksort($groupedRoutes);
              ?>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groupedRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupRoutes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <optgroup label="<?php echo e(ucfirst(str_replace('-', ' ', $group))); ?>">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groupRoutes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($r['name']); ?>" data-uri="<?php echo e($r['uri']); ?>"><?php echo e($r['name']); ?> &mdash; <?php echo e($r['uri']); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </optgroup>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <input type="hidden" id="route" name="route" value="">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['route'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <small class="form-text text-muted">Selecting a route will auto-fill the fields below, which you can then customize.</small>
          </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          <!-- Preview removed per request -->

          <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Create Permission</button>
            <a href="<?php echo e(route('admin.permissions.index')); ?>" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
        <?php else: ?>
        <div class="alert alert-danger">You do not have permission to create permissions.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Help</h5>
      </div>
      <div class="card-body">
        <p><strong>Permission Name:</strong> A human-readable name for the permission.</p>
        <p><strong>Slug:</strong> A unique identifier in lowercase. Used in code to check permissions.</p>
        <p><strong>Description:</strong> Optional details explaining what this permission grants.</p>
        <hr>
        <p class="text-muted small"><strong>Example:</strong></p>
        <p class="text-muted small">
          Name: "Create User"<br>
          Slug: "create-user"
        </p>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra-js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  console.log('DOM loaded - initializing permission form');

  var sel = document.getElementById('route_select');
  var nameInput = document.getElementById('name');
  var slugInput = document.getElementById('slug');
  var descInput = document.getElementById('description');

  console.log('Elements:', { sel: !!sel, nameInput: !!nameInput, slugInput: !!slugInput, descInput: !!descInput });

  // Auto-generate slug from name
  if(nameInput && slugInput){
    if(slugInput.value) slugInput.dataset.touched = true;
    else slugInput.dataset.touched = false;

    nameInput.addEventListener('input', function(){
      if(slugInput.dataset.touched === 'true' || slugInput.dataset.touched === true) return;
      var s = nameInput.value.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9\-]/g, '')
        .replace(/--+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
      slugInput.value = s;
    });

    slugInput.addEventListener('input', function(){
      slugInput.dataset.touched = true;
      updatePreview();
    });
  }

  if(descInput){
    descInput.addEventListener('input', function(){
      // description edited by user
    });
  }

  // Route selector AJAX
  if(!sel) {
    console.log('No route selector found');
    return;
  }

  console.log('Attaching change listener to route selector');

  sel.addEventListener('change', function(){
    var route = sel.value;
    console.log('Route selected:', route);

    if(!route){
      if(nameInput) nameInput.value = '';
      if(slugInput) slugInput.value = '';
      if(descInput) descInput.value = '';
      if(document.getElementById('route')) document.getElementById('route').value = '';
      updatePreview();
      return;
    }

    // Show loading state directly in inputs
    console.log('Setting loading state in inputs');
    if(nameInput) nameInput.value = 'Loading...';
    if(slugInput) slugInput.value = 'Loading...';
    if(descInput) descInput.value = 'Loading...';

    // AJAX call
    setTimeout(function(){
      console.log('Sending AJAX request to <?php echo e(route("admin.permissions.generateFromRoute")); ?>');

      fetch('<?php echo e(route("admin.permissions.generateFromRoute")); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ route: route })
      })
      .then(response => {
        console.log('Response received:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Data received:', data);

        if(data.error){
          console.error('Server error:', data.error);
          if(nameInput) nameInput.value = '';
          return;
        }

        // Fill fields
        if(nameInput) nameInput.value = data.name;
        if(slugInput) slugInput.value = data.slug;
        if(descInput) descInput.value = data.description;
        if(document.getElementById('route')) document.getElementById('route').value = route;
      })
      .catch(error => {
        console.error('AJAX Error:', error);
        if(nameEl) nameEl.textContent = 'Error: ' + error.message;
      });
    }, 300);
  });

  // Initial preview
  updatePreview();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/permissions/create.blade.php ENDPATH**/ ?>