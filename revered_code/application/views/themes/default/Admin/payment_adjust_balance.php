<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('payment_adjust_balance_title')?></h1>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="row mb-4">
	    <div class="col-lg-8">
	      <div class="card shadow mb-4">
		    <div class="card-header py-3">
		      <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('payment_adjust_balance_title')?></h6>
		    </div>
			<?php echo form_open(base_url('admin/payment_adjust_balance_action'), ['method'=>'POST']);?>
			<input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
			<input type="hidden" name="user_ids" id="user_ids" value="<?=my_esc_html($rs->ids)?>">
			<div class="card-body">
			  <div class="row form-group mb-4">
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_user_email_address')?> <a href="<?=base_url('admin/edit_user/' . $rs->ids)?>" class="ml-2">[<?=my_caption('payment_adjust_balance_view_user')?>]</a></label>
				  <?php
					$data = array(
					  'type' => 'text',
					  'value' => $rs->email_address,
					  'class' => 'form-control',
					  'disabled' => 'disabled'
					);
					echo form_input($data);
				  ?>
				</div>
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_adjust_balance_type')?></label>
				  <?php
				    (set_value('adjust_balance_type') == '') ? $adjust_balance_type = 'Add' : $adjust_balance_type = set_value('adjust_balance_type');
					$options = array(
					  'Add' => 'Add Balance',
					  'Cut' => 'Cut Balance'
					);
					$data = array(
					  'class' => 'form-control selectpicker'
					);
					echo form_dropdown('adjust_balance_type', $options, $adjust_balance_type, $data);
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_currency')?></label>
				  <?php
				    (set_value('adjust_currency') == '') ? $adjust_currency = $rs->currency : $adjust_currency = set_value('adjust_currency');
					$data = array(
					  'class' => 'form-control selectpicker'
					);
					echo form_dropdown('adjust_currency', my_currency_list(), $adjust_currency, $data);
				  ?>
				</div>
			    <div class="col-lg-6">
				  <label><span class="text-danger">*</span> <?=my_caption('payment_amount')?></label>
				  <?php
				    (set_value('adjust_amount') == '') ? $adjust_amount = '0.00' : $adjust_amount = set_value('adjust_amount');
					$data = array(
					  'type' => 'number',
					  'step' => '0.01',
					  'name' => 'adjust_amount',
					  'id' => 'adjust_amount',
					  'value' => $adjust_amount,
					  'class' => 'form-control'
					);
					echo form_input($data);
					echo form_error('adjust_amount', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_gateway')?></label>
				  <?php
				    (set_value('adjust_payment_gateway') == '') ? $adjust_payment_gateway = 'Transfer' : $adjust_payment_gateway = set_value('adjust_payment_gateway');
					$options = array(
					  'Bank Transfer' => my_caption('payment_adjust_balance_gateway_bank_transfer'),
					  'Cash' => my_caption('payment_adjust_balance_gateway_cash'),
					  'Note' => my_caption('payment_adjust_balance_gateway_note'),
					  'Others' => my_caption('payment_adjust_balance_gateway_others')
					);
					$data = array(
					  'class' => 'form-control selectpicker'
					);
					echo form_dropdown('adjust_payment_gateway', $options, $adjust_payment_gateway, $data);
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-12">
				  <label><?=my_caption('payment_item_description_label')?></label>
				  <?php
				   (set_value('adjust_description') == '') ? $adjust_description = '' : $adjust_description = set_value('adjust_description');
					$data = array(
					  'name' => 'adjust_description',
					  'id' => 'adjust_description',
					  'value' => $adjust_description,
					  'class' => 'form-control',
					  'rows'=> 8
					);
					echo form_textarea($data);
					echo form_error('adjust_description', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-5">
			    <div class="col-lg-6">
				  <div class="custom-control custom-checkbox">
				    <?php
					  (set_value('adjust_visible_for_user') == '1') ? $checked = 'checked' : $checked = 0;
					  $data = array(
					    'name' => 'adjust_visible_for_user',
						'id' => 'adjust_visible_for_user',
						'value' => '1',
						'checked' => $checked,
						'class' => 'custom-control-input'
					  );
					  echo form_checkbox($data);
					?>
					<label class="custom-control-label" for="adjust_visible_for_user"><?=my_caption('payment_adjust_balance_visible_for_user')?></label>
				  </div>
				</div>
			    <div class="col-lg-6">
				  <div class="custom-control custom-checkbox">
				    <?php
					  (set_value('adjust_create_invoice') == '1') ? $checked = 'checked' : $checked = 0;
					  $data = array(
					    'name' => 'adjust_create_invoice',
						'id' => 'adjust_create_invoice',
						'value' => '1',
						'checked' => $checked,
						'class' => 'custom-control-input'
					  );
					  echo form_checkbox($data);
					?>
					<label class="custom-control-label" for="adjust_create_invoice"><?=my_caption('payment_adjust_balance_create_invoice')?></label>
				  </div>
				</div>
			  </div>
			  <div class="row">
			    <div class="col-lg-6 offset-6 text-right">
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
		    $view_data['rs_user'] = $rs;
			$view_data['title'] = my_caption('payment_adjust_user_balance');
		    my_load_view($this->setting->theme, 'Generic/user_balance_list', $view_data);
		  ?>
		</div>
	  </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>