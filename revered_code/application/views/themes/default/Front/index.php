<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
      <section class="section-header pb-9 bg-primary text-white">
	    <div class="container">
		  <div class="row justify-content-center mb-5">
		    <div class="col-12 col-sm-8 col-md-7 col-lg-6 text-center">
			  <img src="<?=base_url()?>assets/themes/default/front/img/logo_membership.png" class="mb-4" alt="logo">
              <h1 class="display-4 text-muted mb-5 font-weight-normal">Multipurpose PHP Website and User Management Script</h1>
			  <div class="d-flex align-items-center justify-content-center mb-5">
			    <a href="https://codecanyon.net/item/cyberbukit-membership-multipurpose-php-login-and-user-management/28092179" target="_blank" class="btn btn-secondary mb-3 mt-2 mr-3 animate-up-2"><span class="fas fa-cart-plus mr-2"></span> Purchase</a>
              </div>
            </div>
          </div>
        </div>
        <div class="pattern bottom"></div>
      </section>
	  
	  <section class="section section-lg pt-0">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <h2 class="display-2 text-center mb-5 mb-lg-7">What you get?</h2>
            </div>
          </div>
          <div class="row justify-content-between align-items-center mb-5 mb-lg-7">
            <div class="col-lg-5 order-lg-2">
              <h2 class="h1">Powerful Back-End</h2>
              <p class="lead mb-4">More than 60 features</p>
              <p class="mb-5">All features come from our real project experiences. There are all essential for membership management and application building. It's ready to use in mimutes.</p>
              <div class="d-flex justify-content-between align-items-center mt-lg-4 mb-4">
                <div class="d-block">
                  <a href="https://codecanyon.net/item/cyberbukit-membership-multipurpose-php-login-and-user-management/28092179" target="_blank" class="btn btn-secondary mr-3 animate-up-2 mb-3"><span class="fas fa-cart-plus mr-2"></span> Purchase</a>
                  <a href="<?=base_url('auth/signin')?>" target="_blank" class="btn btn-outline-gray animate-up-2 mb-3"><i class="fas fa-th-large mr-2"></i> Back-End Demo</a>
                </div>
              </div>
            </div>
            <div class="col-lg-6 order-lg-1">
              <img src="<?=base_url()?>assets/themes/default/front/img/back_dashboard.jpg" alt="Back-End Preview" class="image_round_corner">
            </div>
          </div>
          <div class="row justify-content-center mb-5 mb-lg-7">
            <div class="col-6 col-md-3 text-center mb-4">
              <div class="icon icon-shape icon-lg bg-white shadow-lg border-light rounded-circle icon-secondary mb-4">
                <i class="fas fa-users"></i>
              </div>
              <p class="text-gray">User Management</p>
            </div>
            <div class="col-6 col-md-3 text-center mb-4">
              <div class="icon icon-shape icon-lg bg-white shadow-lg border-light rounded-circle icon-secondary mb-4">
                <i class="fas fa-money-check-alt"></i>
              </div>
              <p class="text-gray">Payment Module</p>
            </div>
            <div class="col-6 col-md-3 text-center">
              <div class="icon icon-shape icon-lg bg-white shadow-lg border-light rounded-circle icon-secondary mb-4">
                <i class="fab fa-servicestack"></i>
              </div>
              <p class="text-gray">Support Module</p>
            </div>
            <div class="col-6 col-md-3 text-center">
              <div class="icon icon-shape icon-lg bg-white shadow-lg border-light rounded-circle icon-secondary mb-4">
                <i class="fas fa-link"></i>
              </div>
              <p class="text-gray">REST API</p>
            </div>
          </div>
          <div class="row justify-content-between align-items-center mb-5 mb-lg-7">
            <div class="col-lg-5">
              <h2 class="h1">Clear & Clean Front-End</h2>
              <p class="lead mb-4">Based on a MIT License Template</p>
              <p class="mb-5">The front-end is a clear, clean and beautiful bootstrap template which is a MIT license template comes from Impact Design System ( https://www.creative-tim.com/product/impact-design-system ).</p>
              <div class="d-flex justify-content-between align-items-center mt-lg-4 mb-4">
                <div class="d-block">
                  <a href="https://codecanyon.net/item/cyberbukit-membership-multipurpose-php-login-and-user-management/28092179" target="_blank" class="btn btn-primary mr-3 animate-up-2 mb-3"><span class="fas fa-cart-plus mr-2"></span> Purchase</a>
                  <a href="<?=base_url()?>" class="btn btn-outline-gray animate-up-2 mb-3"><i class="fas fa-th-large mr-2"></i> Front-End Demo</a>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <img src="<?=base_url()?>assets/themes/default/front/img/front_index.jpg" alt="Front-End Preview" class="image_round_corner">
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-6 col-md-3 text-center mb-4">
              <div class="icon icon-shape icon-lg bg-white shadow-lg border-light rounded-circle icon-primary mb-4">
                <i class="fas fa-blog"></i>
              </div>
              <p class="text-gray">Blog Module</p>
            </div>
            <div class="col-6 col-md-3 text-center mb-4">
              <div class="icon icon-shape icon-lg bg-white shadow-lg border-light rounded-circle icon-primary mb-4">
                <i class="fab fa-dochub"></i>
              </div>
              <p class="text-gray">Documentation Module</p>
            </div>
            <div class="col-6 col-md-3 text-center">
              <div class="icon icon-shape icon-lg bg-white shadow-lg border-light rounded-circle icon-primary mb-4">
                <i class="fas fa-question"></i>
              </div>
              <p class="text-gray">FAQ Module</p>
            </div>
            <div class="col-6 col-md-3 text-center">
              <div class="icon icon-shape icon-lg bg-white shadow-lg border-light rounded-circle icon-primary mb-4">
                <i class="fas fa-handshake"></i>
              </div>
              <p class="text-gray">Contact Form Module</p>
            </div>
          </div>
        </div>
      </section>
      <section class="section section-lg bg-soft">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <h2 class="mb-4 mb-lg-5">Front-End</h2>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url()?>" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/front_index.jpg" alt="Front-End index">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('home/pricing')?>" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/front_pricing.jpg" alt="Front-End Pricing">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('home/documentation')?>" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/front_documentation.jpg" alt="Front-End Documentation">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('home/faq')?>" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/front_faq.jpg" alt="Front-End FAQ">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a target="_blank" href="<?=base_url('home/contact')?>" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/front_contact_form.jpg" alt="Front-End Contact Form">
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <h2 class="mb-4 mb-lg-5">Back-End</h2>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('auth/signin')?>" target="_blank" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/back_index.jpg" alt="Back-End Signin">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('dashboard')?>" target="_blank" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/back_dashboard.jpg" alt="Back-End Dashboard">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('user/my_profile')?>" target="_blank" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/back_profile.jpg" alt="Back-End Profile">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('admin/payment_list')?>" target="_blank" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/back_payment.jpg" alt="Back-End Payment">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('user/ticket_new')?>" target="_blank" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/back_ticket.jpg" alt="Back-End Ticket">
              </a>
            </div>
            <div class="col-6 col-lg-4 mb-5">
              <a href="<?=base_url('files/file_upload')?>" target="_blank" class="page-preview scale-up-hover-2">
                <img class="shadow-lg rounded scale" src="<?=base_url()?>assets/themes/default/front/img/back_upload.jpg" alt="Back-End Upload">
              </a>
            </div>
          </div>
        </div>
      </section>
      <section class="section section-lg bg-primary text-white">
        <div class="container">
          <div class="row justify-content-center mb-5 mb-lg-6">
            <div class="col-12 text-center">
              <h2 class="h1 px-lg-5">How can you use CyberBukit Membership?</h2>
              <p class="lead px-lg-8">As a multipurpose script, it can be used in many scenarios.</p>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fas fa-users"></i></span>
                  <h5 class="font-weight-normal text-primary">Membership Management</h5>
                  <p>Start to manage your users in the easiest way.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fab fa-servicestack"></i></span>
                  <h5 class="font-weight-normal text-primary">Support Portal</h5>
                  <p>Integrated with FAQ, documentation, ticket, and contact form modules.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fas fa-money-check-alt"></i></span>
                  <h5 class="font-weight-normal text-primary">Payment Portal</h5>
                  <p>Integrated with Paypal and Stripe, one-time and recurring payment.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fab fa-internet-explorer"></i></span>
                  <h5 class="font-weight-normal text-primary">Basis of Web Applications</h5>
                  <p>Using our script, you only need to focus on your key features.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fas fa-mobile-alt"></i></span>
                  <h5 class="font-weight-normal text-primary">Basis of Mobile Applications</h5>
                  <p>It can be used as the server-side for mobile application.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4">
              <div class="card bg-white shadow-soft text-primary rounded mb-4">
                <div class="px-3 px-lg-4 py-5 text-center">
                  <span class="icon icon-lg mb-4"><i class="fas fa-wrench"></i></span>
                  <h5 class="font-weight-normal text-primary">Basis of SAAS Applications</h5>
                  <p>As a multiple user system, it's ready to use as the basis of SAAS.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="section section-lg line-bottom-soft">
        <div class="container">
          <div class="row justify-content-center mb-5 mb-lg-6">
            <div class="col-12 text-center">
              <h2 class="h1 px-lg-5">What's inside?</h2>
              <p class="lead px-lg-8">It's based on Codeigniter3. The structure is clear and easy to customize.</p>
            </div>
          </div>
          <div class="row d-flex align-items-center">
            <div class="col-12 col-lg-6 mb-4">
              <div class="d-none d-lg-block mt-5">
                <h4>Open-Source Software</h4>
                <p class="lead mb-4">The codes of CyberBukit Membership are well-organized. It's based on Codeigniter which is a light weight MVC framework and one of the most easiest MVC framework to learn. You will find it's easy to customize.</p>
                <a href="<?=base_url('home/documentation')?>" class="btn btn-md btn-primary animate-up-2 mr-3"><i class="fas fa-book mr-1"></i> Documentation</a>
              </div>
            </div>
            <div class="col-12 col-lg-6 order-lg-first d-flex justify-content-center">
              <ul class="d-block fmw-100 list-style-none folder-structure">
                <li><i class="fas fa-folder mr-2"></i>application</li>
                <li>
                  <ul class="list-style-none pl-4">
                    <li><i class="fas fa-folder mr-2"></i>controllers</li>
                    <li>
                      <ul class="class list-style-none pl-4">
                        <li><i class="fas fa-file-code mr-2 text-tertiary"></i>Admin.php</li>
                        <li><i class="fas fa-file-code mr-2 text-tertiary"></i> Auth.php</li>
                        <li><i class="fas fa-file-code mr-2 text-tertiary"></i>User.php</li>
						<li><i class="fas fa-file-code mr-2 text-tertiary"></i>...</li>
                      </ul>
                    </li>
                    <li><i class="fas fa-folder mr-2"></i>language</li>
                    <li>
                      <ul class="class list-style-none pl-4">
                        <li><i class="fas fa-folder mr-2"></i>english</li>
                        <li><i class="fas fa-folder mr-2"></i>spanish</li>
						<li><i class="fas fa-folder mr-2"></i>...</li>
                      </ul>
                    </li>
					<li><i class="fas fa-folder mr-2"></i>models</li>
					<li><i class="fas fa-folder mr-2"></i>views</li>
                  </ul>
                </li>
                <li><i class="fas fa-folder text-muted mr-2"></i>system</li>
                <li><i class="fas fa-folder text-muted mr-2"></i>upload</li>
                <li><i class="fas fa-folder text-muted mr-2"></i>vendor</li>
                <li><i class="fas fa-file-code mr-2 text-warning"></i>.htaccess</li>
                <li><i class="fab fa-html5 mr-2 text-secondary"></i> index.php</li>
                <li><i class="fas fa-file-code mr-2 text-tertiary"></i>composer.json</li>
              </ul>
            </div>
            <div class="col-12 mt-5 d-lg-none">
              <h5>You need only HTML, CSS and Javascript?</h5>
              <p>Don't worry, we got you covered. We have a folder called <code class="text-danger">html&css</code> which includes only the basic HTML5, CSS3 and Javascript technologies.</p>
            </div>
          </div>
        </div>
      </section>
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>