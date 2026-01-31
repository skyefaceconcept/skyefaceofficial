<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="description" content="Our Services — {{ config('company.name') }}">
		<meta name="author" content="{{ config('company.name') }}">

		<title>{{ config('company.name') }} — Services</title>
		<link rel="shortcut icon" href="{{ \App\Helpers\CompanyHelper::favicon() }}">

		<!-- Global Stylesheets -->
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
		<link href="{{ asset('buzbox/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('buzbox/font-awesome-4.7.0/css/font-awesome.min.css') }}">
		<link rel="stylesheet" href="{{ asset('buzbox/css/animate/animate.min.css') }}">
		<link rel="stylesheet" href="{{ asset('buzbox/css/owl-carousel/owl.carousel.min.css') }}">
		<link rel="stylesheet" href="{{ asset('buzbox/css/owl-carousel/owl.theme.default.min.css') }}">
		<link rel="stylesheet" href="{{ asset('buzbox/css/style.css') }}">
	</head>

	<body id="page-top">

		<header>
			@include('partials.top-nav')
			@include('partials.navbar')
		</header>

		<!-- HERO -->
		<div id="home-p" class="home-p pages-head1 text-center">
			<div class="container">
				<h1 class="wow fadeInUp" data-wow-delay="0.1s">Our Services</h1>
				<p>Integrated Technology Solutions for Digital Transformation</p>
			</div>
		</div>

		<!--====================================================
						BUSINESS-GROWTH-P1 - MAIN SERVICES
		======================================================-->
		<section id="business-growth-p1" class="business-growth-p1 bg-gray">
			<div class="container">
				<div class="row title-bar">
					<div class="col-md-12">
						<h1 class="wow fadeInUp">Integrated Technology Services</h1>
						<div class="heading-border"></div>
						<p class="wow fadeInUp" data-wow-delay="0.4s">{{ config('company.name') }} specializes in comprehensive integrated technology services. From hardware repairs and embedded systems design to security solutions and audiovisual installations, we deliver reliable, professional solutions for businesses, religious institutions, and corporate organizations.</p>
					</div>
				</div>
				<div class="row wow animated fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s">
					<div class="col-md-3 col-sm-6 service-padding">
						<div class="service-item">
							<div class="service-item-icon">
								<i class="fa fa-paint-brush fa-3x"></i>
							</div>
							<div class="service-item-title">
								<h3>Web Design & Development</h3>
							</div>
							<div class="service-item-desc">
								<p>Create stunning, user-centric websites and web applications. Our expert designers and developers craft beautiful, functional digital solutions that drive business growth and user engagement.</p>
								<div class="content-title-underline-light"></div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 service-padding">
						<div class="service-item">
							<div class="service-item-icon">
								<i class="fa fa-laptop fa-3x"></i>
							</div>
							<div class="service-item-title">
								<h3>Device Repairs</h3>
							</div>
							<div class="service-item-desc">
								<p>Professional repair services for computers and mobile devices. Quick diagnosis, reliable repairs, and quality service to get your devices back in working condition.</p>
								<div class="content-title-underline-light"></div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 service-padding">
						<div class="service-item">
							<div class="service-item-icon">
								<i class="fa fa-code fa-3x"></i>
							</div>
							<div class="service-item-title">
								<h3>Software Programming</h3>
							</div>
							<div class="service-item-desc">
								<p>Custom software development solutions tailored to your business needs. From application development to system integration, we deliver reliable, scalable software.</p>
								<div class="content-title-underline-light"></div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 service-padding">
						<div class="service-item right-bord">
							<div class="service-item-icon">
								<i class="fa fa-shield fa-3x"></i>
							</div>
							<div class="service-item-title">
								<h3>Security Systems</h3>
							</div>
							<div class="service-item-desc">
								<p>Professional CCTV and security system installation. Protect your property with state-of-the-art surveillance and security solutions tailored to your needs.</p>
								<div class="content-title-underline-light"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Quote Modal -->
		<div class="modal fade" id="quote-modal" tabindex="-1" role="dialog" aria-labelledby="quoteModalLabel">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title" id="quoteModalLabel"><i class="fa fa-quote-left mr-2"></i>Request a Quote</h5>
						<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="closeQuoteModal();">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
						<form id="quoteForm" onsubmit="submitQuoteForm(event)">
							@csrf
							<input type="hidden" name="package" id="quote_package">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="quote_name">Your Name <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="quote_name" name="name" placeholder="John Doe" required>
									<small class="form-text text-muted">Full name (letters, spaces, apostrophes)</small>
								</div>
								<div class="form-group col-md-6">
									<label for="quote_email">Email <span class="text-danger">*</span></label>
									<input type="email" class="form-control" id="quote_email" name="email" placeholder="you@example.com" required>
									<small class="form-text text-muted">We'll send your quote response here</small>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="quote_phone">Phone (Optional)</label>
									<input type="tel" class="form-control" id="quote_phone" name="phone" placeholder="+1 (555) 123-4567">
									<small class="form-text text-muted">Include country/area code</small>
								</div>
								<div class="form-group col-md-6">
									<label for="quote_subject">Subject</label>
									<input type="text" class="form-control" id="quote_subject" name="subject" value="Quote Request" readonly>
									<small class="form-text text-muted">Automatically set</small>
								</div>
							</div>
							<div class="form-group">
								<label for="quote_details">Project Details <span class="text-danger">*</span></label>
								<textarea class="form-control" id="quote_details" name="details" rows="4" placeholder="Describe your project, requirements, timeline, budget, etc." required minlength="10"></textarea>
								<small class="form-text text-muted mb-2 d-block">Minimum 10 characters. Be as detailed as possible for better quotes.</small>

								<!-- Suggestion Hints -->
								<div class="mt-3 p-3" style="background: #f0f8ff; border-radius: 5px; border-left: 4px solid #0066cc;">
									<small class="d-block mb-2" style="color: #666; font-weight: 600;"><i class="fa fa-lightbulb-o mr-1" style="color: #ffc107;"></i>Helpful Tips - Click any suggestion to add it:</small>
									<div class="row">
										<div class="col-md-6">
											<button type="button" class="btn btn-outline-primary btn-sm btn-block mb-2" onclick="addSuggestion('What is the main goal of this project?', 'quote_details')">
												<i class="fa fa-plus mr-1"></i>Project Goal
											</button>
										</div>
										<div class="col-md-6">
											<button type="button" class="btn btn-outline-primary btn-sm btn-block mb-2" onclick="addSuggestion('Timeline: When do you need this completed?', 'quote_details')">
												<i class="fa fa-plus mr-1"></i>Timeline
											</button>
										</div>
										<div class="col-md-6">
											<button type="button" class="btn btn-outline-primary btn-sm btn-block mb-2" onclick="addSuggestion('Budget range: What is your budget for this project?', 'quote_details')">
												<i class="fa fa-plus mr-1"></i>Budget Range
											</button>
										</div>
										<div class="col-md-6">
											<button type="button" class="btn btn-outline-primary btn-sm btn-block mb-2" onclick="addSuggestion('Target audience: Who will use this?', 'quote_details')">
												<i class="fa fa-plus mr-1"></i>Target Audience
											</button>
										</div>
										<div class="col-md-6">
											<button type="button" class="btn btn-outline-primary btn-sm btn-block mb-2" onclick="addSuggestion('Current state: Do you have existing systems or infrastructure?', 'quote_details')">
												<i class="fa fa-plus mr-1"></i>Current State
											</button>
										</div>
										<div class="col-md-6">
											<button type="button" class="btn btn-outline-primary btn-sm btn-block mb-2" onclick="addSuggestion('Special requirements: Any specific features or integrations needed?', 'quote_details')">
												<i class="fa fa-plus mr-1"></i>Requirements
											</button>
										</div>
									</div>
									<small class="d-block mt-2" style="color: #999;"><em>You can mix and match suggestions or write your own details.</em></small>
								</div>
							</div>
							<div id="quoteMessage" style="margin-top:15px; display:none;" class="alert"></div>
							<div class="text-right">
								<button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" onclick="closeQuoteModal();">Cancel</button>
								<button type="submit" class="btn btn-general btn-green" id="quoteSubmitBtn">Submit Quote Request</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!--====================================================
						SERVICE TIMELINE
		======================================================-->
		<section id="service-timeline" class="bg-gray" style="padding: 80px 0; position: relative;">
			<div class="container">
				<div class="row title-bar">
					<div class="col-md-12">
						<h1 class="wow fadeInUp">Our Service Journey</h1>
						<div class="heading-border"></div>
						<p class="wow fadeInUp" data-wow-delay="0.4s">Comprehensive solutions tailored to your business needs</p>
					</div>
				</div>

				<!-- Timeline Container -->
				<div class="timeline" style="position: relative; max-width: 900px; margin: 60px auto;">
					<!-- Timeline Line -->
					<div style="position: absolute; left: 50%; width: 4px; height: 100%; background: #28a745; transform: translateX(-50%); top: 0;"></div>

					<!-- Timeline Item 1 -->
					<div class="timeline-item" style="margin-bottom: 50px; position: relative;">
						<div class="row">
							<div class="col-md-6" style="position: relative;">
								<div class="timeline-content wow fadeInLeft" data-wow-delay="0.1s" style="padding: 30px; background: rgba(255,255,255,0.95); border-radius: 8px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); text-align: right;">
									<div style="display: flex; align-items: center; justify-content: flex-end; margin-bottom: 15px;">
										<a href="#" style="color: #333; text-decoration: none; margin-right: 15px;"><b style="font-size: 15px;">{{ config('company.name') }}</b></a>
										<i class="fa fa-laptop fa-2x" style="color: #28a745;"></i>
									</div>
									<h4 style="color: #222; font-size: 24px; margin-bottom: 15px; font-weight: 700;">Professional Device Repairs</h4>
									<div style="border-bottom: 3px solid #28a745; width: 60px; margin: 15px auto 15px 0;"></div>
									<p style="color: #555; line-height: 1.7; font-size: 14px;">We provide comprehensive repair services for computers, laptops, and mobile devices. Our certified technicians offer quick diagnostics, reliable repairs, and quality service to minimize downtime.</p>
									{{-- <button class="btn btn-sm" data-toggle="modal" data-target="#login-modal" role="button" style="background: #28a745; color: white; border: none; padding: 10px 25px; margin-top: 15px; font-weight: 600;">Get Started</button> --}}
								</div>
							</div>
							<div class="col-md-6"></div>
						</div>
						<!-- Timeline Dot -->
						<div style="position: absolute; left: 50%; top: 40px; width: 24px; height: 24px; background: #28a745; border: 4px solid white; border-radius: 50%; transform: translateX(-50%); z-index: 10; box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);"></div>
					</div>

					<!-- Timeline Item 2 -->
					<div class="timeline-item" style="margin-bottom: 50px; position: relative;">
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-md-6" style="position: relative;">
								<div class="timeline-content wow fadeInRight" data-wow-delay="0.2s" style="padding: 30px; background: rgba(255,255,255,0.95); border-radius: 8px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); text-align: left;">
									<div style="display: flex; align-items: center; margin-bottom: 15px;">
										<i class="fa fa-shield fa-2x" style="color: #28a745; margin-right: 15px;"></i>
										<a href="#" style="color: #333; text-decoration: none;"><b style="font-size: 15px;">{{ config('company.name') }}</b></a>
									</div>
									<h4 style="color: #222; font-size: 24px; margin-bottom: 15px; font-weight: 700;">Advanced Security & CCTV Systems</h4>
									<div style="border-bottom: 3px solid #28a745; width: 60px; margin-bottom: 15px;"></div>
									<p style="color: #555; line-height: 1.7; font-size: 14px;">Protect your business with professional CCTV and security system installation services. We design, install, and maintain comprehensive surveillance solutions with cutting-edge technology for maximum peace of mind.</p>
									{{-- <button class="btn btn-sm" data-toggle="modal" data-target="#login-modal" role="button" style="background: #28a745; color: white; border: none; padding: 10px 25px; margin-top: 15px; font-weight: 600;">Learn More</button> --}}
								</div>
							</div>
						</div>
						<!-- Timeline Dot -->
						<div style="position: absolute; left: 50%; top: 40px; width: 24px; height: 24px; background: #28a745; border: 4px solid white; border-radius: 50%; transform: translateX(-50%); z-index: 10; box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);"></div>
					</div>

					<!-- Timeline Item 3 -->
					<div class="timeline-item" style="margin-bottom: 50px; position: relative;">
						<div class="row">
							<div class="col-md-6" style="position: relative;">
								<div class="timeline-content wow fadeInLeft" data-wow-delay="0.3s" style="padding: 30px; background: rgba(255,255,255,0.95); border-radius: 8px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); text-align: right;">
									<div style="display: flex; align-items: center; justify-content: flex-end; margin-bottom: 15px;">
										<a href="#" style="color: #333; text-decoration: none; margin-right: 15px;"><b style="font-size: 15px;">{{ config('company.name') }}</b></a>
										<i class="fa fa-flash fa-2x" style="color: #28a745;"></i>
									</div>
									<h4 style="color: #222; font-size: 24px; margin-bottom: 15px; font-weight: 700;">Reliable Power Systems & Maintenance</h4>
									<div style="border-bottom: 3px solid #28a745; width: 60px; margin: 15px auto 15px 0;"></div>
									<p style="color: #555; line-height: 1.7; font-size: 14px;">Ensure uninterrupted power supply for your business with professional inverter installation and power system maintenance services. We provide diagnostics and preventive maintenance to keep your systems running reliably.</p>
									{{-- <button class="btn btn-sm" data-toggle="modal" data-target="#login-modal" role="button" style="background: #28a745; color: white; border: none; padding: 10px 25px; margin-top: 15px; font-weight: 600;">Learn More</button> --}}
								</div>
							</div>
							<div class="col-md-6"></div>
						</div>
						<!-- Timeline Dot -->
						<div style="position: absolute; left: 50%; top: 40px; width: 24px; height: 24px; background: #28a745; border: 4px solid white; border-radius: 50%; transform: translateX(-50%); z-index: 10; box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);"></div>
					</div>
				</div>
			</div>
		</section>

		<!--====================================================
						FINANCIAL-P5 - ADDITIONAL SERVICES
		======================================================-->
		<section class="what-we-do bg-gradiant financial-p5">
			<div class="container">
				<div class="row title-bar">
					<div class="col-md-12">
						<h1 class="wow fadeInUp cl-white">Complete Service Solutions</h1>
						<div class="heading-border bg-white"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4 col-sm-6">
								<div class="what-we-desc wow fadeInUp" data-wow-delay="0.1s">
									<i class="fa fa-shield"></i>
									<h6>Security Systems Installation</h6>
									<p class="desc">Complete CCTV and security system design, installation, and configuration. We provide surveillance solutions that protect your assets with professional monitoring and maintenance support.</p>
								</div>
							</div>
							<div class="col-md-4 col-sm-6">
								<div class="what-we-desc wow fadeInUp" data-wow-delay="0.2s">
									<i class="fa fa-video-camera"></i>
									<h6>Audiovisual Solutions</h6>
									<p class="desc">We design and install professional audiovisual systems for religious institutions, corporate offices, and events. From projectors to sound systems, we deliver integrated AV solutions that enhance presentations and experiences.</p>
								</div>
							</div>
							<div class="col-md-4 col-sm-6">
								<div class="what-we-desc wow fadeInUp" data-wow-delay="0.3s">
									<i class="fa fa-flash"></i>
									<h6>Power Systems & Inverters</h6>
									<p class="desc">Inverter installation and power system maintenance. We ensure businesses have reliable backup power solutions with regular maintenance, diagnostics, and professional support.</p>
								</div>
							</div>
							<div class="col-md-4 col-sm-6">
								<div class="what-we-desc wow fadeInUp" data-wow-delay="0.4s">
									<i class="fa fa-microchip"></i>
									<h6>Embedded Systems Design</h6>
									<p class="desc">Custom embedded systems solutions for various applications. We handle hardware design, firmware development, and integration for efficient device functionality and performance optimization.</p>
								</div>
							</div>
							<div class="col-md-4 col-sm-6">
								<div class="what-we-desc wow fadeInUp" data-wow-delay="0.5s">
									<i class="fa fa-cube"></i>
									<h6>Electronic Components Supply</h6>
									<p class="desc">We import and supply quality electronic components for various applications. Our sourcing expertise ensures you get reliable components for your projects at competitive prices.</p>
								</div>
							</div>
							<div class="col-md-4 col-sm-6">
								<div class="what-we-desc wow fadeInUp" data-wow-delay="0.6s">
									<i class="fa fa-wrench"></i>
								<h6 style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal; line-height: 1.0;">Technical Support & Maintenance</h6>
									<p class="desc">Comprehensive technical support and maintenance services for all equipment. We provide diagnostics, repairs, preventive maintenance, and ongoing support to keep your systems running optimally.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!--====================================================
						SERVICE PACKAGES
		======================================================-->
		<section id="service-packages" class="bg-gray" style="padding: 80px 0;">
			<div class="container">
				<div class="row title-bar">
					<div class="col-md-12">
						<h1 class="wow fadeInUp" style="color: #222; font-weight: 700; font-size: 48px; margin-bottom: 15px;">Professional Service Packages</h1>
						<div class="heading-border"></div>
						<p class="wow fadeInUp" data-wow-delay="0.4s" style="font-size: 16px; color: #666; max-width: 700px; margin: 20px auto;">Comprehensive solutions organized across six specialized service categories. Choose the package that fits your needs or request a custom solution.</p>
					</div>
				</div>

				<!-- GROUP 1: SOFTWARE & DIGITAL SOLUTIONS -->
				<div style="margin-bottom: 80px;">
					<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 50px;">
						<h3 style="color: #28a745; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 12px;"><i class="fa fa-laptop" style="font-size: 32px;"></i>Software & Digital Solutions</h3>
					</div>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px; margin-bottom: 70px;">

					<!-- Package 1: Software Solutions -->
					<div class="wow fadeInUp" data-wow-delay="0.1s" style="transition: all 0.3s ease;">
						<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.1); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.2)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
							<!-- Icon Header -->
							<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
								<i class="fa fa-code" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
								<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Software Development</h3>
								<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">Custom Solutions</p>
							</div>

							<!-- Content -->
							<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
								<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Web Design & Development</span></li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Custom Software Programming</span></li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Responsive Web Applications</li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Database Design & Integration</li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>E-commerce Solutions</li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>API Development & Integration</li>
									<li style="padding: 12px 0;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Ongoing Support & Maintenance</li>
								</ul>

								<!-- Button -->
								<button type="button" class="btn open-quote-btn" data-package="Software Development" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
							</div>
						</div>
					</div>

					<!-- Package: Digital Media & Signage -->
					<div class="wow fadeInUp" data-wow-delay="0.2s" style="transition: all 0.3s ease;">
						<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.1); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.2)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
							<!-- Icon Header -->
							<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
								<i class="fa fa-film" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
								<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Digital Media & Signage</h3>
								<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">Content Solutions</p>
							</div>

							<!-- Content -->
							<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
								<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Digital Signage Solutions</span></li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Media Player Setup & Config</span></li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Content Management Systems</li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Display Installation & Configuration</li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Remote Monitoring & Updates</li>
									<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Video Content Creation Support</li>
									<li style="padding: 12px 0;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Technical Support & Maintenance</li>
								</ul>

								<!-- Button -->
								<button type="button" class="btn open-quote-btn" data-package="Digital Media & Signage" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
							</div>
						</div>
					</div>

				</div>
				</div>

				<!-- GROUP 2: HARDWARE & DEVICE SERVICES -->
				<div style="margin-bottom: 80px;">
					<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 50px;">
						<h3 style="color: #28a745; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 12px;"><i class="fa fa-microchip" style="font-size: 32px;"></i>Hardware & Device Services</h3>
					</div>
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px;">

						<!-- Package 2: Hardware & Embedded Systems -->
						<div class="wow fadeInUp" data-wow-delay="0.3s" style="transition: all 0.3s ease;">
							<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 3px solid #28a745; height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer; position: relative;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.3)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
								<!-- Popular Badge -->
								<div style="position: absolute; top: 15px; right: 15px; background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white; padding: 8px 16px; border-radius: 25px; font-size: 11px; font-weight: 700; box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);">⭐ POPULAR</div>

								<!-- Icon Header -->
								<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
									<i class="fa fa-microchip" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
									<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Hardware & Embedded</h3>
									<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">Repair & Design</p>
								</div>

								<!-- Content -->
								<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
									<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Device Repair Services</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Embedded Systems Design</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Computer & Laptop Repairs</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Mobile Device Repairs</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Firmware Development</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Electronic Components Supply</li>
										<li style="padding: 12px 0;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Technical Support & Consultation</li>
									</ul>

									<!-- Button -->
									<button type="button" class="btn open-quote-btn" data-package="Hardware & Embedded Systems" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- GROUP 3: POWER & ENERGY SYSTEMS -->
				<div style="margin-bottom: 80px;">
					<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 50px;">
						<h3 style="color: #28a745; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 12px;"><i class="fa fa-flash" style="font-size: 32px;"></i>Power & Energy Systems</h3>
					</div>
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px;">

						<!-- Package 3: Power Systems -->
						<div class="wow fadeInUp" data-wow-delay="0.1s" style="transition: all 0.3s ease;">
							<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.1); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.2)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
								<!-- Icon Header -->
								<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
									<i class="fa fa-flash" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
									<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Power Systems & Energy</h3>
									<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">Inverter & UPS Solutions</p>
								</div>

								<!-- Content -->
								<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
									<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Inverter Installation & Setup</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Power System Maintenance</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>System Design & Planning</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Battery & UPS Installation</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>System Testing & Commissioning</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Preventive Maintenance Plans</li>
										<li style="padding: 12px 0;"><i class="fa fa-plug" style="color: #28a745; margin-right: 12px;"></i>Emergency Support & Service</li>
									</ul>

									<!-- Button -->
									<button type="button" class="btn open-quote-btn" data-package="Power Systems & Energy" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- GROUP 4: SECURITY & AUDIOVISUAL SYSTEMS -->
				<div style="margin-bottom: 80px;">
					<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 50px;">
						<h3 style="color: #28a745; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 12px;"><i class="fa fa-shield" style="font-size: 32px;"></i>Security & Audiovisual Systems</h3>
					</div>
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px;">

						<!-- Package: Security Systems -->
						<div class="wow fadeInUp" data-wow-delay="0.2s" style="transition: all 0.3s ease;">
							<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.1); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.2)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
								<!-- Icon Header -->
								<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
									<i class="fa fa-shield" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
									<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Security Systems</h3>
									<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">CCTV & Monitoring</p>
								</div>

								<!-- Content -->
								<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
									<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">CCTV System Design & Installation</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Professional Surveillance Setup</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>HD & 4K Camera Installation</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Network Video Recorder (NVR) Setup</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Remote Monitoring & Mobile Access</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>System Maintenance & Support</li>
										<li style="padding: 12px 0;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>24/7 Security Monitoring Support</li>
									</ul>

									<!-- Button -->
									<button type="button" class="btn open-quote-btn" data-package="Security Systems" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
								</div>
							</div>
						</div>

						<!-- Package: Audiovisual Solutions -->
						<div class="wow fadeInUp" data-wow-delay="0.3s" style="transition: all 0.3s ease;">
							<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.1); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.2)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
								<!-- Icon Header -->
								<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
									<i class="fa fa-video-camera" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
									<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Audiovisual Solutions</h3>
									<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">Integration & Setup</p>
								</div>

								<!-- Content -->
								<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
									<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">AV System Design & Integration</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Professional Installation Services</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Projector & Display Installation</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Sound System Configuration</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Video Conferencing Setup</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Control System Programming</li>
										<li style="padding: 12px 0;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Ongoing Maintenance & Support</li>
									</ul>

									<!-- Button -->
									<button type="button" class="btn open-quote-btn" data-package="Audiovisual Solutions" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- GROUP 5: NETWORKING & INFRASTRUCTURE -->
				<div style="margin-bottom: 80px;">
					<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 50px;">
						<h3 style="color: #28a745; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 12px;"><i class="fa fa-sitemap" style="font-size: 32px;"></i>Networking & Infrastructure</h3>
					</div>
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px;">

						<!-- Package 4: Networking Services -->
						<div class="wow fadeInUp" data-wow-delay="0.1s" style="transition: all 0.3s ease;">
							<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.1); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.2)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
								<!-- Icon Header -->
								<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
									<i class="fa fa-sitemap" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
									<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Networking Services</h3>
									<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">Network & Infrastructure</p>
								</div>

								<!-- Content -->
								<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
									<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Network Design & Implementation</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">WiFi Setup & Configuration</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Network Security & Firewalls</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Cabling & Infrastructure</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Network Monitoring & Management</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Data Backup & Recovery Solutions</li>
										<li style="padding: 12px 0;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>24/7 Network Support</li>
									</ul>

									<!-- Button -->
									<button type="button" class="btn open-quote-btn" data-package="Networking Services" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- GROUP 6: ENTERPRISE & SUPPORT SERVICES -->
				<div style="margin-bottom: 80px;">
					<div style="display: flex; align-items: center; justify-content: center; margin-bottom: 50px;">
						<h3 style="color: #28a745; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 12px;"><i class="fa fa-wrench" style="font-size: 32px;"></i>Enterprise & Support Services</h3>
					</div>
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px;">

						<!-- Package: Technical Support & Maintenance -->
						<div class="wow fadeInUp" data-wow-delay="0.2s" style="transition: all 0.3s ease;">
							<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.1); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.2)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
								<!-- Icon Header -->
								<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
									<i class="fa fa-wrench" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
									<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Tech Support & Maintenance</h3>
									<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">Ongoing Support</p>
								</div>

								<!-- Content -->
								<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
									<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">24/7 Technical Support</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Preventive Maintenance Plans</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>System Diagnostics & Troubleshooting</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Remote & On-Site Support</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Hardware Repairs & Upgrades</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Software Updates & Patching</li>
										<li style="padding: 12px 0;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>SLA-Based Support Agreements</li>
									</ul>

									<!-- Button -->
									<button type="button" class="btn open-quote-btn" data-package="Technical Support & Maintenance" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
								</div>
							</div>
						</div>

						<!-- Package: Consulting & Audit Services -->
						<div class="wow fadeInUp" data-wow-delay="0.3s" style="transition: all 0.3s ease;">
							<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.1); height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.boxShadow='0 15px 40px rgba(40, 167, 69, 0.2)'; this.style.transform='translateY(-8px)';" onmouseout="this.style.boxShadow='0 5px 20px rgba(40, 167, 69, 0.08)'; this.style.transform='translateY(0)';">
								<!-- Icon Header -->
								<div style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); padding: 35px 20px; text-align: center;">
									<i class="fa fa-clipboard" style="color: white; font-size: 50px; display: block; margin-bottom: 10px;"></i>
									<h3 style="font-size: 24px; color: white; margin: 0; font-weight: 700;">Consulting & Audit</h3>
									<p style="color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">Strategic Planning</p>
								</div>

								<!-- Content -->
								<div style="padding: 30px; flex: 1; display: flex; flex-direction: column;">
									<ul style="list-style: none; padding: 0; text-align: left; flex: 1;">
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">IT Infrastructure Audit</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><span style="color: #222; font-weight: 600;">Security Assessment & Planning</span></li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Technology Roadmap Development</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Cost Optimization Analysis</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Compliance & Risk Assessment</li>
										<li style="padding: 12px 0; border-bottom: 1px solid #eee;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Vendor Evaluation & Selection</li>
										<li style="padding: 12px 0;"><i class="fa fa-check" style="color: #28a745; margin-right: 12px;"></i>Change Management Consulting</li>
									</ul>

									<!-- Button -->
									<button type="button" class="btn open-quote-btn" data-package="Consulting & Audit Services" style="margin-top: 25px; width: 100%; padding: 14px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-size: 15px;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">Request Quote</button>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- Additional Info Row -->
				<div class="row" style="margin-top: 40px;">
					<div class="col-md-12">
						<div class="text-center" style="background: rgba(40, 167, 69, 0.05); padding: 40px; border-radius: 8px;">
							<h4 style="color: #222; margin-bottom: 20px;">Need a Bundled or Custom Solution?</h4>
							<p style="color: #555; margin-bottom: 25px;">We offer flexible bundled packages and fully customized solutions that combine services from multiple categories to match your unique business requirements, budget, and timeline.</p>
							<button class="btn btn-general btn-green" data-toggle="modal" data-target="#login-modal" style="background: #28a745; border: none; padding: 12px 40px; font-weight: 600;">Contact Our Specialists</button>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!--====================================================
						WHY CHOOSE US
		======================================================-->
		<section id="why-choose-us" class="financial-p5 bg-white">
			<div class="container">
				<div class="row title-bar">
					<div class="col-md-12">
						<h1 class="wow fadeInUp">Why Choose {{ config('company.name') }}</h1>
						<div class="heading-border"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="why-choose-item wow fadeInUp" data-wow-delay="0.1s">
							<div class="why-choose-icon">
								<i class="fa fa-trophy fa-2x"></i>
							</div>
							<div class="why-choose-desc">
								<h5>Proven Track Record</h5>
								<p>With years of experience delivering successful projects across industries, we have a proven track record of transforming businesses through technology.</p>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="why-choose-item wow fadeInUp" data-wow-delay="0.2s">
							<div class="why-choose-icon">
								<i class="fa fa-users fa-2x"></i>
							</div>
							<div class="why-choose-desc">
								<h5>Expert Team</h5>
								<p>Our talented team of designers, developers, and strategists are passionate about creating exceptional solutions that exceed expectations.</p>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="why-choose-item wow fadeInUp" data-wow-delay="0.3s">
							<div class="why-choose-icon">
								<i class="fa fa-handshake-o fa-2x"></i>
							</div>
							<div class="why-choose-desc">
								<h5>Partnership Approach</h5>
								<p>We view our clients as partners. Your success is our success, and we work collaboratively to ensure we deliver maximum value.</p>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="why-choose-item wow fadeInUp" data-wow-delay="0.4s">
							<div class="why-choose-icon">
								<i class="fa fa-rocket fa-2x"></i>
							</div>
							<div class="why-choose-desc">
								<h5>Innovation First</h5>
								<p>We stay ahead of industry trends and technologies to deliver cutting-edge solutions that give you a competitive advantage.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!--====================================================
				DEVICE REPAIR BOOKING SECTION
		======================================================-->
		<section id="device-repair" class="bg-white" style="padding: 80px 0;">
			<div class="container">
				<div class="row title-bar">
					<div class="col-md-12">
						<h1 class="wow fadeInUp" style="color: #222; font-weight: 700; font-size: 48px; margin-bottom: 15px;">Quick Device Repair Booking</h1>
						<div class="heading-border"></div>
						<p class="wow fadeInUp" data-wow-delay="0.4s" style="font-size: 16px; color: #666; max-width: 700px; margin: 20px auto;">Get your devices repaired quickly! Submit your device details below and receive an instant tracking number to monitor your repair progress.</p>
					</div>
				</div>

				<div class="row" style="margin-top: 50px;">
					<div class="col-md-8 offset-md-2">
						<div style="background: linear-gradient(135deg, #fff 0%, #f8fffe 100%); border-radius: 15px; box-shadow: 0 8px 30px rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.1); padding: 40px;">
							<form id="repairBookingForm" onsubmit="submitRepairBooking(event)">
								@csrf

								<!-- Row 1: Name and Email -->
								<div class="form-row" style="margin-bottom: 25px;">
									<div class="form-group col-md-6">
										<label for="repair_name" style="font-weight: 600; color: #222; margin-bottom: 8px;">Your Full Name <span style="color: #dc3545;">*</span></label>
										<input type="text" class="form-control" id="repair_name" name="name" placeholder="John Doe" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
									</div>
									<div class="form-group col-md-6">
										<label for="repair_email" style="font-weight: 600; color: #222; margin-bottom: 8px;">Email Address <span style="color: #dc3545;">*</span></label>
										<input type="email" class="form-control" id="repair_email" name="email" placeholder="you@example.com" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
									</div>
								</div>

								<!-- Row 2: Phone and Device Type -->
								<div class="form-row" style="margin-bottom: 25px;">
									<div class="form-group col-md-6">
										<label for="repair_phone" style="font-weight: 600; color: #222; margin-bottom: 8px;">Phone Number <span style="color: #dc3545;">*</span></label>
										<input type="tel" class="form-control" id="repair_phone" name="phone" placeholder="+1 (555) 123-4567" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
									</div>
									<div class="form-group col-md-6">
										<label for="repair_device_type" style="font-weight: 600; color: #222; margin-bottom: 8px;">Device Type <span style="color: #dc3545;">*</span></label>
										<select class="form-control" id="repair_device_type" name="device_type" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
											<option value="">-- Select Device Type --</option>
											<option value="Laptop">Laptop</option>
											<option value="Desktop Computer">Desktop Computer</option>
											<option value="Mobile Phone">Mobile Phone</option>
											<option value="Tablet">Tablet</option>
											<option value="Printer">Printer</option>
											<option value="Other">Other Electronic Device</option>
										</select>
									</div>
								</div>

								<!-- Row 3: Device Brand and Model -->
								<div class="form-row" style="margin-bottom: 25px;">
									<div class="form-group col-md-6">
										<label for="repair_brand" style="font-weight: 600; color: #222; margin-bottom: 8px;">Brand <span style="color: #dc3545;">*</span></label>
										<input type="text" class="form-control" id="repair_brand" name="brand" placeholder="e.g., Apple, Dell, Samsung" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
									</div>
									<div class="form-group col-md-6">
										<label for="repair_model" style="font-weight: 600; color: #222; margin-bottom: 8px;">Model <span style="color: #dc3545;">*</span></label>
										<input type="text" class="form-control" id="repair_model" name="model" placeholder="e.g., MacBook Pro, ThinkPad X1" required style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
									</div>
								</div>

								<!-- Issue Description -->
								<div class="form-group" style="margin-bottom: 25px;">
									<label for="repair_issue" style="font-weight: 600; color: #222; margin-bottom: 8px;">Issue Description <span style="color: #dc3545;">*</span></label>
									<textarea class="form-control" id="repair_issue" name="issue_description" rows="5" placeholder="Describe the problem you're experiencing..." required minlength="10" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; resize: vertical;"></textarea>
									<small style="color: #999; display: block; margin-top: 5px;">Minimum 10 characters. Describe the symptoms and any errors you see.</small>
								</div>

								<!-- Urgency -->
								<div class="form-group" style="margin-bottom: 25px;">
									<label for="repair_urgency" style="font-weight: 600; color: #222; margin-bottom: 8px;">Repair Urgency</label>
									<select class="form-control" id="repair_urgency" name="urgency" style="border: 1px solid #ddd; border-radius: 8px; padding: 12px;">
										<option value="Normal">Normal (5-7 days)</option>
										<option value="Express">Express (2-3 days)</option>
										<option value="Urgent">Urgent (Next day)</option>
									</select>
								</div>

								<!-- Estimated Cost Display -->
								<div style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%); border-radius: 12px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #28a745; display: none;" id="priceDisplay">
									<div style="display: flex; justify-content: space-between; align-items: center;">
										<div>
											<p style="margin: 0; color: #666; font-size: 14px; font-weight: 500;">Estimated Diagnosis Fee</p>
											<p style="margin: 5px 0 0 0; color: #222; font-size: 12px;">Base cost (may vary after diagnosis)</p>
										</div>
										<div style="text-align: right;">
											<p style="margin: 0; color: #28a745; font-size: 28px; font-weight: 700;" id="priceAmount">$0.00</p>
											<p style="margin: 5px 0 0 0; color: #999; font-size: 12px;">+ Additional parts cost if needed</p>
										</div>
									</div>
									<input type="hidden" id="repair_cost_estimate" name="cost_estimate" value="0">
								</div>

								<!-- Message Display -->
								<div id="repairMessage" style="margin-bottom: 20px; display: none;" class="alert"></div>

								<!-- Submit Button -->
								<div style="text-align: center;">
									<button type="submit" id="repairSubmitBtn" class="btn" style="background: linear-gradient(135deg, #28a745 0%, #1fa935 100%); color: white; border: none; padding: 14px 50px; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: 16px; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 8px 20px rgba(40, 167, 69, 0.3)';" onmouseout="this.style.boxShadow='none';">
										<i class="fa fa-check mr-2"></i>Submit Repair Request
									</button>
								</div>
							</form>
						</div>

						<!-- Tracking Info Box -->
						<div style="margin-top: 40px; background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.02) 100%); border-radius: 12px; padding: 30px; border-left: 5px solid #28a745;">
							<h5 style="color: #28a745; font-weight: 700; margin-bottom: 15px;"><i class="fa fa-info-circle mr-2"></i>After Booking</h5>
							<ul style="list-style: none; padding: 0; color: #555; line-height: 1.8;">
								<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Instant Tracking Number:</strong> You'll receive a unique invoice/ticket number immediately after booking</li>
								<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Email Confirmation:</strong> A detailed confirmation will be sent to your email with repair details</li>
								<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Track Progress:</strong> Use your tracking number to check repair status and estimated completion time</li>
								<li><i class="fa fa-check" style="color: #28a745; margin-right: 12px; font-weight: bold;"></i><strong>Invoice Management:</strong> Your invoice number serves as your ticket to track every stage of the repair process</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!--====================================================
						CTA SECTION
		======================================================-->
		<section id="cta-section" class="bg-gradiant financial-p5">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="cl-white wow fadeInUp">Ready to transform your business?</h2>
						<p class="cl-white wow fadeInUp" data-wow-delay="0.2s">Let's discuss how our services can help you achieve your goals</p>
						<button class="btn btn-general btn-white wow fadeInUp" data-wow-delay="0.4s" data-toggle="modal" data-target="#login-modal" role="button">Get In Touch Today</button>
					</div>
				</div>
			</div>
		</section>

		<!--====================================================
					CONTACT MODAL
		======================================================-->
		<section id="login">
			<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="loginModalLabel">Log In</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div id="loginMessage" style="display: none;"></div>

							<form id="loginForm" method="POST" action="{{ route('login') }}">
								@csrf

								<div class="mb-3">
									<label for="modal-username" class="form-label">Username</label>
									<input id="modal-username" name="username" required autofocus class="form-control" />
								</div>

								<div class="mb-3">
									<label for="modal-password" class="form-label">Password</label>
									<input id="modal-password" name="password" type="password" required class="form-control" />
								</div>

								<div class="mb-3 form-check">
									<input type="checkbox" class="form-check-input" id="modal-remember" name="remember">
									<label class="form-check-label" for="modal-remember">Remember me</label>
								</div>

								<div class="text-right">
									<button type="submit" class="btn btn-general btn-green">Log In</button>
								</div>
							</form>

							<div class="mt-3 text-center">
								<p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- FOOTER -->
		<footer>
			@include('partials.footer-top')
			@include('partials.footer-bottom')
		</footer>

		<!-- Global Javascripts -->
		<script src="{{ asset('buzbox/js/jquery-3.3.1.min.js') }}"></script>
		<script src="{{ asset('buzbox/js/bootstrap/popper.min.js') }}"></script>
		<script src="{{ asset('buzbox/js/bootstrap/bootstrap.min.js') }}"></script>
		<script src="{{ asset('buzbox/js/smoothscroll.js') }}"></script>
		<script src="{{ asset('buzbox/js/jquery.easing.min.js') }}"></script>
		<script src="{{ asset('buzbox/js/wow.min.js') }}"></script>
		<script src="{{ asset('buzbox/js/owl.carousel.min.js') }}"></script>
		<script src="{{ asset('buzbox/js/custom.js') }}"></script>

			<script>
			// Handle login form - allow normal submission
			document.addEventListener('DOMContentLoaded', function() {
				const loginForm = document.getElementById('loginForm');
				if (loginForm) {
					// Allow the form to submit normally to the login route
					// Laravel will authenticate and the modal will close on successful login
				}
			});
			</script>

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

					const response = await fetch('{{ route("contact.store") }}', {
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
						const trackingLink = document.createElement('p');
						trackingLink.innerHTML = `<a href="/ticket/${data.ticket_number}" target="_blank" style="text-decoration: underline;"><strong>Click here to view your ticket and track responses</strong></a>`;
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
					submitButton.textContent = 'Send Message';
				}
			}
			</script>

			<script>
			// Function to open quote modal
			function openQuoteModal(packageName) {
				console.log('openQuoteModal called with:', packageName);

				// Try jQuery first
				if (typeof $ !== 'undefined') {
					$('#quote_package').val(packageName || '');
					$('#quote_subject').val((packageName || '') + ' - Quote Request');
					$('#quoteModalLabel').text('Request a Quote — ' + (packageName || ''));
					$('#quoteMessage').hide().removeClass();
					$('#quote_name').val('');
					$('#quote_email').val('');
					$('#quote_phone').val('');
					$('#quote_details').val('');
					$('#quote-modal').modal('show');
					console.log('Modal opened with jQuery');
				} else {
					// Fallback to plain JavaScript
					document.getElementById('quote_package').value = packageName || '';
					document.getElementById('quote_subject').value = (packageName || '') + ' - Quote Request';
					document.getElementById('quoteModalLabel').textContent = 'Request a Quote — ' + (packageName || '');
					const modal = document.getElementById('quote-modal');
					modal.classList.add('show');
					modal.style.display = 'block';
					document.body.classList.add('modal-open');
					console.log('Modal opened with vanilla JS');
				}
			}

			// Fill quote modal package when opening - jQuery way
			if (typeof $ !== 'undefined') {
				$(document).on('click', '.open-quote-btn', function (e) {
					e.preventDefault();
					e.stopPropagation();
					var pkg = $(this).data('package') || '';
					console.log('jQuery: Opening quote modal for package:', pkg);
					openQuoteModal(pkg);
				});
			}

			// Also add plain JavaScript event listeners
			document.addEventListener('click', function(e) {
				if (e.target.closest('.open-quote-btn')) {
					e.preventDefault();
					e.stopPropagation();
					const btn = e.target.closest('.open-quote-btn');
					const pkg = btn.getAttribute('data-package') || '';
					console.log('Plain JS: Opening quote modal for package:', pkg);
					openQuoteModal(pkg);
				}
			});

			// Close quote modal function
			function closeQuoteModal() {
				console.log('Closing quote modal');
				const modal = document.getElementById('quote-modal');
				if (typeof jQuery !== 'undefined') {
					jQuery(modal).modal('hide');
				} else if (typeof $ !== 'undefined') {
					$(modal).modal('hide');
				} else {
					modal.classList.remove('show');
					modal.style.display = 'none';
					document.body.classList.remove('modal-open');
				}
				return false;
			}

			// Add suggestion text to textarea
			function addSuggestion(suggestion, textareaId) {
				const textarea = document.getElementById(textareaId);
				const currentText = textarea.value.trim();

				// If empty, just add the suggestion
				if (!currentText) {
					textarea.value = suggestion;
				} else {
					// Otherwise, add on a new line
					textarea.value = currentText + '\n' + suggestion;
				}

				// Focus on textarea and update character count
				textarea.focus();
				textarea.scrollTop = textarea.scrollHeight;

				// Update character counter if it exists
				updateCharacterCount(textareaId);
			}

			// Update character count display (optional)
			function updateCharacterCount(textareaId) {
				const textarea = document.getElementById(textareaId);
				const charCount = textarea.value.length;
				// You can add a character counter display here if desired
				console.log('Characters: ' + charCount);
			}

			async function submitQuoteForm(event) {
				event.preventDefault();

				const form = document.getElementById('quoteForm');
				const messageDiv = document.getElementById('quoteMessage');
				const submitButton = form.querySelector('button[type="submit"]');

				const payload = {
					package: document.getElementById('quote_package').value,
					name: document.getElementById('quote_name').value,
					email: document.getElementById('quote_email').value,
					phone: document.getElementById('quote_phone').value || null,
					details: document.getElementById('quote_details').value,
				};

				try {
					submitButton.disabled = true;
					submitButton.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Submitting...';

					// CSRF token fallback: prefer meta tag, then hidden input
					let csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
					if (!csrfToken) {
						const tokenInput = document.querySelector('input[name="_token"]');
						csrfToken = tokenInput ? tokenInput.value : null;
					}

					const response = await fetch('{{ route("quotes.store") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': csrfToken || ''
						},
						body: JSON.stringify(payload)
					});

					const data = await response.json().catch(() => null);
					messageDiv.style.display = 'block';
					messageDiv.innerHTML = '';

					if (!response.ok) {
						// Handle validation errors (422) or quota limit errors (429)
						let errHtml = '';
						if (data && data.errors) {
							for (const key in data.errors) {
								errHtml += `<div><i class="fa fa-exclamation-circle mr-2"></i>${data.errors[key].join('<br>')}</div>`;
							}
						} else if (data && data.message) {
							errHtml = `<div><i class="fa fa-exclamation-circle mr-2"></i>${data.message}</div>`;
						} else {
							errHtml = '<div><i class="fa fa-exclamation-circle mr-2"></i>Unable to submit quote. Please try again later.</div>';
						}
						messageDiv.className = 'alert alert-danger';
						messageDiv.innerHTML = errHtml;
						return;
					}

					if (data && data.success) {
						messageDiv.className = 'alert alert-success';
						messageDiv.innerHTML = `
							<div>
								<i class="fa fa-check-circle mr-2"></i>
								<strong>Success!</strong> Your quote request has been submitted.
								<br><small>Quote ID: <strong>${data.quote_id}</strong></small>
								<br><small>Check your email for a confirmation message.</small>
							</div>
						`;
						form.reset();

						// Show tracking info
						setTimeout(() => {
							messageDiv.innerHTML += `
								<hr>
								<div style="font-size: 12px;">
									<strong>Track your quote:</strong> You can track the status of your quote using your email and quote ID.
								</div>
							`;
						}, 500);

						// Close modal after 3 seconds
						setTimeout(() => {
							closeQuoteModal();
						}, 3000);
					} else {
						messageDiv.className = 'alert alert-danger';
						messageDiv.innerHTML = `<div><i class="fa fa-exclamation-circle mr-2"></i><strong>Error!</strong> ${data && data.message ? data.message : 'Unable to submit quote.'}</div>`;
					}
				} catch (error) {
					messageDiv.style.display = 'block';
					messageDiv.className = 'alert alert-danger';
					messageDiv.innerHTML = `<div><i class="fa fa-exclamation-circle mr-2"></i><strong>Error!</strong> An error occurred while submitting your quote. Please try again.</div>`;
					console.error('Quote submission error:', error);
				} finally {
					submitButton.disabled = false;
					submitButton.innerHTML = 'Submit Quote Request';
				}
			}

		<script>
			// Device repair pricing based on type
			const repairPricing = {
				'Laptop': 35.00,
				'Desktop Computer': 30.00,
				'Mobile Phone': 25.00,
				'Tablet': 28.00,
				'Printer': 40.00,
				'Other': 50.00
			};

			// Update price when device type changes
			function updateRepairPrice() {
				const deviceType = document.getElementById('repair_device_type').value;
				const priceDisplay = document.getElementById('priceDisplay');
				const priceAmount = document.getElementById('priceAmount');
				const costInput = document.getElementById('repair_cost_estimate');

				if (deviceType && repairPricing[deviceType]) {
					const price = repairPricing[deviceType];
					priceAmount.textContent = '$' + price.toFixed(2);
					costInput.value = price;
					priceDisplay.style.display = 'block';
				} else {
					priceDisplay.style.display = 'none';
					costInput.value = '0';
				}
			}

			// Add event listener to device type dropdown
			document.addEventListener('DOMContentLoaded', function() {
				const deviceTypeSelect = document.getElementById('repair_device_type');
				if (deviceTypeSelect) {
					deviceTypeSelect.addEventListener('change', updateRepairPrice);
				}
			});

			async function submitRepairBooking(event) {
				event.preventDefault();

				const form = document.getElementById('repairBookingForm');
				const messageDiv = document.getElementById('repairMessage');
				const submitButton = form.querySelector('button[type="submit"]');

				const payload = {
					name: document.getElementById('repair_name').value,
					email: document.getElementById('repair_email').value,
					phone: document.getElementById('repair_phone').value,
					device_type: document.getElementById('repair_device_type').value,
					brand: document.getElementById('repair_brand').value,
					model: document.getElementById('repair_model').value,
					issue_description: document.getElementById('repair_issue').value,
					urgency: document.getElementById('repair_urgency').value,
					cost_estimate: parseFloat(document.getElementById('repair_cost_estimate').value) || 0,
				};

				try {
					submitButton.disabled = true;
					submitButton.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i>Processing...';

					let csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : null;
					if (!csrfToken) {
						const tokenInput = document.querySelector('input[name="_token"]');
						csrfToken = tokenInput ? tokenInput.value : null;
					}

					const response = await fetch('{{ route("repairs.store") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': csrfToken || ''
						},
						body: JSON.stringify(payload)
					});

					const data = await response.json().catch(() => null);
					messageDiv.style.display = 'block';
					messageDiv.innerHTML = '';

					if (!response.ok) {
						let errHtml = '';
						if (data && data.errors) {
							for (const key in data.errors) {
								errHtml += `<div><i class="fa fa-exclamation-circle mr-2"></i>${data.errors[key].join('<br>')}</div>`;
							}
						} else if (data && data.message) {
							errHtml = `<div><i class="fa fa-exclamation-circle mr-2"></i>${data.message}</div>`;
						} else {
							errHtml = '<div><i class="fa fa-exclamation-circle mr-2"></i>Unable to submit repair request. Please try again later.</div>';
						}
						messageDiv.className = 'alert alert-danger';
						messageDiv.innerHTML = errHtml;
						return;
					}

					if (data && data.success) {
						messageDiv.className = 'alert alert-success';
						const trackingNumber = data.tracking_number || data.invoice_number;
						messageDiv.innerHTML = `
							<div>
								<i class="fa fa-check-circle mr-2"></i>
								<strong>Booking Confirmed!</strong> Your device repair has been scheduled.
								<br><br>
								<div style="background: rgba(0,0,0,0.1); padding: 15px; border-radius: 8px; margin: 15px 0;">
									<strong style="font-size: 16px;">YOUR TRACKING NUMBER: <span style="color: #28a745; font-size: 18px;">${trackingNumber}</span></strong>
									<br><small>Save this number to track your repair progress</small>
								</div>
								<small>Invoice Number: <strong>${data.invoice_number}</strong></small>
								<br><small>Check your email for confirmation and detailed instructions.</small>
							</div>
						`;
						form.reset();

						// Scroll to message
						setTimeout(() => {
							messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
						}, 300);
					} else {
						messageDiv.className = 'alert alert-danger';
						messageDiv.innerHTML = `<div><i class="fa fa-exclamation-circle mr-2"></i><strong>Error!</strong> ${data && data.message ? data.message : 'Unable to process your repair booking.'}</div>`;
					}
				} catch (error) {
					messageDiv.style.display = 'block';
					messageDiv.className = 'alert alert-danger';
					messageDiv.innerHTML = `<div><i class="fa fa-exclamation-circle mr-2"></i><strong>Error!</strong> An error occurred. Please try again.</div>`;
					console.error('Repair booking error:', error);
				} finally {
					submitButton.disabled = false;
					submitButton.innerHTML = '<i class="fa fa-check mr-2"></i>Submit Repair Request';
				}
			}
			</script>

			<!-- Include Repair Booking Modal -->
			@include('partials.repair-booking-modal')

	</body>

</html>
