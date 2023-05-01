<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<?php
  $method = $this->router->fetch_method();
  if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
	  $page_title = my_caption('payment_add_item_title');
	  $action_url = 'admin/payment_item_add_action/';
	  $hidden_ids = '';
	  $btn_text = my_caption('global_submit');
  }
  else {
	  $page_title = my_caption('payment_item_modify_title');
	  $action_url = 'admin/payment_item_modify_action/';
	  $hidden_ids = $rs->ids;
	  $btn_text = my_caption('global_save_changes');
  }
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_esc_html($page_title)?></h1>

  <div class="row">
    <div class="col-lg-12">
	  <div class="row mb-4">
	    <div class="col-lg-9">
		  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	      <div class="card shadow mb-4">
		    <div class="card-header py-3">
		      <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($page_title)?></h6>
		    </div>
			<?php echo form_open(base_url($action_url), ['method'=>'POST']);?>
			<input type="hidden" id="ids" name="ids" value="<?=my_esc_html($hidden_ids)?>">
			<div class="card-body">
			  <div class="row form-group mb-4">
			    <div class="col-lg-6">
				  <label><?=my_caption('global_enabled')?></label>
				  <div class="custom-control custom-checkbox">
				    <?php
					  if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						  (set_value('item_enabled') == '1' || $this->router->fetch_method() == 'payment_item_add') ? $checked = 'checked' : $checked = '';
					  }
					  else {
						  (set_value('item_enabled') == '1') ? $checked = 'checked' : $checked = $rs->enabled;
					  }
					  $data = array(
					    'name' => 'item_enabled',
						'id' => 'item_enabled',
						'value' => '1',
						'checked' => $checked,
						'class' => 'custom-control-input'
					  );
					  echo form_checkbox($data);
					?>
					<label class="custom-control-label" for="item_enabled"><?=my_caption('global_enabled')?></label>
				  </div>
				</div>
				<div class="col-lg-6">
				  <label><?=my_caption('payment_type_label')?></label>
				  <?php
				    $data = array();
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(!empty(set_value('item_type'))) ? $item_type = set_value('item_type') : $item_type = 'purchase';
					}
					else {
						(!empty(set_value('item_type'))) ? $item_type = set_value('item_type') : $item_type = $rs->type;
						$data['disabled'] = 'disabled';
					}
					$options = array (
					  'purchase' => my_caption('payment_type_purchase'),
					  'top-up' => my_caption('payment_type_topup'),
					  'subscription' => my_caption('payment_type_subscription')
					);
					$data += array(
					  'id' => 'item_type',
					  'class' => 'form-control selectpicker'
					);
					echo form_dropdown('item_type', $options, $item_type, $data);
					echo form_error('item_type', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-6">
				  <label><span class="text-danger">*</span> <?=my_caption('payment_item_name_label')?></label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('item_name') == '') ? $item_name = '' : $item_name = set_value('item_name');
					}
					else {
						(set_value('item_name') == '') ? $item_name = $rs->item_name : $item_name = set_value('item_name');
					}
				    
					$data = array(
					  'name' => 'item_name',
					  'id' => 'item_name',
					  'value' => $item_name,
					  'class' => 'form-control'
					);
					echo form_input($data);
					echo form_error('item_name', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_item_access_code_label')?></label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('access_code') == '') ? $access_code = '' : $access_code = set_value('access_code');
					}
					else {
						(set_value('access_code') == '') ? $access_code = $rs->access_code : $access_code = set_value('access_code');
					}
					$data = array(
					  'name' => 'access_code',
					  'id' => 'access_code',
					  'value' => $access_code,
					  'class' => 'form-control',
					  'placeholder' => my_caption('payment_item_access_code_placeholder')
					);
					echo form_input($data);
					echo form_error('access_code', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_currency')?></label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('item_currency') == '') ? $item_currency = $this->user_currency : $item_currency = set_value('item_currency');
					}
					else {
						(set_value('item_currency') == '') ? $item_currency = $rs->item_currency : $item_currency = set_value('item_currency');
					}
					$options = my_currency_list();
					$data = array(
					  'class' => 'form-control selectpicker'
					);
					echo form_dropdown('item_currency', $options, $item_currency, $data);
					echo form_error('item_currency', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			    <div class="col-lg-6">
				  <label><span class="text-danger">*</span> <?=my_caption('payment_item_price_label')?></label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('item_price') == '') ? $item_price = '0.00' : $item_price = set_value('item_price');
					}
					else {
						(set_value('item_price') == '') ? $item_price = $rs->item_price : $item_price = set_value('item_price');
					}
					$data = array(
					  'type' => 'number',
					  'step' => '0.01',
					  'name' => 'item_price',
					  'id' => 'item_price',
					  'value' => $item_price,
					  'class' => 'form-control'
					);
					echo form_input($data);
					echo form_error('item_price', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4" id="payment_item_row_recurring">
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_item_recurring_interval_count_label')?> (<?=my_caption('payment_item_recurring_notice')?>)</label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('recurring_interval_count') == '') ? $recurring_interval_count = '' : $recurring_interval_count = set_value('recurring_interval_count');
					}
					else {
						(set_value('recurring_interval_count') == '') ? $recurring_interval_count = $rs->recurring_interval_count : $recurring_interval_count = set_value('recurring_interval_count');
					}
					$data = array(
					  'type' => 'number',
					  'name' => 'recurring_interval_count',
					  'id' => 'recurring_interval_count',
					  'value' => $recurring_interval_count,
					  'class' => 'form-control'
					);
					echo form_input($data);
					echo form_error('recurring_interval_count', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_item_recurring_interval_label')?> (<?=my_caption('payment_item_recurring_notice')?>)</label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('recurring_interval') == '') ? $recurring_interval = 'day' : $recurring_interval = set_value('recurring_interval');
					}
					else {
						(set_value('recurring_interval') == '') ? $recurring_interval = $rs->recurring_interval : $recurring_interval = set_value('recurring_interval');
					}
					$options = array(
					  'day' => 'Day',
					  'week' => 'Week',
					  'month' => 'Month',
					  'year' => 'Year'
					);
					$data = array(
					  'class' => 'form-control selectpicker'
					);
					echo form_dropdown('recurring_interval', $options, $recurring_interval, $data);
					echo form_error('recurring_interval', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_item_purchase_times_limit')?></label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('purchase_times') == '') ? $purchase_times = '0' : $purchase_times = set_value('purchase_times');
					}
					else {
						(set_value('purchase_times') == '') ? $purchase_times = $rs->purchase_limit : $purchase_times = set_value('purchase_times');
					}
					$options = array(
					  '0' => my_caption('payment_item_no_limit'),
					  '1' => my_caption('payment_item_limit_times_one')
					);
					$data = array(
					  'id' => 'purchase_times',
					  'class' => 'form-control selectpicker'
					);
					echo form_dropdown('purchase_times', $options, $purchase_times, $data);
					echo form_error('purchase_times', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_item_access_condition')?> ( <?=my_caption('payment_item_access_condition_notice')?> )</label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('access_condition') == '') ? $access_condition = '0' : $access_condition = set_value('access_condition');
					}
					else {  //edit
						if (set_value('access_condition') == '') {
							$access_condition = explode(',', $rs->access_condition);
							foreach ($access_condition as $condition) {
								array_push($access_condition, $condition);
							}
						}
						else {
							$access_condition = set_value('access_condition');
						}
					}
					$options = array('0' => my_caption('payment_item_no_limit'));
					$options += my_get_all_payment_items();
					$data = array(
					  'class' => 'form-control selectpicker'
					);
					echo form_multiselect('access_condition[]', $options, $access_condition, $data);
					echo form_error('access_condition[]', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4" id="payment_item_row_actions">
			    <div class="col-lg-6">
				  <label><?=my_caption('payment_renew_title')?></label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('renew_action') == '') ? $renew_action = '0' : $renew_action = set_value('renew_action');
					}
					else {
						if (set_value('renew_action') == '') {
							if ($rs->type == 'purchase') {
								($rs->auto_renew == 0) ? $renew_action = 1 : $renew_action = 2;
							}
							else {
								($rs->auto_renew == 0) ? $renew_action = 4 : $renew_action = 3;
							}
						}
						else {
							$renew_action = set_value('renew_action');
						}
					}
					$options = array(
					  '1' => my_caption('payment_renew_1'),
					  '2' => my_caption('payment_renew_2'),
					  '3' => my_caption('payment_renew_3'),
					  '4' => my_caption('payment_renew_4')
					);
					$data = array(
					  'id' => 'renew_action',
					  'class' => 'form-control'
					);
					echo form_dropdown('renew_action', $options, $renew_action, $data);
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-12">
				  <label><span class="text-danger">*</span> <?=my_caption('payment_item_description_label')?></label>
				  <?php
				    if ($method == 'payment_item_add' || $method == 'payment_item_add_action') {
						(set_value('item_description') == '') ? $item_description = '' : $item_description = set_value('item_description');
					}
					else {
						(set_value('item_description') == '') ? $item_description = $rs->item_description : $item_description = set_value('item_description');
					}
					$data = array(
					  'name' => 'item_description',
					  'id' => 'item_description',
					  'value' => $item_description,
					  'class' => 'form-control',
					  'rows'=> 5
					);
					echo form_textarea($data);
					echo form_error('item_description', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row">
			    <div class="col-lg-6 offset-6 text-right">
				  <input type="button" class="btn btn-secondary mr-2" value="<?=my_caption('global_go_back')?>" onclick="javascript:window.history.back()">
				  <?php
				    $data = array(
					  'type' => 'submit',
					  'name' => 'btn_submit',
					  'id' => 'btn_submit',
					  'value' => $btn_text,
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
	  </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>