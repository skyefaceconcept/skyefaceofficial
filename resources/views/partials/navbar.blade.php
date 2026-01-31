<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav" data-toggle="affix">
  <div class="container">
    <a class="navbar-brand smooth-scroll" href="{{ url('/') }}">
      <img src="{{ \App\Helpers\CompanyHelper::logoWhite() }}" alt="{{ config('company.name') }} white logo" style="max-height: 40px;">
      <img src="{{ \App\Helpers\CompanyHelper::logo() }}" alt="{{ config('company.name') }} logo" style="max-height: 40px; margin-right:8px;">
    </a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link smooth-scroll" href="{{ route('home') }}">Home</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle smooth-scroll" href="#" id="navbarDropdownAbout" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">About</a>
          <div class="dropdown-menu dropdown-cust" aria-labelledby="navbarDropdownAbout">
            <a class="dropdown-item" href="{{ route('about') }}">About Us</a>
            <a class="dropdown-item" href="{{ asset('buzbox/careers.html') }}">Career Opportunity</a>
            <a class="dropdown-item" href="{{ asset('buzbox/team.html') }}">Meet Our Team</a>
            <a class="dropdown-item" href="{{ asset('buzbox/faq.html') }}">FAQ</a>
            <a class="dropdown-item" href="{{ asset('buzbox/testimonials.html') }}">Testimonials</a>
          </div>
        </li>

        <li class="nav-item"><a class="nav-link smooth-scroll" href="{{ route('services') }}">Services</a></li>

        <li class="nav-item"><a class="nav-link smooth-scroll" href="#" data-toggle="modal" data-target="#repairBookingModal" style="color: #28a745; font-weight: 600;"><i class="fa fa-wrench mr-1"></i>Quick Repair Booking</a></li>

        {{-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle smooth-scroll" href="#" id="navbarDropdownPages" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
          <div class="dropdown-menu dropdown-cust" aria-labelledby="navbarDropdownPages">
            <a class="dropdown-item" href="{{ asset('buzbox/news-list.html') }}">News list layouts</a>
            <a class="dropdown-item" href="{{ asset('buzbox/news-grid.html') }}">News grid layouts</a>
            <a class="dropdown-item" href="{{ asset('buzbox/news-details.html') }}">News Details</a>
            <a class="dropdown-item" href="{{ asset('buzbox/project.html') }}">Project</a>
            <a class="dropdown-item" href="{{ asset('buzbox/comming-soon.html') }}">Coming Soon</a>
            <a class="dropdown-item" href="{{ asset('buzbox/pricing.html') }}">Pricing Tables</a>
          </div>
        </li> --}}

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle smooth-scroll" href="#" id="navbarDropdownShop" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
          <div class="dropdown-menu dropdown-cust mega-menu" aria-labelledby="navbarDropdownShop">
            @php
              $branding = \App\Models\Branding::first();
              $showOfferImage = $branding ? $branding->show_menu_offer_image : true;
            @endphp
            <div class="row">
              <div class="col-md-{{ $showOfferImage ? '5' : '12' }}">
                <a class="dropdown-item" href="{{ route('shop.index') }}">Shop Detail</a>
                <a class="dropdown-item" href="{{ asset('buzbox/single-product.html') }}">Single Product</a>
                <a class="dropdown-item" href="{{ route('cart.show') }}">Cart</a>
                <a class="dropdown-item" href="{{ route('cart.checkout') }}">Checkout</a>
                <a class="dropdown-item" href="{{ route('dashboard') }}">My Account</a>
              </div>
              @if($showOfferImage)
                <div class="col-md-7 mega-menu-img m-auto text-center pl-0">
                  <a href="#"><img src="{{ asset('buzbox/img/offer_icon.png') }}" alt="" class="img-fluid"></a>
                </div>
              @endif
            </div>
          </div>
        </li>

        <li class="nav-item"><a class="nav-link smooth-scroll" href="{{ route('contact.show') }}">Contact Us</a></li>

        <li>
          <i class="search fa fa-search search-btn"></i>
          <div class="search-open">
            <div class="input-group animated fadeInUp">
              <input type="text" class="form-control" placeholder="Search" aria-describedby="basic-addon2">
              <span class="input-group-addon" id="basic-addon2">Go</span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Handle dropdown toggles
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      const menu = this.nextElementSibling;
      if (menu && menu.classList.contains('dropdown-menu')) {
        menu.classList.toggle('show');
        this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true');
      }
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function(e) {
    dropdownToggles.forEach(toggle => {
      if (!toggle.contains(e.target) && !toggle.nextElementSibling.contains(e.target)) {
        toggle.nextElementSibling.classList.remove('show');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  });

  // Close dropdown when clicking on a dropdown item
  const dropdownItems = document.querySelectorAll('.dropdown-item');
  dropdownItems.forEach(item => {
    item.addEventListener('click', function() {
      const menu = this.closest('.dropdown-menu');
      if (menu) {
        menu.classList.remove('show');
        const toggle = menu.previousElementSibling;
        if (toggle) {
          toggle.setAttribute('aria-expanded', 'false');
        }
      }
    });
  });
});
</script>
