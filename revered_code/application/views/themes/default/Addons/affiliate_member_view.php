<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('addons_affiliate_member_view')?></h1>
	</div>
	<div class="col-lg-8 text-right">
	  <a href="<?=base_url('affiliate/setting')?>" class="btn btn-primary mr-1"><?=my_caption('addons_affiliate_title')?></a>
	  <a href="<?=base_url('affiliate/payout')?>" class="btn btn-primary mr-1"><?=my_caption('addons_affiliate_payout')?></a>
	  <a href="<?=base_url('affiliate/member')?>" class="btn btn-info mr-1"><?=my_caption('addons_affiliate_member')?></a>
	  <a href="<?=base_url('affiliate/member_new')?>" class="btn btn-primary"><?=my_caption('addons_affiliate_member_new')?></a>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_member_view')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('affiliate/member_view_action/'), ['method'=>'POST']);
			$affiliate_setting_array = json_decode($rs->affiliate_setting, TRUE);
		  ?>
		  <input type="hidden" name="ids" id="ids" value="<?=my_esc_html($rs->ids)?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('affiliate_enabled') == '') ? $checked = $rs->affiliate_enabled : $checked = set_value('affiliate_enabled');
				  $data = array(
				    'name' => 'affiliate_enabled',
					'id' => 'affiliate_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="affiliate_enabled"><?php echo my_caption('global_enabled');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('addons_affiliate_member_email_address')?></label>
			  <?php
			    $data = array(
				  'name' => 'email_address',
				  'id' => 'email_address',
				  'value' => $rs->email_address,
				  'class' => 'form-control',
				  'readonly' => 'readonly'
				);
				echo form_input($data);
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('addons_affiliate_member_referral_url')?></label>
			  <?php
			    $referral_url = base_url() . 'redirect?id=' . $rs->affiliate_code;
			    $data = array(
				  'name' => 'referral_url',
				  'id' => 'referral_url',
				  'value' => $referral_url,
				  'class' => 'form-control',
				  'readonly' => 'readonly'
				);
				echo form_input($data);
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_affiliate_affiliate_policy')?></label>
			  <?php
			    (set_value('commission_policy') == '') ? $commission_policy = $affiliate_setting_array['commission_policy'] : $commission_policy  = set_value('commission_policy');
				$options = array(
				  'A' => my_caption('addons_affiliate_policy_once'),
				  'B' => my_caption('addons_affiliate_policy_everytime')
				);
				$data = array(
				  'class' => 'form-control selectpicker'
				);
				echo form_dropdown('commission_policy', $options, $commission_policy, $data);
				echo form_error('commission_policy', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_affiliate_basic_commission_rate')?></label>
			  <?php
			    (set_value('commission_rate') == '') ? $commission_rate = $affiliate_setting_array['commission_rate'] : $commission_rate = set_value('commission_rate');
				$data = array(
				  'name' => 'commission_rate',
				  'id' => 'commission_rate',
				  'value' => $commission_rate,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('commission_rate', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-6">
			  <label><b><?=my_caption('addons_affiliate_commission')?></b></label>
			  <br>
			  <?=my_esc_html($rs->affiliate_earning)?>
			</div>
			<div class="col-lg-6">
			  <label><b><?=my_caption('addons_affiliate_payout_bank_account')?></b></label>
			  <br>
			  <?php
			  if ($affiliate_setting_array['payout_information'] == '') {
				  echo my_caption('global_no_entries_found');
			  }
			  else {
				  echo my_reverse_from_input_for_html($affiliate_setting_array['payout_information'], TRUE);
			  }
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 text-left">
			  <?php
			    $data = array(
				  'type' => 'button',
				  'name' => 'affiliate_payout_btn',
				  'id' => 'affiliate_payout_btn',
				  'id' => 'btn_payout',
				  'value' => my_caption('addons_affiliate_payout_all_commission'),
				  'class' => 'btn btn-danger',
				  'onclick' => 'actionQuery(\'' . my_caption('addons_affiliate_commission_payout_query') . '\', \'' . my_caption('global_not_revert') . '\', \'\', \'' . base_url('affiliate/payout_all/' . $rs->ids) . '\')'
			    );
			    echo form_submit($data);
			  ?>
			</div>
			<div class="col-lg-6 text-right">
			  <input type="button" class="btn btn-secondary mr-2" value="<?=my_caption('global_go_back')?>" onclick="javascript:window.history.back()">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_submit_block',
				  'id' => 'btn_submit_block',
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