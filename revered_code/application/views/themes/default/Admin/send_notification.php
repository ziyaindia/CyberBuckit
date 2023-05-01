<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('notification_send_title')?></h1>
	</div>
    <div class="col-lg-8 text-right">
	  <button type="button" class="btn btn-primary" onclick="window.location.href='<?=base_url('admin/list_notification')?>'"><?=my_caption('notification_list_notification')?></button>
    </div>
  </div>
  
  <div class="row">
    <div class="col-lg-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('notification_send_title')?> <?=my_caption('notification_send_tips')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('admin/send_notification_action/'), ['method'=>'POST']);
			(set_value('notification_body') == '') ? $notification_body = '' : $notification_body = set_value('notification_body');
		  ?>
		  <input type="hidden" name="notification_body_value" id="notification_body_value" value="<?=my_esc_html($notification_body)?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('notification_subject')?></label>
			  <?php
			    (set_value('notification_subject') == '') ? $notification_subject = set_value('notification_subject') : $notification_subject = '';
			    $data = array(
				  'name' => 'notification_subject',
				  'id' => 'notification_subject',
				  'value' => $notification_subject,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('notification_subject', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('notification_body')?></label>
			  <textarea id="notification_body" name="notification_body"></textarea>
			  <?=form_error('notification_body', '<small class="text-danger">', '</small>')?>
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
				  'value' => my_caption('global_send'),
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