<!DOCTYPE html>
<html lang="en">

    <!-- Basic -->
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
   
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
 
     <!-- Site Metas -->
    <title>Nickie - Personal Portfolio OnePage Responsive HTML5 Template</title>  
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="img/favicon.ico" type="img/x-icon" />
	<?= $this->Html->meta('icon') ?>
	<!-- <img src="img/favicon.ico" class="img-fluid" alt="svg image"> -->
	<!-- <img src="img/apple-touch-icon.png" class="apple-touch-icon" alt="svg image"> -->
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
	
    <!-- Site CSS -->
	<?= $this->Html->css([
        'bootstrap.min',
        '/style',
		'responsive',
		'custom',
		'owl.carousel',
		'flaticon',
		'font-awesome.min',
		'animated-slider',
		'animate',
		'owl.carousel',
		'slick.min',
		'prettyPhoto'
      ]) 
	?>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <title>
        Nang - Personal Portfolio OnePage Responsive HTML5 Template:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body id="page-top" class="politics_version">

    <!-- LOADER -->
    <!-- <div id="preloader">
        <div class="loader">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div>
    </div> -->
	<!-- end loader -->
    <!-- END LOADER -->
	
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">
			<img class="img-fluid" src="img/logo.png" alt="" />
		</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger active" href="#home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#about">About Me</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#services">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#portfolio">Portfolio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#testimonials">Testimonials</a>
            </li>
			<li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#blog">Blog</a>
            </li>
			<li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#contact">Contect Us</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	
	<div id="home" class="ct-header ct-header--slider ct-slick-custom-dots">
		<div class="ct-slick-homepage" data-arrows="true" data-autoplay="false">

			<div class="ct-header tablex item" data-background="img/banner-01.jpg">
				<div class="ct-u-display-tablex">
					<div class="inner">
						<div class="container">
							<div class="row">
								<div class="col-md-8 col-lg-8 slider-inner">
									<h1 class="big animated">Hi, I'm Web Developer</h1>
									<p class="animated">"Crafting clean, functional websites that bring ideas to life."</p>
									<a class="btn-new from-middle animated" href="#">Download cv</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="ct-header tablex item" data-background="img/banner-01.jpg">
				<div class="ct-u-display-tablex">
					<div class="inner">
						<div class="container">
							<div class="row">
								<div class="col-md-8 col-lg-8 slider-inner">
									<h1 class="big animated">Hi, I'm QA/Testing Engineer</h1>
									<p class="animated">"Driven by quality, guided by precision."</p>
									<a class="btn-new from-middle animated" href="#">Download cv</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="ct-header tablex item" data-background="img/banner-01.jpg">
				<div class="ct-u-display-tablex">
					<div class="inner">
						<div class="container">
							<div class="row">
								<div class="col-md-8 col-lg-8 slider-inner">
									<h1 class="big animated">Hello I'm Web Developer</h1>
									<p class="animated">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse iaculis massa bibendum sodales rhoncus.</p>
									<a class="btn-new from-middle animated" href="#">Download cv</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div><!-- .ct-slick-homepage -->
	</div><!-- .ct-header --> 

    <div id="about" class="section wb">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="message-box">                        
                        <h2>About Me</h2>
                        <p>I`m a Web Developer Specialist with over 5 years of hands-on experience in developing and maintaining dynamic, database-driven websites and applications. 
							My core stack includes PHP, HTML, CSS, JavaScript, jQuery, and MySQL, with a strong emphasis on writing clean, efficient, and maintainable code.</p>
						<p>Throughout my career, I`ve worked across various industries including e-commerce, education, and small business services, delivering responsive websites and custom web solutions tailored to client needs. 
							I`m particularly experienced in developing custom CMS features, integrating APIs, and optimizing MySQL databases for performance.</p>
						<p>My current career goal is to deepen my expertise in backend development by exploring modern PHP frameworks like Laravel, while also improving my front-end skills with modern JavaScript libraries. 
							I`m also interested in learning DevOps basics to streamline deployment and improve site reliability.</p>
						<p>Outside of coding, I enjoy exploring UI/UX design trends, reading about tech innovations, and working on side projects that automate everyday tasks. 
							I believe in continuous learning and take pride in delivering quality work that not only functions well but also provides a great user experience.</p>
						<ul>
							<li><b>Follow Me</b></li>
							<li><a href="https://www.facebook.com/profile.php?id=100081567111640" target="_blank"> <i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a href="https://x.com/NangKhinOhn" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
							<li><a href="#" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							<li><a href="https://www.instagram.com/na_ng3621?igsh=NTE4aW9rdThtdXV4&utm_source=qr" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							<li><a href="#" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
							<li><a href="https://www.youtube.com/@NangKhinOhn" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
						</ul>

						<a class="btn-new from-middle animated" href="#">Download cv</a>
                    </div><!-- end messagebox -->
                </div><!-- end col -->

                <div class="col-md-6">
                    <div class="right-box-pro wow fadeIn">
						<img src="img/about-1.png" alt="" class="img-fluid img-rounded fat-ab">
                    </div><!-- end media -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end section -->
	
    <div id="services" class="section lb">
        <div class="container">
            <div class="section-title text-center">
                <h3>Services</h3>
                <p>I provide end-to-end web development and software testing services to help businesses build reliable, user-friendly, and high-performing digital solutions.
					Whether you need a fast, functional website or want to ensure your application runs flawlessly, I bring a combination of development expertise and quality assurance experience to every project.</p>
            </div><!-- end title -->

            <div class="row">
				<div class="col-md-4">
                    <div class="services-inner-box">
						<div class="ser-icon">
							<i class="flaticon-idea-1"></i>
						</div>
						<h2>Graphic Design</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
                </div><!-- end col -->
				<div class="col-md-4">
                    <div class="services-inner-box">
						<div class="ser-icon">
							<i class="flaticon-discuss-issue"></i>
						</div>
						<h2>Support</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
                </div><!-- end col -->
				<div class="col-md-4">
                    <div class="services-inner-box">
						<div class="ser-icon">
							<i class="flaticon-idea"></i>
						</div>
						<h2>Web Idea</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
                </div><!-- end col -->
				<div class="col-md-4">
                    <div class="services-inner-box">
						<div class="ser-icon">
							<i class="flaticon-seo"></i>
						</div>
						<h2>Web Development</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
                </div><!-- end col -->
                <div class="col-md-4">
                    <div class="services-inner-box">
						<div class="ser-icon">
							<i class="flaticon-development"></i>
						</div>
						<h2>Responsive Design</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
                </div><!-- end col -->
				<div class="col-md-4">
                    <div class="services-inner-box">
						<div class="ser-icon">
							<i class="flaticon-process"></i>
						</div>
						<h2>Creative Design</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end section -->
	
	<div id="portfolio" class="section lb">
		<div class="container">
			<div class="section-title text-center">
                <h3>Portfolio</h3>
                <p>Welcome to my portfolio! 
					I`m a passionate web developer specializing in building dynamic, user-friendly websites and applications. With expertise in PHP, HTML, CSS, JavaScript, jQuery, and MySQL, 
					I focus on creating responsive, functional solutions that solve real-world problems. 
					Explore my work and see how I turn ideas into digital experiences.</p>
				<p>I`m a dedicated QA/Testing Engineer with experience in ensuring the highest quality for web applications. 
					I specialize in both manual and automation testing, focusing on functional accuracy, performance, and cross-browser compatibility. 
					Browse my portfolio to see how I help businesses deliver flawless, bug-free user experiences.</p>
            </div><!-- end title -->
			<div class="section-title text-left">
                <!-- <h3>Portfolio</h3> -->
                <!-- <p>Quisque eget nisl id nulla sagittis auctor quis id. Aliquam quis vehicula enim, non aliquam risus.</p> -->
				<p>E-Commence Website for Local Retailler</p>
				<p>Role: Full Stack Web Developer</p>
				<p>Tech Used: PHP, MySQL, HTML, CSS, JavaScript, jQuery</p>
				<p>Description: Developed a custom e-commerce site with product filters, user login, and cart functionality. 
					Integrated with a local payment gateway and optimized for mobile.</p>
				<p>Live Demo: <a href="http://localhost:8888/" target="_blank">GiveAway</a> Code: <a href="https://github.com/N34825" target="_blank">GitHub</a></p>
            </div>
			
			<div class="gallery-menu text-center row">
				<div class="col-md-12">
					<div class="button-group filter-button-group">
						<button class="btn-new from-middle animated active" data-filter="*">All</button>
						<button class="btn-new from-middle animated" data-filter=".gal_a">Web Development</button>
						<button class="btn-new from-middle animated" data-filter=".gal_b">Creative Design</button>
						<button class="btn-new from-middle animated" data-filter=".gal_c">Graphic Design</button>
					</div>
				</div>
			</div>
			
			<div class="gallery-list row">
				<div class="col-md-4 col-sm-6 gallery-grid gal_a gal_b">
					<div class="gallery-single spi-hr fix hover">
						<img src="img/gallery_img-01.jpg" class="img-fluid" alt="Image">
						<div class="text-hover">
							<h3>App design</h3>
							<h4>Lorem ipsum</h4>
						</div>
						<div class="img-overlay">
							<a href="img/gallery_img-01.jpg" data-rel="prettyPhoto[gal]" class="hoverbutton global-radius"><i class="fa fa-picture-o"></i></a>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-6 gallery-grid gal_c gal_b">
					<div class="gallery-single spi-hr fix">
						<img src="img/gallery_img-02.jpg" class="img-fluid" alt="Image">
						<div class="text-hover">
							<h3>Logo design</h3>
							<h4>Lorem ipsum</h4>
						</div>
						<div class="img-overlay">
							<a href="img/gallery_img-02.jpg" data-rel="prettyPhoto[gal]" class="hoverbutton global-radius"><i class="fa fa-picture-o"></i></a>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-6 gallery-grid gal_a gal_c">
					<div class="gallery-single spi-hr fix">
						<img src="img/gallery_img-03.jpg" class="img-fluid" alt="Image">
						<div class="text-hover">
							<h3>App design</h3>
							<h4>Lorem ipsum</h4>
						</div>
						<div class="img-overlay">
							<a href="img/gallery_img-03.jpg" data-rel="prettyPhoto[gal]" class="hoverbutton global-radius"><i class="fa fa-picture-o"></i></a>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-6 gallery-grid gal_b gal_a">
					<div class="gallery-single spi-hr fix">
						<img src="img/gallery_img-04.jpg" class="img-fluid" alt="Image">
						<div class="text-hover">
							<h3>Logo design</h3>
							<h4>Lorem ipsum</h4>
						</div>
						<div class="img-overlay">
							<a href="img/gallery_img-04.jpg" data-rel="prettyPhoto[gal]" class="hoverbutton global-radius"><i class="fa fa-picture-o"></i></a>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-6 gallery-grid gal_a gal_c">
					<div class="gallery-single spi-hr fix">
						<img src="img/gallery_img-05.jpg" class="img-fluid" alt="Image">
						<div class="text-hover">
							<h3>App design</h3>
							<h4>Lorem ipsum</h4>
						</div>
						<div class="img-overlay">
							<a href="img/gallery_img-05.jpg" data-rel="prettyPhoto[gal]" class="hoverbutton global-radius"><i class="fa fa-picture-o"></i></a>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-6 gallery-grid gal_c gal_a">
					<div class="gallery-single spi-hr fix">
						<img src="img/gallery_img-06.jpg" class="img-fluid" alt="Image">
						<div class="text-hover">
							<h3>Logo design</h3>
							<h4>Lorem ipsum</h4>
						</div>
						<div class="img-overlay">
							<a href="img/gallery_img-06.jpg" data-rel="prettyPhoto[gal]" class="hoverbutton global-radius"><i class="fa fa-picture-o"></i></a>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
	
	 <div id="testimonials" class="section wb">
        <div class="container">
            <div class="section-title text-center">
                <h3>Testimonials</h3>
                <p>We thanks for all our awesome testimonials! There are hundreds of our happy customers! </p>
            </div><!-- end title -->

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="testi-carousel owl-carousel owl-theme">
                        <div class="testimonial clearfix">
                            <div class="desc">
                                <h3> Wonderful Support!</h3>
                                <p class="lead">They have got my project on time with the competition with a sed highly skilled, and experienced & professional team.</p>
								<i class="fa fa-quote-right"></i>
                            </div>
                            <div class="testi-meta">
                                <img src="img/testi_01.png" alt="" class="img-fluid">
                                <h4>Nang Khin Ohn </h4>
                            </div>
                            <!-- end testi-meta -->
                        </div>
                        <!-- end testimonial -->
                    </div><!-- end carousel -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end section -->
	
	<div id="blog" class="section lb">
		<div class="container">
			<div class="section-title text-center">
                <h3>Blog</h3>
                <p>Quisque eget nisl id nulla sagittis auctor quis id. Aliquam quis vehicula enim, non aliquam risus.</p>
            </div><!-- end title -->
			
			<div class="row">
				<div class="col-md-4 col-sm-6 col-lg-4">
					<figure class="snip1401">
						<img src="img/blog-01.jpg" alt="" />
						<figcaption>
							<h3>Quisque auctor lectus interdum</h3>
							<p>Etiam materials ut mollis tellus, vel posuere nulla. Etiam sit amet massa.</p>
							<ul>
                                <li>by admin</li>
                                <li>Apr 21, 2018</li>
                                <li>Comments</li>
                            </ul>
						</figcaption>
						<i class="ion-ios-home-outline"></i>
						<a href="#"></a>
					</figure>
				</div>
				<div class="col-md-4 col-sm-6 col-lg-4">
					<figure class="snip1401 hover">
						<img src="img/blog-02.jpg" alt="" />
						<figcaption>
							<h3>Quisque auctor lectus interdum</h3>
							<p>Etiam materials ut mollis tellus, vel posuere nulla. Etiam sit amet massa.</p>
							<ul>
                                <li>by admin</li>
                                <li>Apr 21, 2018</li>
                                <li>Comments</li>
                            </ul>
						</figcaption>
						<i class="ion-ios-home-outline"></i>
						<a href="#"></a>
					</figure>
				</div>
				<div class="col-md-4 col-sm-6 col-lg-4">
					<figure class="snip1401">
						<img src="img/blog-03.jpg" alt="" />
						<figcaption>
							<h3>Quisque auctor lectus interdum</h3>
							<p>Etiam materials ut mollis tellus, vel posuere nulla. Etiam sit amet massa.</p>
							<ul>
                                <li>by admin</li>
                                <li>Apr 21, 2018</li>
                                <li>Comments</li>
                            </ul>
						</figcaption>
						<i class="ion-ios-home-outline"></i>
						<a href="#"></a>
					</figure>
				</div>
			</div>
			
		</div>
	</div>

    <div id="contact" class="section db">
        <div class="container">
            <div class="section-title text-center">
                <h3>Contact Me</h3>
                <p>Quisque eget nisl id nulla sagittis auctor quis id. Aliquam quis vehicula enim, non aliquam risus.</p>
            </div><!-- end title -->

            <div class="row">
                <div class="col-md-12">
                    <div class="contact_form">
                        <div id="message"></div>
                        <form id="contactForm" name="sentMessage" novalidate="novalidate">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" id="name" type="text" placeholder="Your Name" required="required" data-validation-required-message="Please enter your name.">
										<p class="help-block text-danger"></p>
									</div>
									<div class="form-group">
										<input class="form-control" id="email" type="email" placeholder="Your Email" required="required" data-validation-required-message="Please enter your email address.">
										<p class="help-block text-danger"></p>
									</div>
									<div class="form-group">
										<input class="form-control" id="phone" type="tel" placeholder="Your Phone" required="required" data-validation-required-message="Please enter your phone number.">
										<p class="help-block text-danger"></p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<textarea class="form-control" id="message" placeholder="Your Message" required="required" data-validation-required-message="Please enter a message."></textarea>
										<p class="help-block text-danger"></p>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-lg-12 text-center">
									<div id="success"></div>
									<button id="sendMessageButton" class="sim-btn btn-new from-middle animated" data-text="Send Message" type="submit">Send Message</button>
								</div>
							</div>
						</form>
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end section -->

    <div class="copyrights">
        <div class="container">
            <div class="footer-distributed">
                <div class="footer-left">
                    <p class="footer-company-name">All Rights Reserved. &copy; 2025 <a href="#">Nang Khin Ohn</a> Design By : 
					<a href="https://html.design/">html design</a></p>
                </div>
            </div>
        </div><!-- end container -->
    </div><!-- end copyrights -->

    <a href="#" id="scroll-to-top" class="dmtop global-radius"><i class="fa fa-angle-up"></i></a>

    <!-- ALL JS FILES -->
	<?= $this->Html->script([
		'all.js', 
		'jquery.mobile.customized.min.js',
		'jquery.easing.1.3.js',
		'parallaxie.js',
		'slick.min.js',
		'animated-slider.js',
		'jqBootstrapValidation.js',
		'contact_me.js',
		'custom.js'
		]); ?>
</body>
</html>