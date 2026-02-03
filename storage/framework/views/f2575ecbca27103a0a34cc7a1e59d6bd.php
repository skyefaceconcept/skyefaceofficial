<?php $__env->startSection('title', 'Company Branding'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Company Branding</h4>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form action="<?php echo e(route('admin.settings.storeCompanyBranding')); ?>" method="POST" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>

          <!-- Logo Upload -->
          <div class="form-group">
            <label for="logo">Company Logo</label>
            <p class="small text-muted">Accepted formats: JPEG, PNG, GIF, SVG. Max size: 2MB</p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($branding) && $branding->logo): ?>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!\Illuminate\Support\Facades\Storage::disk('public')->exists($branding->logo)): ?>
                <div class="alert alert-warning">Stored logo file not found in storage: <strong><?php echo e($branding->logo); ?></strong>. Please re-upload the logo.</div>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <div class="mb-3">
                <p class="text-muted">Current Logo:</p>
                <img src="<?php echo e(route('branding.asset', ['path' => $branding->logo])); ?>" alt="Company Logo" class="img-fluid" style="max-width: 200px; max-height: 100px;">
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="custom-file">
              <input type="file" class="custom-file-input <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="logo" name="logo" accept="image/*">
              <label class="custom-file-label" for="logo">Choose logo file</label>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          <!-- Favicon Upload -->
          <div class="form-group">
            <label for="favicon">Favicon</label>
            <p class="small text-muted">Accepted formats: JPEG, PNG, ICO, GIF. Max size: 512KB</p>

            <?php if(!empty($branding) && $branding->favicon): ?>
              <?php if(!\Illuminate\Support\Facades\Storage::disk('public')->exists($branding->favicon)): ?>
                <div class="alert alert-warning">Stored favicon file not found in storage: <strong><?php echo e($branding->favicon); ?></strong>. Please re-upload the favicon.</div>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <div class="mb-3">
                <p class="text-muted">Current Favicon:</p>
                <img src="<?php echo e(route('branding.asset', ['path' => $branding->favicon])); ?>" alt="Favicon" class="img-fluid" style="max-width: 100px; max-height: 100px;">
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="custom-file">
              <input type="file" class="custom-file-input <?php $__errorArgs = ['favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="favicon" name="favicon" accept="image/*">
              <label class="custom-file-label" for="favicon">Choose favicon file</label>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          <!-- Name Logo Upload -->
          <div class="form-group">
            <label for="name_logo">Logo Name/Text Image</label>
            <p class="small text-muted">Accepted formats: JPEG, PNG, GIF, SVG. Max size: 1MB</p>

            <?php if(!empty($branding) && $branding->name_logo): ?>
              <?php if(!\Illuminate\Support\Facades\Storage::disk('public')->exists($branding->name_logo)): ?>
                <div class="alert alert-warning">Stored logo name file not found in storage: <strong><?php echo e($branding->name_logo); ?></strong>. Please re-upload the file.</div>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <div class="mb-3">
                <p class="text-muted">Current Logo Name/Text:</p>
                <img src="<?php echo e(route('branding.asset', ['path' => $branding->name_logo])); ?>" alt="Logo Name" class="img-fluid" style="max-width: 200px; max-height: 60px;">
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="custom-file">
              <input type="file" class="custom-file-input <?php $__errorArgs = ['name_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name_logo" name="name_logo" accept="image/*">
              <label class="custom-file-label" for="name_logo">Choose logo name/text image</label>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>

          <!-- Menu Offer Image Toggle -->
          <div class="form-group mt-4 pt-3 border-top">
            <label for="show_menu_offer_image" class="form-label">Shop Menu Offer Image</label>
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="show_menu_offer_image" name="show_menu_offer_image" value="1" <?php echo e((!empty($branding) && $branding->show_menu_offer_image) ? 'checked' : ''); ?>>
              <label class="form-check-label" for="show_menu_offer_image">
                Show offer image in Shop dropdown menu
              </label>
            </div>
            <small class="text-muted d-block mt-2">
              Enable this to display the offer icon image in the Shop menu dropdown. Disable to hide it during non-festive periods.
            </small>
          </div>

          <!-- Company Information -->
          <div class="form-group mt-4 pt-3 border-top">
            <h6 class="mb-3">Company Registration Information</h6>
            
            <!-- Company Name -->
            <div class="form-group">
              <label for="company_name">Company Name</label>
              <input type="text" class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="company_name" name="company_name" placeholder="e.g., SKYEFACE DIGITAL LIMITED" value="<?php echo e((!empty($branding) && $branding->company_name) ? $branding->company_name : ''); ?>">
              <small class="text-muted d-block mt-2">Your full registered company name as per CAC registration.</small>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- CAC Registration Number -->
            <div class="form-group">
              <label for="cac_number">CAC Registration Number</label>
              <input type="text" class="form-control <?php $__errorArgs = ['cac_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cac_number" name="cac_number" placeholder="e.g., 12345678" value="<?php echo e((!empty($branding) && $branding->cac_number) ? $branding->cac_number : ''); ?>">
              <small class="text-muted d-block mt-2">Your Corporate Affairs Commission registration number (typically 8 digits).</small>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cac_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- RC Number -->
            <div class="form-group">
              <label for="rc_number">RC Number</label>
              <input type="text" class="form-control <?php $__errorArgs = ['rc_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="rc_number" name="rc_number" placeholder="e.g., RC 9053242" value="<?php echo e((!empty($branding) && $branding->rc_number) ? $branding->rc_number : ''); ?>">
              <small class="text-muted d-block mt-2">Your RC (Registration Certificate) number as per CAC.</small>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['rc_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>

          <button type="submit" class="btn btn-primary mt-4">
            <i class="mdi mdi-content-save"></i> Save Branding
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">How to Use</h5>
        <div class="alert alert-info" role="alert">
          <h6>Logo</h6>
          <p>Upload your company logo here. It will be displayed in emails, headers, and other branding areas. Recommended size: 200x100px or wider aspect ratio.</p>

          <h6 class="mt-3">Favicon</h6>
          <p>Upload a favicon (the small icon shown in browser tabs). Recommended size: 32x32px or square. You can use .ico or .png format.</p>

          <h6 class="mt-3">Supported Formats</h6>
          <ul class="mb-0">
            <li>Logo: JPEG, PNG, GIF, SVG (max 2MB)</li>
            <li>Favicon: JPEG, PNG, ICO, GIF (max 512KB)</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra-js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function() {
      var label = this.nextElementSibling;
      if (label) {
        label.textContent = this.files && this.files[0] ? this.files[0].name : 'Choose file';
      }
    });
  });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/settings/company_branding.blade.php ENDPATH**/ ?>