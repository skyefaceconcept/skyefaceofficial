<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $__env->yieldContent('title', 'Auth'); ?> - <?php echo e(config('app.name')); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Company favicon (if uploaded, fallback to default) -->
    <?php
        $branding = \App\Models\CompanySetting::first();
        $favicon = \App\Helpers\CompanyHelper::favicon();
    ?>
    <link rel="icon" type="image/png" href="<?php echo e($favicon); ?>" />

    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/vendors/css/vendor.bundle.base.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/css/shared/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('StarAdmin/src/assets/css/demo_1/style.css')); ?>">

    <?php echo $__env->yieldContent('extra-css'); ?>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('StarAdmin/src/assets/vendors/js/vendor.bundle.base.js')); ?>"></script>
    <script src="<?php echo e(asset('StarAdmin/src/assets/js/shared/off-canvas.js')); ?>"></script>
    <script src="<?php echo e(asset('StarAdmin/src/assets/js/shared/misc.js')); ?>"></script>

    <?php echo $__env->yieldContent('extra-js'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/layouts/staradmin_auth.blade.php ENDPATH**/ ?>