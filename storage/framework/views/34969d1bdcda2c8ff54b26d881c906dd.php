<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title><?php echo e(config('app.name')); ?></title>
		<link rel="shortcut icon" href="<?php echo e(\App\Helpers\CompanyHelper::favicon()); ?>">

		<!-- Global Stylesheets -->
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
		<link href="<?php echo e(asset('buzbox/css/bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo e(asset('buzbox/font-awesome-4.7.0/css/font-awesome.min.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(asset('buzbox/css/animate/animate.min.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(asset('buzbox/css/owl-carousel/owl.carousel.min.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(asset('buzbox/css/owl-carousel/owl.theme.default.min.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(asset('buzbox/css/style.css')); ?>">
	</head>

	<body id="page-top">

		<header>
		<?php echo $__env->make('partials.top-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
		<?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	</header>
		<section id="login">
			<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
					<div class="modal-dialog">
							<div class="modal-content">
									<div class="modal-header" align="center">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span class="fa fa-times" aria-hidden="true"></span>
											</button>
									</div>
									<div id="div-forms">
											<form id="login-form">
													<h3 class="text-center">Login</h3>
													<div class="modal-body">
															<label for="username">Username</label>
															<input id="login_username" class="form-control" type="text" placeholder="Enter username " required>
															<label for="username">Password</label>
															<input id="login_password" class="form-control" type="password" placeholder="Enter password" required>
															<div class="checkbox">
																	<label>
																			<input type="checkbox"> Remember me
																	</label>
															</div>
													</div>
													<div class="modal-footer text-center">
															<div>
																	<button type="submit" class="btn btn-general btn-white">Login</button>
															</div>
															<div>
																	<button id="login_register_btn" type="button" class="btn btn-link">Register</button>
															</div>
													</div>
											</form>
											<form id="register-form" style="display:none;">
													<h3 class="text-center">Register</h3>
													<div class="modal-body">
															<label for="username">Username</label>
															<input id="register_username" class="form-control" type="text" placeholder="Enter username" required>
															<label for="register_email">E-mailId</label>
															<input id="register_email" class="form-control" type="text" placeholder="Enter eMail" required>
															<label for="register_password">Password</label>
															<input id="register_password" class="form-control" type="password" placeholder="Password" required>
													</div>
													<div class="modal-footer">
															<div>
																	<button type="submit" class="btn btn-general btn-white">Register</button>
															</div>
															<div>
																	<button id="register_login_btn" type="button" class="btn btn-link">Log In</button>
															</div>
													</div>
											</form>
									</div>
							</div>
					</div>
			</div>
		</section>

		<section id="home">
			<div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">
				<div class="carousel-inner">
						<div class="carousel-item active slides">
							<div class="overlay"></div>
							<div class="slide-1"></div>
								<div class="hero " >
									<hgroup class="wow fadeInUp">
											<h1>We Help <span ><a href="" class="typewrite" data-period="2000" data-type='[ " Business", " Startup", " Entrepreneurs", " Companies", " Innovation"]'>
												<span class="wrap"></span></a></span> </h1>
											<h3>Innovative Digital Solutions for Modern Businesses</h3>
									</hgroup>
									<button class="btn btn-general btn-green wow fadeInUp" role="button">Contact Now</button>
								</div>
						</div>
				</div>
			</div>
		</section>

		<!-- REPAIR INVOICE SEARCH SECTION -->
		<section class="bg-light py-5" style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.02) 100%);">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="text-center mb-4">
							<h3 style="color: #222; font-weight: 700; margin-bottom: 10px;">Track Your Device Repair</h3>
							<p style="color: #666; font-size: 14px;">Enter your repair invoice number to check the status of your device repair</p>
						</div>
						<div class="row justify-content-center">
							<div class="col-md-6">
								<form method="POST" action="<?php echo e(route('repairs.status')); ?>" style="display: flex; gap: 10px;">
									<?php echo csrf_field(); ?>
									<input type="text" name="invoice_number" class="form-control" placeholder="Enter invoice number (e.g., INV-2026-001)" required style="border-radius: 6px; border: 1px solid #ddd; padding: 12px 15px;">
									<button type="submit" class="btn" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 6px; padding: 12px 25px; font-weight: 600; white-space: nowrap;">
										<i class="fa fa-search mr-2"></i>Search
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="about" class="about">
			<div class="container">
				<div class="row title-bar">
					<div class="col-md-12">
						<h1 class="wow fadeInUp">We committed to helping</h1>
						<div class="heading-border"></div>
						<p class="wow fadeInUp" data-wow-delay="0.4s">Skyeface Digital Ltd is committed to helping businesses, organizations, and individuals leverage technology to achieve sustainable growth. We focus on delivering practical, secure, and scalable digital solutions that solve real problems and create long-term value.</p>
<p class="wow fadeInUp" data-wow-delay="0.4s">We work closely with our clients to understand their goals, challenges, and vision, ensuring every solution we deliver is aligned with their business objectives.</p>
						
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-4 bg-starship">
						<div class="about-content-box wow fadeInUp" data-wow-delay="0.3s">
							<i class="fa fa-snowflake-o"></i>
							<h5>Thoughts Leadership Platform</h5>
							<p class="desc">Our Thought Leadership Platform shares expert insights, ideas, and perspectives on technology, digital innovation, and business growth—helping organizations stay informed, make better decisions, and lead confidently in a digital world.</p>
						</div>
					</div>
					<div class="col-md-4 bg-chathams">
						<div class="about-content-box wow fadeInUp" data-wow-delay="0.5s">
							<i class="fa fa-circle-o-notch"></i>
							<h5>Corporate world Platform</h5>
							<p class="desc">Our Corporate World Platform delivers practical digital solutions and insights that support business efficiency, innovation, and sustainable growth in today’s competitive corporate environment.</p>
						</div>
					</div>
					<div class="col-md-4 bg-matisse">
						<div class="about-content-box wow fadeInUp" data-wow-delay="0.7s">
							<i class="fa fa-hourglass-o"></i>
							<h5>End to End Testing Platform</h5>
							<p class="desc">Our End-to-End Testing Platform ensures the quality, performance, and reliability of digital systems by validating every stage of development—from functionality to deployment.</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="comp-offer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3 col-sm-6 desc-comp-offer wow fadeInUp" data-wow-delay="0.2s">
						<h2>What We Offer</h2>
						<div class="heading-border-light"></div>
						<button class="btn btn-general btn-green" role="button">See Curren Offers</button>
						<button class="btn btn-general btn-white" role="button">Contact Us Today</button>
					</div>
					<div class="col-md-3 col-sm-6 desc-comp-offer wow fadeInUp" data-wow-delay="0.4s">
						<div class="desc-comp-offer-cont">
							<div class="thumbnail-blogs">
									<div class="caption">
										<i class="fa fa-chain"></i>
									</div>
									<img src="<?php echo e(asset('buzbox/img/news/news-11.png')); ?>" class="img-fluid" alt="...">
							</div>
							<h3>Digital Product Development</h3>
							<p class="desc">We design and build secure, scalable websites and software solutions tailored to meet specific business needs. </p>
							<a href="#"><i class="fa fa-arrow-circle-o-right"></i> Learn More</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 desc-comp-offer wow fadeInUp" data-wow-delay="0.6s">
						<div class="desc-comp-offer-cont">
							<div class="thumbnail-blogs">
									<div class="caption">
										<i class="fa fa-chain"></i>
									</div>
									<img src="<?php echo e(asset('buzbox/img/news/news-13.jpg')); ?>" class="img-fluid" alt="...">
							</div>
							<h3>Strategic Digital Solutions</h3>
							<p class="desc">We provide technology-driven strategies that improve efficiency, strengthen online presence, and support business growth. </p>
							<a href="#"><i class="fa fa-arrow-circle-o-right"></i> Learn More</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 desc-comp-offer wow fadeInUp" data-wow-delay="0.8s">
						<div class="desc-comp-offer-cont">
							<div class="thumbnail-blogs">
									<div class="caption">
										<i class="fa fa-chain"></i>
									</div>
									<img src="<?php echo e(asset('buzbox/img/news/news-14.jpg')); ?>" class="img-fluid" alt="...">
							</div>
							<h3>Reliable Support & Maintenance</h3>
							<p class="desc">We offer ongoing technical support, system maintenance, and performance optimization to ensure long-term reliability. </p>
							<a href="#"><i class="fa fa-arrow-circle-o-right"></i> Learn More</a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="what-we-do bg-gradiant">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<h3>What we Do</h3>
						<div class="heading-border-light"></div>
						<p class="desc">We partner with clients to put recommendations into practice. </p>
					</div>
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-4  col-sm-6">
								<div class="what-we-desc">
									<i class="fa fa-briefcase"></i>
									<h6>Workspace Solutions</h6>
									<p class="desc">We design and develop digital workspaces that enhance collaboration, productivity, and efficient business operations through secure and scalable technology. </p>
								</div>
							</div>
							<div class="col-md-4  col-sm-6">
								<div class="what-we-desc">
									<i class="fa fa-shopping-bag"></i>
									<h6>Storefront Solutions</h6>
									<p class="desc">We build modern digital storefronts and e-commerce platforms that help businesses showcase products, manage sales, and engage customers effectively online. </p>
								</div>
							</div>
							<div class="col-md-4  col-sm-6">
								<div class="what-we-desc">
									<i class="fa fa-building-o"></i>
									<h6>Apartment & Property Platforms</h6>
									<p class="desc">We create property management and listing platforms that simplify rentals, bookings, and property administration with user-friendly digital systems. </p>
								</div>
							</div>
							<div class="col-md-4  col-sm-6">
								<div class="what-we-desc">
									<i class="fa fa-bed"></i>
									<h6>Hotel & Hospitality Systems</h6>
									<p class="desc">We develop hospitality-focused solutions including booking platforms, management dashboards, and customer engagement tools tailored for hotels and short-stay businesses. </p>
								</div>
							</div>
							<div class="col-md-4  col-sm-6">
								<div class="what-we-desc">
									<i class="fa fa-hourglass-2"></i>
									<h6>Concept Development</h6>
									<p class="desc">We help businesses define and refine their digital strategies, ensuring alignment with goals and market demands. </p>
								</div>
							</div>
							<div class="col-md-4  col-sm-6">
								<div class="what-we-desc">
									<i class="fa fa-cutlery"></i>
									<h6>Restaurant Digital Solutions</h6>
									<p class="desc">We design and develop digital solutions for restaurants, including online ordering systems, reservation platforms, and customer engagement tools. </p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section id="story">
				<div class="container">
					<div class="row title-bar">
						<div class="col-md-12">
							<h1 class="wow fadeInUp">Our Success Transformation Story</h1>
							<div class="heading-border"></div>
						</div>
					</div>
				</div>
				<div class="container-fluid">
					<div class="row" >
						<div class="col-md-6" >
							<div class="story-himg" >
								<img src="<?php echo e(asset('buzbox/img/image-4.jpg')); ?>" class="img-fluid" alt="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="story-desc">
								<h3>How to grow world with Us</h3>
								<div class="heading-border-light"></div>
								<p>At Skyeface Digital Ltd, our success is built on helping businesses transform through technology. We partner with our clients to understand their vision, implement the right digital solutions, and support continuous growth through innovation, reliability, and strategic execution. </p>
								<p>By working with us, organizations gain a trusted digital partner committed to delivering measurable results and long-term success in an evolving digital world.</p>
								
								
							</div>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
							<div class="story-descb">
									<img src="<?php echo e(asset('buzbox/img/news/news-10.jpg')); ?>" class="img-fluid" alt="...">
									<h6>Virtual training systems</h6>
									<p>We design and deploy virtual training platforms that enable organizations to deliver structured learning, skill development, and knowledge sharing through secure and interactive digital environments.</p>
									
							</div>
						</div>
						<div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
							<div class="story-descb">
									<img src="<?php echo e(asset('buzbox/img/news/news-2.jpg')); ?>" class="img-fluid" alt="...">
									<h6>Design planning</h6>
									<p>We provide strategic design and planning services that align technology with business objectives, ensuring every digital solution is well-structured, scalable, and results-driven from inception.</p>
									
							</div>
						</div>
						<div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
							<div class="story-descb">
									<img src="<?php echo e(asset('buzbox/img/news/news-8.jpg')); ?>" class="img-fluid" alt="...">
									<h6>Remote condition monitoring</h6>
									<p>We develop digital monitoring solutions that allow businesses to track system performance, usage, and operational status remotely, enabling proactive maintenance and improved decision-making.</p>
									
							</div>
						</div>
					</div>
				</div>
		</section>

		<div class="overlay-thought"></div>
		<section id="thought" class="bg-parallax thought-bg">
			<div class="container">
				<div id="thought-desc" class="row title-bar title-bar-thought owl-carousel owl-theme">
					<div class="col-md-12 ">
						<div class="heading-border bg-white"></div>
						<p class="wow fadeInUp" data-wow-delay="0.4s">We are just getting started. Be among the first to work with Skyeface Digital Ltd and share your experience as we build innovative digital solutions together.</p>
						<h6>Skyeface Digital Ltd</h6>
					</div>
					<div class="col-md-12 thought-desc">
						<div class="heading-border bg-white"></div>
						<p class="wow fadeInUp" data-wow-delay="0.4s">This space is reserved for our clients’ success stories. Partner with us today and be the first to tell your story. </p>
						<h6>Your Feedback Matters</h6>
					</div>
				</div>
			</div>
		</section>

		<section id="service-h">
				<div class="container-fluid">
					<div class="row" >
						<div class="col-md-6" >
							<div class="service-himg" >
								<iframe src="" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
						<div class="col-md-6 wow fadeInUp" data-wow-delay="0.3s">
							<div class="service-h-desc">
								<h3>We are Providing great Services</h3>
								<div class="heading-border-light"></div>
								<p>Skyeface Digital Ltd delivers end-to-end digital services designed to help businesses operate efficiently, scale sustainably, and remain competitive in a fast-evolving digital environment. From solution design to implementation and support, we focus on quality, performance, and long-term value.</p>
							<div class="service-h-tab">
								<nav class="nav nav-tabs" id="myTab" role="tablist">
									<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-expanded="true">Developing</a>
									<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile">Training</a>
									<a class="nav-item nav-link" id="my-profile-tab" data-toggle="tab" href="#my-profile" role="tab" aria-controls="my-profile">Medical</a>
								</nav>
								<div class="tab-content" id="nav-tabContent">
									<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"><p>We specialize in the design and development of modern digital solutions, including websites, web applications, management systems, and custom software. Our development process emphasizes security, scalability, and performance to ensure solutions that grow with your business. </p></div>
									<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
										<p>We provide structured digital training and knowledge-transfer programs that help teams adopt new technologies, improve productivity, and operate systems effectively. Our training solutions are practical, flexible, and tailored to organizational needs.</p>
									</div>
									<div class="tab-pane fade" id="my-profile" role="tabpanel" aria-labelledby="my-profile-tab">
										<p>We support healthcare and medical-related organizations with digital solutions such as data management systems, portals, and process automation tools that enhance efficiency, accuracy, and secure information handling.</p>
									</div>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
		</section>

		<section id="client" class="client">
			<div class="container">
				<div class="row title-bar">
					<div class="col-md-12">
						<h1 class="wow fadeInUp">Our Client Say</h1>
						<div class="heading-border"></div>
						<p class="wow fadeInUp" data-wow-delay="0.4s">We are committed to helping businesses build and maintain strong brand value through reliable digital solutions.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="client-cont wow fadeInUp" data-wow-delay="0.1s">
							<img src="<?php echo e(asset('buzbox/img/client/avatar-6.jpg')); ?>" class="img-fluid" alt="">
							<h5>You Could Be Here</h5>
							<h6>Our First Client</h6>
							<i class="fa fa-quote-left"></i>
							<p>We are building meaningful partnerships with businesses ready to grow digitally. Work with Skyeface Digital Ltd and be among the first to share your experience with our solutions.</p>
						</div>
					</div>
					<div class="col-md-6 col-sm-12">
						<div class="client-cont wow fadeInUp" data-wow-delay="0.3s">
							<img src="<?php echo e(asset('buzbox/img/client/avatar-2.jpg')); ?>" class="img-fluid" alt="">
							<h6>Your Story Matters</h6>
							<h6>Join Our Journey</h6>
							<i class="fa fa-quote-left"></i>
							<p>This space is reserved for our future clients. Partner with us today and let your success story be featured as we grow together.</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<div class="overlay-contact-h"></div>
		<section id="contact-h" class="bg-parallax contact-h-bg">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="contact-h-cont">
							<h3 class="cl-white">Continue The Conversation</h3><br>
							<form id="contactForm" onsubmit="submitContactForm(event)">
								<?php echo csrf_field(); ?>
								<div class="form-group cl-white">
									<label for="name">Your Name</label>
									<input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="Enter name" required>
								</div>
								<div class="form-group cl-white">
									<label for="email">Email address</label>
									<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
								</div>
								<div class="form-group cl-white">
									<label for="phone">Phone (Optional)</label>
									<input type="tel" class="form-control" id="phone" name="phone" aria-describedby="phoneHelp" placeholder="Enter phone number">
								</div>
								<div class="form-group cl-white">
									<label for="subject">Subject</label>
									<input type="text" class="form-control" id="subject" name="subject" aria-describedby="subjectHelp" placeholder="Enter subject" required>
								</div>
								<div class="form-group cl-white">
									<label for="message">Message</label>
									<textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter your message" required></textarea>
								</div>
								<button type="submit" class="btn btn-general btn-white" role="button"><i class="fa fa-arrow-circle-o-right"></i> GET CONVERSATION</button>
								<div id="formMessage" style="margin-top: 15px; display: none;"></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>

		

		<footer>
			<?php echo $__env->make('partials.footer-top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
			<?php echo $__env->make('partials.footer-bottom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
		</footer>

		<!--Global JavaScript -->
		<script src="<?php echo e(asset('buzbox/js/jquery/jquery.min.js')); ?>"></script>
		<script src="<?php echo e(asset('buzbox/js/popper/popper.min.js')); ?>"></script>
		<script src="<?php echo e(asset('buzbox/js/bootstrap/bootstrap.min.js')); ?>"></script>
		<script src="<?php echo e(asset('buzbox/js/wow/wow.min.js')); ?>"></script>
		<script src="<?php echo e(asset('buzbox/js/owl-carousel/owl.carousel.min.js')); ?>"></script>

		<!-- Plugin JavaScript -->
		<script src="<?php echo e(asset('buzbox/js/jquery-easing/jquery.easing.min.js')); ?>"></script>
		<script src="<?php echo e(asset('buzbox/js/custom.js')); ?>"></script>

		<script>
		async function submitContactForm(event) {
			event.preventDefault();

			const form = document.getElementById('contactForm');
			const messageDiv = document.getElementById('formMessage');
			const submitButton = form.querySelector('button[type="submit"]');

			const formData = {
				name: document.getElementById('name').value,
				email: document.getElementById('email').value,
				phone: document.getElementById('phone').value || null,
				subject: document.getElementById('subject').value,
				message: document.getElementById('message').value,
			};

			try {
				submitButton.disabled = true;
				submitButton.textContent = 'Sending...';

				const response = await fetch('<?php echo e(route("contact.store")); ?>', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
					},
					body: JSON.stringify(formData)
				});

				const data = await response.json();

				messageDiv.style.display = 'block';

				if (data.success) {
					messageDiv.className = 'alert alert-success';
					messageDiv.innerHTML = `<strong>Success!</strong> Your message has been sent. Ticket #${data.ticket_number} has been created. We will get back to you soon.`;
					form.reset();
					// Show tracking link
					const trackingLink = document.createElement('p');
					trackingLink.innerHTML = `<a href="/ticket/${data.ticket_number}" target="_blank" style="color: white; text-decoration: underline;"><strong>Click here to view your ticket and track responses</strong></a>`;
					messageDiv.appendChild(trackingLink);
				} else {
					messageDiv.className = 'alert alert-danger';
					messageDiv.innerHTML = `<strong>Error!</strong> ${data.message}`;
				}
			} catch (error) {
				messageDiv.style.display = 'block';
				messageDiv.className = 'alert alert-danger';
				messageDiv.innerHTML = `<strong>Error!</strong> An error occurred while sending your message. Please try again.`;
				console.error('Form submission error:', error);
			} finally {
				submitButton.disabled = false;
				submitButton.textContent = 'GET CONVERSATION';
			}
		}
		</script>

	<!-- Include Repair Booking Modal -->
	<?php echo $__env->make('partials.repair-booking-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
	<!-- Include Repair Search Modal -->
	<?php echo $__env->make('partials.repair-search-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/home.blade.php ENDPATH**/ ?>