<?php use \Illuminate\Support\Facades\Storage; ?>

<?php $__env->startSection('title', 'Admin Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <div class="page-title">
        <h1>Admin Profile</h1>
      </div>
    </div>
  </div>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="mdi mdi-check-circle mr-2"></i><?php echo e(session('status')); ?>

    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="row mt-4">
    <div class="col-lg-8">
        <!-- Profile Card -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center flex-column flex-sm-row">
                    <div class="mr-sm-4 text-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->profile_photo_path && Storage::disk('public')->exists(auth()->user()->profile_photo_path)): ?>
                            <img id="admin-photo-preview" src="<?php echo e(asset('storage/' . auth()->user()->profile_photo_path)); ?>" alt="Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <div id="admin-photo-preview" class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="mdi mdi-account" style="font-size:48px;color:#9aa0a6"></i>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <small class="text-muted d-block mt-2">Member since <?php echo e(auth()->user()->created_at->format('M d, Y')); ?></small>
                    </div>

                    <div class="flex-grow-1 text-center text-sm-left mt-3 mt-sm-0 ml-sm-3">
                        <h5 class="mb-1"><?php echo e(auth()->user()->fname ?? auth()->user()->name); ?> <?php echo e(auth()->user()->lname); ?></h5>
                        <p class="text-muted mb-2"><?php echo e(auth()->user()->email); ?></p>
                        <div class="mb-3">
                            <span class="badge badge-success">Active</span>
                            <?php if(auth()->user()->role): ?>
                                <span class="badge badge-info ml-2"><?php echo e(auth()->user()->role->name); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(auth()->user()->two_factor_secret): ?>
                                <span class="badge badge-success ml-2">
                                    <i class="mdi mdi-shield-check mr-1"></i>2FA Enabled
                                </span>
                            <?php else: ?>
                                <span class="badge badge-secondary ml-2">
                                    <i class="mdi mdi-shield-alert mr-1"></i>2FA Disabled
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information Form -->
        <div class="card mb-3" id="profile-form">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="mdi mdi-pencil mr-2"></i>Profile Information</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="form-group">
                        <label for="profile_photo_path" class="font-weight-600">Profile Photo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input <?php $__errorArgs = ['profile_photo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="profile_photo_path" name="profile_photo_path" accept="image/*">
                            <label class="custom-file-label" for="profile_photo_path">Choose file</label>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['profile_photo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="fname">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['fname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="fname" name="fname" value="<?php echo e(old('fname', auth()->user()->fname ?? '')); ?>" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['fname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="mname">Middle Name</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['mname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="mname" name="mname" value="<?php echo e(old('mname', auth()->user()->mname ?? '')); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="lname">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['lname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="lname" name="lname" value="<?php echo e(old('lname', auth()->user()->lname ?? '')); ?>" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email', auth()->user()->email ?? '')); ?>" required>
                                <?php if(auth()->user()->email_verified_at): ?>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-success text-white">
                                            <i class="mdi mdi-check-circle"></i>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-warning text-white">
                                            <i class="mdi mdi-clock-alert"></i> Pending
                                        </span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="username">Username</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="username" name="username" value="<?php echo e(old('username', auth()->user()->username ?? '')); ?>" placeholder="Optional username">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="phone">Phone Number</label>
                            <input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone" name="phone" value="<?php echo e(old('phone', auth()->user()->phone ?? '')); ?>" placeholder="e.g., +1 (555) 123-4567">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="dob" name="dob" value="<?php echo e(old('dob', auth()->user()->dob ?? '')); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save mr-1"></i> Save Changes</button>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-light ml-2">Cancel</a>
                </form>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="card" id="password-form">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="mdi mdi-lock mr-2"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.profile.updatePassword')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="current_password" name="current_password" placeholder="Enter current password">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password" name="password" placeholder="Min. 8 characters">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-lock-check mr-1"></i> Update Password</button>
                </form>
            </div>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="card mb-3" id="two-factor-form">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="mdi mdi-shield-check mr-2"></i>Two-Factor Authentication</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1"><strong>Secure your account with 2FA</strong></p>
                        <p class="text-muted small mb-0">Add an extra layer of security by requiring a verification code when logging in</p>
                    </div>
                    <div>
                        <?php if(auth()->user()->two_factor_secret): ?>
                            <span class="badge badge-success">Enabled</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Disabled</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <?php if(auth()->user()->two_factor_secret): ?>
                            <form action="<?php echo e(route('admin.profile.disableTwoFactor')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="mdi mdi-shield-off mr-1"></i>Disable 2FA
                                </button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#enable2FAModal">
                                <i class="mdi mdi-shield-plus mr-1"></i>Enable 2FA
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="col-sm-6 text-sm-right">
                        <small class="text-muted">
                            <?php if(auth()->user()->two_factor_secret): ?>
                                Enabled since <?php echo e(auth()->user()->updated_at->format('M d, Y')); ?>

                            <?php else: ?>
                                Not yet configured
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Browser Sessions -->
        <div class="card" id="sessions-list">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="mdi mdi-devices mr-2"></i>Browser Sessions</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">These are the active sessions and browsers that are logged into your account</p>
                <div id="sessions-container">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="session-item mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <?php
                                            $deviceIcon = 'mdi-monitor';
                                            $deviceColor = '#2196F3';
                                            
                                            if (strpos($session['device'], 'Mobile') !== false || strpos($session['device'], 'iPhone') !== false) {
                                                $deviceIcon = 'mdi-phone';
                                                $deviceColor = '#FF9800';
                                            } elseif (strpos($session['device'], 'Tablet') !== false || strpos($session['device'], 'iPad') !== false) {
                                                $deviceIcon = 'mdi-tablet';
                                                $deviceColor = '#9C27B0';
                                            } else {
                                                $deviceIcon = 'mdi-laptop';
                                                $deviceColor = '#4CAF50';
                                            }
                                        ?>
                                        <i class="mdi <?php echo e($deviceIcon); ?> mr-2" style="font-size: 20px; color: <?php echo e($deviceColor); ?>;"></i>
                                        <div>
                                            <p class="mb-1"><strong><?php echo e($session['device']); ?></strong></p>
                                            <p class="text-muted small mb-0"><?php echo e($session['browser']); ?> on <?php echo e($session['platform']); ?></p>
                                            <p class="text-muted small mb-0">IP Address: <code><?php echo e($session['ip_address']); ?></code></p>
                                            <p class="text-muted small mb-0">Last active: <?php echo e($session['last_activity']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($session['is_current']): ?>
                                    <span class="badge badge-success">Active Now</span>
                                <?php else: ?>
                                    <form action="<?php echo e(route('admin.profile.logoutSession', ['sessionId' => $session['id']])); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                                    </form>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="alert alert-info" role="alert">
                            <i class="mdi mdi-information-outline mr-2"></i>No active sessions found.
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($sessions) > 1): ?>
                    <hr>
                    <form action="<?php echo e(route('admin.profile.logoutOtherSessions')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-warning">
                            <i class="mdi mdi-logout mr-1"></i>Logout All Other Sessions
                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
                    <button type="submit" class="btn btn-sm btn-outline-warning">
                        <i class="mdi mdi-logout mr-1"></i>Logout All Other Sessions
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Enable 2FA Modal -->
<div class="modal fade" id="enable2FAModal" tabindex="-1" role="dialog" aria-labelledby="enable2FAModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enable2FAModalLabel">
                    <i class="mdi mdi-shield-check mr-2"></i>Enable Two-Factor Authentication
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Two-factor authentication adds an extra layer of security to your account. You'll need a code from your phone to log in.</p>
                
                <div class="alert alert-info" role="alert">
                    <strong>Step 1:</strong> Download an authenticator app on your phone
                    <ul class="mt-2 mb-0 small">
                        <li>Google Authenticator</li>
                        <li>Microsoft Authenticator</li>
                        <li>Authy</li>
                    </ul>
                </div>

                <div class="alert alert-info" role="alert">
                    <strong>Step 2:</strong> Scan this QR code with your authenticator app
                    <div class="text-center mt-3" id="qrCodeContainer">
                        <div class="p-3 bg-light rounded">
                            <?php
                                $appName = config('app.name', 'Skyeface');
                                $userEmail = auth()->user()->email;
                                $secret = $twoFactorSecret;
                                // Properly format the OTPAuth URI
                                $otpAuthUri = sprintf(
                                    'otpauth://totp/%s:%s?issuer=%s&secret=%s&algorithm=SHA1&digits=6&period=30',
                                    urlencode($appName),
                                    urlencode($userEmail),
                                    urlencode($appName),
                                    $secret
                                );
                            ?>
                            <img id="qrCodeImage" 
                                 src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&format=png&data=<?php echo e(rawurlencode($otpAuthUri)); ?>" 
                                 alt="QR Code for Two-Factor Authentication" 
                                 class="img-fluid" 
                                 style="max-width: 250px; border: 2px solid #ddd; padding: 10px; border-radius: 4px; background: white;">
                            <p class="text-muted small mt-3 mb-1"><strong>Can't scan?</strong> Enter this code manually in your authenticator app:</p>
                            <div class="alert alert-light" role="alert" style="font-family: monospace; letter-spacing: 3px; font-size: 16px; font-weight: bold; word-break: break-all;">
                                <?php echo e($secret); ?>

                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('<?php echo e($secret); ?>')">
                                <i class="mdi mdi-content-copy mr-1"></i>Copy Secret
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="twoFactorCode">Verification Code</label>
                    <input type="text" class="form-control" id="twoFactorCode" name="two_factor_code" placeholder="Enter 6-digit code" maxlength="6" inputmode="numeric">
                    <small class="text-muted">Enter the 6-digit code from your authenticator app</small>
                </div>

                <div class="alert alert-warning" role="alert">
                    <h6 class="mb-3"><i class="mdi mdi-backup-restore mr-2"></i>Save Recovery Codes</h6>
                    <p class="small mb-3">Save these backup codes in a safe place. Each code can be used once to regain access to your account if you lose access to your authenticator app.</p>
                    
                    <div class="bg-light p-3 rounded mb-3" id="recoveryCodesContainer" style="font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.8;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recoveryCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="recovery-code"><?php echo e($code); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="copyRecoveryCodesToClipboard()">
                            <i class="mdi mdi-content-copy mr-1"></i>Copy All Codes
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="downloadRecoveryCodes()">
                            <i class="mdi mdi-download mr-1"></i>Download as Text
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="printRecoveryCodes()">
                            <i class="mdi mdi-printer mr-1"></i>Print
                        </button>
                    </div>

                    <div class="mt-2">
                        <small class="text-danger d-block">
                            <i class="mdi mdi-alert-circle"></i>
                            <strong>Important:</strong> Keep these codes safe. Anyone who has these codes can access your account.
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="<?php echo e(route('admin.profile.enableTwoFactor')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="two_factor_code" id="modalCode">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-shield-check mr-1"></i>Enable 2FA
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra-css'); ?>
<style>
    /* Responsive tweaks for admin profile */
    .admin-photo { width:140px; height:140px; object-fit:cover; border:4px solid #f7f7f7; }
    .admin-photo-wrap { width:160px; }
    .btn-responsive { min-width: 140px; }
    .custom-file.w-50 { width: 50%; }

    /* Session item styling */
    .session-item {
        transition: all 0.3s ease;
    }
    .session-item:hover {
        background-color: #f9f9f9;
        border-radius: 4px;
        padding: 8px;
    }

    /* 2FA card styling */
    #two-factor-form .badge {
        font-size: 12px;
        padding: 6px 12px;
    }

    /* Modal styling */
    .font-family-monospace {
        font-family: 'Courier New', monospace;
    }

    /* Device icon colors */
    .device-icon-desktop { color: #2196F3; }
    .device-icon-mobile { color: #FF9800; }
    .device-icon-tablet { color: #4CAF50; }

    @media (max-width: 576px) {
        .admin-photo { width:120px; height:120px; }
        .admin-photo-wrap { width:100%; }
        .custom-file.w-50 { width: 100%; }
        .btn-responsive { width:100%; }

        .session-item .d-flex {
            flex-direction: column;
        }

        .session-item .badge {
            margin-top: 8px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra-js'); ?>
<script>
    // Store the 2FA secret for the current session
    const twoFactorSecret = <?php echo json_encode($twoFactorSecret ?? null, 15, 512) ?>;
    const recoveryCodes = <?php echo json_encode($recoveryCodes ?? [], 15, 512) ?>;

    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Copied to clipboard!');
        });
    }

    // Copy recovery codes function
    function copyRecoveryCodesToClipboard() {
        const codesText = recoveryCodes.join('\n');
        copyToClipboard(codesText);
    }

    // Download recovery codes as text file
    function downloadRecoveryCodes() {
        const codesText = 'SKYEFACE TWO-FACTOR AUTHENTICATION RECOVERY CODES\n' +
                          '=' .repeat(50) + '\n\n' +
                          'Generated: ' + new Date().toLocaleString() + '\n' +
                          'Account: ' + document.querySelector('h5') ? document.querySelector('h5').textContent : 'Admin Account' + '\n\n' +
                          'SAVE THESE CODES IN A SAFE PLACE\n' +
                          'Each code can only be used once.\n\n' +
                          recoveryCodes.join('\n') + '\n\n' +
                          'WARNING: Keep these codes secure. Anyone with these codes can access your account.\n';
        
        const blob = new Blob([codesText], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'skyeface-recovery-codes-' + new Date().toISOString().split('T')[0] + '.txt';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    // Print recovery codes
    function printRecoveryCodes() {
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Recovery Codes - Skyeface</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
        printWindow.document.write('h1 { text-align: center; }');
        printWindow.document.write('h2 { margin-top: 20px; font-size: 14px; }');
        printWindow.document.write('.codes { font-family: monospace; margin: 20px 0; }');
        printWindow.document.write('.code { margin: 8px 0; font-size: 14px; }');
        printWindow.document.write('.warning { color: red; font-weight: bold; margin-top: 30px; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write('<h1>Skyeface Recovery Codes</h1>');
        printWindow.document.write('<p>Generated: ' + new Date().toLocaleString() + '</p>');
        printWindow.document.write('<p>SAVE THESE CODES IN A SAFE PLACE</p>');
        printWindow.document.write('<div class="codes">');
        recoveryCodes.forEach(code => {
            printWindow.document.write('<div class="code">' + code + '</div>');
        });
        printWindow.document.write('</div>');
        printWindow.document.write('<div class="warning">⚠️ WARNING: Keep these codes secure. Anyone with these codes can access your account.</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

    // File input label update and preview
    const fileInput = document.getElementById('profile_photo_path');
    if(fileInput){
        fileInput.addEventListener('change', function(e){
            const file = e.target.files[0];
            const label = e.target.nextElementSibling;
            if(label) label.textContent = file ? file.name : 'Choose file';
            if(file){
                const reader = new FileReader();
                reader.onload = function(ev){
                    const preview = document.getElementById('admin-photo-preview');
                    if(preview){
                        if(preview.tagName === 'IMG'){
                            preview.src = ev.target.result;
                        } else {
                            preview.innerHTML = '<img src="'+ev.target.result+'" class="img-fluid rounded-circle admin-photo" alt="Profile">';
                        }
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Two-Factor Authentication Modal Handling
    const twoFactorCodeInput = document.getElementById('twoFactorCode');
    const modalCodeInput = document.getElementById('modalCode');
    
    if(twoFactorCodeInput) {
        // Allow only numeric input
        twoFactorCodeInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if(this.value.length === 6) {
                modalCodeInput.value = this.value;
            }
        });

        // Auto-transfer code to hidden input
        twoFactorCodeInput.addEventListener('change', function(e) {
            if(modalCodeInput) {
                modalCodeInput.value = this.value;
            }
        });

        // Focus and select all on click
        twoFactorCodeInput.addEventListener('focus', function(e) {
            this.select();
        });
    }

    // Clear modal on close
    const enable2FAModal = document.getElementById('enable2FAModal');
    if(enable2FAModal) {
        enable2FAModal.addEventListener('hide.bs.modal', function(e) {
            if(twoFactorCodeInput) twoFactorCodeInput.value = '';
            if(modalCodeInput) modalCodeInput.value = '';
        });
    }

    // Session logout confirmation
    document.querySelectorAll('form[action*="logoutSession"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if(!confirm('Are you sure you want to logout this session?')) {
                e.preventDefault();
            }
        });
    });

    // Logout all other sessions confirmation
    const logoutOthersForm = document.querySelector('form[action*="logoutOtherSessions"]');
    if(logoutOthersForm) {
        logoutOthersForm.addEventListener('submit', function(e) {
            if(!confirm('This will logout all other sessions. Are you sure?')) {
                e.preventDefault();
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/profile/show.blade.php ENDPATH**/ ?>