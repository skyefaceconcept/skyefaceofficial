<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row align-items-center">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center" style="min-width: 250px; max-width: 250px; padding: 8px 12px;">
    <?php
        $branding = \App\Models\CompanySetting::first();
    ?>
    <a class="navbar-brand brand-logo d-flex align-items-center" href="<?php echo e(route('admin.dashboard')); ?>" style="gap: 0px; display: none;">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($branding && $branding->logo): ?>
        <img src="<?php echo e(\App\Helpers\CompanyHelper::logoWhite()); ?>" alt="Company Logo" style="max-height: 30px; object-fit: contain; margin-right: -8px; z-index: 2;" />
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($branding && $branding->name_logo): ?>
        <img src="<?php echo e(\App\Helpers\CompanyHelper::logo()); ?>" alt="Logo Name" style="max-height: 30px; object-fit: contain;" />
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </a>
    <a class="navbar-brand brand-logo-mini" href="<?php echo e(route('admin.dashboard')); ?>">
      <img src="<?php echo e(asset('StarAdmin-Free-Bootstrap-Admin-Template-master/src/assets/images/logo-mini.svg')); ?>" alt="logo" style="max-height: 30px;" />
    </a>

    <!-- Mobile: show hamburger menu -->
    <button class="sidebar-toggle d-md-none" type="button" id="mobileSidebarToggle" style="margin-left: 10px;">
      <span class="mdi mdi-menu" style="font-size: 24px; color: white;"></span>
    </button>
  </div>

  <div class="navbar-menu-wrapper d-flex align-items-center flex-grow-1 px-2 px-md-3">
    <!-- Desktop: sidebar toggle button -->
    <button class="navbar-toggler navbar-toggler-left d-none d-lg-inline-block align-self-center" type="button" id="sidebarToggle" style="margin-left: 5px;">
      <span class="mdi mdi-menu" style="font-size: 24px;"></span>
    </button>

    <!-- Support info - hidden on mobile -->
    <ul class="navbar-nav d-none d-lg-flex ms-3">
      <li class="nav-item font-weight-semibold" style="font-size: 12px;">
        Support: <?php echo e(config('app.support_phone', '(+234) 806 212 1410')); ?>

      </li>
    </ul>

    <!-- Search - hidden on mobile/tablet, shown on desktop -->
    <form class="ms-auto search-form d-none d-lg-block flex-grow-1 mx-3" action="#">
      <div class="form-group mb-0">
        <input type="search" class="form-control form-control-sm" placeholder="Search..." style="max-width: 300px;">
      </div>
    </form>

    <!-- User dropdown - responsive -->
    <ul class="navbar-nav ms-auto d-flex align-items-center">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle p-1 p-md-2" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check() && auth()->user()->profile_photo_url): ?>
            <img class="img-xs rounded-circle" src="<?php echo e(auth()->user()->profile_photo_url); ?>" alt="Profile image" style="width: 32px; height: 32px;">
          <?php else: ?>
            <?php
              $fn = auth()->user()->fname ?? null;
              $ln = auth()->user()->lname ?? null;
              $name = auth()->user()->name ?? null;
              $initials = '';
              if($fn) $initials .= strtoupper(substr($fn,0,1));
              if($ln) $initials .= strtoupper(substr($ln,0,1));
              if(!$initials && $name) $initials = strtoupper(substr($name,0,1));
            ?>
            <div class="img-xs rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:12px;"><?php echo e($initials); ?></div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="min-width: 200px;">
          <div class="dropdown-header text-center py-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check() && auth()->user()->profile_photo_url): ?>
              <img class="img-md rounded-circle" src="<?php echo e(auth()->user()->profile_photo_url); ?>" alt="Profile image" style="width: 50px; height: 50px;">
            <?php else: ?>
              <div class="img-md rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width:50px;height:50px;font-size:16px;"><?php echo e($initials ?? (strtoupper(substr(auth()->user()->name ?? '',0,1)))); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <p class="mb-1 mt-2 font-weight-semibold" style="font-size: 14px;"><?php echo e(auth()->user()->role->name ?? 'User'); ?></p>
            <p class="font-weight-light text-muted mb-0" style="font-size: 12px;"><?php echo e(auth()->user()->email ?? ''); ?></p>
          </div>
          <a class="dropdown-item" href="<?php echo e(route('admin.profile.show')); ?>" style="font-size: 13px;">
            My Profile <i class="dropdown-item-icon mdi mdi-account"></i>
          </a>
          <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
            <?php echo csrf_field(); ?>
            <button class="dropdown-item" type="submit" style="font-size: 13px;">
              Sign Out <i class="dropdown-item-icon mdi mdi-power"></i>
            </button>
          </form>
        </div>
      </li>
    </ul>
  </div>
</nav>

<style>
  @media (max-width: 768px) {
    .navbar-brand-wrapper {
      min-width: auto !important;
      max-width: none !important;
      padding: 8px !important;
    }

    .navbar {
      padding: 8px 5px !important;
    }

    .navbar-menu-wrapper {
      padding: 0 5px !important;
    }

    .form-control {
      font-size: 12px;
    }
  }

  @media (max-width: 480px) {
    .navbar {
      padding: 6px 3px !important;
    }

    .navbar-brand-wrapper {
      padding: 6px !important;
    }

    .nav-item {
      font-size: 11px;
    }
  }
</style>

<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/components/navbar.blade.php ENDPATH**/ ?>