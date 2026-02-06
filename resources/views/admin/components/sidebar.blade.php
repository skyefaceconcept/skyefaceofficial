<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <style>
    .sidebar {
      background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
      color: #ecf0f1;
    }

    .nav {
      padding: 12px 0;
      list-style: none;
      margin: 0;
    }

    .nav-item {
      margin: 0;
      padding: 0;
    }

    .nav-profile {
      padding: 20px 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      margin-bottom: 12px;
    }

    .nav-profile .nav-link {
      padding: 0;
      color: inherit;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .nav-profile .nav-link:hover {
      color: #3498db;
    }

    .profile-image {
      position: relative;
      flex-shrink: 0;
    }

    .profile-image img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, 0.2);
      transition: border-color 0.3s ease;
    }

    .nav-profile .nav-link:hover .profile-image img {
      border-color: #3498db;
    }

    .dot-indicator {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      border: 2px solid #2c3e50;
    }

    .text-wrapper {
      flex: 1;
    }

    .profile-name {
      font-size: 13px;
      margin: 0;
      font-weight: 600;
      color: #ecf0f1;
    }

    .designation {
      font-size: 11px;
      margin: 2px 0 0 0;
      color: #bdc3c7;
      font-weight: 400;
    }

    .nav-category {
      padding: 12px 20px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      color: #95a5a6;
      letter-spacing: 0.5px;
      margin-top: 8px;
    }

    .nav-category:first-of-type {
      margin-top: 0;
    }

    .nav-link {
      padding: 12px 16px;
      color: #bdc3c7;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
      font-size: 13px;
      margin: 0 8px;
      border-radius: 0 4px 4px 0;
      cursor: pointer;
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.08);
      color: #3498db;
      border-left-color: #3498db;
    }

    .nav-link.active {
      background-color: rgba(52, 152, 219, 0.15);
      color: #3498db;
      border-left-color: #3498db;
      font-weight: 600;
    }

    .menu-icon {
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 20px;
      flex-shrink: 0;
    }

    .menu-title {
      flex: 1;
      font-size: 13px;
      font-weight: 500;
    }

    .menu-arrow {
      font-size: 16px;
      color: #7f8c8d;
      transition: transform 0.3s ease;
      margin-left: auto;
      display: flex;
      align-items: center;
    }

    .nav-link[aria-expanded="true"] .menu-arrow {
      transform: rotate(180deg);
      color: #3498db;
    }

    .sub-menu {
      padding: 8px 0;
      list-style: none;
      margin: 0;
      background-color: rgba(0, 0, 0, 0.2);
      display: flex;
      flex-direction: column;
    }

    .sub-menu .nav-item {
      margin: 0;
      width: 100%;
    }

    .sub-menu .nav-link {
      padding: 10px 16px;
      padding-left: 52px;
      font-size: 12px;
      color: #95a5a6;
      margin: 0;
      border-radius: 0;
      width: 100%;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .sub-menu .nav-link:hover {
      color: #3498db;
      background-color: rgba(52, 152, 219, 0.1);
      border-left-color: #3498db;
    }

    .sub-menu .nav-link.active {
      color: #3498db;
      background-color: rgba(52, 152, 219, 0.15);
      font-weight: 600;
    }

    .collapsible-item {
      position: relative;
      display: block;
      width: 100%;
    }

    .collapse {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.35s ease-in-out;
      display: block;
      width: 100%;
    }

    .sub-collapse {
      width: 100%;
      position: static;
      display: block;
    }

    .collapse.show {
      max-height: 3000px;
      overflow: visible;
    }

    .badge {
      font-size: 10px;
      padding: 3px 7px;
      border-radius: 10px;
      margin-left: auto;
      flex-shrink: 0;
      font-weight: 600;
      color: white;
    }

    .badge-danger {
      background-color: #e74c3c;
    }

    .badge-success {
      background-color: #27ae60;
    }

    .badge-warning {
      background-color: #f39c12;
    }

    .badge-info {
      background-color: #3498db;
    }

    /* Mobile responsive */
    @media (max-width: 992px) {
      .sidebar {
        position: fixed;
        left: 0;
        top: 60px;
        bottom: 0;
        width: 220px;
        z-index: 1020;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        max-height: none;
        overflow-y: auto;
      }

      .sidebar.show {
        transform: translateX(0);
        box-shadow: 2px 0 12px rgba(0, 0, 0, 0.2);
      }
    }

    @media (max-width: 768px) {
      .nav-profile {
        padding: 16px 10px;
      }

      .profile-name {
        font-size: 12px;
      }

      .designation {
        font-size: 10px;
      }

      .nav-link {
        padding: 10px 12px;
        gap: 10px;
        font-size: 12px;
        margin: 0 6px;
      }

      .menu-title {
        font-size: 12px;
      }

      .sub-menu .nav-link {
        padding: 8px 12px;
        padding-left: 45px;
        font-size: 11px;
      }
    }

    @media (max-width: 576px) {
      .sidebar {
        width: 100%;
        max-width: 250px;
      }

      .nav-profile {
        padding: 12px 8px;
      }

      .menu-title {
        font-size: 11px;
      }
    }
  </style>

  <ul class="nav">
    @php
      $user = auth()->user();
      $isSuper = $user && optional($user->role)->name === 'SuperAdmin';
    @endphp
    <li class="nav-item nav-profile">
      <a href="{{ route('admin.profile.show') }}" class="nav-link">
        <div class="profile-image">
          <img class="img-xs rounded-circle" src="{{ auth()->user()->profile_photo_url ?? asset('StarAdmin-Free-Bootstrap-Admin-Template-master/src/assets/images/faces/face8.jpg') }}" alt="profile image">
          <div class="dot-indicator bg-success"></div>
        </div>
        <div class="text-wrapper">
          <p class="profile-name">{{ auth()->user()->fname ?? 'User' }} {{ auth()->user()->lname ?? '' }}</p>
          <p class="designation">{{ auth()->user()->role->name ?? 'User' }}</p>
        </div>
      </a>
    </li>
    <li class="nav-item nav-category">Main Menu</li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="menu-icon mdi mdi-home-outline"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    @if($isSuper)
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.contact-tickets.index') }}">
        <i class="menu-icon mdi mdi-chat-multiple-outline"></i>
        <span class="menu-title">Contact Tickets</span>
        @php
          $openTickets = \App\Models\ContactTicket::where('status', 'open')->count();
        @endphp
        @if($openTickets > 0)
          <span class="badge badge-danger">{{ $openTickets }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.portfolio.index') }}">
        <i class="menu-icon mdi mdi-image-multiple-outline"></i>
        <span class="menu-title">Portfolio Shop</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.orders.index') }}">
        <i class="menu-icon mdi mdi-shopping-outline"></i>
        <span class="menu-title">Orders</span>
        @php
          $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        @endphp
        @if($pendingOrders > 0)
          <span class="badge badge-success">{{ $pendingOrders }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.quotes.index') }}">
        <i class="menu-icon mdi mdi-file-document-outline"></i>
        <span class="menu-title">Quotes</span>
        @php
          $newQuotes = \App\Models\Quote::where('status', 'new')->count();
        @endphp
        @if($newQuotes > 0)
          <span class="badge badge-warning">{{ $newQuotes }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.repairs.index') }}">
        <i class="menu-icon mdi mdi-wrench-outline"></i>
        <span class="menu-title">Device Repairs</span>
        @php
          $pendingRepairs = \App\Models\Repair::where('status', '!=', 'completed')->count();
        @endphp
        @if($pendingRepairs > 0)
          <span class="badge badge-warning">{{ $pendingRepairs }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item collapsible-item">
      <a class="nav-link" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#repair-settings" aria-expanded="false" aria-controls="repair-settings">
        <i class="menu-icon mdi mdi-toolbox-outline"></i>
        <span class="menu-title">Repair Settings</span>
        <i class="menu-arrow mdi mdi-chevron-down"></i>
      </a>
      <div class="collapse sub-collapse" id="repair-settings">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.repairs.pricing.index') }}">
              <i class="menu-icon mdi mdi-currency-usd"></i>
              <span>Repair Pricing</span>
            </a>
          </li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.payments.index') }}">
        <i class="menu-icon mdi mdi-credit-card-outline"></i>
        <span class="menu-title">Payments</span>
        @php
          $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();
        @endphp
        @if($pendingPayments > 0)
          <span class="badge badge-info">{{ $pendingPayments }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.licenses.index') }}">
        <i class="menu-icon mdi mdi-license-outline"></i>
        <span class="menu-title">Licenses</span>
        @php
          $expiringLicenses = \App\Models\License::whereBetween('expiry_date', [now(), now()->addDays(7)])->count();
        @endphp
        @if($expiringLicenses > 0)
          <span class="badge badge-warning">{{ $expiringLicenses }}</span>
        @endif
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.seo.index') }}">
        <i class="menu-icon mdi mdi-search-web"></i>
        <span class="menu-title">SEO Management</span>
      </a>
    </li>

    @permission(['view_page_impressions','view_impression_details','view_impression_statistics'])
    <li class="nav-item collapsible-item">
      <a class="nav-link" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#page-impressions" aria-expanded="false" aria-controls="page-impressions">
        <i class="menu-icon mdi mdi-chart-line-variant"></i>
        <span class="menu-title">Page Impressions</span>
        <i class="menu-arrow mdi mdi-chevron-down"></i>
      </a>
      <div class="collapse sub-collapse" id="page-impressions">
        <ul class="nav flex-column sub-menu">
          @permission('view_page_impressions')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.analytics.page-impressions.index') }}">
              <i class="menu-icon mdi mdi-chart-bar"></i>
              <span>Dashboard</span>
            </a>
          </li>
          @endpermission

          @permission('view_impression_statistics')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.analytics.page-impressions.stats') }}">
              <i class="menu-icon mdi mdi-chart-box-multiple-outline"></i>
              <span>Statistics</span>
            </a>
          </li>
          @endpermission

          @permission('view_device_analytics')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.analytics.page-impressions.devices') }}">
              <i class="menu-icon mdi mdi-devices"></i>
              <span>Device Analytics</span>
            </a>
          </li>
          @endpermission

          @permission('view_browser_analytics')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.analytics.page-impressions.browsers') }}">
              <i class="menu-icon mdi mdi-web"></i>
              <span>Browser Analytics</span>
            </a>
          </li>
          @endpermission

          @permission('view_os_analytics')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.analytics.page-impressions.os') }}">
              <i class="menu-icon mdi mdi-microsoft-windows"></i>
              <span>OS Analytics</span>
            </a>
          </li>
          @endpermission

          @permission('view_visitor_analytics')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.analytics.page-impressions.visitors') }}">
              <i class="menu-icon mdi mdi-account-multiple-outline"></i>
              <span>Visitor Analytics</span>
            </a>
          </li>
          @endpermission

          @permission('export_impressions_data')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.analytics.page-impressions.export') }}">
              <i class="menu-icon mdi mdi-file-export-outline"></i>
              <span>Export Data</span>
            </a>
          </li>
          @endpermission
        </ul>
      </div>
    </li>
    @endpermission

    @endif

    @permission(['view_users','view_roles','view_permissions'])
    <li class="nav-item collapsible-item">
      <a class="nav-link" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#user-management" aria-expanded="false" aria-controls="user-management">
        <i class="menu-icon mdi mdi-account-tie-outline"></i>
        <span class="menu-title">User Management</span>
        <i class="menu-arrow mdi mdi-chevron-down"></i>
      </a>
      <div class="collapse sub-collapse" id="user-management">
        <ul class="nav flex-column sub-menu">
          @permission('view_users')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
              <i class="menu-icon mdi mdi-account-outline"></i>
              <span>Users</span>
            </a>
          </li>
          @endpermission

          @permission('view_roles')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.roles.index') }}">
              <i class="menu-icon mdi mdi-shield-account-outline"></i>
              <span>Roles</span>
            </a>
          </li>
          @endpermission

          @permission('view_permissions')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.permissions.index') }}">
              <i class="menu-icon mdi mdi-lock-outline"></i>
              <span>Permissions</span>
            </a>
          </li>
          @endpermission
        </ul>
      </div>
    </li>
    @endpermission

    @permission(['view_settings','edit_settings','backup_system','restore_system'])
    <li class="nav-item collapsible-item">
      <a class="nav-link" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#settings" aria-expanded="false" aria-controls="settings">
        <i class="menu-icon mdi mdi-cog-outline"></i>
        <span class="menu-title">Settings</span>
        <i class="menu-arrow mdi mdi-chevron-down"></i>
      </a>
      <div class="collapse sub-collapse" id="settings">
        <ul class="nav flex-column sub-menu">
          @permission('view_settings')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.index') }}">
              <i class="menu-icon mdi mdi-wrench-outline"></i>
              <span>System Settings</span>
            </a>
          </li>
          @endpermission

          @permission('view_settings')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.settings.company_branding') }}">
                <i class="menu-icon mdi mdi-palette-outline"></i>
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
              <i class="menu-icon mdi mdi-email-outline"></i>
              <span>Email Deliverability</span>
            </a>
          </li>
          @endpermission

          @permission('view_settings')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.migrations') }}">
              <i class="menu-icon mdi mdi-database-outline"></i>
              <span>Migrations</span>
            </a>
          </li>
          @endpermission
        </ul>
      </div>
    </li>
    @endpermission
  </ul>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Handle collapse toggle for all menu items with data-bs-toggle="collapse"
      const collapseButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');

      collapseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();

          const target = this.getAttribute('data-bs-target');
          if (!target) return;

          const collapseEl = document.querySelector(target);
          if (!collapseEl) return;

          const isExpanded = this.getAttribute('aria-expanded') === 'true';

          // Toggle the collapse
          if (isExpanded) {
            collapseEl.classList.remove('show');
            this.setAttribute('aria-expanded', 'false');
          } else {
            collapseEl.classList.add('show');
            this.setAttribute('aria-expanded', 'true');
          }
        });
      });
    });
  </script>
</nav>
