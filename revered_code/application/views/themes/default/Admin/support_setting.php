<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_setting_title')?></h1>

  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
  <div class="row">
    <div class="col-lg-8">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_setting_title')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('admin/support_setting_action/'), ['method'=>'POST']);
			$ticket_setting_array = json_decode($this->setting->ticket_setting, TRUE);
		  ?>
		  <input type="hidden" id="act" name="act" value="support_setting">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><b><?=my_caption('support_ticket_title')?></b></label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('support_ticket_enabled') == '') ? $checked = $ticket_setting_array['enabled'] : $checked = set_value('support_ticket_enabled');
				  $data = array(
				    'name' => 'support_ticket_enabled',
					'id' => 'support_ticket_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="support_ticket_enabled"><?php echo my_caption('support_ticket_setting_enable');?></label>
              </div>
			</div>
		    <div class="col-lg-6">
			  <label>&nbsp;</label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('support_ticket_guest_enabled') == '') ? $checked = $ticket_setting_array['guest_ticket'] : $checked = set_value('support_ticket_guest_enabled');
				  $data = array(
				    'name' => 'support_ticket_guest_enabled',
					'id' => 'support_ticket_guest_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="support_ticket_guest_enabled"><?php echo my_caption('support_ticket_setting_enable_guest');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('support_ticket_rating_enabled') == '') ? $checked = $ticket_setting_array['rating'] : $checked = set_value('support_ticket_rating_enabled');
				  $data = array(
				    'name' => 'support_ticket_rating_enabled',
					'id' => 'support_ticket_rating_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="support_ticket_rating_enabled"><?php echo my_caption('support_ticket_setting_enable_rating');?></label>
              </div>
			</div>
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox">
			    <?php
				  (set_value('support_ticket_notify_user') == '') ? $checked = $ticket_setting_array['notify_user'] : $checked = set_value('support_ticket_notify_user');
				  $data = array(
				    'name' => 'support_ticket_notify_user',
					'id' => 'support_ticket_notify_user',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="support_ticket_notify_user"><?php echo my_caption('support_ticket_setting_notify_user');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
		    <div class="col-lg-12">
			  <label><?=my_caption('support_ticket_setting_notify_email_address')?></label>
			  <?php
			    (set_value('support_ticket_notification_email_address') == '') ? $support_ticket_notification_email_address = $ticket_setting_array['notify_agent_list'] : $support_ticket_notification_email_address = set_value('support_ticket_notification_email_address');
				$data = array(
				  'name' => 'support_ticket_notification_email_address',
				  'id' => 'support_ticket_notification_email_address',
				  'value' => $support_ticket_notification_email_address,
				  'placeholder' => my_caption('support_ticket_setting_notify_email_address_placeholder'),
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('support_ticket_notification_email_address', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('support_ticket_setting_close_policy')?></label>
			  <?php
			    (set_value('support_ticket_setting_close_policy') == '') ? $support_ticket_setting_close_policy = $ticket_setting_array['close_rule'] : $support_ticket_setting_close_policy = set_value('support_ticket_setting_close_policy');
			    $options = array (
				  '3' => '3 ' . my_caption('support_ticket_setting_close_policy_replaceholder_days'),
				  '7' => '7 ' . my_caption('support_ticket_setting_close_policy_replaceholder_days'),
				  '15' => '15 ' . my_caption('support_ticket_setting_close_policy_replaceholder_days'),
				  '0' => my_caption('support_ticket_setting_close_policy_replaceholder_never')
				);
				$data = array(
				  'class' => 'form-control selectpicker'
				);
				echo form_dropdown('support_ticket_setting_close_policy', $options, $support_ticket_setting_close_policy, $data);
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
		  <?php echo form_close(); ?>
		</div>
      </div>
	</div>
	<div class="col-lg-4">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_setting_catalog')?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-12">
			  <a href="<?=base_url('admin/catalog')?>"><u><?=my_caption('global_goto_catalog_button')?></u></a>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>