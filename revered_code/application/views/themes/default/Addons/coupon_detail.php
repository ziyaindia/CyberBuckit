<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$current_method = $this->router->fetch_method();
if ($current_method == 'add') { //new add
    $action_url = 'coupon/add_action/';
	$ids = '';
	$enabled = 1;
	$title = my_caption('addons_coupon_add');
	$addons_coupon_name = '';
	$addons_coupon_code = '';
	$addons_coupon_discount_type = '';
	$addons_coupon_discount_amount = '';
	$addons_coupon_applicable_scope = '0';
	$addons_coupon_times_limit = '';
	$addons_coupon_valid_from = '';
	$addons_coupon_valid_till = '';
	$addons_coupon_description = '';
	$btn_text = my_caption('addons_coupon_add');
}
elseif ($current_method == 'edit') {  //simple edit
    $action_url = 'coupon/edit_action/';
	$ids = $rs->ids;
	$enabled = $rs->enabled;
	$title = my_caption('addons_coupon_edit');
	$addons_coupon_name = $rs->name;
	$addons_coupon_code = $rs->code;
	$addons_coupon_discount_type = $rs->discount_type;
	$addons_coupon_discount_amount = $rs->discount_amount;
	$addons_coupon_applicable_scope = $rs->applicable_scope;
	$addons_coupon_times_limit = $rs->use_times_limit;
	$addons_coupon_valid_from = date('Y-m-d', strtotime($rs->valid_from));
	$addons_coupon_valid_till = date('Y-m-d', strtotime($rs->valid_till));
	$addons_coupon_description = $rs->description;
	$btn_text = my_caption('global_save_changes');
}
else {  //action
	if ($current_method == 'add_action') {
		$title = my_caption('addons_coupon_add');
		$action_url = 'coupon/add_action/';
		$btn_text = my_caption('addons_coupon_add');
	}
	else {
		$title = my_caption('addons_coupon_edit');
		$action_url = 'coupon/edit_action/';
		$btn_text = my_caption('global_save_changes');
	}
	$ids = set_value('addons_coupon_ids');
	$enabled = set_value('enabled');
	$addons_coupon_name = set_value('addons_coupon_name');
	$addons_coupon_code = set_value('addons_coupon_code');
	$addons_coupon_discount_type = set_value('addons_coupon_discount_type');
	$addons_coupon_discount_amount = set_value('addons_coupon_discount_amount');
	$addons_coupon_applicable_scope = set_value('addons_coupon_applicable_scope');
	$addons_coupon_times_limit = set_value('addons_coupon_times_limit');
	$addons_coupon_valid_from = set_value('addons_coupon_valid_from');
	$addons_coupon_valid_till = set_value('addons_coupon_valid_till');
	$addons_coupon_description = set_value('addons_coupon_description');
}
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_esc_html($title)?></h1>
  <div class="row">
    <div class="col-lg-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($title)?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url($action_url), ['method'=>'POST']);
		  ?>
		  <input type="hidden" name="addons_coupon_ids" id="addons_coupon_ids" value="<?=my_esc_html($ids)?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('global_enabled')?></label>
			  <div class="custom-control custom-checkbox">
			  <?php
				 $data = array(
				   'name' => 'enabled',
				   'id' => 'enabled',
				   'value' => '1',
				   'checked' => $enabled,
				   'class' => 'custom-control-input'
				);
				echo form_checkbox($data);
			  ?>
			  <label class="custom-control-label" for="enabled"><?=my_caption('global_enabled')?></label>
			 </div>
			</div>
			<?php if ($current_method == 'edit' || $current_method == 'edit_action') { ?>
		    <div class="col-lg-6">
			  <label><?=my_caption('addons_coupon_url')?></label>
			  <?php
			    $data = array(
				  'name' => 'addons_coupon_url',
				  'id' => 'addons_coupon_url',
				  'value' => base_url('redirect?code=' . $addons_coupon_code),
				  'class' => 'form-control',
				  'readonly' => 'readonly'
				);
				echo form_input($data);
			  ?>
			</div>
			<?php } ?>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_coupon_name')?></label>
			  <?php
			    $data = array(
				  'name' => 'addons_coupon_name',
				  'id' => 'addons_coupon_name',
				  'value' => $addons_coupon_name,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('addons_coupon_name', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_coupon_code')?>
			    <?php if ($current_method == 'add' || $current_method == 'add_action') { ?>
			      <span id="addons_coupon_generate_code" class="hand-cursor ml-2"><u><?=my_caption('addons_coupon_generate_code')?></u></span>
				<?php } ?>
			  </label>
			  <?php
			    $data = array(
				  'name' => 'addons_coupon_code',
				  'id' => 'addons_coupon_code',
				  'value' => $addons_coupon_code,
				  'class' => 'form-control'
				);
				if ($current_method == 'edit' || $current_method == 'edit_action') {
					$data['readonly'] = 'readonly';
				}
				echo form_input($data);
				echo form_error('addons_coupon_code', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_coupon_discount_type')?></label>
			  <?php
			    $options = array(
				  'A'=>my_caption('addons_coupon_discount_type_a'),
				  'B'=>my_caption('addons_coupon_discount_type_b')
				);
				$data = array(
				  'class' => 'form-control selectpicker'
				);
				echo form_dropdown('addons_coupon_discount_type', $options, $addons_coupon_discount_type, $data);
			 ?>
			</div>
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_coupon_discount_amount')?></label>
			  <?php
			    $data = array(
				  'name' => 'addons_coupon_discount_amount',
				  'id' => 'addons_coupon_discount_amount',
				  'value' => $addons_coupon_discount_amount,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('addons_coupon_discount_amount', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('addons_coupon_applicable_scope')?></label>
			  <?php
			    $options = array('0' => my_caption('addons_coupon_applicable_scope_all'));
			    $options += my_get_all_payment_items();
				if ($current_method == 'edit') {
					$scope_array = array();
					$addons_coupon_applicable_scope_array = explode(',', $addons_coupon_applicable_scope);
					foreach ($addons_coupon_applicable_scope_array as $scope) {
						array_push($scope_array, $scope);
					}
					$addons_coupon_applicable_scope = $scope_array;
				}
				$data = array(
				  'class' => 'form-control selectpicker'
				);
				echo form_multiselect('addons_coupon_applicable_scope[]', $options, $addons_coupon_applicable_scope, $data);
			 ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('addons_coupon_times_limit')?></label>
			  <?php
			    $data = array(
				  'name' => 'addons_coupon_times_limit',
				  'id' => 'addons_coupon_times_limit',
				  'value' => $addons_coupon_times_limit,
				  'class' => 'form-control',
				  'placeholder' => my_caption('addons_coupon_unlimited_placeholder')
				);
				echo form_input($data);
				echo form_error('addons_coupon_times_limit', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('addons_coupon_valid_from')?></label>
			  <?php
			    $data = array(
				  'name' => 'addons_coupon_valid_from',
				  'id' => 'addons_coupon_valid_from',
				  'value' => $addons_coupon_valid_from,
				  'class' => 'form-control',
				  'autocomplete' => 'off',
				  'placeholder' => my_caption('addons_coupon_unlimited_placeholder')
				);
				echo form_input($data);
				echo form_error('addons_coupon_valid_from', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('addons_coupon_valid_till')?></label>
			  <?php
			    $data = array(
				  'name' => 'addons_coupon_valid_till',
				  'id' => 'addons_coupon_valid_till',
				  'value' => $addons_coupon_valid_till,
				  'class' => 'form-control',
				  'autocomplete' => 'off',
				  'placeholder' => my_caption('addons_coupon_unlimited_placeholder')
				);
				echo form_input($data);
				echo form_error('addons_coupon_valid_till', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-12">
			  <label><?=my_caption('addons_coupon_description')?></label>
			  <?php
			    $data = array(
				  'name' => 'addons_coupon_description',
				  'id' => 'addons_coupon_description',
				  'value' => $addons_coupon_description,
				  'class' => 'form-control'
				);
				echo form_textarea($data);
				echo form_error('addons_coupon_description', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <input type="button" class="btn btn-secondary mr-2" value="<?=my_caption('global_go_back')?>" onclick="javascript:window.history.back()">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_submit_block',
				  'id' => 'btn_submit_block',
				  'value' => $btn_text,
				  'class' => 'btn btn-primary mr-2'
			    );
			    echo form_submit($data);
			  ?>
			</div>
		  </div>
		  <?php echo form_close(); ?>
		</div>
      </div>
	  <?php
	    if ($current_method == 'edit' || $current_method == 'edit_action') {
	  ?>
	  <input type="hidden" id="coupon_code" name="coupon_code" value="<?=my_esc_html($addons_coupon_code)?>">
	  <div class="row mt-5" id="coupon_log">
	    <div class="col-lg-12">
		  <div class="card shadow mb-4">
		    <div class="card-header py-3">
			  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_coupon_usage_log')?></h6>
			</div>
			<div class="card-body">
			  <div class="table-responsive">
			    <table id="dataTable_coupon_log_admin" class="table table-bordered" width="100%">
				  <thead>
				    <tr>
					  <th width="20%"><?=my_caption('global_time')?></th>
					  <th width="30%"><?=my_caption('payment_item_name_label')?></th>
					  <th width="10%"><?=my_caption('mp_currency_label')?></th>
					  <th width="10%"><?=my_caption('payment_item_price_label')?></th>
					  <th width="10%"><?=my_caption('addons_coupon_discount')?></th>
					  <th width="10%"><?=my_caption('payment_amount')?></th>
					  <th width="10%"><?=my_caption('global_actions')?></th>
					</tr>
				  </thead>
				</table>
		      </div>
			</div>
		  </div>
		</div>
	  </div>
	  <?php
	    }
	  ?>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>