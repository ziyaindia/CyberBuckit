<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
$ids = my_uri_segment(3);
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('user_edit_title')?></h1>
  <div class="row">
    <div class="col-lg-9">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-5">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('user_edit_left_top_card_title')?></h6>
		</div>
		<div class="card-body">
		  <p><?=my_caption('user_edit_info_created_time')?><?=my_conversion_from_server_to_local_time($rs->created_time, $this->user_timezone, $this->user_dtformat)?></p>
		  <p><?=my_caption('user_edit_info_update_time')?><?=my_conversion_from_server_to_local_time($rs->update_time, $this->user_timezone, $this->user_dtformat)?></p>
		  <br>
		  <div class="row mb-3">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('user_edit_info_balance_title')?></span>
			  <p>
			    <?=$rs->balance?>
			  </p>
			</div>
		  </div>
		  <div class="row mb-3">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('user_edit_info_signin_detail')?></span>
			  <?php
			    if ($rs->login_success_detail != '') {
					$login_success_detail_array = json_decode($rs->login_success_detail, TRUE);
					$signin_time = $login_success_detail_array['time'];
					$signin_interface = $login_success_detail_array['interface'];
					$signin_ip = $login_success_detail_array['ip_address'];
					$signin_ua = $login_success_detail_array['user_agent'];
				}
				else {
					$signin_time = '';
					$signin_interface = '';
					$signin_ip = '';
					$signin_ua = '';
				}
			  ?>
			  <p class="mt-2 ml-3"><?php echo my_caption('user_edit_info_signin_time') . my_conversion_from_server_to_local_time($signin_time, $this->user_timezone, $this->user_dtformat)?></p>
			  <p class="mt-2 ml-3"><?php echo my_caption('user_edit_info_signin_detail_interface') . $signin_interface?></p>
			  <p class="mt-2 ml-3"><?php echo my_caption('user_edit_info_signin_detail_ip') . $signin_ip?></p>
			  <p class="ml-3"><?php echo my_caption('user_edit_info_signin_detail_ua') . $signin_ua?></p>
			</div>
		  </div>
		  <div class="row mb-3">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('user_edit_info_api_key_title')?></span>
			  <p>
			    <?php
				($this->config->item('my_demo_mode')) ? $api_key = '*****' . '' . substr($rs->api_key, 5) : $api_key = $rs->api_key;
				
				?>
			    <?=my_esc_html($api_key)?>
			  </p>
			</div>
		  </div>
		  <div class="row">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('user_edit_info_action_title')?></span>
			  <div class="row mt-4">
			    <div class="col-lg-4">
			      <a href="<?=base_url('admin/payment_adjust_balance/' . $ids)?>" class="btn btn-light text-gray-600 mr-3"><i class="fa fa-dollar-sign text-gray-500 mr-2"></i> <?=my_caption('user_sheet_adjust_balance')?></a>
			    </div>
			    <div class="col-lg-4">
				  <a href="<?=base_url('admin/payment_add_purchase/' . $ids)?>" class="btn btn-light text-gray-600 mr-3"><i class="fas fa-shopping-cart text-gray-500 mr-2"></i> <?=my_caption('user_sheet_add_purchase')?></a>
				</div>
				<div class="col-lg-4">
				  <a href="<?=base_url('admin/payment_add_subscription/' . $ids)?>" class="btn btn-light text-gray-600 mr-3"><i class="fab fa-buffer text-gray-500 mr-2"></i> <?=my_caption('user_sheet_add_subscription')?></a>
				</div>
			  </div>
			  <div class="row mt-4">
				<div class="col-lg-4">
				  <button type="button" class="btn btn-light text-gray-600 mr-3" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?=my_js_escape(my_caption('user_reset_api_key_confirm'))?>', '<?=base_url('admin/reset_api_key/' . $ids)?>', '')"><i class="fa fa-key fa-sm fa-fw mr-1"></i> <?=my_caption('user_sheet_reset_api_key')?></button>
				</div>
				<div class="col-lg-4">
				  <button type="button" class="btn btn-light text-gray-600 mr-3" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?=my_caption('user_impersonate_confirm')?>', '', '<?=base_url('admin/signin_as_user/' . $ids)?>')"><i class="fa fa-sign-out-alt fa-sm fa-fw mr-1"></i> <?=my_caption('user_sheet_signin')?></button>
				</div>
				<div class="col-lg-4">
				  <button class="btn btn-light text-gray-600 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-piggy-bank"></i> <?=my_caption('user_edit_info_show_payment')?></button>
				  <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
				    <a class="dropdown-item" target="_blank" href="<?=base_url('admin/payment_list?user=' . $ids)?>"><?=my_caption('user_edit_info_payment_all')?></a>
				    <a class="dropdown-item" target="_blank" href="<?=base_url('admin/payment_list/purchase?user=' . $ids)?>"><?=my_caption('user_edit_info_payment_purchase')?></a>
				    <a class="dropdown-item" target="_blank" href="<?=base_url('admin/payment_list/topup?user=' . $ids)?>"><?=my_caption('user_edit_info_payment_topup')?></a>
				    <a class="dropdown-item" target="_blank" href="<?=base_url('admin/payment_list/subscription?user=' . $ids)?>"><?=my_caption('user_edit_info_payment_subscription')?></a>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
    <div class="col-lg-4">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
	      <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('user_edit_right_top_card_title')?></h6>
	    </div>
		<?php echo form_open(base_url('admin/edit_user_setting_action/' . $ids) . '/', ['method'=>'POST']);?>
		<input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
	    <div class="card-body">
	      <div class="row form-group mb-4">
		    <div class="col-lg-12">
		      <label><span class="text-danger">*</span> <?=my_caption('user_edit_setting_status')?></label>
			  <?php
			    
			    (!empty(set_value('user_status'))) ? $user_status = set_value('user_status') : $user_status = $rs->status;
			    $options = array (
			      '0' => my_caption('user_sheet_label_0'),
				  '1' => my_caption('user_sheet_label_1'),
				  '2' => my_caption('user_sheet_label_2')
			    );
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('user_status', $options, $user_status, $data);
			    echo form_error('user_status', '<small class="text-danger">', '</small>');
			  ?>
		    </div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
		      <label><span class="text-danger">*</span> <?=my_caption('user_edit_setting_role')?></label>
			  <?php
			    (!empty(set_value('user_role'))) ? $user_role = set_value('user_role') : $user_role = $rs->role_ids;
			    $options = my_role_list();
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('user_role', $options, $user_role, $data);
			    echo form_error('user_role', '<small class="text-danger">', '</small>');
			  ?>
		    </div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
		      <label><span class="text-danger">*</span> <?=my_caption('user_edit_setting_password')?></label>
		      <?php
			    (!empty(set_value('new_password'))) ? $new_password = set_value('new_password') : $new_password = '';
			    $data = array(
			      'name' => 'new_password',
				  'id' => 'new_password',
				  'value' => $new_password,
				  'class' => 'form-control',
				  'placeholder' => my_caption('user_edit_setting_password_placeholder')
			    );
			    echo form_password($data);
			    echo form_error('new_password', '<small class="text-danger">', '</small>');
			  ?>
		    </div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
		      <label><span class="text-danger">*</span> <?=my_caption('user_edit_setting_password_confirm')?></label>
			  <?php
			    (!empty(set_value('confirm_new_password'))) ? $confirm_new_password = set_value('confirm_new_password') : $confirm_new_password = '';
			    $data = array(
			      'name' => 'confirm_new_password',
				  'id' => 'confirm_new_password',
				  'value' => $confirm_new_password,
				  'class' => 'form-control',
				  'placeholder' => my_caption('user_edit_setting_password_confirm_placeholder')
			    );
			    echo form_password($data);
			    echo form_error('confirm_new_password', '<small class="text-danger">', '</small>');
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
				  'value' => my_caption('global_save_changes'),
				  'class' => 'btn btn-primary mr-2'
			    );
				($rs->id == 1) ? $data['disabled'] = 'disabled' : null;
			    echo form_submit($data);
			  ?>
	        </div>
		  </div>
	    </div>
		<?php echo form_close(); ?>
	  </div>
	</div>
  </div>

  <div class="row">
    <div class="col-lg-9">
	  <?php
		$data['rs'] = $rs;
		$data['card_title'] = my_caption('user_edit_bottom_card_title');
		echo form_open(base_url('user/my_profile_impersonate_action/' . $ids . '/'), ['method'=>'POST']);
		  my_load_view($this->setting->theme, 'Generic/user_profile', $data);
		echo form_close();
	  ?>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>