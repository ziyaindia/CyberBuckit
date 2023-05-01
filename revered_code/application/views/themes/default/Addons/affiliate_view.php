<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('addons_affiliate_title')?></h1>
	</div>
	<div class="col-lg-8 text-right">
	  <a href="<?=base_url('affiliate/setting')?>" class="btn btn-info mr-1"><?=my_caption('addons_affiliate_title')?></a>
	  <a href="<?=base_url('affiliate/payout')?>" class="btn btn-primary mr-1"><?=my_caption('addons_affiliate_payout')?></a>
	  <a href="<?=base_url('affiliate/member')?>" class="btn btn-primary mr-1"><?=my_caption('addons_affiliate_member')?></a>
	  <a href="<?=base_url('affiliate/member_new')?>" class="btn btn-primary"><?=my_caption('addons_affiliate_member_new')?></a>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
		$currency = $rs->currency;
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_view')?></h6>
        </div>
        <div class="card-body">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_time')?> :</span><br>
			  <?=my_conversion_from_server_to_local_time($rs->created_time, $this->user_timezone, $this->user_dtformat)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('addons_affiliate_from_ip')?> :</span><br>
			  <?=my_esc_html($rs->from_ip)?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_user')?> :</span><br>
			  <a href="<?=base_url('admin/edit_user/' . $rs->user_ids)?>" target="_blank"><?=my_user_setting($rs->user_ids, 'email_address')?></a>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('addons_affiliate_referred_user')?> :</span><br>
			  <a href="<?=base_url('admin/edit_user/' . $rs->user_ids_referred)?>" target="_blank"><?=my_user_setting($rs->user_ids_referred, 'email_address')?></a>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_item_name_label')?> :</span><br>
			  <?=my_esc_html($rs->item_name)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('addons_affiliate_payment_log')?> :</span><br>
			  <a href="<?=base_url('admin/payment_list_view/' . $rs->payment_ids)?>" target="_blank"><?=my_caption('addons_affiliate_payment_view_log')?></a>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_item_price_label')?> :</span><br>
			  <?php echo my_esc_html($currency) . ' ' . my_esc_html($rs->amount);?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('addons_affiliate_commission')?> :</span><br>
			  <?php echo my_esc_html($currency) . ' ' .  my_esc_html($rs->commission);?>
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
				  'value' => my_caption('addons_affiliate_btn_reverse_commission'),
				  'class' => 'btn btn-danger',
				  'onclick' => 'actionQuery(\'' . my_caption('addons_affiliate_reverse_commission_query') . '\', \'' . my_caption('global_not_revert') . '\', \'\', \'' . base_url('affiliate/affiliate_reverse/' . $rs->ids) . '\')'
			    );
			    echo form_submit($data);
			  ?>
			</div>
			<div class="col-lg-6 text-right">
			  <button type="button" class="btn btn-primary" onclick="window.history.back()"><?=my_caption('global_go_back')?></button>
			</div>
		  </div>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>