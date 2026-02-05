<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    @php
      $user = auth()->user();
      $isSuper = $user && optional($user->role)->name === 'SuperAdmin';
    @endphp
    <li class="nav-item nav-profile">
      <a href="{{ route('admin.profile.show') }}" class="nav-link">
        <div class="profile-image">
          <img class="img-xs rounded-circle" src="{{ auth()->user()->profile_photo_url ?? asset('StarAdmin-Free-Bootstrap-Admin-Template-master/src/assets/images/faces/face8.jpg') }}" alt="profile image" style="width: 40px; height: 40px;">
          <div class="dot-indicator bg-success"></div>
        </div>
        <div class="text-wrapper">
          <p class="profile-name" style="font-size: 13px; margin-bottom: 2px;">{{ auth()->user()->fname ?? 'User' }} {{ auth()->user()->lname ?? 'User' }}</p>
          <p class="designation" style="font-size: 11px;">{{ auth()->user()->role->name ?? 'User' }}</p>
        </div>
      </a>
    </li>
    <li class="nav-item nav-category" style="font-size: 12px;">Main Menu</li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="menu-icon mdi mdi-home"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    @if($isSuper)
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.contact-tickets.index') }}">
        <i class="menu-icon mdi mdi-email-multiple"></i>
        <span class="menu-title">Contact Tickets</span>
        @php
          $openTickets = \App\Models\ContactTicket::where('status', 'open')->count();
        @endphp
        @if($openTickets > 0)
          <span class="badge badge-danger badge-pill ms-2" style="font-size: 10px;">{{ $openTickets }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.portfolio.index') }}">
        <i class="menu-icon mdi mdi-shopping"></i>
        <span class="menu-title">Portfolio Shop</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.orders.index') }}">
        <i class="menu-icon mdi mdi-receipt"></i>
        <span class="menu-title">Orders</span>
        @php
          $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        @endphp
        @if($pendingOrders > 0)
          <span class="badge badge-success badge-pill ms-2" style="font-size: 10px;">{{ $pendingOrders }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.quotes.index') }}">
        <i class="menu-icon mdi mdi-file-document-box"></i>
        <span class="menu-title">Quotes</span>
        @php
          $newQuotes = \App\Models\Quote::where('status', 'new')->count();
        @endphp
        @if($newQuotes > 0)
          <span class="badge badge-warning badge-pill ml-2">{{ $newQuotes }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.repairs.index') }}">
        <i class="menu-icon mdi mdi-wrench"></i>
        <span class="menu-title">Device Repairs</span>
        @php
          $pendingRepairs = \App\Models\Repair::where('status', '!=', 'completed')->count();
        @endphp
        @if($pendingRepairs > 0)
          <span class="badge badge-warning badge-pill ml-2">{{ $pendingRepairs }}</span>
        @endif
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
            <a class="nav-link" href="{{ route('admin.repairs.pricing.index') }}">
              <i class="menu-icon mdi mdi-cash"></i>
              <span>Repair Pricing</span>
            </a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.payments.index') }}">
        <i class="menu-icon mdi mdi-credit-card"></i>
        <span class="menu-title">Payments</span>
        @php
          $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();
        @endphp
        @if($pendingPayments > 0)
          <span class="badge badge-info badge-pill ml-2">{{ $pendingPayments }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.licenses.index') }}">
        <i class="menu-icon mdi mdi-certificate"></i>
        <span class="menu-title">Licenses</span>
        @php
          $expiringLicenses = \App\Models\License::whereBetween('expiry_date', [now(), now()->addDays(7)])->count();
        @endphp
        @if($expiringLicenses > 0)
          <span class="badge badge-warning badge-pill ml-2">{{ $expiringLicenses }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.seo.index') }}">
        <i class="menu-icon mdi mdi-web"></i>
        <span class="menu-title">SEO Management</span>
      </a>
    </li>
    @endif

    @permission(['view_users','view_roles','view_permissions'])
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#user-management" aria-expanded="false" aria-controls="user-management">
        <i class="menu-icon mdi mdi-account-multiple"></i>
        <span class="menu-title">User Management</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="user-management">
        <ul class="nav flex-column sub-menu">
          @permission('view_users')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
              <i class="menu-icon mdi mdi-account"></i>
              <span>Users</span>
            </a>
          </li>
          @endpermission

          @permission('view_roles')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.roles.index') }}">
              <i class="menu-icon mdi mdi-shield-account"></i>
              <span>Roles</span>
            </a>
          </li>
          @endpermission

          @permission('view_permissions')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.permissions.index') }}">
              <i class="menu-icon mdi mdi-lock-multiple"></i>
              <span>Permissions</span>
            </a>
          </li>
          @endpermission
        </ul>
      </div>
    </li>
    @endpermission

    @permission(['view_settings','edit_settings','backup_system','restore_system'])
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
        <i class="menu-icon mdi mdi-settings"></i>
        <span class="menu-title">Settings</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="settings">
        <ul class="nav flex-column sub-menu">
          @permission('view_settings')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.index') }}">
              <i class="menu-icon mdi mdi-cog"></i>
              <span>System Settings</span>
            </a>
          </li>
          @endpermission

          @permission('view_settings')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.settings.company_branding') }}">
                <i class="menu-icon mdi mdi-palette"></i>
                <span>Company Branding</span>
              </a>
            </li>
            @endpermission

          @permission('backup_system')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.backup') }}">
              <i class="menu-icon mdi mdi-backup-restore"></i>
              <span>Backup & Restore</span>
            </a>
          </li>
          @endpermission
          @permission('view_settings')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.email_deliverability') }}">
              <i class="menu-icon mdi mdi-email-alert"></i>
              <span>Email Deliverability</span>
            </a>
          </li>
          @endpermission

          {{-- @permission('view_settings')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.payment_processors') }}">
              <i class="menu-icon mdi mdi-credit-card"></i>
              <span>Payment Processors</span>
            </a>
          </li>
          @endpermission --}}

          @permission('view_settings')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.migrations') }}">
              <i class="menu-icon mdi mdi-database"></i>
              <span>Migrations</span>
            </a>
          </li>
          @endpermission
        </ul>
      </div>
    </li>
    @endpermission
  </ul>
</nav>
