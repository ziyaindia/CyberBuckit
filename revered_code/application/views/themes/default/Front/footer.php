<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
	    $global_caption = my_caption('global_view') . '||';
		$global_caption .= my_caption('global_edit') . '||';	
		$global_caption .= my_caption('global_delete') . '||';
		$global_caption .= my_caption('global_delete') . '||';
		$global_caption .= my_caption('global_not_revert') . '||';
		$global_caption .= my_caption('global_yes') . '||';
		$global_caption .= my_caption('global_no') . '||';
		$global_caption .= my_caption('global_cancel') . '||';
		$global_caption .= my_caption('global_ok');
		$language = get_cookie('site_lang', TRUE);
		if (!$language) {
			$language = $this->config->item('language');
			my_set_language_cookie($language);
		}
	  ?>
	  <input type="hidden" name="global_caption" id="global_caption" value="<?=my_esc_html($global_caption)?>">


      <footer class="footer section pt-6 pt-md-8 pt-lg-10 pb-3 bg-primary text-white overflow-hidden">
	    <div class="pattern pattern-soft top"></div>
		<div class="container">
		  <?php if ($front_setting_array['enabled']) { ?>
		  <div class="row">
		    <div class="col-lg-4 mb-4 mb-lg-0">
              <a class="footer-brand mr-lg-5 d-flex" href="<?=base_url('home')?>">
                <img src="<?=base_url('upload/' . $front_setting_array['logo'])?>" height="35" class="mr-3" alt="Footer logo">
              </a>
              <div class="my-4"><a href="<?=base_url($this->home_url . 'about')?>"><?=my_get_words($front_setting_array['about_us'], 15)?>...</a></div>
              <div class="dropdown mb-4 mb-lg-0">
				<a id="langsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle footer-language-link"></a>
                <div aria-labelledby="langsDropdown" class="dropdown-menu dropdown-menu-center">
                  <?php
				    $supported_language_array = my_supported_language();
					foreach ($supported_language_array as $language) {
						echo '<a id="language_' . strtolower($language) . '" href="' .base_url('generic/switchLang/' . strtolower($language)) . '" class="dropdown-item text-gray text-sm">' .$language . '</a>';
				    }
				  ?>
                </div>
              </div>
            </div>
            <div class="col-6 col-sm-3 col-lg-2 mb-4 mb-lg-0">
              <h6><b><?=my_caption('front_footer_support')?></b></h6>
              <ul class="links-vertical">
			    <?php if ($front_setting_array['faq_enabled']) {?>
                  <li><a href="<?=base_url($this->home_url . 'faq')?>"><?=my_caption('front_footer_support_faq')?></a></li>
				<?php
				  }
				  if ($front_setting_array['documentation_enabled']) {
				?>
                  <li><a href="<?=base_url($this->home_url . 'documentation')?>"><?=my_caption('front_footer_support_documentation')?></a></li>
                <?php } ?>
				<li><a href="<?=base_url($this->home_url . 'contact')?>"><?=my_caption('front_footer_support_contact')?></a></li>
              </ul>
            </div>
            <div class="col-6 col-sm-3 col-lg-2 mb-4 mb-lg-0">
              <h6><b><?=my_caption('front_footer_quick_links')?></b></h6>
              <ul class="links-vertical">
                <li><a target="_blank" href="<?=base_url('auth/signin')?>"><?=my_caption('front_footer_quick_links_signin')?></a></li>
				<?php if ($this->setting->signup_enabled) { ?>
                <li><a target="_blank" href="<?=base_url('auth/signup')?>"><?=my_caption('front_footer_quick_links_signup')?></a></li>
				<?php 
				}
				if ($this->setting->forget_enabled) {
				?>
                <li><a target="_blank" href="<?=base_url('auth/forget')?>"><?=my_caption('front_footer_quick_links_forget')?></a></li>
				<?php } ?>
              </ul>
			</div>
			<div class="col-12 col-sm-6 col-lg-4">
			  <?php  if ($front_setting_array['subscriber_enabled']) { ?>
                <h6><?=my_caption('front_subscribe')?></h6>
                <p class="font-small"><?=my_caption('front_subscribe_instruction')?></p>
                  <?php echo form_open(base_url('home/subscriber_action'), ['method'=>'POST']);?>
			        <div class="form-row">
				      <div class="col-8">
				        <input type="email" name="email_address" id="email_address" class="form-control mb-2" placeholder="<?=my_caption('front_subscribe_email_placeholder')?>" required>
                      </div>
                      <div class="col-4">
					    <button type="submit" class="btn btn-secondary btn-block"><span><?=my_caption('front_subscribe_submit_button')?></span></button>
                      </div>
                    </div>
                  <?php echo form_close(); ?>
                <small class="mt-2 form-text">We’ll never share your details. See our <a href="<?=base_url('generic/terms_conditions')?>" target="_blank" class="font-weight-bold text-underline"><?=my_caption('front_footer_terms_conditions')?></a></small>
			  <?php } ?>
			</div>
          </div>
		  <?php  } ?>
          <hr class="my-4 my-lg-5">
		  <div class="row">
			<div class="col pb-4 mb-md-0">
              <div class="d-flex text-center justify-content-center align-items-center">
                <p class="font-weight-normal mb-0">© <?=my_esc_html($front_setting_array['company_name'])?> <span class="current-year"></span>. All rights reserved.</p>
              </div>
            </div>
          </div>
        </div>
	  </footer>
    </main>
	
	<?php
	  $language = get_cookie('site_lang', TRUE);
	  if (!$language) {
		$language = $this->config->item('language');
		my_set_language_cookie($language);
	  }
	?>
	<input type="hidden" name="global_base_url" id="global_base_url" value="<?=base_url()?>">
	<input type="hidden" name="global_language" id="global_language" value="<?=my_esc_html(strtolower($language))?>">
	<input type="hidden" name="global_current_class" id="global_current_class" value="<?=my_esc_html($this->router->fetch_class())?>">
	<input type="hidden" name="global_current_method" id="global_current_method" value="<?=my_esc_html($this->router->fetch_method())?>">
	
	<script src="<?=base_url('assets/themes/default/front/vendor/jquery/dist/jquery.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/default/front/vendor/popper.js/dist/umd/popper.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/default/front/vendor/bootstrap/dist/js/bootstrap.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/default/front/vendor/headroom.js/dist/headroom.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/default/front/vendor/onscreen/dist/on-screen.umd.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/default/front/vendor/waypoints/lib/jquery.waypoints.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/default/front/vendor/jarallax/dist/jarallax.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/default/front/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js')?>"></script>
	<script async defer src="https://buttons.github.io/buttons.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<?php
	  //GDPR
	  $gdpr_array = json_decode($this->setting->gdpr, TRUE);
	  if ($gdpr_array['enabled']) {
	?>
	    <input type="hidden" id="cookie_message" name="cookie_message" value="<?=my_esc_html($gdpr_array['cookie_message'])?>">
		<input type="hidden" id="cookie_policy_link_text" name="cookie_policy_link_text" value="<?=my_esc_html($gdpr_array['cookie_policy_link_text'])?>">
		<input type="hidden" id="cookie_policy_link" name="cookie_policy_link" value="<?=my_esc_html($gdpr_array['cookie_policy_link'])?>">
		<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/themes/default/vendor/cookieconsent/cookieconsent.min.css" />
		<script src="<?=base_url('assets/themes/default/vendor/cookieconsent/cookieconsent.min.js')?>" data-cfasync="false"></script>
	<?php } ?>
	<?php if (!empty($front_setting_array['custom_javascript'])) { ?>
	  <script src="<?=$front_setting_array['custom_javascript']?>"></script>
	<?php } ?>
	<?php
	  if ($this->setting->google_analytics_id != '') {
	?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
    <input type="hidden" id="google_analytics_id" name="google_analytics_id" value="<?=my_esc_html($this->setting->google_analytics_id)?>">
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?=my_esc_html($this->setting->google_analytics_id)?>"></script>
	<?php
	  }
	?>
	<script src="<?=base_url('assets/themes/default/front/js/front.js?v=' . $this->setting->version)?>"></script>
	<script src="<?=base_url()?>assets/themes/default/js/app.js?v=<?=$this->setting->version?>"></script>
  </body>
</html>