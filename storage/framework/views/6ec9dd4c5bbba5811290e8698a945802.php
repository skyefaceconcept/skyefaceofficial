<!-- Footer Top -->
<div id="footer-s1" class="footer-s1">
	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-sm-6 ">
					<div><img src="<?php echo e(\App\Helpers\CompanyHelper::logoWhite()); ?>" alt="<?php echo e(config('company.name')); ?> white logo" style="max-height: 40px;">
      <img src="<?php echo e(\App\Helpers\CompanyHelper::logo()); ?>" alt="<?php echo e(config('company.name')); ?> logo" style="max-height: 40px; margin-right:8px;"></div>
					<ul class="list-unstyled comp-desc-f">
						 <li><p><?php echo e(config('company.name')); ?> is a leading business solution dedicated to providing exceptional services. We help businesses achieve their goals with innovative strategies and comprehensive support tailored to your unique needs.</p></li>
					</ul><br>
				</div>

				<div class="col-md-3 col-sm-6 ">
					<div class="heading-footer"><h2>Useful Links</h2></div>
					<ul class="list-unstyled link-list">
						<li><a href="<?php echo e(route('about')); ?>">About us</a><i class="fa fa-angle-right"></i></li>
						<li><a href="<?php echo e(asset('buzbox/project.html')); ?>">Project</a><i class="fa fa-angle-right"></i></li>
						<li><a href="<?php echo e(asset('buzbox/careers.html')); ?>">Career</a><i class="fa fa-angle-right"></i></li>
						<li><a href="<?php echo e(asset('buzbox/faq.html')); ?>">FAQ</a><i class="fa fa-angle-right"></i></li>
						<li><a href="<?php echo e(asset('buzbox/contact.html')); ?>">Contact us</a><i class="fa fa-angle-right"></i></li>
					</ul>
				</div>

				<div class="col-md-3 col-sm-6 ">
					<div class="heading-footer"><h2>Why Choose Us</h2></div>
					<ul class="list-unstyled link-list">
						<li style="color: white;"><i class="fa fa-check"></i> Expert Team & Support</li>
						<li style="color: white;"><i class="fa fa-check"></i> Proven Track Record</li>
						<li style="color: white;"><i class="fa fa-check"></i> Innovative Solutions</li>
						<li style="color: white;"><i class="fa fa-check"></i> Client Satisfaction</li>
						<li style="color: white;"><i class="fa fa-check"></i> 24/7 Availability</li>
					</ul>
				</div>

				<div class="col-md-3 col-sm-6">
					<div class="heading-footer"><h2>Get In Touch</h2></div>
					<address class="address-details-f">
						No. I40, Kojomoonu Street, Ijagbo. <br>
						Kwara State, NG <br>
						Phone: <?php echo e(config('company.support_phone')); ?> <br>
						Email: <a href="mailto:<?php echo e(config('company.support_email')); ?>" class=""><?php echo e(config('company.support_email')); ?></a>
					</address>
					<ul class="list-inline social-icon-f top-data">
						<li><a href="#" target="_empty"><i class="fa top-social fa-facebook"></i></a></li>
						<li><a href="#" target="_empty"><i class="fa top-social fa-twitter"></i></a></li>
						<li><a href="#" target="_empty"><i class="fa top-social fa-google-plus"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/partials/footer-top.blade.php ENDPATH**/ ?>