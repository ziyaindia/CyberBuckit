<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('addons_install')?></h1>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-6">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_install')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('payment/install_action/'), ['method'=>'POST']);
			$referral_code = random_string('alnum', 8);
		  ?>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_purchase_code')?></label>
			  <?php
			    (!empty(set_value('purchase_code'))) ? $purchase_code = set_value('purchase_code') : $purchase_code = '';
			    $data = array(
				  'name' => 'purchase_code',
				  'id' => 'purchase_code',
				  'value' => $purchase_code,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('purchase_code', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_submit_block',
				  'id' => 'btn_submit_block',
				  'value' => my_caption('addons_intall_btn_text'),
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