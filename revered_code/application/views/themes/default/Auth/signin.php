<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<?php my_load_view($this->setting->theme, 'Auth/header');?>

<body class="bg-gradient-primary">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><?=my_caption('signin_welcome_message')?></h1>
                  </div>
				    <?php
				    my_load_view($this->setting->theme, 'Generic/show_flash_card');
				    echo form_open(base_url('auth/signin_action/'), ['method'=>'POST', 'class'=>'user']);
				    if (!empty($_SESSION['oauth_ids'])) {
						echo '<div class="form-group">';
						(set_value('choose_action') == '') ? $choose_action = 'signin' : $choose_action  = set_value('choose_action');
						$options = array(
						  'signin' => my_caption('signin_oauth_choose_to_signin'),
						  'signup' => my_caption('signin_oauth_choose_to_signup'),
						  'ignore' => my_caption('signin_oauth_choose_ignore')
						);
						$data = array(
						  'id' => 'choose_action',
						  'class' => 'form-control',
						  'style' => 'border-radius:25px;height:50px;',
						  'onchange' => 'go_to_signup()'
						);
						echo form_dropdown('choose_action', $options, $choose_action, $data);
						echo form_error('choose_action', '<small class="text-danger">', '</small>');
						echo '</div>';
					}
					?>
					<input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
                    <div class="form-group">
					  <?php
					    $data = array(
						  'name' => 'username',
						  'id' => 'username',
						  'value' => set_value('username'),
						  'class' => 'form-control form-control-user',
						  'placeholder' => my_caption('signin_username_placeholder')
						);
						echo form_input($data);
						echo form_error('username', '<small class="text-danger">', '</small>');
					  ?>
					</div>
                    <div class="form-group">
					  <?php
					    $data = array(
						  'name' => 'password',
						  'id' => 'password',
						  'value' => set_value('password'),
						  'class' => 'form-control form-control-user',
						  'autocomplete' => 'off',
						  'placeholder' => my_caption('signin_password_placeholder')
						);
						echo form_password($data);
						echo form_error('password', '<small class="text-danger">', '</small>');
					  ?>
                    </div>
					<?php
					  if ($this->setting->recaptcha_enabled) { 
					    $recaptcha_array = json_decode($this->setting->recaptcha_detail, TRUE);
					?>
					  <div class="form-group text-center">
					    <div class="g-recaptcha style-inline-block" data-sitekey="<?php echo my_esc_html($recaptcha_array['site_key']); ?>"></div>
						<?php echo form_error('g-recaptcha-response', '<small class="text-danger">', '</small>');?>
					  </div>
					<?php
					  }
					  if ($this->setting->remember) {  
					?>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
						<?php
						  (set_value('remember') == '') ? $checked = '' : $checked  = 'checked';
						  $data = array(
						    'name' => 'remember',
							'id' => 'remember',
							'value' => 1,
							'checked' => $checked,
							'class' => 'custom-control-input'
						  );
						  echo form_checkbox($data);
						?>
                        <label class="custom-control-label" for="remember"><?=my_caption('signin_rememberme')?></label>
                      </div>
                    </div>
					<?php
					  }
					  $data = array(
					    'type' => 'submit',
					    'name' => 'btn_submit_block',
					    'id' => 'btn_submit_block',
						'value' => my_caption('signin_signin_button'),
					    'class' => 'btn btn-primary btn-user btn-block'
					  );
					  echo form_submit($data);
					?>
					<hr>
					<?php
					  $oauth_array = json_decode($this->setting->oauth_setting, TRUE);
					  $social_enabled = 0;
					  if ($oauth_array['google']['enabled']) {
						  $social_enabled = 1;
					?>
                    <button type="button" onclick="window.location.href='<?=base_url('oauth/verify/signin/?provider=Google')?>'" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> <?=my_caption('signin_signin_button_google')?>
                    </button>
                    <?php
					  }
					  if ($oauth_array['facebook']['enabled']) {
						  $social_enabled = 1;
					?>
					<button type="button" onclick="window.location.href='<?=base_url('oauth/verify/signin/?provider=Facebook')?>'" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook fa-fw"></i> <?=my_caption('signin_signin_button_facebook')?>
                    </button>
                    <?php
					  }
					  if ($oauth_array['twitter']['enabled']) {
						  $social_enabled = 1;
					?>
					<button type="button" onclick="window.location.href='<?=base_url('oauth/verify/signin/?provider=Twitter')?>'" class="btn btn-twitter btn-user btn-block">
                      <i class="fab fa-twitter fa-fw"></i> <?=my_caption('signin_signin_button_twitter')?>
                    </button>
					<?php
					  }
					  if ($social_enabled) { echo '<hr>'; }
					?>
                  <?php echo form_close(); ?>
				  <?php
				    if ($this->setting->signup_enabled) {
				  ?>
                  <div class="text-center">
                    <a class="small" href="<?=base_url('auth/signup')?>"><?=my_caption('auth_signup_link')?></a>
                  </div>
				  <?php
				    }
				    if ($this->setting->forget_enabled) {
				  ?>
                  <div class="text-center">
                    <a class="small" href="<?=base_url('auth/forget')?>"><?=my_caption('auth_forget_link')?></a>
                  </div>
				  <?php
				    }
				    if ($this->config->item('my_demo_mode')) {
				  ?>
				  <div class="text-left mt-4">
				    <b>Demo Credentials</b> (Click to Autofill)<br>
					Super Admin: <a href="javascript:void(0)" onclick="demo_show_credential('admin')"><u>admin/admin</u></a><br>
					User: <a href="javascript:void(0)" onclick="demo_show_credential('user')"><u>user/user</u></a>
				  </div>
				  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php my_load_view($this->setting->theme, 'Auth/footer')?>
</body>

</html>