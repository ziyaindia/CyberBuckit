<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
      <section class="section-header pb-8 pb-lg-9 mb-1 mb-lg-1 bg-primary text-white">
	    <div class="container">
		  <div class="row justify-content-center">
		    <div class="col-12 col-md-8 text-center">
			  <h1 class="display-2 mb-3"><?=my_caption('front_about_title')?></h1>
            </div>
          </div>
        </div>
        <div class="pattern bottom"></div>
      </section>
	  <section class="section section-lg pt-6 line-bottom-light">
	    <div class="container">
          <div class="row row-grid align-items-center mb-5 mb-md-7">
            <div class="col-12 col-md-5">
              <h2 class="font-weight-bolder mb-4"><?=my_caption('front_about_title')?></h2>
              <p class="lead"><?=my_esc_html(my_reverse_from_input_for_html($front_setting_array['about_us'], TRUE))?></p>
              <a href="<?=base_url('home/contact')?>" class="btn btn-primary mt-3 animate-up-2">
                <?=my_caption('front_about_contact_us')?>
                <span class="icon icon-xs ml-2">
                  <i class="fas fa-external-link-alt"></i>
                </span>
              </a>
            </div>
            <div class="col-12 col-md-6 ml-md-auto">
              <img src="<?=base_url()?>assets/themes/default/front/img/illustrations/feature-illustration.svg" alt="">
            </div>
          </div>
        </div>
      </section>	
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>