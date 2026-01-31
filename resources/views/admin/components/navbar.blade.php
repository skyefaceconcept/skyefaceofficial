<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
    @php
        $branding = \App\Models\CompanySetting::first();
    @endphp
    <a class="navbar-brand brand-logo d-flex align-items-center" href="{{ route('admin.dashboard') }}" style="gap: 0px;">
      @if ($branding && $branding->logo)
        <img src="{{ \App\Helpers\CompanyHelper::logoWhite() }}" alt="Company Logo" style="max-height: 40px; object-fit: contain; margin-right: -8px; z-index: 2;" />
      @endif
      @if ($branding && $branding->name_logo)
        <img src="{{ \App\Helpers\CompanyHelper::logo() }}" alt="Logo Name" style="max-height: 40px; object-fit: contain;" />
      @endif
      @if ((!$branding || !$branding->logo) && (!$branding || !$branding->name_logo))
        <img src="{{ asset('StarAdmin-Free-Bootstrap-Admin-Template-master/src/assets/images/logo.svg') }}" alt="logo" />
      @endif
    </a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('StarAdmin-Free-Bootstrap-Admin-Template-master/src/assets/images/logo-mini.svg') }}" alt="logo" />
    </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center">
    <button class="navbar-toggler navbar-toggler-left d-none d-lg-block align-self-center" type="button" id="sidebarToggle" style="margin-left: 15px;">
      <span class="mdi mdi-menu" style="font-size: 24px;"></span>
    </button>
    <ul class="navbar-nav">
      <li class="nav-item font-weight-semibold d-none d-lg-block">Support: {{ config('app.support_phone', '(+234) 806 212 1410') }}</li>
    </ul>
    <form class="ml-auto search-form d-none d-md-block" action="#">
      <div class="form-group">
        <input type="search" class="form-control" placeholder="Search Here">
      </div>
    </form>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown d-none d-xl-inline-block user-dropdown">
        <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
          @if(auth()->check() && auth()->user()->profile_photo_url)
            <img class="img-xs rounded-circle" src="{{ auth()->user()->profile_photo_url }}" alt="Profile image">
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
            <div class="img-xs rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:32px;height:32px;">{{ $initials }}</div>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <div class="dropdown-header text-center">
            @if(auth()->check() && auth()->user()->profile_photo_url)
              <img class="img-md rounded-circle" src="{{ auth()->user()->profile_photo_url }}" alt="Profile image">
            @else
              <div class="img-md rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" style="width:64px;height:64px;">{{ $initials ?? (strtoupper(substr(auth()->user()->name ?? '',0,1))) }}</div>
            @endif
            <p class="mb-1 mt-3 font-weight-semibold">{{ auth()->user()->role->name ?? 'User' }}</p>
            <p class="font-weight-light text-muted mb-0">{{ auth()->user()->email ?? '' }}</p>
            {{-- Debug: show profile photo path/url for troubleshooting --}}

          </div>
          <a class="dropdown-item" href="{{ route('admin.profile.show') }}">My Profile <i class="dropdown-item-icon mdi mdi-account"></i></a>
          {{-- <a class="dropdown-item" href="{{  }}"> Settings <i class="dropdown-item-icon mdi mdi-cog"></i></a> --}}
          <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button class="dropdown-item" type="submit"> Sign Out <i class="dropdown-item-icon mdi mdi-power"></i></button>
          </form>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>

