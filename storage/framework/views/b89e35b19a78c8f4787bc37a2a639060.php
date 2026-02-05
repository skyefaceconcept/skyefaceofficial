<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav" data-toggle="affix">
  <div class="container">
    <a class="navbar-brand smooth-scroll" href="<?php echo e(url('/')); ?>">
      <img src="<?php echo e(\App\Helpers\CompanyHelper::logoWhite()); ?>" alt="<?php echo e(config('company.name')); ?> white logo" style="max-height: 40px;">
      <img src="<?php echo e(\App\Helpers\CompanyHelper::logo()); ?>" alt="<?php echo e(config('company.name')); ?> logo" style="max-height: 40px; margin-right:8px;">
    </a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link smooth-scroll" href="<?php echo e(route('home')); ?>">Home</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle smooth-scroll" href="#" id="navbarDropdownAbout" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">About</a>
          <div class="dropdown-menu dropdown-cust" aria-labelledby="navbarDropdownAbout">
            <a class="dropdown-item" href="<?php echo e(route('about')); ?>">About Us</a>
            <a class="dropdown-item" href="<?php echo e(asset('buzbox/careers.html')); ?>">Career Opportunity</a>
            <a class="dropdown-item" href="<?php echo e(asset('buzbox/team.html')); ?>">Meet Our Team</a>
            <a class="dropdown-item" href="<?php echo e(asset('buzbox/faq.html')); ?>">FAQ</a>
            <a class="dropdown-item" href="<?php echo e(asset('buzbox/testimonials.html')); ?>">Testimonials</a>
          </div>
        </li>

        <li class="nav-item"><a class="nav-link smooth-scroll" href="<?php echo e(route('services')); ?>">Services</a></li>

        <li class="nav-item"><a class="nav-link smooth-scroll" href="#" data-toggle="modal" data-target="#repairBookingModal" style="color: #28a745; font-weight: 600;"><i class="fa fa-wrench mr-1"></i>Quick Repair Booking</a></li>

        

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle smooth-scroll" href="#" id="navbarDropdownShop" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
          <div class="dropdown-menu dropdown-cust mega-menu" aria-labelledby="navbarDropdownShop">
            <?php
              $branding = \App\Models\Branding::first();
              $showOfferImage = $branding ? $branding->show_menu_offer_image : true;
            ?>
            <div class="row">
              <div class="col-md-<?php echo e($showOfferImage ? '5' : '12'); ?>">
                <a class="dropdown-item" href="<?php echo e(route('shop.index')); ?>">Shop Detail</a>
                <a class="dropdown-item" href="<?php echo e(asset('buzbox/single-product.html')); ?>">Single Product</a>
                <a class="dropdown-item" href="<?php echo e(route('cart.show')); ?>">Cart</a>
                <a class="dropdown-item" href="<?php echo e(route('cart.checkout')); ?>">Checkout</a>
                <a class="dropdown-item" href="<?php echo e(route('dashboard')); ?>">My Account</a>
              </div>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showOfferImage): ?>
                <div class="col-md-7 mega-menu-img m-auto text-center pl-0">
                  <a href="#"><img src="<?php echo e(asset('buzbox/img/offer_icon.png')); ?>" alt="" class="img-fluid"></a>
                </div>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>
        </li>

        <li class="nav-item"><a class="nav-link smooth-scroll" href="<?php echo e(route('contact.show')); ?>">Contact Us</a></li>

        <li>
          <i class="search fa fa-search search-btn"></i>
          <div class="search-open">
            <div class="input-group animated fadeInUp">
              <input type="text" class="form-control" placeholder="Search" aria-describedby="basic-addon2">
              <span class="input-group-addon" id="basic-addon2">Go</span>
            </div>
          </div>
        </li>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
            <li class="nav-item d-lg-none"><a class="nav-link" href="<?php echo e(url('/dashboard')); ?>">Dashboard</a></li>
          <?php else: ?>
            <li class="nav-item d-lg-none"><a class="nav-link" href="<?php echo e(route('login')); ?>">Log in</a></li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
              <li class="nav-item d-lg-none"><a class="nav-link" href="<?php echo e(route('register')); ?>">Register</a></li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/partials/navbar.blade.php ENDPATH**/ ?>