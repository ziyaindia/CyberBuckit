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
              <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><?=my_caption('reset_welcome_message')?></h1>
                  </div>
				  <?php
				    my_load_view($this->setting->theme, 'Generic/show_flash_card');
				    echo form_open(base_url('auth/reset_password_action/') . my_uri_segment(3) . '/', ['method'=>'POST', 'class'=>'user']);
				  ?>
				    <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
                    <div class="form-group">
					  <?php
					    $data = array(
						  'name' => 'password',
						  'id' => 'password',
						  'value' => set_value('password'),
						  'class' => 'form-control form-control-user',
						  'autocomplete' => 'off',
						  'placeholder' => my_caption('reset_password_placeholder')
						);
						echo form_password($data);
						echo form_error('password', '<small class="text-danger">', '</small>');
					  ?>
					</div>
                    <div class="form-group">
					  <?php
					    $data = array(
						  'name' => 'confirm_password',
						  'id' => 'confirm_password',
						  'value' => set_value('confirm_password'),
						  'class' => 'form-control form-control-user',
						  'autocomplete' => 'off',
						  'placeholder' => my_caption('reset_confirm_password_placeholder')
						);
						echo form_password($data);
						echo form_error('confirm_password', '<small class="text-danger">', '</small>');
					  ?>
					</div>
				    <?php
					  $data = array(
					    'type' => 'submit',
					    'name' => 'btn_submit_block',
					    'id' => 'btn_submit_block',
						'value' => my_caption('reset_reset_button'),
					    'class' => 'btn btn-primary btn-user btn-block'
					  );
					  echo form_submit($data);
					?>
                  <?php echo form_close(); ?>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="<?=base_url('auth/signin')?>"><?=my_caption('auth_signin_link')?></a>
                  </div>
				  <?php
				    if ($this->setting->signup_enabled) {
				  ?>
                  <div class="text-center">
                    <a class="small" href="<?=base_url('auth/signup')?>"><?=my_caption('auth_signup_link')?></a>
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