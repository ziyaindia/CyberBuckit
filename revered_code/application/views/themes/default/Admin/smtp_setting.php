<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('smtp_titile')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('smtp_titile')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('admin/smtp_setting_action/'), ['method'=>'POST']);
			$smtp_array = json_decode($this->setting->smtp_setting, TRUE);
		  ?>
		  <div class="row form-group mb-4">
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('smtp_setting_host_label')?></label>
			  <?php
			    (set_value('host') == '') ? $host = $smtp_array['host'] : $host = set_value('host');
			    $data = array(
				  'name' => 'host',
				  'id' => 'host',
				  'value' => $host,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('host', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('smtp_setting_encryption_label')?></label>
			  <?php
			    (set_value('encryption') == '') ? $encryption = $smtp_array['crypto'] : $encryption  = set_value('encryption');
			    $options = array (
				  'none' => 'None',
				  'tls' => 'TLS',
				  'ssl' => 'SSL'
				);
				$data = array(
				  'class' => 'form-control selectpicker'
				);
				echo form_dropdown('encryption', $options, $encryption, $data);
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('smtp_setting_port_label')?></label>
			  <?php
			    (set_value('port') == '') ? $port = $smtp_array['port'] : $port = set_value('port');
			    $data = array(
				  'name' => 'port',
				  'id' => 'port',
				  'value' => $port,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('port', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('smtp_setting_username_label')?></label>
			  <?php
			    (set_value('username') == '') ? $username = $smtp_array['username'] : $username = set_value('username');
			    $data = array(
				  'name' => 'username',
				  'id' => 'username',
				  'value' => $username,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('username', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('smtp_setting_password_label')?></label>
			  <?php
			    (set_value('password') == '') ? $password = $smtp_array['password'] : $password = set_value('password');
				($this->config->item('my_demo_mode')) ? $password = '********' : null;
			    $data = array(
				  'name' => 'password',
				  'id' => 'password',
				  'value' => $password,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('password', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('smtp_setting_from_email_label')?></label>
			  <?php
			    (set_value('from_email') == '') ? $from_email = $smtp_array['from_email'] : $from_email = set_value('from_email');
			    $data = array(
				  'name' => 'from_email',
				  'id' => 'from_email',
				  'value' => $from_email,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('from_email', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('smtp_setting_sender_name_label')?></label>
			  <?php
			    (set_value('from_name') == '') ? $from_name = $smtp_array['from_name'] : $from_name = set_value('from_name');
			    $data = array(
				  'name' => 'from_name',
				  'id' => 'from_name',
				  'value' => $from_name,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('from_name', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  
		  <hr>
		  <div class="row">
			<div class="col-lg-6 text-left">
			  <button type="button" name="btn_test" id="btn_test" class="btn btn-success" data-toggle="tooltip" data-placement="top" data-original-title="<?=my_caption('smtp_button_send_test_tooltip')?>" onclick="simple_input_modal_show('<?=base_url('admin/send_test_email/')?>', '', '<?=my_caption('smtp_test_modal_title')?>', '<?=my_caption('smtp_test_modal_input_label')?>')"><?=my_caption('smtp_button_send_test')?></button>
			</div>
			<div class="col-lg-6 text-right">
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
<?php my_load_view($this->setting->theme, 'Generic/simple_input_modal');?>