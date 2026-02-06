<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<meta name="description" content="Contact Us — <?php echo e(config('company.name')); ?>">
	<meta name="author" content="<?php echo e(config('company.name')); ?>">

	<title><?php echo e(config('company.name')); ?> — Contact Us</title>
	<link rel="shortcut icon" href="<?php echo e(\App\Helpers\CompanyHelper::favicon()); ?>">

	<!-- Global Stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
	<link href="<?php echo e(asset('buzbox/css/bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo e(asset('buzbox/font-awesome-4.7.0/css/font-awesome.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('buzbox/css/animate/animate.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('buzbox/css/owl-carousel/owl.carousel.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('buzbox/css/owl-carousel/owl.theme.default.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('buzbox/css/style.css')); ?>">
	<style>
		body {
			font-family: 'Roboto Condensed', sans-serif;
		}

		.contact-section {
			padding: 40px 15px sm:padding: 60px 20px md:padding: 80px 0;
			background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
			min-height: 100vh;
		}

		.contact-heading {
			font-size: clamp(28px, 6vw, 48px);
			font-weight: 700;
			color: #2c3e50;
			margin-bottom: 15px;
			text-align: center;
		}

		.contact-subtitle {
			font-size: clamp(14px, 4vw, 18px);
			color: #7f8c8d;
			text-align: center;
			margin-bottom: 30px sm:margin-bottom: 50px;
		}

		.contact-form {
			background: white;
			padding: 25px 15px sm:padding: 40px 25px md:padding: 50px;
			border-radius: 12px;
			box-shadow: 0 10px 40px rgba(0,0,0,0.1);
			transition: transform 0.3s ease, box-shadow 0.3s ease;
		}

		.contact-form:hover {
			transform: translateY(-5px);
			box-shadow: 0 15px 50px rgba(0,0,0,0.15);
		}

		.contact-form h4 {
			font-size: clamp(22px, 5vw, 28px);
			font-weight: 700;
			color: #2c3e50;
			margin-bottom: 20px sm:margin-bottom: 30px;
			border-bottom: 3px solid #007bff;
			padding-bottom: 15px;
		}

		.form-group {
			margin-bottom: 20px sm:margin-bottom: 25px;
		}

		.form-group label {
			font-weight: 600;
			color: #2c3e50;
			margin-bottom: 8px sm:margin-bottom: 10px;
			display: block;
			font-size: 12px sm:font-size: 14px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.form-control {
			border: 2px solid #ecf0f1;
			padding: 10px 12px sm:padding: 12px 18px;
			border-radius: 6px;
			font-size: 14px;
			transition: all 0.3s ease;
			background-color: #f8f9fa;
			width: 100%;
		}

		.form-control:focus {
			border-color: #007bff;
			background-color: white;
			box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
			outline: none;
		}

		.form-control::placeholder {
			color: #bdc3c7;
		}

		textarea.form-control {
			resize: vertical;
			min-height: 120px sm:min-height: 150px;
			font-family: 'Roboto Condensed', sans-serif;
		}

		.submit-btn {
			background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
			color: white;
			padding: 12px 20px sm:padding: 14px 40px;
			border: none;
			border-radius: 6px;
			cursor: pointer;
			font-weight: 700;
			font-size: 14px sm:font-size: 16px;
			transition: all 0.3s ease;
			text-transform: uppercase;
			letter-spacing: 1px;
			width: 100%;
			box-shadow: 0 5px 15px rgba(0,123,255,0.3);
		}

		.submit-btn:hover {
			background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
			transform: translateY(-2px);
			box-shadow: 0 8px 20px rgba(0,123,255,0.4);
		}

		.submit-btn:active {
			transform: translateY(0);
		}

		.contact-info {
			padding: 20px 0;
		}

		.contact-info h4 {
			font-size: clamp(22px, 5vw, 28px);
			font-weight: 700;
			color: #2c3e50;
			margin-bottom: 25px sm:margin-bottom: 35px;
			border-bottom: 3px solid #007bff;
			padding-bottom: 15px;
		}

		.contact-info-item {
			margin-bottom: 25px sm:margin-bottom: 35px;
			display: flex;
			align-items: flex-start;
		}

		.contact-info-item i {
			font-size: 28px;
			color: #007bff;
			margin-right: 20px;
			min-width: 35px;
			text-align: center;
			padding-top: 5px;
		}

		.contact-info-item div {
			flex: 1;
		}

		.contact-info-item h5 {
			font-weight: 700;
			margin-bottom: 8px;
			color: #2c3e50;
			font-size: 16px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.contact-info-item p {
			color: #7f8c8d;
			margin-bottom: 0;
			line-height: 1.6;
			font-size: 14px;
		}

		.alert {
			margin-bottom: 30px;
			border: none;
			border-radius: 6px;
			padding: 18px 25px;
			font-weight: 500;
			animation: slideIn 0.3s ease;
		}

		@keyframes slideIn {
			from {
				opacity: 0;
				transform: translateY(-10px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.alert-danger {
			background-color: #fff5f5;
			border-left: 4px solid #e74c3c;
			color: #c0392b;
		}

		.alert-danger strong {
			color: #c0392b;
		}

		.alert-success {
			background-color: #f0fdf4;
			border-left: 4px solid #27ae60;
			color: #229954;
		}

		.invalid-feedback {
			color: #e74c3c;
			font-size: 12px;
			margin-top: 5px;
			display: block;
		}

		.text-danger {
			color: #e74c3c;
		}

		.map-section {
			margin-top: 80px;
			padding: 60px 0;
			background: white;
		}

		.map-heading {
			font-size: 36px;
			font-weight: 700;
			color: #2c3e50;
			margin-bottom: 10px;
			text-align: center;
		}

		.map-subtitle {
			font-size: 16px;
			color: #7f8c8d;
			text-align: center;
			margin-bottom: 40px;
		}

		.map-container {
			border-radius: 12px;
			overflow: hidden;
			box-shadow: 0 10px 40px rgba(0,0,0,0.1);
			height: 450px;
		}

		.map-container iframe {
			width: 100%;
			height: 100%;
			border: none;
		}
	</style>
</head>

<body id="page-top">
	<header>
		<?php echo $__env->make('partials.top-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
		<?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	</header>

	<!-- Contact Section -->
	<section class="contact-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 mx-auto">
					<h1 class="contact-heading">Get in Touch</h1>
					<p class="contact-subtitle">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>

					<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
						<div class="alert alert-danger">
							<strong><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</strong>
							<ul class="mb-0 mt-3">
								<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<li><?php echo e($error); ?></li>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
							</ul>
						</div>
					<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

					<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
						<div class="alert alert-success">
							<strong><i class="fa fa-check-circle"></i> Success!</strong>
							<div class="mt-2"><?php echo e(session('status')); ?></div>
						</div>
					<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

					<div class="row">
						<div class="col-lg-8">
							<div class="contact-form">
								<h4><i class="fa fa-envelope"></i> Send us a Message</h4>
								<form action="<?php echo e(route('contact.store')); ?>" method="POST" novalidate>
									<?php echo csrf_field(); ?>

									<div class="form-group">
										<label for="name">Full Name <span class="text-danger">*</span></label>
										<input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name')); ?>" placeholder="Your Full Name" required>
										<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<span class="invalid-feedback"><?php echo e($message); ?></span>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
									</div>

									<div class="form-group">
										<label for="email">Email Address <span class="text-danger">*</span></label>
										<input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="your@email.com" required>
										<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<span class="invalid-feedback"><?php echo e($message); ?></span>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
									</div>

									<div class="form-group">
										<label for="phone">Phone Number</label>
										<input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="+1 (555) 000-0000">
										<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<span class="invalid-feedback"><?php echo e($message); ?></span>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
									</div>

									<div class="form-group">
										<label for="subject">Subject <span class="text-danger">*</span></label>
										<input type="text" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="subject" name="subject" value="<?php echo e(old('subject')); ?>" placeholder="What is this about?" required>
										<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<span class="invalid-feedback"><?php echo e($message); ?></span>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
									</div>

									<div class="form-group">
										<label for="message">Message <span class="text-danger">*</span></label>
										<textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="message" name="message" placeholder="Tell us more about your inquiry..." required><?php echo e(old('message')); ?></textarea>
										<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
											<span class="invalid-feedback"><?php echo e($message); ?></span>
										<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
									</div>

									<button type="submit" class="submit-btn" id="submit-btn">
										<i class="fa fa-paper-plane"></i> Send Message
									</button>
								</form>
							</div>
						</div>

						<div class="col-lg-4">
							<div class="contact-info">
								<h4><i class="fa fa-info-circle"></i> Contact Info</h4>

								<div class="contact-info-item">
									<i class="fa fa-map-marker"></i>
									<div>
										<h5>Address</h5>
										<p><?php echo e(config('company.address', 'Your Company Address')); ?></p>
									</div>
								</div>

								<div class="contact-info-item">
									<i class="fa fa-phone"></i>
									<div>
										<h5>Phone</h5>
										<p><a href="tel:<?php echo e(str_replace([' ', '(', ')', '-'], '', config('company.phone', ''))); ?>" style="color: #7f8c8d; text-decoration: none;"><?php echo e(config('company.phone', 'Your Phone Number')); ?></a></p>
									</div>
								</div>

								<div class="contact-info-item">
									<i class="fa fa-envelope"></i>
									<div>
										<h5>Email</h5>
										<p><a href="mailto:<?php echo e(config('company.email', '')); ?>" style="color: #7f8c8d; text-decoration: none;"><?php echo e(config('company.email', 'your@email.com')); ?></a></p>
									</div>
								</div>

								<div class="contact-info-item">
									<i class="fa fa-clock-o"></i>
									<div>
										<h5>Working Hours</h5>
										<p>
											Monday - Friday<br>
											<strong><?php echo e(config('company.working_hours.weekday_start', '9:00 AM')); ?> - <?php echo e(config('company.working_hours.weekday_end', '6:00 PM')); ?></strong>
										</p>
										<p style="margin-top: 10px;">
											Saturday<br>
											<strong><?php echo e(config('company.working_hours.saturday_start', '10:00 AM')); ?> - <?php echo e(config('company.working_hours.saturday_end', '4:00 PM')); ?></strong>
										</p>
										<p style="margin-top: 10px; color: #e74c3c;">
											Sunday: Closed
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Map Section -->
	<section class="map-section">
		<div class="container">
			<h2 class="map-heading">Visit Us</h2>
			<p class="map-subtitle">Find our location on the map below</p>

			<div class="map-container">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.0223!2d4.7353314!3d8.1743426!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1037c0a255a14db9:0x699f415c5454df0!2sSkyeface+Digital+Limited!5e0!3m2!1sen!2sng!4v1673000000000" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>

			<div class="row mt-5">
				<div class="col-md-4 text-center">
					<div style="padding: 30px; background: #f8f9fa; border-radius: 8px;">
						<i class="fa fa-map-pin" style="font-size: 32px; color: #007bff; margin-bottom: 15px; display: block;"></i>
						<h5 style="color: #2c3e50; margin-bottom: 10px;">Our Location</h5>
						<p style="color: #7f8c8d;"><?php echo e(config('company.address', 'Your Company Address')); ?></p>
					</div>
				</div>

				<div class="col-md-4 text-center">
					<div style="padding: 30px; background: #f8f9fa; border-radius: 8px;">
						<i class="fa fa-phone" style="font-size: 32px; color: #007bff; margin-bottom: 15px; display: block;"></i>
						<h5 style="color: #2c3e50; margin-bottom: 10px;">Call Us</h5>
						<p style="color: #7f8c8d;"><a href="tel:<?php echo e(str_replace([' ', '(', ')', '-'], '', config('company.phone', ''))); ?>" style="color: #7f8c8d; text-decoration: none;"><?php echo e(config('company.phone', 'Your Phone Number')); ?></a></p>
					</div>
				</div>

				<div class="col-md-4 text-center">
					<div style="padding: 30px; background: #f8f9fa; border-radius: 8px;">
						<i class="fa fa-envelope" style="font-size: 32px; color: #007bff; margin-bottom: 15px; display: block;"></i>
						<h5 style="color: #2c3e50; margin-bottom: 10px;">Email Us</h5>
						<p style="color: #7f8c8d;"><a href="mailto:<?php echo e(config('company.email', '')); ?>" style="color: #7f8c8d; text-decoration: none;"><?php echo e(config('company.email', 'your@email.com')); ?></a></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Footer -->
	<?php echo $__env->make('partials.footer-top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	<?php echo $__env->make('partials.footer-bottom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

	<!-- Script Files -->
	<script src="<?php echo e(asset('buzbox/js/jquery.min.js')); ?>"></script>
	<script src="<?php echo e(asset('buzbox/js/bootstrap.bundle.min.js')); ?>"></script>
	<script src="<?php echo e(asset('buzbox/js/jquery.easing.min.js')); ?>"></script>
	<script src="<?php echo e(asset('buzbox/js/scrollreveal.min.js')); ?>"></script>
	<script src="<?php echo e(asset('buzbox/js/script.js')); ?>"></script>

	<!-- Contact Form Handler Script -->
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const contactForm = document.querySelector('.contact-form form');
			const submitBtn = document.getElementById('submit-btn');

			if (contactForm) {
				contactForm.addEventListener('submit', function(e) {
					e.preventDefault();

					// Disable submit button while processing
					submitBtn.disabled = true;
					submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

					// Get form data
					const formData = new FormData(this);

					// Make AJAX request
					fetch(this.getAttribute('action'), {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'Accept': 'application/json',
						},
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						// Remove any existing alerts
						const existingAlerts = contactForm.closest('.contact-form').parentElement.querySelectorAll('.alert');
						existingAlerts.forEach(alert => alert.remove());

						if (data.success) {
							// Show success message
							const successAlert = document.createElement('div');
							successAlert.className = 'alert alert-success';
							successAlert.innerHTML = `
								<strong><i class="fa fa-check-circle"></i> Success!</strong>
								<div class="mt-2">
									<p>${data.message}</p>
									<p><strong>Your Ticket Number:</strong> <span style="color: #007bff; font-weight: bold;">${data.ticket_number}</span></p>
									<p style="color: #555; font-size: 13px; margin-bottom: 0;">Please save this ticket number for reference. You can use it to track your inquiry.</p>
								</div>
							`;
							contactForm.closest('.contact-form').parentElement.insertBefore(successAlert, contactForm.closest('.contact-form'));

							// Reset form
							contactForm.reset();

							// Scroll to alert
							successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });

							// Reset button
							submitBtn.disabled = false;
							submitBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send Message';
						} else {
							// Show error message
							const errorAlert = document.createElement('div');
							errorAlert.className = 'alert alert-danger';
							errorAlert.innerHTML = `
								<strong><i class="fa fa-exclamation-circle"></i> Error!</strong>
								<div class="mt-2">${data.message}</div>
							`;
							contactForm.closest('.contact-form').parentElement.insertBefore(errorAlert, contactForm.closest('.contact-form'));

							// Reset button
							submitBtn.disabled = false;
							submitBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send Message';
						}
					})
					.catch(error => {
						console.error('Error:', error);

						// Show error message
						const errorAlert = document.createElement('div');
						errorAlert.className = 'alert alert-danger';
						errorAlert.innerHTML = `
							<strong><i class="fa fa-exclamation-circle"></i> Error!</strong>
							<div class="mt-2">An unexpected error occurred. Please try again.</div>
						`;
						contactForm.closest('.contact-form').parentElement.insertBefore(errorAlert, contactForm.closest('.contact-form'));

						// Reset button
						submitBtn.disabled = false;
						submitBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Send Message';
					});
				});
			}
		});
	</script>

</html>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/contact.blade.php ENDPATH**/ ?>