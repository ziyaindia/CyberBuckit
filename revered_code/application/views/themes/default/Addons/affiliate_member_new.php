<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('addons_affiliate_member_new')?></h1>
	</div>
	<div class="col-lg-8 text-right">
	  <a href="<?=base_url('affiliate/setting')?>" class="btn btn-primary mr-1"><?=my_caption('addons_affiliate_title')?></a>
	  <a href="<?=base_url('affiliate/payout')?>" class="btn btn-primary mr-1"><?=my_caption('addons_affiliate_payout')?></a>
	  <a href="<?=base_url('affiliate/member')?>" class="btn btn-primary mr-1"><?=my_caption('addons_affiliate_member')?></a>
	  <a href="<?=base_url('affiliate/member_new')?>" class="btn btn-info"><?=my_caption('addons_affiliate_member_new')?></a>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_member_new')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('affiliate/member_new_action/'), ['method'=>'POST']);
			$affiliate_setting_array = json_decode($this->setting->affiliate_setting, TRUE);
			$referral_code = random_string('alnum', 8);
		  ?>
		  <input type="hidden" name="referral_code" id="referral_code" value="<?=my_esc_html($referral_code)?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_affiliate_member_email_address')?></label>
			  <?php
			    (!empty(set_value('email_address'))) ? $email_address = set_value('email_address') : $email_address = '';
			    $data = array(
				  'name' => 'email_address',
				  'id' => 'email_address',
				  'value' => $email_address,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('email_address', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('addons_affiliate_member_referral_url')?></label>
			  <?php
			    $referral_url = base_url() . 'redirect?id=' . $referral_code;
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
		  <div class="row form-group mb-5">
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
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <input type="button" class="btn btn-secondary mr-2" value="<?=my_caption('global_go_back')?>" onclick="javascript:window.history.back()">
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
		  <?php echo form_close(); ?>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>