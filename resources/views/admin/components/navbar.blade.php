<nav class="navbar default-layout d-flex flex-row align-items-center" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); border-bottom: 1px solid rgba(255,255,255,0.1); box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-between" style="width: 260px; padding: 0 15px; border-right: 1px solid rgba(255,255,255,0.1); height: 60px;">
    @php
        $branding = \App\Models\CompanySetting::first();
    @endphp
    <a class="navbar-brand brand-logo d-flex align-items-center" href="{{ route('admin.dashboard') }}" style="gap: 8px; display: none;">
      @if ($branding && $branding->logo)
        <img src="{{ \App\Helpers\CompanyHelper::logoWhite() }}" alt="Company Logo" style="max-height: 30px; object-fit: contain;" />
      @endif
      @if ($branding && $branding->name_logo)
        <img src="{{ \App\Helpers\CompanyHelper::logo() }}" alt="Logo Name" style="max-height: 28px; object-fit: contain;" />
      @endif
    </a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}" title="{{ config('app.name') }}" style="padding: 6px 10px; border-radius: 6px; transition: all 0.3s ease;">
      <img src="{{ asset('StarAdmin/src/assets/images/logo-mini.svg') }}" alt="logo" style="max-height: 36px;" />
    </a>

    <!-- Desktop: sidebar toggle button -->
    <button class="navbar-toggler btn btn-link p-0 d-none d-lg-block" type="button" id="sidebarToggle" style="color: #ecf0f1; font-size: 20px; border: none; background: none; transition: all 0.3s ease;" title="Toggle Sidebar">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>

  <div class="navbar-menu-wrapper d-flex align-items-center flex-grow-1 px-3 px-md-4" style="gap: 15px;">
    <!-- Support info - hidden on mobile -->
    <ul class="navbar-nav d-none d-lg-flex align-items-center">
      <li class="nav-item" style="font-size: 13px; color: #bdc3c7; font-weight: 500;">
        <i class="mdi mdi-phone" style="color: #3498db; margin-right: 6px;"></i>
        Support: {{ config('app.support_phone', '(+234) 806 212 1410') }}
      </li>
    </ul>

    <!-- Search - hidden on mobile/tablet, shown on desktop -->
    <form class="search-form d-none d-lg-flex flex-grow-1 mx-4" action="#" style="max-width: 350px;">
      <div class="form-group mb-0 w-100">
        <div class="input-group input-group-sm">
          <input type="search" class="form-control" placeholder="Search pages, users..." style="font-size: 13px; border-radius: 4px 0 0 4px; background: rgba(255,255,255,0.95); border: 1px solid rgba(255,255,255,0.2);">
          <button class="btn" type="submit" style="border-radius: 0 4px 4px 0; background: rgba(52, 152, 219, 0.9); color: white; border: 1px solid rgba(52, 152, 219, 0.9);">
            <i class="mdi mdi-magnify"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- User dropdown - responsive -->
    <ul class="navbar-nav ms-auto d-flex align-items-center gap-3">
      <!-- Notifications -->
      <li class="nav-item dropdown d-none d-md-flex">
        <a class="nav-link p-0" href="#" role="button" data-toggle="dropdown" aria-expanded="false" title="Notifications" style="color: #ecf0f1; font-size: 20px; transition: all 0.3s ease;">
          <i class="mdi mdi-bell-outline"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge badge-danger badge-sm" style="margin-left: -8px;">3</span>
        </a>
      </li>

      <!-- Messages -->
      <li class="nav-item dropdown d-none d-md-flex">
        <a class="nav-link p-0" href="#" role="button" data-toggle="dropdown" aria-expanded="false" title="Messages" style="color: #ecf0f1; font-size: 20px; transition: all 0.3s ease;">
          <i class="mdi mdi-email-outline"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge badge-primary badge-sm" style="margin-left: -8px;">5</span>
        </a>
      </li>

      <!-- User Profile Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle p-0 d-flex align-items-center gap-2" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false" style="color: #ecf0f1; transition: all 0.3s ease;">
          @if(auth()->check() && auth()->user()->profile_photo_url)
            <img class="img-xs rounded-circle" src="{{ auth()->user()->profile_photo_url }}" alt="Profile image" style="width: 36px; height: 36px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
          @else
            @php
              $fn = auth()->user()->fname ?? null;
              $ln = auth()->user()->lname ?? null;
              $name = auth()->user()->name ?? null;
              $initials = '';
              if($fn) $initials .= strtoupper(substr($fn,0,1));
              if($ln) $initials .= strtoupper(substr($ln,0,1));
              if(!$initials && $name) $initials = strtoupper(substr($name,0,1));
            @endphp
            <div class="img-xs rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 13px; font-weight: 600; border: 2px solid rgba(255,255,255,0.3);">{{ $initials }}</div>
          @endif
          <span class="d-none d-md-inline" style="font-size: 13px; color: #ecf0f1; font-weight: 500;">{{ auth()->user()->fname ?? 'User' }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="min-width: 220px; margin-top: 8px; background: #f8f9fa; border: 1px solid #e3e6f0; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
          <div class="dropdown-header text-center py-3" style="border-bottom: 1px solid #e3e6f0; background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; border-radius: 6px 6px 0 0;">
            @if(auth()->check() && auth()->user()->profile_photo_url)
              <img class="rounded-circle mb-2" src="{{ auth()->user()->profile_photo_url }}" alt="Profile image" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid rgba(255,255,255,0.3);">
            @else
              <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px; font-size: 20px; font-weight: 600;">{{ $initials ?? (strtoupper(substr(auth()->user()->name ?? '',0,1))) }}</div>
            @endif
            <p class="mb-1 mt-2 font-weight-semibold" style="font-size: 14px; color: white;">{{ auth()->user()->role->name ?? 'User' }}</p>
            <p class="font-weight-light text-white" style="font-size: 12px; opacity: 0.9;">{{ auth()->user()->email ?? '' }}</p>
          </div>
          <a class="dropdown-item py-2" href="{{ route('admin.profile.show') }}" style="font-size: 13px; color: #2c3e50; border-left: 3px solid transparent; transition: all 0.2s ease;">
            <i class="mdi mdi-account" style="color: #3498db;"></i> &nbsp;My Profile
          </a>
          <a class="dropdown-item py-2" href="#" style="font-size: 13px; color: #2c3e50; border-left: 3px solid transparent; transition: all 0.2s ease;">
            <i class="mdi mdi-cog" style="color: #95a5a6;"></i> &nbsp;Settings
          </a>
          <div class="dropdown-divider my-1"></div>
          <form method="POST" action="{{ route('logout') }}" style="display: contents;">
            @csrf
            <button class="dropdown-item py-2" type="submit" style="font-size: 13px; color: #e74c3c; border-left: 3px solid transparent; transition: all 0.2s ease;">
              <i class="mdi mdi-logout" style="color: #e74c3c;"></i> &nbsp;Sign Out
            </button>
          </form>
        </div>
      </li>
    </ul>
  </div>
</nav>

<style>
  .navbar {
    padding: 0 !important;
    min-height: 60px;
    border: none;
  }

  .navbar-brand-wrapper {
    background: none;
    flex-shrink: 0;
  }

  .navbar-toggler {
    transition: all 0.3s ease;
  }

  .navbar-toggler:hover {
    color: #3498db !important;
    transform: scale(1.1);
  }

  .navbar-menu-wrapper {
    gap: 15px;
  }

  .form-control {
    border: 1px solid rgba(255,255,255,0.2) !important;
    font-size: 13px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .form-control:focus {
    border-color: #3498db !important;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    outline: none;
  }

  .dropdown-menu {
    border: 1px solid #e3e6f0;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .dropdown-item {
    color: #2c3e50;
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
    font-size: 13px;
  }

  .dropdown-item:hover {
    background-color: #ecf0f1;
    border-left-color: #3498db;
    color: #2c3e50;
  }

  .nav-link {
    transition: all 0.3s ease;
  }

  .nav-link:hover {
    color: #3498db !important;
  }

  .nav-item {
    display: flex;
    align-items: center;
  }

  /* Mobile responsive */
  @media (max-width: 992px) {
    .navbar-brand-wrapper {
      width: auto;
      padding: 0 10px !important;
    }

    .navbar-menu-wrapper {
      padding: 0 12px !important;
    }

    .search-form {
      display: none !important;
    }
  }

  @media (max-width: 768px) {
    .navbar {
      min-height: 55px;
      padding: 6px 0;
    }

    .navbar-brand-wrapper {
      width: auto;
      padding: 0 8px !important;
    }

    .navbar-menu-wrapper {
      padding: 0 8px !important;
      gap: 8px !important;
    }

    .navbar-toggler {
      font-size: 18px;
    }

    .dropdown-menu {
      font-size: 12px;
    }
  }

  @media (max-width: 576px) {
    .navbar {
      min-height: 50px;
      padding: 4px 0;
    }

    .navbar-brand-wrapper {
      padding: 0 6px !important;
    }

    .navbar-menu-wrapper {
      padding: 0 6px !important;
      gap: 4px !important;
    }

    .search-form {
      display: none !important;
    }

    .d-none.d-md-flex {
      display: none !important;
    }
  }

  /* Custom scrollbar for dropdown */
  .dropdown-menu {
    max-height: 500px;
    overflow-y: auto;
  }

  .dropdown-menu::-webkit-scrollbar {
    width: 6px;
  }

  .dropdown-menu::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .dropdown-menu::-webkit-scrollbar-thumb {
    background: #3498db;
    border-radius: 3px;
  }

  .dropdown-menu::-webkit-scrollbar-thumb:hover {
    background: #2980b9;
  }
</style>

