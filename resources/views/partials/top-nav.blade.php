<!-- Top Navbar -->
<div class="top-menubar">
  <div class="topmenu">
    <div class="container">
      <div class="row">
        <div class="col-md-7">
          <ul class="list-inline top-contacts">
            <li>
              <i class="fa fa-envelope"></i> Email: <a href="mailto:{{ config('company.support_email') }}">{{ config('company.support_email') }}</a>
            </li>
            <li>
              <i class="fa fa-phone"></i> Hotline: {{ config('company.support_phone') }}
            </li>
          </ul>
        </div>
        <div class="col-md-5">
          <ul class="list-inline top-data">
            <li><a href="https://www.facebook.com" target="_empty"><i class="fa top-social fa-facebook"></i></a></li>
            {{-- <li><a href="https://twitter.com" target="_empty"><i class="fa top-social fa-twitter"></i></a></li> --}}
            <li><a href="https://plus.google.com" target="_empty"><i class="fa top-social fa-google-plus"></i></a></li>
            @auth
              <li><a href="{{ route('dashboard') }}" class="log-top" target="_blank">Dashboard</a></li>
            @else
              <li><a href="{{ route('login') }}" class="log-top">Login</a></li>
            @endauth
            <li style="margin-left: 15px;">
              <form id="topRepairSearchForm" style="display: flex; gap: 5px; align-items: center;">
                @csrf
                <input type="text" id="topRepairInvoiceInput" name="invoice_number" class="form-control" placeholder="Search repair invoice" style="width: 180px; padding: 6px 10px; font-size: 12px; border-radius: 4px; border: 1px solid #ddd; height: 32px;">
                <button type="button" id="topRepairSearchBtn" class="btn" style="background: #28a745; color: white; border: none; border-radius: 4px; padding: 6px 12px; font-size: 12px; font-weight: 600; height: 32px; white-space: nowrap;">
                  <i class="fa fa-search"></i>
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
