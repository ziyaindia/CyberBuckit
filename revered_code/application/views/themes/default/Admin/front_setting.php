<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('front_setting_title')?></h1>

  <?php
    my_load_view($this->setting->theme, 'Generic/show_flash_card');
    echo form_open_multipart(base_url('admin/front_setting_action/'), ['method'=>'POST']);
	$front_setting_array = json_decode($this->setting->front_setting, TRUE);
  ?>
  <div class="row">
    <div class="col-lg-8">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('front_setting_title')?></h6>
        </div>
        <div class="card-body">
		  <input type="hidden" id="act" name="act" value="front_setting">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><b><?=my_caption('front_setting_basic_setting')?></b></label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('enabled_front') == '') ? $checked = $front_setting_array['enabled'] : $checked = set_value('enabled_front');
				  $data = array(
				    'name' => 'enabled_front',
					'id' => 'enabled_front',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="enabled_front"><?php echo my_caption('front_setting_enabled');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_company_name')?></label>
			  <?php
			    (set_value('company_name') == '') ? $company_name = $front_setting_array['company_name'] : $company_name = set_value('company_name');
				$data = array(
				  'name' => 'company_name',
				  'id' => 'company_name',
				  'value' => $company_name,
				  'placeholder' => my_caption('front_setting_company_name'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('company_name', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_email_address')?></label>
			  <?php
			    (set_value('email_address') == '') ? $email_address = $front_setting_array['email_address'] : $email_address = set_value('email_address');
				$data = array(
				  'name' => 'email_address',
				  'id' => 'email_address',
				  'value' => $email_address,
				  'placeholder' => my_caption('front_setting_email_address'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('email_address', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_html_title')?></label>
			  <?php
			    (set_value('html_title') == '') ? $html_title = $front_setting_array['html_title'] : $html_title = set_value('html_title');
				$data = array(
				  'name' => 'html_title',
				  'id' => 'html_title',
				  'value' => $html_title,
				  'placeholder' => my_caption('front_setting_html_title'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('html_title', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_html_author')?></label>
			  <?php
			    (set_value('html_author') == '') ? $html_author = $front_setting_array['html_author'] : $html_author = set_value('html_author');
				$data = array(
				  'name' => 'html_author',
				  'id' => 'html_author',
				  'value' => $html_author,
				  'placeholder' => my_caption('front_setting_html_author'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('html_author', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><?=my_caption('front_setting_html_description')?></label>
			  <?php
			    (set_value('html_description') == '') ? $html_description = $front_setting_array['html_description'] : $html_description = set_value('html_description');
				$data = array(
				  'name' => 'html_description',
				  'id' => 'html_description',
				  'value' => $html_description,
				  'placeholder' => my_caption('front_setting_html_description'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('html_description', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><?=my_caption('front_setting_html_keyword')?></label>
			  <?php
			    (set_value('html_keyword') == '') ? $html_keyword = $front_setting_array['html_keyword'] : $html_keyword = set_value('html_keyword');
				$data = array(
				  'name' => 'html_keyword',
				  'id' => 'html_keyword',
				  'value' => $html_keyword,
				  'placeholder' => my_caption('front_setting_html_keyword_placeholder'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('html_keyword', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-12">
			  <label><?=my_caption('front_setting_about_us')?></label>
			  <?php
			    (set_value('about_us') == '') ? $about_us = $front_setting_array['about_us'] : $about_us = set_value('about_us');
				$data = array(
				  'name' => 'about_us',
				  'id' => 'about_us',
				  'value' => $about_us,
				  'placeholder' => my_caption('front_setting_about_us'),
				  'class' => 'form-control'
				);
				echo form_textarea($data);
				echo form_error('about_us', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><b><?=my_caption('front_setting_modules_setting')?></b></label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('enabled_pricing') == '') ? $checked = $front_setting_array['pricing_enabled'] : $checked = set_value('enabled_pricing');
				  $data = array(
				    'name' => 'enabled_pricing',
					'id' => 'enabled_pricing',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="enabled_pricing"><?php echo my_caption('front_setting_enabled_pricing');?></label>
              </div>
			</div>
		    <div class="col-lg-6">
			  <label>&nbsp;</label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('enabled_faq') == '') ? $checked = $front_setting_array['faq_enabled'] : $checked = set_value('enabled_faq');
				  $data = array(
				    'name' => 'enabled_faq',
					'id' => 'enabled_faq',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="enabled_faq"><?php echo my_caption('front_setting_enabled_faq');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mt-4">
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('enabled_documentation') == '') ? $checked = $front_setting_array['documentation_enabled'] : $checked = set_value('enabled_documentation');
				  $data = array(
				    'name' => 'enabled_documentation',
					'id' => 'enabled_documentation',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="enabled_documentation"><?php echo my_caption('front_setting_enabled_documentation');?></label>
              </div>
			</div>
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('enabled_blog') == '') ? $checked = $front_setting_array['blog_enabled'] : $checked = set_value('enabled_blog');
				  $data = array(
				    'name' => 'enabled_blog',
					'id' => 'enabled_blog',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="enabled_blog"><?php echo my_caption('front_setting_enabled_blog');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-5">
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('enabled_subscriber') == '') ? $checked = $front_setting_array['subscriber_enabled'] : $checked = set_value('enabled_subscriber');
				  $data = array(
				    'name' => 'enabled_subscriber',
					'id' => 'enabled_subscriber',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="enabled_subscriber"><?php echo my_caption('front_setting_enabled_subscriber');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-3">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('front_setting_custom_setting')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_custom_setting_css')?></label>
			  <?php
			    (!empty($front_setting_array['custom_css'])) ? $custom_css = $front_setting_array['custom_css'] : $custom_css = '';
			    (set_value('custom_css') != '') ? $custom_css = set_value('custom_css') : null;
				$data = array(
				  'name' => 'custom_css',
				  'id' => 'custom_css',
				  'value' => $custom_css,
				  'placeholder' => my_caption('front_setting_custom_setting_css_placheholder'),
				  'class' => 'form-control'
				);
				echo form_input($data);
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_custom_setting_js')?></label>
			  <?php
			    (!empty($front_setting_array['custom_javascript'])) ? $custom_javascript = $front_setting_array['custom_javascript'] : $custom_javascript = '';
			    (set_value('custom_javascript') != '') ? $custom_javascript = set_value('custom_javascript') : null;
				$data = array(
				  'name' => 'custom_javascript',
				  'id' => 'custom_javascript',
				  'value' => $custom_javascript,
				  'placeholder' => my_caption('front_setting_custom_setting_js_placheholder'),
				  'class' => 'form-control'
				);
				echo form_input($data);
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-3">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('front_setting_social_setting')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_social_facebook')?></label>
			  <?php
			    (set_value('social_facebook') == '') ? $social_facebook = $front_setting_array['social_facebook'] : $social_facebook = set_value('social_facebook');
				$data = array(
				  'name' => 'social_facebook',
				  'id' => 'social_facebook',
				  'value' => $social_facebook,
				  'placeholder' => my_caption('front_setting_social_facebook'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('social_facebook', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_social_twitter')?></label>
			  <?php
			    (set_value('social_twitter') == '') ? $social_twitter = $front_setting_array['social_twitter'] : $social_twitter = set_value('social_twitter');
				$data = array(
				  'name' => 'social_twitter',
				  'id' => 'social_twitter',
				  'value' => $social_twitter,
				  'placeholder' => my_caption('front_setting_social_twitter'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('social_twitter', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_social_linkedin')?></label>
			  <?php
			    (set_value('social_linkedin') == '') ? $social_linkedin = $front_setting_array['social_linkedin'] : $social_linkedin = set_value('social_linkedin');
				$data = array(
				  'name' => 'social_linkedin',
				  'id' => 'social_linkedin',
				  'value' => $social_linkedin,
				  'placeholder' => my_caption('front_setting_social_linkedin'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('social_linkedin', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('front_setting_social_github')?></label>
			  <?php
			    (set_value('social_github') == '') ? $social_github = $front_setting_array['social_github'] : $social_github = set_value('social_github');
				$data = array(
				  'name' => 'social_github',
				  'id' => 'social_github',
				  'value' => $social_github,
				  'placeholder' => my_caption('front_setting_social_github'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('social_github', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  
		  <hr>
		  <div class="row">
			<div class="col-lg-12 text-right">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_change',
				  'id' => 'btn_change',
				  'value' => my_caption('global_save_changes'),
				  'class' => 'btn btn-primary mr-2'
			    );
			    echo form_submit($data);
			  ?>
			</div>
		  </div>
		</div>
      </div>
	</div>
	<div class="col-lg-4">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('front_setting_header_logo')?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-12 mb-4">
			  <?php $img_url = base_url('upload/' . $front_setting_array['logo']) . '?dummy=' . random_string('alnum', 6); ?>
		      <img id="img" src="<?=my_esc_html($img_url)?>" class="front-end-logo-setting">
		    </div>
		  </div>
		  <div class="row mb-2">
		    <div class="col-lg-12">
		      <input type='file' id="userfile" name="userfile" class="mb-3" accept=".png,.jpg,.jpeg,.gif,.svg">
			  <?php echo form_error('userfile', '<small class="text-danger">', '</small>'); ?>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>