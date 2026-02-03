<?php $__env->startSection('title', 'Payment Processors Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Payment Processors Configuration</h1>
        <p class="text-muted">Configure and manage your payment processors. You can set up multiple processors and switch between them if one goes down.</p>
      </div>
    </div>
  </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <?php echo e(session('success')); ?>

  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Please fix the following errors:</strong>
  <ul class="mb-0">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li><?php echo e($error); ?></li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </ul>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="row">
  <!-- Active Payment Processor Selection -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Active Processor</h5>
      </div>
      <div class="card-body">
        <form action="<?php echo e(route('admin.settings.setActivePaymentProcessor')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <div class="form-group">
            <label for="active_processor">Select Payment Processor</label>
            <select class="form-control <?php $__errorArgs = ['active_processor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="active_processor" name="active_processor" required>
              <option value="">-- Choose Processor --</option>
              <option value="flutterwave" <?php echo e(($activeProcessor === 'flutterwave') ? 'selected' : ''); ?>>
                Flutterwave
              </option>
              <option value="paystack" <?php echo e(($activeProcessor === 'paystack') ? 'selected' : ''); ?>>
                Paystack
              </option>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['active_processor'];
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

          <div class="alert alert-info" role="alert">
            <h6 class="alert-heading">Current Active Processor:</h6>
            <p class="mb-0">
              <strong><?php echo e(ucfirst($activeProcessor)); ?></strong>
              <span class="badge badge-success ml-2">Active</span>
            </p>
          </div>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'edit_settings')): ?>
          <button type="submit" class="btn btn-primary btn-block">Switch Processor</button>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
      </div>
    </div>

    <!-- Processor Status Cards -->
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="card-title mb-0">Processor Status</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <h6>Flutterwave</h6>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flutterwaveConfigured): ?>
            <span class="badge badge-success">✓ Configured</span>
          <?php else: ?>
            <span class="badge badge-warning">⚠ Not Configured</span>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div>
          <h6>Paystack</h6>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paystackConfigured): ?>
            <span class="badge badge-success">✓ Configured</span>
          <?php else: ?>
            <span class="badge badge-warning">⚠ Not Configured</span>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Flutterwave Configuration -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header <?php if($activeProcessor === 'flutterwave'): ?> bg-success text-white <?php endif; ?>">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Flutterwave Configuration</h5>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeProcessor === 'flutterwave'): ?>
            <span class="badge badge-light">Active</span>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <form action="<?php echo e(route('admin.settings.savePaymentProcessor', 'flutterwave')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <div class="form-group">
            <label for="flutterwave_public_key">Public Key</label>
            <input
              type="text"
              class="form-control <?php $__errorArgs = ['flutterwave_public_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="flutterwave_public_key"
              name="public_key"
              value="<?php echo e(old('public_key', $paymentSettings['flutterwave']['public_key'] ?? '')); ?>"
              placeholder="Enter Flutterwave public key"
            >
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['flutterwave_public_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <small class="form-text text-muted">Find this in your Flutterwave dashboard under API keys</small>
          </div>

          <div class="form-group">
            <label for="flutterwave_secret_key">Secret Key</label>
            <div class="input-group">
              <input
                type="password"
                class="form-control <?php $__errorArgs = ['flutterwave_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="flutterwave_secret_key"
                name="secret_key"
                value="<?php echo e(old('secret_key', $paymentSettings['flutterwave']['secret_key'] ?? '')); ?>"
                placeholder="Enter Flutterwave secret key"
              >
              <div class="input-group-append">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="flutterwave_secret_key">
                  <i class="far fa-eye"></i>
                </button>
              </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['flutterwave_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <small class="form-text text-muted">Keep this secret and secure</small>
          </div>

          <div class="form-group">
            <label for="flutterwave_encrypt_key">Encryption Key</label>
            <div class="input-group">
              <input
                type="password"
                class="form-control <?php $__errorArgs = ['flutterwave_encrypt_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="flutterwave_encrypt_key"
                name="encrypt_key"
                value="<?php echo e(old('encrypt_key', $paymentSettings['flutterwave']['encrypt_key'] ?? '')); ?>"
                placeholder="Enter Flutterwave encryption key (optional)"
              >
              <div class="input-group-append">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="flutterwave_encrypt_key">
                  <i class="far fa-eye"></i>
                </button>
              </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['flutterwave_encrypt_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <small class="form-text text-muted">Optional: Used for additional security</small>
          </div>

          <div class="form-group">
            <label for="flutterwave_environment">Environment</label>
            <select class="form-control <?php $__errorArgs = ['flutterwave_environment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="flutterwave_environment" name="environment">
              <option value="live" <?php echo e(($paymentSettings['flutterwave']['environment'] ?? 'live') === 'live' ? 'selected' : ''); ?>>Live</option>
              <option value="sandbox" <?php echo e(($paymentSettings['flutterwave']['environment'] ?? 'live') === 'sandbox' ? 'selected' : ''); ?>>Sandbox (Testing)</option>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['flutterwave_environment'];
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
            <div class="custom-control custom-switch">
              <input
                type="checkbox"
                class="custom-control-input"
                id="flutterwave_enabled"
                name="enabled"
                value="1"
                <?php echo e(($paymentSettings['flutterwave']['enabled'] ?? false) ? 'checked' : ''); ?>

              >
              <label class="custom-control-label" for="flutterwave_enabled">
                Enable Flutterwave
              </label>
            </div>
          </div>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'edit_settings')): ?>
          <button type="submit" class="btn btn-primary btn-block">Save Flutterwave</button>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
      </div>
    </div>
  </div>

  <!-- Paystack Configuration -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header <?php if($activeProcessor === 'paystack'): ?> bg-success text-white <?php endif; ?>">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Paystack Configuration</h5>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeProcessor === 'paystack'): ?>
            <span class="badge badge-light">Active</span>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <form action="<?php echo e(route('admin.settings.savePaymentProcessor', 'paystack')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <div class="form-group">
            <label for="paystack_public_key">Public Key</label>
            <input
              type="text"
              class="form-control <?php $__errorArgs = ['paystack_public_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="paystack_public_key"
              name="public_key"
              value="<?php echo e(old('public_key', $paymentSettings['paystack']['public_key'] ?? '')); ?>"
              placeholder="Enter Paystack public key"
            >
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['paystack_public_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <small class="form-text text-muted">Find this in your Paystack dashboard under Settings > API Keys</small>
          </div>

          <div class="form-group">
            <label for="paystack_secret_key">Secret Key</label>
            <div class="input-group">
              <input
                type="password"
                class="form-control <?php $__errorArgs = ['paystack_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="paystack_secret_key"
                name="secret_key"
                value="<?php echo e(old('secret_key', $paymentSettings['paystack']['secret_key'] ?? '')); ?>"
                placeholder="Enter Paystack secret key"
              >
              <div class="input-group-append">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="paystack_secret_key">
                  <i class="far fa-eye"></i>
                </button>
              </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['paystack_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <small class="form-text text-muted">Keep this secret and secure</small>
          </div>

          <div class="form-group">
            <label for="paystack_environment">Environment</label>
            <select class="form-control <?php $__errorArgs = ['paystack_environment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="paystack_environment" name="environment">
              <option value="live" <?php echo e(($paymentSettings['paystack']['environment'] ?? 'live') === 'live' ? 'selected' : ''); ?>>Live</option>
              <option value="test" <?php echo e(($paymentSettings['paystack']['environment'] ?? 'live') === 'test' ? 'selected' : ''); ?>>Test</option>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['paystack_environment'];
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
            <label for="paystack_currency">Currency</label>
            <select class="form-control <?php $__errorArgs = ['paystack_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="paystack_currency" name="currency">
              <option value="NGN" <?php echo e(($paymentSettings['paystack']['currency'] ?? 'NGN') === 'NGN' ? 'selected' : ''); ?>>NGN - Nigerian Naira</option>
              <option value="GHS" <?php echo e(($paymentSettings['paystack']['currency'] ?? 'NGN') === 'GHS' ? 'selected' : ''); ?>>GHS - Ghana Cedi</option>
              <option value="KES" <?php echo e(($paymentSettings['paystack']['currency'] ?? 'NGN') === 'KES' ? 'selected' : ''); ?>>KES - Kenya Shilling</option>
              <option value="ZAR" <?php echo e(($paymentSettings['paystack']['currency'] ?? 'NGN') === 'ZAR' ? 'selected' : ''); ?>>ZAR - South Africa Rand</option>
              <option value="USD" <?php echo e(($paymentSettings['paystack']['currency'] ?? 'NGN') === 'USD' ? 'selected' : ''); ?>>USD - US Dollar</option>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['paystack_currency'];
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
            <div class="custom-control custom-switch">
              <input
                type="checkbox"
                class="custom-control-input"
                id="paystack_enabled"
                name="enabled"
                value="1"
                <?php echo e(($paymentSettings['paystack']['enabled'] ?? false) ? 'checked' : ''); ?>

              >
              <label class="custom-control-label" for="paystack_enabled">
                Enable Paystack
              </label>
            </div>
          </div>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'edit_settings')): ?>
          <button type="submit" class="btn btn-primary btn-block">Save Paystack</button>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Additional Settings Row -->
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Global Payment Settings</h5>
      </div>
      <div class="card-body">
        <form action="<?php echo e(route('admin.settings.savePaymentGlobalSettings')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="payment_currency">Default Currency</label>
                <select class="form-control <?php $__errorArgs = ['payment_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="payment_currency" name="payment_currency">
                  <option value="NGN" <?php echo e(($globalSettings['payment_currency'] ?? 'NGN') === 'NGN' ? 'selected' : ''); ?>>NGN - Nigerian Naira</option>
                  <option value="USD" <?php echo e(($globalSettings['payment_currency'] ?? 'NGN') === 'USD' ? 'selected' : ''); ?>>USD - US Dollar</option>
                  <option value="GBP" <?php echo e(($globalSettings['payment_currency'] ?? 'NGN') === 'GBP' ? 'selected' : ''); ?>>GBP - British Pound</option>
                  <option value="EUR" <?php echo e(($globalSettings['payment_currency'] ?? 'NGN') === 'EUR' ? 'selected' : ''); ?>>EUR - Euro</option>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payment_currency'];
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
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="payment_timeout">Payment Timeout (minutes)</label>
                <input
                  type="number"
                  class="form-control <?php $__errorArgs = ['payment_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  id="payment_timeout"
                  name="payment_timeout"
                  value="<?php echo e(old('payment_timeout', $globalSettings['payment_timeout'] ?? 30)); ?>"
                  min="5"
                  max="120"
                >
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payment_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <span class="invalid-feedback"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <small class="form-text text-muted">How long to wait for payment confirmation before considering it failed</small>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="payment_webhook_secret">Webhook Secret</label>
            <input
              type="text"
              class="form-control <?php $__errorArgs = ['payment_webhook_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="payment_webhook_secret"
              name="payment_webhook_secret"
              value="<?php echo e(old('payment_webhook_secret', $globalSettings['payment_webhook_secret'] ?? '')); ?>"
              placeholder="Secret key for verifying webhook requests"
            >
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payment_webhook_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <small class="form-text text-muted">Used to verify incoming webhook requests from payment processors</small>
          </div>

          <div class="form-group">
            <div class="custom-control custom-switch">
              <input
                type="checkbox"
                class="custom-control-input"
                id="payment_test_mode"
                name="test_mode"
                value="1"
                <?php echo e(($globalSettings['test_mode'] ?? false) ? 'checked' : ''); ?>

              >
              <label class="custom-control-label" for="payment_test_mode">
                Enable Test Mode (All transactions will be test transactions)
              </label>
            </div>
            <small class="form-text text-muted d-block mt-2">When enabled, all payments will use the test/sandbox environment regardless of individual processor settings</small>
          </div>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'edit_settings')): ?>
          <button type="submit" class="btn btn-primary">Save Global Settings</button>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Documentation -->
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">📚 Documentation & Tips</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h6>Flutterwave Setup</h6>
            <ol class="small">
              <li>Log in to your <a href="https://dashboard.flutterwave.com" target="_blank">Flutterwave Dashboard</a></li>
              <li>Go to <strong>Settings → API Keys</strong></li>
              <li>Copy your <strong>Public Key</strong> and <strong>Secret Key</strong></li>
              <li>Paste them in the Flutterwave Configuration section above</li>
              <li>Choose <strong>Sandbox</strong> for testing or <strong>Live</strong> for production</li>
              <li>Enable Flutterwave and set as active processor</li>
            </ol>
          </div>

          <div class="col-md-6">
            <h6>Paystack Setup</h6>
            <ol class="small">
              <li>Log in to your <a href="https://dashboard.paystack.com" target="_blank">Paystack Dashboard</a></li>
              <li>Go to <strong>Settings → API Keys & Webhooks</strong></li>
              <li>Copy your <strong>Public Key</strong> and <strong>Secret Key</strong></li>
              <li>Paste them in the Paystack Configuration section above</li>
              <li>Choose <strong>Test</strong> for testing or <strong>Live</strong> for production</li>
              <li>Enable Paystack and set as active processor</li>
            </ol>
          </div>
        </div>

        <hr>

        <h6>Best Practices</h6>
        <ul class="small">
          <li>✅ Always test with sandbox/test credentials first before using live credentials</li>
          <li>✅ Keep your secret keys secure - never share them or commit them to version control</li>
          <li>✅ Set up both processors as a backup in case one goes down</li>
          <li>✅ Monitor payment logs regularly for any failed transactions</li>
          <li>✅ Test webhook delivery after configuration changes</li>
          <li>✅ Keep your API keys updated if you regenerate them in the payment processor's dashboard</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Toggle password visibility
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
      const targetId = this.dataset.target;
      const input = document.getElementById(targetId);
      const icon = this.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

  // Form submission handling
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      const submitBtn = this.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span>Saving...';
      }
    });
  });


});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/settings/payment_processors.blade.php ENDPATH**/ ?>