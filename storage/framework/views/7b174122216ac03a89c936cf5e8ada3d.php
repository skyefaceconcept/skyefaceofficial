<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo e(config('app.name')); ?>">
    <meta name="author" content="<?php echo e(config('app.name')); ?>">

    <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        $branding = \App\Models\Branding::first();
        $favicon = \App\Helpers\CompanyHelper::favicon();
    ?>
    <link rel="shortcut icon" href="<?php echo e($favicon); ?>">

    <!-- Global Stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
    <link href="<?php echo e(asset('buzbox/css/bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('buzbox/font-awesome-4.7.0/css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/animate/animate.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/owl-carousel/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/owl-carousel/owl.theme.default.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/responsive-professional.css')); ?>">

    <?php echo $__env->yieldContent('extra-css'); ?>
</head>

<body id="page-top">

    <!-- ====================================================
                         HEADER
    ======================================================== -->

    <header>

        <!-- Top Navbar  -->
        <div class="top-menubar">
            <div class="topmenu">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <ul class="list-inline top-contacts">
                                <li>
                                    <i class="fa fa-envelope"></i> Email: <a href="mailto:<?php echo e(config('mail.from.address', 'info@company.com')); ?>"><?php echo e(config('mail.from.address', 'info@company.com')); ?></a>
                                </li>
                                <li>
                                    <i class="fa fa-phone"></i> Hotline: <a href="tel:+1234567890">+1 (234) 567-890</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <ul class="list-inline top-data">
                                <li><a href="#" target="_blank"><i class="fa top-social fa-facebook"></i></a></li>
                                <li><a href="#" target="_blank"><i class="fa top-social fa-twitter"></i></a></li>
                                <li><a href="#" target="_blank"><i class="fa top-social fa-linkedin"></i></a></li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                    <li><a class="log-top" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                <?php else: ?>
                                    <li><a class="log-top" href="<?php echo e(route('login')); ?>">Login</a></li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <!-- ====================================================
                         MAIN CONTENT
    ======================================================== -->

    <main id="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- ====================================================
                         FOOTER
    ======================================================== -->

    <footer id="footer" class="bg-dark">
        <div class="container">
            <div class="row py-5">
                <!-- Company Info -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <h6 class="cl-white mb-3"><?php echo e($branding?->company_name ?? config('app.name')); ?></h6>
                    <ul class="list-unstyled cl-white" style="font-size: 13px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($branding?->rc_number): ?>
                            <li class="mb-2">
                                <strong>RC Number:</strong> <?php echo e($branding->rc_number); ?>

                            </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($branding?->cac_number): ?>
                            <li class="mb-2">
                                <strong>CAC Registration:</strong> <?php echo e($branding->cac_number); ?>

                            </li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <li class="mb-2">
                            <i class="fa fa-envelope"></i>
                            <a href="mailto:<?php echo e(config('mail.from.address', 'info@company.com')); ?>" class="cl-white" style="word-break: break-word;">
                                <?php echo e(config('mail.from.address', 'info@company.com')); ?>

                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <h6 class="cl-white mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(route('home')); ?>" class="cl-white">Home</a></li>
                        <li><a href="<?php echo e(route('about')); ?>" class="cl-white">About Us</a></li>
                        <li><a href="<?php echo e(route('services')); ?>" class="cl-white">Services</a></li>
                        <li><a href="<?php echo e(route('contact')); ?>" class="cl-white">Contact</a></li>
                    </ul>
                </div>

                <!-- Legal Links -->
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <h6 class="cl-white mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(route('policy')); ?>" class="cl-white">Privacy Policy</a></li>
                        <li><a href="<?php echo e(route('terms')); ?>" class="cl-white">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="row border-top pt-4">
                <div class="col-12 text-center">
                    <p class="cl-white mt-3">
                        &copy; <?php echo e(date('Y')); ?> <?php echo e($branding?->company_name ?? config('app.name')); ?>. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?php echo e(asset('buzbox/js/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('buzbox/js/popper/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('buzbox/js/bootstrap/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('buzbox/js/wow/wow.min.js')); ?>"></script>
    <script src="<?php echo e(asset('buzbox/js/owl-carousel/owl.carousel.min.js')); ?>"></script>
    <script src="<?php echo e(asset('buzbox/js/jquery-easing/jquery.easing.min.js')); ?>"></script>
    <script src="<?php echo e(asset('buzbox/js/custom.js')); ?>"></script>

    <?php echo $__env->yieldContent('extra-js'); ?>

</body>

</html>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/layouts/buzbox.blade.php ENDPATH**/ ?>