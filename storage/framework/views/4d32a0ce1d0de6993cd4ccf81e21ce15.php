<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="About <?php echo e(config('company.name')); ?>">
        <meta name="author" content="<?php echo e(config('company.name')); ?>">

        <title><?php echo e(config('company.name')); ?> — About</title>
	        <link rel="shortcut icon" href="<?php echo e(\App\Helpers\CompanyHelper::favicon()); ?>">

        <!-- Global Stylesheets -->
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
        <link href="<?php echo e(asset('buzbox/css/bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo e(asset('buzbox/font-awesome-4.7.0/css/font-awesome.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/animate/animate.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/owl-carousel/owl.carousel.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/owl-carousel/owl.theme.default.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/style.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('buzbox/css/about.css')); ?>">
    </head>

    <body id="page-top">

        <header>
            <?php echo $__env->make('partials.top-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </header>

        <!-- HERO -->
        <div id="home-p" class="home-p pages-head1 text-center">
            <div class="container">
                <h1 class="wow fadeInUp" data-wow-delay="0.1s"><?php echo e(config('company.name')); ?> — Building digital solutions that grow businesses</h1>
                <p>Learn who we are, what we do, and why clients trust us.</p>
            </div>
        </div>

        <!-- ABOUT INTRO -->
        <section id="about-p1">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="about-p1-cont">
                            <h1>About <?php echo e(config('company.name')); ?></h1>
                            <p><?php echo e(config('company.name')); ?> is a results-driven business technology company focused on helping organizations transform, grow, and innovate. Our team combines strategic insight with practical execution to deliver digital products, services, and experiences that create measurable value.</p>
                            <p>Since our founding, we've partnered with clients across industries to solve complex challenges — from modernizing legacy systems to designing customer-centric digital experiences. We pride ourselves on a collaborative approach, deep technical expertise, and a commitment to long-term client success.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="about-p1-img">
                            <img src="<?php echo e(asset('buzbox/img/ipad-tablet.png')); ?>" class="img-fluid wow fadeInUp" data-wow-delay="0.1s" alt="<?php echo e(config('company.name')); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CAPABILITIES -->
        <section class="about-p2 bg-gradiant">
            <div class="container-fluid">
                <div class="row">
                    <div class="about-p2-heading">
                        <h1 class="cl-white">Global leader in technology services</h1>
                        <div class="heading-border-light bg-white"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="about-p2-cont cl-white">
                            <img src="<?php echo e(asset('buzbox/img/news/news-5.jpg')); ?>" class="img-fluid wow fadeInUp" data-wow-delay="0.1s" alt="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="about-p2-cont cl-white wow fadeInUp" data-wow-delay="0.3s">
                            <h5>WEB &amp; INTERACTIVE DESIGN</h5>
                            <p class="cl-white">The most impressive websites and app experiences are rooted in smart design, clear vision, and the right technology. We design with users in mind and build for scale.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="about-p2-cont cl-white wow fadeInUp" data-wow-delay="0.5s">
                            <h5>BRANDING &amp; STRATEGY</h5>
                            <p class="cl-white">We pair strategic thinking with creative execution to build brands and digital experiences that resonate and perform.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STORY / DETAILS -->
        <section id="story" class="about-p3">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="story-desc">
                            <h3>Our Experience</h3>
                            <div class="heading-border-light"></div>
                            <p>We bring domain knowledge and technical excellence to every engagement. Our teams have delivered projects in web and mobile development, cloud migrations, data analytics, and customer experience design. We measure success by the outcomes we deliver for our clients.</p>
                            <p>Collaboration, transparency, and a focus on measurable results guide how we work — ensuring projects run smoothly and stakeholders stay informed every step of the way.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="story-himg">
                            <img src="<?php echo e(asset('buzbox/img/img/image-3.jpg')); ?>" class="img-fluid wow fadeInUp" data-wow-delay="0.1s" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container ">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="story-himg story-himg-middle-u">
                            <img src="<?php echo e(asset('buzbox/img/img/image-2.jpg')); ?>" class="img-fluid wow fadeInUp" data-wow-delay="0.1s" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="story-desc story-desc-middle ">
                            <h3>Information Architecture</h3>
                            <div class="heading-border-light"></div>
                            <p>Well-structured information architecture helps customers find the right information quickly and improves overall user experience. Our UX strategists create intuitive navigation, clear content hierarchies, and accessible interfaces.</p>
                            <p>We balance usability with performance and scalability to deliver solutions that serve both users and business goals.</p>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="story-himg story-himg-middle-l">
                            <img src="<?php echo e(asset('buzbox/img/img/image-2.jpg')); ?>" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="story-desc">
                            <h3>Brand Positioning &amp; Publishing</h3>
                            <div class="heading-border-light"></div>
                            <p>We help brands articulate their value propositions and publish content that resonates. From strategy to execution, our creative and marketing teams ensure consistent brand messaging across channels.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="story-himg">
                            <img src="<?php echo e(asset('buzbox/img/img/image-1.jpg')); ?>" class="img-fluid wow fadeInUp" data-wow-delay="0.1s" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CONTACT CTA -->
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

        
        <?php echo $__env->make('partials.footer-top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.footer-bottom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Scripts -->
        <script src="<?php echo e(asset('buzbox/js/jquery/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(asset('buzbox/js/bootstrap/bootstrap.bundle.min.js')); ?>"></script>
        <script src="<?php echo e(asset('buzbox/js/main.js')); ?>"></script>

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
                messageDiv.innerHTML = `<strong>Error!</strong> An error occurred while sending your message.`;
                console.error('Error:', error);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'GET CONVERSATION';
            }
        }
        </script>

        <!-- Include Repair Booking Modal -->
        <?php echo $__env->make('partials.repair-booking-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </body>

</html>
<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/about.blade.php ENDPATH**/ ?>