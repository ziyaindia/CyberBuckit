<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('misc_title')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('misc_title')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('admin/miscellaneous_action/'), ['method'=>'POST']);
			$gdpr_array = json_decode($this->setting->gdpr, TRUE);
			$file_setting_array = json_decode(my_global_setting('file_setting'), TRUE);
		  ?>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('misc_gdpr')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('cookie_popup') == '') ? $checked = $gdpr_array['enabled'] : $checked = set_value('cookie_popup');
				  $data = array(
				    'name' => 'cookie_popup',
					'id' => 'cookie_popup',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="cookie_popup"><?=my_caption('misc_enable_cookie_popup')?></label>
              </div>
			</div>
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('allow_remove') == '') ? $checked = $gdpr_array['allow_remove'] : $checked = set_value('allow_remove');
				  $data = array(
				    'name' => 'allow_remove',
					'id' => 'allow_remove',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="allow_remove"><?=my_caption('misc_remove_self')?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-4">
			<div class="col-lg-12">
			  <label><?=my_caption('misc_cookie_message')?></label>
			  <?php
			    (set_value('cookie_message') == '') ? $cookie_message = $gdpr_array['cookie_message'] : $cookie_message = set_value('cookie_message');
			    $data = array(
				  'name' => 'cookie_message',
				  'id' => 'cookie_message',
				  'value' => $cookie_message,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('cookie_message', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-6">
			  <label><?=my_caption('misc_cookie_policy_link_text')?></label>
			  <?php
			    (set_value('cookie_policy_link_text') == '') ? $cookie_policy_link_text = $gdpr_array['cookie_policy_link_text'] : $cookie_policy_link_text = set_value('cookie_policy_link_text');
			    $data = array(
				  'name' => 'cookie_policy_link_text',
				  'id' => 'cookie_policy_link_text',
				  'value' => $cookie_policy_link_text,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('cookie_policy_link_text', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('misc_cookie_policy_link')?></label>
			  <?php
			    (set_value('cookie_policy_link') == '') ? $cookie_policy_link = $gdpr_array['cookie_policy_link'] : $cookie_policy_link = set_value('cookie_policy_link');
			    $data = array(
				  'name' => 'cookie_policy_link',
				  'id' => 'cookie_policy_link',
				  'value' => $cookie_policy_link,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('cookie_policy_link', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('file_manager_setting')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-6">
			  <label><?=my_caption('file_manager_setting_file_type')?></label>
			  <?php
			    (set_value('file_type') == '') ? $file_type = $file_setting_array['file_type'] : $file_type = set_value('file_type');
			    $data = array(
				  'name' => 'file_type',
				  'id' => 'file_type',
				  'value' => $file_type,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('file_type', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('file_manager_setting_file_size')?></label>
			  <?php
			    (set_value('file_size') == '') ? $file_size = $file_setting_array['file_size'] : $file_size = set_value('file_size');
			    $data = array(
				  'name' => 'file_size',
				  'id' => 'file_size',
				  'value' => $file_size,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('file_size', '<small class="text-danger">', '</small>');
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