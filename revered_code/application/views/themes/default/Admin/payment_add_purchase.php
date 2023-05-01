<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('payment_add_purchase_title')?></h1>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="row mb-4">
	    <div class="col-lg-8">
	      <div class="card shadow mb-4">
		    <div class="card-header py-3">
		      <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('payment_add_purchase_title')?></h6>
		    </div>
			<?php echo form_open(base_url('admin/payment_add_purchase_action/'), ['method'=>'POST']);?>
			<input type="hidden" name="user_ids" id="user_ids" value="<?=my_esc_html($rs_user->ids)?>">
			<div class="card-body">
			  <div class="row form-group mb-4">
			    <div class="col-lg-7">
				  <label><?=my_caption('payment_user_email_address')?> <a href="<?=base_url('admin/edit_user/' . $rs_user->ids)?>" class="ml-2">[<?=my_caption('payment_adjust_balance_view_user')?>]</a></label>
				  <?php
					$data = array(
					  'type' => 'text',
					  'value' => $rs_user->email_address,
					  'class' => 'form-control',
					  'disabled' => 'disabled'
					);
					echo form_input($data);
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-7">
				  <label><span class="text-danger">*</span> <?=my_caption('payment_add_purchase_item')?></label>
				  <?php
				    (set_value('purchase') == '') ? $purchase = '' : $purchase = set_value('purchase');
					$options = array(
					  '' => my_caption('payment_add_purchase_please_select'),
					);
					foreach ($rs_purchase as $row) {
						$options[$row->ids] = $row->item_name . ', ' . $row->item_currency . ' ' . $row->item_price;
					}
					$data = array(
					  'class' => 'form-control selectpicker'
					);
					echo form_dropdown('purchase', $options, $purchase, $data);
					echo form_error('purchase', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-7">
				  <label><?=my_caption('payment_description')?></label>
				  <?php
				    (set_value('description') == '') ? $description = '' : $description = set_value('description');
					$data = array(
					  'name' => 'description',
					  'id' => 'description',
					  'value' => $description,
					  'class' => 'form-control',
					  'rows'=> 5
					);
					echo form_textarea($data);
					echo form_error('description', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-7">
				  <?=my_caption('payment_add_purchase_instructions')?>
				</div>
			  </div>
			  <div class="row">
			    <div class="col-lg-7 text-right">
				  <?php
				    $data = array(
					  'type' => 'submit',
					  'name' => 'btn_submit_block',
					  'id' => 'btn_submit_block',
					  'value' => my_caption('global_submit'),
					  'class' => 'btn btn-primary mr-2'
					);
					echo form_submit($data);
					?>
				</div>
			  </div>
			</div>
			<?php echo form_close(); ?>
		  </div>
	    </div>
		<div class="col-lg-4">
		  <?php 
		    $view_data['rs_user'] = $rs_user;
			$view_data['title'] = my_caption('payment_adjust_user_balance');
		    my_load_view($this->setting->theme, 'Generic/user_balance_list', $view_data);
		  ?>
		</div>
	  </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>