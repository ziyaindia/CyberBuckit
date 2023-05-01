<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<?php my_load_view($this->setting->theme, 'Auth/header');?>

<body class="bg-gradient-primary">

  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4"><?=my_caption('signup_welcome_message')?></h1>
              </div>
			  <?php
			    if ($this->setting->kyc) {
					echo '<div class="card mb-4 py-3 border-left-success"><div class="card-body">' . my_caption('signup_signup_payment_notice') . '</div></div>';
				}
			    my_load_view($this->setting->theme, 'Generic/show_flash_card');
			    echo form_open(base_url('auth/signup_action/'), ['method'=>'POST', 'class'=>'user']);
				if (!empty($oauth_rs)) {
					$_SESSION['oauth_signup_ids'] = $oauth_rs->ids;
				}
				else {
					if (!empty($_SESSION['oauth_signup_ids'])) { unset($_SESSION['oauth_signup_ids']); }
				}
			  ?>
			    <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
					<?php
					  if (set_value('first_name') == '') {
						  (!empty($oauth_rs)) ? $first_name = $oauth_rs->first_name : $first_name = '';
					  }
					  else {
						  $first_name = set_value('first_name');
					  }
					  $data = array(
					    'name' => 'first_name',
						'id' => 'first_name',
						'value' => $first_name,
						'class' => 'form-control form-control-user',
						'placeholder' => my_caption('signup_first_name_placeholder')
					  );
					  echo form_input($data);
					  echo form_error('first_name', '<small class="text-danger">', '</small>');
					?>
                  </div>
                  <div class="col-sm-6">
					<?php
					  if (set_value('last_name') == '') {
						  (!empty($oauth_rs)) ? $last_name = $oauth_rs->last_name : $last_name = '';
					  }
					  else {
						  $last_name = set_value('last_name');
					  }
					  $data = array(
					    'name' => 'last_name',
						'id' => 'last_name',
						'value' => $last_name,
						'class' => 'form-control form-control-user',
						'placeholder' => my_caption('signup_last_name_placeholder')
					  );
					  echo form_input($data);
					  echo form_error('last_name', '<small class="text-danger">', '</small>');
					?>
                  </div>
                </div>
                <div class="form-group">
				  <?php
					if (set_value('email_address') == '') {
						if (!empty($oauth_rs)) {
							$email_address = $oauth_rs->email_address;
						}
						elseif (!empty($_SESSION['invite_email'])) {
							$email_address = $_SESSION['invite_email'];
						}
						else {
							$email_address = '';
						}
					}
					else {
						$email_address = set_value('email_address');
					}
				    $data = array(
					  'name' => 'email_address',
					  'id' => 'email_address',
					  'value' => $email_address,
					  'class' => 'form-control form-control-user',
					  'placeholder' => my_caption('global_email_address')
					);
					(!empty($_SESSION['invite_email'])) ? $data['readonly'] = 'readonly' : null;
					echo form_input($data);
					echo form_error('email_address', '<small class="text-danger">', '</small>');
				  ?>
                </div>
                <div class="form-group row mb-4">
                  <div class="col-sm-6 mb-3 mb-sm-0">
				    <?php
					  $data = array(
					    'name' => 'password',
						'id' => 'password',
						'value' => set_value('password'),
						'class' => 'form-control form-control-user',
						'autocomplete' => 'off',
						'placeholder' => my_caption('signup_password_placeholder')
					  );
					  echo form_password($data);
					  echo form_error('password', '<small class="text-danger">', '</small>');
				    ?>
                  </div>
                  <div class="col-sm-6">
				    <?php
					  $data = array(
					    'name' => 'confirm_password',
						'id' => 'confirm_password',
						'value' => set_value('confirm_password'),
						'class' => 'form-control form-control-user',
						'autocomplete' => 'off',
						'placeholder' => my_caption('signup_confirm_placeholder')
					  );
					  echo form_password($data);
					  echo form_error('confirm_password', '<small class="text-danger">', '</small>');
				    ?>
                  </div>
                </div>
				<?php
				  if ($this->setting->tc_show) {
				?>
                <div class="form-group row mb-4">
                  <div class="col-sm-12 mb-3 mb-sm-0">
                      <div class="custom-control custom-checkbox small">
						<?php
						  (set_value('tc_agree') == '1') ? $tc_agree = 'checked' : $tc_agree = '';
						  $data = array(
						    'name' => 'tc_agree',
							'id' => 'tc_agree',
							'value' => '1',
							'checked' => $tc_agree,
							'class' => 'custom-control-input'
						  );
						  echo form_checkbox($data);
						?>
                        <label class="custom-control-label" for="tc_agree"><a href="<?=base_url('generic/terms_conditions')?>" target="_blank"><?=my_caption('signup_tc_agree')?></a></label>
                      </div>
					  <?php echo form_error('tc_agree', '<small class="text-danger">', '</small>');?>
                  </div>
                </div>
				<?php
				  }
				  if ($this->setting->recaptcha_enabled) { 
				    $recaptcha_array = json_decode($this->setting->recaptcha_detail, TRUE);
				?>
				    <div class="form-group">
					  <div class="g-recaptcha style-inline-block" data-sitekey="<?php echo my_esc_html($recaptcha_array['site_key']); ?>"></div>
					  <?php echo form_error('g-recaptcha-response', '<br><small class="text-danger">', '</small>');?>
					</div>
				<?php
				  }
				  $data = array(
				    'type' => 'submit',
				    'name' => 'btn_submit_block',
				    'id' => 'btn_submit_block',
				    'value' => my_caption('signup_signup_button'),
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
                <button type="button" onclick="window.location.href='<?=base_url('oauth/verify/signup/?provider=Google')?>'" class="btn btn-google btn-user btn-block">
                  <i class="fab fa-google fa-fw"></i> <?=my_caption('signup_signup_button_google')?>
                </button>
                <?php
				  }
				  if ($oauth_array['facebook']['enabled']) {
					  $social_enabled = 1;
				?>
				<button type="button" onclick="window.location.href='<?=base_url('oauth/verify/signup/?provider=Facebook')?>'" class="btn btn-facebook btn-user btn-block">
                  <i class="fab fa-facebook fa-fw"></i> <?=my_caption('signup_signup_button_facebook')?>
                </button>
                <?php
				  }
				  if ($oauth_array['twitter']['enabled']) {
					  $social_enabled = 1;
				?>
				<button type="button" onclick="window.location.href='<?=base_url('oauth/verify/signup/?provider=Twitter')?>'" class="btn btn-twitter btn-user btn-block">
                  <i class="fab fa-twitter fa-fw"></i> <?=my_caption('signup_signup_button_twitter')?>
                </button>
				<?php
				  }
				  if ($social_enabled) { echo '<hr>'; }
				?>
              <?php echo form_close(); ?>
              <div class="text-center">
                <a class="small" href="<?=base_url('auth/signin')?>"><?=my_caption('auth_signin_link')?></a>
              </div>
			  <?php
			    if ($this->setting->forget_enabled) {
			  ?>
              <div class="text-center">
                <a class="small" href="<?=base_url('auth/forget')?>"><?=my_caption('auth_forget_link')?></a>
              </div>
			  <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php my_load_view($this->setting->theme, 'Auth/footer')?>
</body>

</html>