<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('user_new_title')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('user_new_title')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('admin/new_user_action/'), ['method'=>'POST']);
		  ?>
		  <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('signup_first_name_label')?></label>
			  <?php
			    (!empty(set_value('first_name'))) ? $first_name = set_value('first_name') : $first_name = '';
			    $data = array(
				  'name' => 'first_name',
				  'id' => 'first_name',
				  'value' => $first_name,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('first_name', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('signup_last_name_label')?></label>
			  <?php
			    (!empty(set_value('last_name'))) ? $last_name = set_value('last_name') : $last_name = '';
			    $data = array(
				  'name' => 'last_name',
				  'id' => 'last_name',
				  'value' => $last_name,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('last_name', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('global_email_address')?></label>
			  <?php
			    (!empty(set_value('email_address'))) ? $email_address = set_value('email_address') : $email_address = '';
			    $data = array(
				  'name' => 'email_address',
				  'id' => 'email_address',
				  'value' => $email_address,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('email_address', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><?=my_caption('user_new_send_notification')?></label>
			  <div class="custom-control custom-checkbox ml-2">
			    <?php
			      (set_value('send_notification') == '1') ? $checked = 'checked' : $checked = '';
				  $data = array(
				    'name' => 'send_notification',
				    'id' => 'send_notification',
				    'value' => '1',
				    'checked' => $checked,
				   'class' => 'custom-control-input'
				  ); 
				  echo form_checkbox($data);
			    ?>
			    <label class="custom-control-label" for="send_notification"><?=my_caption('global_yes')?></label>
			  </div>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('signup_confirm_label')?></label>
			  <?php
			    (!empty(set_value('password'))) ? $password = set_value('password') : $password = '';
			    $data = array(
				  'name' => 'password',
				  'id' => 'password',
				  'value' => $password,
				  'class' => 'form-control'
				);
				echo form_password($data);
				echo form_error('password', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('cp_new_password_confirm_label')?></label>
			  <?php
			    (!empty(set_value('confirm_password'))) ? $confirm_password = set_value('confirm_password') : $confirm_password = '';
			    $data = array(
				  'name' => 'confirm_password',
				  'id' => 'confirm_password',
				  'value' => $confirm_password,
				  'class' => 'form-control'
				);
				echo form_password($data);
				echo form_error('confirm_password', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_submit_block',
				  'id' => 'btn_submit_block',
				  'value' => my_caption('user_new_create_button'),
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