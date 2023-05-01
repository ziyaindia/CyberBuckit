<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$front_setting_array = json_decode($this->setting->front_setting, TRUE);
//echo '<pr/>';print_r($front_setting_array);echo '<pr/>';
(empty($html_title)) ? $html_title = $front_setting_array['html_title'] : null;
(empty($html_keyword)) ? $html_keyword = $front_setting_array['html_keyword'] : null;
?>
<!DOCTYPE html>
<html lang="en">
  
  <head>
    <title><?=my_esc_html($html_title)?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="<?=my_esc_html($html_keyword)?>">
	<meta name="description" content="<?=my_esc_html($front_setting_array['html_description'])?>">
	<meta name="author" content="<?=my_esc_html($front_setting_array['html_author'])?>">
	<link rel="shortcut icon" href="<?=base_url('upload/favicon.ico')?>" type="image/x-icon">
	<link type="text/css" href="<?=base_url('assets/themes/default/front/vendor/@fortawesome/fontawesome-free/css/all.min.css')?>" rel="stylesheet">
	<link type="text/css" href="<?=base_url('assets/themes/default/front/vendor/prismjs/themes/prism.css')?>" rel="stylesheet">
	<link type="text/css" href="<?=base_url('assets/themes/default/front/css/front.css?v=' . $this->setting->version)?>" rel="stylesheet">
	<?php if (!empty($front_setting_array['custom_css'])) { ?>
	  <link type="text/css" href="<?=$front_setting_array['custom_css']?>" rel="stylesheet">
	<?php } ?>
  </head>
  
  <body>
    <header class="header-global">
      <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg headroom py-lg-3 px-lg-6 navbar-dark navbar-theme-primary">
	    <div class="container">
		  <a class="navbar-brand @@logo_classes" href="<?=base_url($this->home_url)?>">
		    <img class="navbar-brand-dark common" src="<?=base_url('upload/' . $front_setting_array['logo'])?>" alt="<?=my_esc_html($front_setting_array['html_title'])?> logo">
          </a>
		  <?php if ($front_setting_array['enabled']) { ?>
          <div class="navbar-collapse collapse" id="navbar_global">
		    <div class="navbar-collapse-header">
			  <div class="row">
			    <div class="col-6 collapse-brand">
				  <h2 class="font-weight-bolder"><?=my_esc_html($front_setting_array['html_title'])?></h2>
				</div>
                <div class="col-6 collapse-close">
				  <a href="#navbar_global" role="button" class="fas fa-times" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation"></a>
                </div>
              </div>
            </div>
            <ul class="navbar-nav navbar-nav-hover justify-content-center">
              <li class="nav-item">
			    <a id="menu_home" href="<?=base_url($this->home_url)?>" class="nav-link"><?=my_caption('front_header_index')?></a>
              </li>
			  <?php if ($front_setting_array['pricing_enabled']) { ?>
              <li class="nav-item">
			    <a id="menu_pricing" href="<?=base_url($this->home_url . 'pricing')?>" class="nav-link"><?=my_caption('front_header_pricing')?></a>
			  </li>
			  <?php
			    }
			    if ($front_setting_array['faq_enabled'] || $front_setting_array['documentation_enabled']) {
			  ?>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" aria-expanded="false" data-toggle="dropdown">
                  <span id="menu_support" class="nav-link-inner-text mr-1"><?=my_caption('front_header_support')?></span>
                  <i class="fas fa-angle-down nav-link-arrow"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg">
                  <div class="col-auto px-0" data-dropdown-content>
                    <div class="list-group list-group-flush">
					  <?php if ($front_setting_array['faq_enabled']) {?>
                      <a href="<?=base_url($this->home_url . 'faq')?>" id="menu_faq" class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4">
                        <span class="icon icon-sm icon-secondary"><i class="fas fa-question-circle"></i></span>
                        <div class="ml-4">
                          <span class="text-dark d-block"><?=my_caption('front_header_support_faq')?></span>
                        </div>
                      </a>
					  <?php
					    }
					    if ($front_setting_array['documentation_enabled']) {
					  ?>
                      <a href="<?=base_url($this->home_url . 'documentation')?>" id="menu_documentation" class="list-group-item list-group-item-action d-flex align-items-center p-0 py-3 px-lg-4">
                        <span class="icon icon-sm icon-primary"><i class="fas fa-book"></i></span>
                        <div class="ml-4">
                          <span class="text-dark d-block"><?=my_caption('front_header_support_documentation')?></span>
                        </div>
                      </a>
					  <?php } ?>
                    </div>
                  </div>
                </div>
              </li>

			<!--Videos-->
			  <li class="nav-item">
			    <a id="menu_videos" href="<?=base_url($this->home_url . 'videos')?>" class="nav-link"><?=my_caption('front_header_videos')?></a>
			  </li>

			  <?php
			    }
				if ($front_setting_array['blog_enabled']) {
			  ?>
              <li class="nav-item">
			    <a id="menu_blog" href="<?=base_url('blog')?>" class="nav-link"><?=my_caption('front_header_blog')?></a>
			  </li>
			  <?php } ?>
              <li class="nav-item">
			    <a id="menu_about" href="<?=base_url($this->home_url . 'about')?>" class="nav-link"><?=my_caption('front_header_about')?></a>
              </li> 
              <li class="nav-item">
			    <a id="menu_contact" href="<?=base_url($this->home_url . 'contact')?>" class="nav-link"><?=my_caption('front_header_contact')?></a>
              </li>
            </ul>
          </div>
		  <?php  } ?>
          <div class="d-block @@cta_button_classes">
		    <?php if (!isset($_SESSION['user_ids'])) { ?>
              <a href="<?=base_url('auth/signin')?>" target="_blank" class="btn btn-md btn-docs btn-outline-white animate-up-2 mr-3"><i class="fas fa-sign-in-alt mr-2"></i> <?=my_caption('front_header_signin')?></a>
              <a href="<?=base_url('auth/signup')?>" target="_blank"  class="btn btn-md btn-secondary animate-up-2"><i class="fas fa-user-plus mr-2"></i> <?=my_caption('front_header_signup')?></a>
            <?php
			}
			else {
			?>
			  <a href="<?=base_url('dashboard')?>" class="btn btn-md btn-secondary animate-up-2 mr-3"><i class="fab fa-artstation mr-2"></i> <?=my_caption('front_header_dashboard')?></a>
			  <a href="<?=base_url('generic/sign_out')?>" class="btn btn-md btn-docs btn-outline-white animate-up-2"><i class="fas fa-sign-out-alt mr-2"></i> <?=my_caption('menu_sidebar_topbar_signout')?></a>
		    <?php } ?>
		  </div>
          <div class="d-flex d-lg-none align-items-center">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
			</button>
          </div>
        </div>
		<div>
			<?php
				$language = get_cookie('site_lang', TRUE);
				if (!$language) {
					$language = $this->config->item('language');
					my_set_language_cookie($language);
				}
				$data = array(
				'id' => 'language_switcher',
				'class' => 'form-control selectpicker'
				);
				echo form_dropdown('language_switcher', my_supported_language(), ucfirst(my_esc_html($language)), $data);
			?>
		</div>
      </nav>
    </header>
<main>