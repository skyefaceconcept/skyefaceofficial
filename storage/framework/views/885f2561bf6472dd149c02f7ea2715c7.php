<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <?php
      $user = auth()->user();
      $isSuper = $user && optional($user->role)->name === 'SuperAdmin';
    ?>
    <li class="nav-item nav-profile">
      <a href="<?php echo e(route('admin.profile.show')); ?>" class="nav-link">
        <div class="profile-image">
          <img class="img-xs rounded-circle" src="<?php echo e(auth()->user()->profile_photo_url ?? asset('StarAdmin-Free-Bootstrap-Admin-Template-master/src/assets/images/faces/face8.jpg')); ?>" alt="profile image" style="width: 40px; height: 40px;">
          <div class="dot-indicator bg-success"></div>
        </div>
        <div class="text-wrapper">
          <p class="profile-name" style="font-size: 13px; margin-bottom: 2px;"><?php echo e(auth()->user()->fname ?? 'User'); ?> <?php echo e(auth()->user()->lname ?? 'User'); ?></p>
          <p class="designation" style="font-size: 11px;"><?php echo e(auth()->user()->role->name ?? 'User'); ?></p>
        </div>
      </a>
    </li>
    <li class="nav-item nav-category" style="font-size: 12px;">Main Menu</li>

    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">
        <i class="menu-icon mdi mdi-home"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSuper): ?>
    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(route('admin.contact-tickets.index')); ?>">
        <i class="menu-icon mdi mdi-email-multiple"></i>
        <span class="menu-title">Contact Tickets</span>
        <?php
          $openTickets = \App\Models\ContactTicket::where('status', 'open')->count();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($openTickets > 0): ?>
          <span class="badge badge-danger badge-pill ms-2" style="font-size: 10px;"><?php echo e($openTickets); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(route('admin.portfolio.index')); ?>">
        <i class="menu-icon mdi mdi-shopping"></i>
        <span class="menu-title">Portfolio Shop</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(route('admin.orders.index')); ?>">
        <i class="menu-icon mdi mdi-receipt"></i>
        <span class="menu-title">Orders</span>
        <?php
          $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingOrders > 0): ?>
          <span class="badge badge-success badge-pill ms-2" style="font-size: 10px;"><?php echo e($pendingOrders); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(route('admin.quotes.index')); ?>">
        <i class="menu-icon mdi mdi-file-document-box"></i>
        <span class="menu-title">Quotes</span>
        <?php
          $newQuotes = \App\Models\Quote::where('status', 'new')->count();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($newQuotes > 0): ?>
          <span class="badge badge-warning badge-pill ml-2"><?php echo e($newQuotes); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(route('admin.repairs.index')); ?>">
        <i class="menu-icon mdi mdi-wrench"></i>
        <span class="menu-title">Device Repairs</span>
        <?php
          $pendingRepairs = \App\Models\Repair::where('status', '!=', 'completed')->count();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingRepairs > 0): ?>
          <span class="badge badge-warning badge-pill ml-2"><?php echo e($pendingRepairs); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#repair-settings" aria-expanded="false" aria-controls="repair-settings">
        <i class="menu-icon mdi mdi-cog"></i>
        <span class="menu-title">Repair Settings</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="repair-settings">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.repairs.pricing.index')); ?>">
              <i class="menu-icon mdi mdi-cash"></i>
              <span>Repair Pricing</span>
            </a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(route('admin.payments.index')); ?>">
        <i class="menu-icon mdi mdi-credit-card"></i>
        <span class="menu-title">Payments</span>
        <?php
          $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingPayments > 0): ?>
          <span class="badge badge-info badge-pill ml-2"><?php echo e($pendingPayments); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="<?php echo e(route('admin.licenses.index')); ?>">
        <i class="menu-icon mdi mdi-certificate"></i>
        <span class="menu-title">Licenses</span>
        <?php
          $expiringLicenses = \App\Models\License::whereBetween('expiry_date', [now(), now()->addDays(7)])->count();
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiringLicenses > 0): ?>
          <span class="badge badge-warning badge-pill ml-2"><?php echo e($expiringLicenses); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </a>
    </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', ['view_users','view_roles','view_permissions'])): ?>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#user-management" aria-expanded="false" aria-controls="user-management">
        <i class="menu-icon mdi mdi-account-multiple"></i>
        <span class="menu-title">User Management</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="user-management">
        <ul class="nav flex-column sub-menu">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_users')): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.users.index')); ?>">
              <i class="menu-icon mdi mdi-account"></i>
              <span>Users</span>
            </a>
          </li>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_roles')): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.roles.index')); ?>">
              <i class="menu-icon mdi mdi-shield-account"></i>
              <span>Roles</span>
            </a>
          </li>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_permissions')): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.permissions.index')); ?>">
              <i class="menu-icon mdi mdi-lock-multiple"></i>
              <span>Permissions</span>
            </a>
          </li>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
      </div>
    </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', ['view_settings','edit_settings','backup_system','restore_system'])): ?>
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
        <i class="menu-icon mdi mdi-settings"></i>
        <span class="menu-title">Settings</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="settings">
        <ul class="nav flex-column sub-menu">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_settings')): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.settings.index')); ?>">
              <i class="menu-icon mdi mdi-cog"></i>
              <span>System Settings</span>
            </a>
          </li>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_settings')): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('admin.settings.company_branding')); ?>">
                <i class="menu-icon mdi mdi-palette"></i>
                <span>Company Branding</span>
              </a>
            </li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'backup_system')): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.settings.backup')); ?>">
              <i class="menu-icon mdi mdi-backup-restore"></i>
              <span>Backup & Restore</span>
            </a>
          </li>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_settings')): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.settings.email_deliverability')); ?>">
              <i class="menu-icon mdi mdi-email-alert"></i>
              <span>Email Deliverability</span>
            </a>
          </li>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('permission', 'view_settings')): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('admin.settings.migrations')); ?>">
              <i class="menu-icon mdi mdi-database"></i>
              <span>Migrations</span>
            </a>
          </li>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
      </div>
    </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </ul>
</nav>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/admin/components/sidebar.blade.php ENDPATH**/ ?>