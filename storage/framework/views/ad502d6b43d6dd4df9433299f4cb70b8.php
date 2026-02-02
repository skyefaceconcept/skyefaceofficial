<!-- Top Navbar -->
<div class="top-menubar">
  <div class="topmenu">
    <div class="container">
      <div class="row">
        <div class="col-md-7">
          <ul class="list-inline top-contacts">
            <li>
              <i class="fa fa-envelope"></i> Email: <a href="mailto:<?php echo e(config('company.support_email')); ?>"><?php echo e(config('company.support_email')); ?></a>
            </li>
            <li>
              <i class="fa fa-phone"></i> Hotline: <?php echo e(config('company.support_phone')); ?>

            </li>
          </ul>
        </div>
        <div class="col-md-5">
          <ul class="list-inline top-data">
            <li><a href="https://www.facebook.com" target="_empty"><i class="fa top-social fa-facebook"></i></a></li>
            
            <li><a href="https://plus.google.com" target="_empty"><i class="fa top-social fa-google-plus"></i></a></li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
              <li><a href="<?php echo e(route('dashboard')); ?>" class="log-top" target="_blank">Dashboard</a></li>
            <?php else: ?>
              <li><a href="<?php echo e(route('login')); ?>" class="log-top">Login</a></li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <li style="margin-left: 15px;">
              <form id="topRepairSearchForm" style="display: flex; gap: 5px; align-items: center;">
                <?php echo csrf_field(); ?>
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
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/partials/top-nav.blade.php ENDPATH**/ ?>