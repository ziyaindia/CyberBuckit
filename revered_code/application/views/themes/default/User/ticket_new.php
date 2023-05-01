<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_ticket_new')?></h1>
	</div>
  </div>
  
  <div class="row">
    <div class="col-lg-9">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
		$ticket_setting_array = json_decode($this->setting->ticket_setting, 1);
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_ticket_new')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('user/ticket_new_action/'), ['method'=>'POST']);
			(set_value('ticket_description') == '') ? $ticket_description = '' : $ticket_description = set_value('ticket_description');
		  ?>
		  <input type="hidden" name="ticket_description_value" id="ticket_description_value" value="<?=my_esc_html($ticket_description)?>">
		  <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('global_catalog')?></label>
			  <?php
			    (set_value('ticket_catalog') == '') ? $ticket_catalog = '1' : $ticket_catalog  = set_value('ticket_catalog');
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('ticket_catalog', $catalog_options, $ticket_catalog, $data);
			    echo form_error('ticket_catalog', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('support_ticket_priority')?></label>
			  <?php
			    (set_value('ticket_priority') == '') ? $ticket_priority = '1' : $ticket_priority  = set_value('ticket_priority');
			    $options = array(
				  '0' => my_caption('support_ticket_priority_low'),
				  '1' => my_caption('support_ticket_priority_normal'),
				  '2' => my_caption('support_ticket_priority_high'),
				);
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('ticket_priority', $options, $ticket_priority, $data);
			    echo form_error('ticket_priority', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('support_ticket_subject')?></label>
			  <?php
			    (set_value('ticket_subject') != '') ? $ticket_subject = set_value('ticket_subject') : $ticket_subject = '';
			    $data = array(
				  'name' => 'ticket_subject',
				  'id' => 'ticket_subject',
				  'value' => $ticket_subject,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ticket_subject', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('support_ticket_description')?></label>
			  <textarea id="ticket_description" name="ticket_description"></textarea>
			  <?=form_error('ticket_description', '<small class="text-danger">', '</small>')?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <?php
			    ($ticket_setting_array['notify_agent_list'] != '') ? $btn_id = 'btn_submit_block' : $btn_id = 'btn_submit'; //determine whether use BlockUI, depends on the setting of sending email
			    $data = array(
				  'type' => 'submit',
				  'name' => $btn_id,
				  'id' => $btn_id,
				  'value' => my_caption('global_submit'),
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