<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (substr($this->router->fetch_method(), 0, 18) == 'email_template_new') {
	$email_type = my_caption('et_email_custom_label');
	$title = my_caption('et_email_new');
	$action_url = 'admin/email_template_new_action/';
}
else {
	switch ($rs->purpose) {
	  case 'signup_activation' :
		$email_type = my_caption('et_email_register_label');
		break;
	  case 'reset_password' :
	    $email_type = my_caption('et_email_forgot_label');
		break;
	  case 'change_email' :
	    $email_type = my_caption('et_email_change_label');
		break;
	  case 'invite_email' :
	    $email_type = my_caption('et_email_invite_label');
		break;
	  case 'notify_email' :
	    $email_type = my_caption('et_email_new_notify_label');
		break;
	  default :
	    $email_type = my_caption('et_email_custom_label');
	}
	$title = my_caption('et_email_edit');
	$action_url = 'admin/email_template_edit_action/' . my_uri_segment(3) . '/';
}
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_esc_html($title)?></h1>

  <div class="row">
    <div class="col-lg-9">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($title)?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url($action_url), ['method'=>'POST']);
			if (substr($this->router->fetch_method(), 0, 18) == 'email_template_new') {
				(set_value('email_body') == '') ? $email_body = '' : $email_body = set_value('email_body');
			}
			else {
				(set_value('email_body') == '') ? $email_body = html_escape($rs->body) : $email_body = set_value('email_body');
			}
		  ?>
		  <input type="hidden" name="email_body_value" id="email_body_value" value="<?=my_esc_html($email_body)?>">
		  <div class="row form-group mb-3">
		    <div class="col-lg-12">
			  <p class="h5"><?=my_esc_html($email_type)?></p>
			</div>
		  </div>
		  <div class="row form-group mb-3">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('et_email_purpose')?></label>
			  <?php
			    $data = [];
			    if (substr($this->router->fetch_method(), 0, 18) == 'email_template_new') {
					(set_value('email_purpose') == '') ? $email_purpose = '' : $email_purpose = set_value('email_purpose');
				}
				else {
					(set_value('email_purpose') == '') ? $email_purpose = $rs->purpose : $email_purpose = set_value('email_purpose');
					($rs->built_in) ? $data['readonly'] = '' : null;
				}
			    $data += array(
				  'name' => 'email_purpose',
				  'id' => 'email_purpose',
				  'value' => $email_purpose,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('email_purpose', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('et_email_subject')?></label>
			  <?php
			    if (substr($this->router->fetch_method(), 0, 18) == 'email_template_new') {
					(set_value('email_subject') == '') ? $email_subject = '' : $email_subject = set_value('email_subject');
				}
				else {
					(set_value('email_subject') == '') ? $email_subject = $rs->subject : $email_subject = set_value('email_subject');
				}
			    $data = array(
				  'name' => 'email_subject',
				  'id' => 'email_subject',
				  'value' => $email_subject,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('email_subject', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('et_email_body')?></label>
			  <textarea id="email_body" name="email_body"></textarea>
			  <?=form_error('email_body', '<small class="text-danger">', '</small>')?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <?php
			    (substr($this->router->fetch_method(), 0, 18) == 'email_template_new') ? $button_name = my_caption('global_submit') : $button_name = my_caption('global_save_changes');
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_change',
				  'id' => 'btn_change',
				  'value' => $button_name,
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