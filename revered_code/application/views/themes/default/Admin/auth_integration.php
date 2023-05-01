<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('ai_title')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('ai_title')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('admin/auth_integration_action/'), ['method'=>'POST']);
			$google_recaptcha_array = json_decode($this->setting->recaptcha_detail, TRUE);
			$OAuth_detail = json_decode($this->setting->oauth_setting, TRUE);
		  ?>
		  <input type="hidden" id="act" name="act" value="auth_integration">
		  <div class="row form-group mb-3">
		    <div class="col-lg-12">
			  <label>Google reCAPTCHA ( <?=my_caption('ai_google_recaptcha_version_notice')?> )</label>
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('google_recaptcha_enabled') == '') ? $checked = $this->setting->recaptcha_enabled : $checked = set_value('google_recaptcha_enabled');
				  $data = array(
				    'name' => 'google_recaptcha_enabled',
					'id' => 'google_recaptcha_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="google_recaptcha_enabled"><?=my_caption('global_enabled')?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-6">
			  <label>Site Key</label>
			  <?php
			    (set_value('google_recaptcha_site_key') == '') ? $google_recaptcha_site_key = $google_recaptcha_array['site_key'] : $google_recaptcha_site_key = set_value('google_recaptcha_site_key');
			    $data = array(
				  'name' => 'google_recaptcha_site_key',
				  'id' => 'google_recaptcha_site_key',
				  'value' => $google_recaptcha_site_key,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('google_recaptcha_site_key', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label>Secret Key</label>
			  <?php
			    (set_value('google_recaptcha_secret_key') == '') ? $google_recaptcha_secret_key = $google_recaptcha_array['secret_key'] : $google_recaptcha_secret_key = set_value('google_recaptcha_secret_key');
			    ($this->config->item('my_demo_mode')) ? $google_recaptcha_secret_key = '********' : null;
				$data = array(
				  'name' => 'google_recaptcha_secret_key',
				  'id' => 'google_recaptcha_secret_key',
				  'value' => $google_recaptcha_secret_key,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('google_recaptcha_secret_key', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mt-5 mb-3">
		    <div class="col-lg-12">
			  <label>Google Login</label>
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('google_login_enabled') == '') ? $checked = $OAuth_detail['google']['enabled'] : $checked = set_value('google_login_enabled');
				  $data = array(
				    'name' => 'google_login_enabled',
					'id' => 'google_login_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="google_login_enabled"><?=my_caption('global_enabled')?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-6">
			  <label>Client ID</label>
			  <?php
			    (set_value('google_client_id') == '') ? $google_client_id = $OAuth_detail['google']['client_id'] : $google_client_id = set_value('google_client_id');
			    $data = array(
				  'name' => 'google_client_id',
				  'id' => 'google_client_id',
				  'value' => $google_client_id,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('google_client_id', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label>Client Secret</label>
			  <?php
			    (set_value('google_client_secret') == '') ? $google_client_secret = $OAuth_detail['google']['client_secret'] : $google_client_secret = set_value('google_client_secret');
			    ($this->config->item('my_demo_mode')) ? $google_client_secret = '********' : null;
				$data = array(
				  'name' => 'google_client_secret',
				  'id' => 'google_client_secret',
				  'value' => $google_client_secret,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('google_client_secret', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mt-5 mb-3">
		    <div class="col-lg-12">
			  <label>Facebook Login</label>
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('facebook_login_enabled') == '') ? $checked = $OAuth_detail['facebook']['enabled'] : $checked = set_value('facebook_login_enabled');
				  $data = array(
				    'name' => 'facebook_login_enabled',
					'id' => 'facebook_login_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="facebook_login_enabled"><?=my_caption('global_enabled')?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-6">
			  <label>App ID</label>
			  <?php
			    (set_value('facebook_app_id') == '') ? $facebook_app_id = $OAuth_detail['facebook']['app_id'] : $facebook_app_id = set_value('facebook_app_id');
			    $data = array(
				  'name' => 'facebook_app_id',
				  'id' => 'facebook_app_id',
				  'value' => $facebook_app_id,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('facebook_app_id', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label>App Secret</label>
			  <?php
			    (set_value('facebook_app_secret') == '') ? $facebook_app_secret = $OAuth_detail['facebook']['app_secret'] : $facebook_app_secret = set_value('facebook_app_secret');
			    ($this->config->item('my_demo_mode')) ? $facebook_app_secret = '********' : null;
				$data = array(
				  'name' => 'facebook_app_secret',
				  'id' => 'facebook_app_secret',
				  'value' => $facebook_app_secret,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('facebook_app_secret', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mt-5 mb-3">
		    <div class="col-lg-12">
			  <label>Twitter Login</label>
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('twitter_login_enabled') == '') ? $checked = $OAuth_detail['twitter']['enabled'] : $checked = set_value('twitter_login_enabled');
				  $data = array(
				    'name' => 'twitter_login_enabled',
					'id' => 'twitter_login_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="twitter_login_enabled"><?=my_caption('global_enabled')?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-6">
			  <label>Consumer Key</label>
			  <?php
			    (set_value('twitter_consumer_key') == '') ? $twitter_consumer_key = $OAuth_detail['twitter']['consumer_key'] : $twitter_consumer_key = set_value('twitter_consumer_key');
			    $data = array(
				  'name' => 'twitter_consumer_key',
				  'id' => 'twitter_consumer_key',
				  'value' => $twitter_consumer_key,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('twitter_consumer_key', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label>Consumer Secret</label>
			  <?php
			    (set_value('twitter_consumer_secret') == '') ? $twitter_consumer_secret = $OAuth_detail['twitter']['consumer_secret'] : $twitter_consumer_secret = set_value('twitter_consumer_secret');
			    ($this->config->item('my_demo_mode')) ? $twitter_consumer_secret = '********' : null;
				$data = array(
				  'name' => 'twitter_consumer_secret',
				  'id' => 'twitter_consumer_secret',
				  'value' => $twitter_consumer_secret,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('twitter_consumer_secret', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
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
		  <?php echo form_close(); ?>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>