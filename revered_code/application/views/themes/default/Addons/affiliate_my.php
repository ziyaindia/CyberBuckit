<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('addons_affiliate_user')?></h1>
	</div>
	<div class="col-lg-8 text-right">
	  <a href="<?=base_url('affiliate/my_affiliate')?>" class="btn btn-info mr-1"><?=my_caption('addons_affiliate_user')?></a>
	  <a href="<?=base_url('affiliate/my_payout')?>" class="btn btn-primary mr-1"><?=my_caption('addons_affiliate_payout_my')?></a>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
		$affiliate_setting_array = json_decode(my_user_setting($_SESSION['user_ids'], 'affiliate_setting'), TRUE);
		$affiliate_global_setting_array = json_decode($this->setting->affiliate_setting, TRUE);
	  ?>
	  <div class="row mb-4">
	    <div class="col-lg-4">
	      <div class="card shadow mb-4">
		    <div class="card-header py-3">
		      <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_setting')?></h6>
		    </div>
			<div class="card-body">
			  <?php echo form_open(base_url('affiliate/my_affiliate_action'), ['method'=>'POST']);?>
			  <div class="row form-group mt-2 mb-4">
			    <div class="col-lg-12">
				  <label><b><?=my_caption('addons_affiliate_my_referral_url')?></b></label>
				  <br>
				  <input type="text" class="form-control" value="<?=base_url('redirect?id=' . my_user_setting($_SESSION['user_ids'], 'affiliate_code'))?>" readonly>
				</div>
			  </div>
			  <div class="row form-group mt-2 mb-4">
			    <div class="col-lg-12">
				  <label><b><?=my_caption('addons_affiliate_my_earning')?></b></label>
				  <br>
				  <?php
				    $affEarning = my_user_setting($_SESSION['user_ids'], 'affiliate_earning');
				    if ($affEarning == '{}') {
						$earning = 0;
					}
					else {
						$affEarningArr = json_decode($affEarning ,TRUE);
						$affEarningCurrency = array_keys($affEarningArr);
						$affEarningTotal = array_values($affEarningArr);
						$affEarningTotal = number_format($affEarningTotal[0], 2);
						$earning = strtoupper($affEarningCurrency[0]) . ': ' . $affEarningTotal;
					}
				  ?>
				  <?=$earning?>
				</div>
			  </div>
			  <div class="row form-group mt-2 mb-4">
			    <div class="col-lg-6">
				  <label><b><?=my_caption('addons_affiliate_affiliate_policy')?></b></label>
				  <br>
				  <?=str_replace('A', my_caption('addons_affiliate_policy_once'), str_replace('B', my_caption('addons_affiliate_policy_everytime'), $affiliate_setting_array['commission_policy']))?>
				</div>
			    <div class="col-lg-6">
				  <label><b><?=my_caption('addons_affiliate_basic_commission_rate')?></b></label>
				  <br>
				  <?=my_esc_html($affiliate_setting_array['commission_rate'])?>%
				</div>
			  </div>
			  <div class="row form-group mt-2 mb-4">
			    <div class="col-lg-12">
				  <label><b><?=my_caption('addons_affiliate_payout_your_bank_account')?></b></label>
				  <?php
					$data = array(
					  'name' => 'bank_account',
					  'id' => 'bank_account',
					  'value' => my_reverse_from_html_for_input($affiliate_setting_array['payout_information']),
					  'class' => 'form-control',
					  'rows' => 6
					);
					echo form_textarea($data);
				  ?>
				</div>
			  </div>
			  <div class="row">
			    <div class="col-lg-12 text-right">
				<?php
				  $data = array(
				    'type' => 'submit',
					'name' => 'btn_change',
					'id' => 'btn_change',
					'value' => my_caption('global_save_changes'),
					'class' => 'btn btn-primary'
				  );
				  echo form_submit($data);
				?>
				</div>
			  </div>
			  <?php echo form_close(); ?>
			</div>
		  </div>
		</div>
	    <div class="col-lg-8">
	      <div class="card shadow mb-4">
		    <div class="card-header py-3">
		      <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_guideline')?></h6>
		    </div>
			<div class="card-body">
			  <?php
			    if ($affiliate_global_setting_array['description'] != '') {
					echo my_reverse_from_input_for_html($affiliate_global_setting_array['description'], TRUE);
				}
				else {
					echo my_caption('global_no_entries_found');
				}
			  ?>
			</div>
		  </div>
	    </div>
	  </div>
	  <div class="row">
	    <div class="col-lg-12">
		  <div class="card shadow mb-4">
		    <div class="card-header py-3">
			  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_log')?></h6>
			</div>
			<div class="card-body">
			  <div class="table-responsive">
			    <table id="dataTable_affiliate_log" class="table table-bordered" width="100%">
				  <thead>
				    <tr>
					  <th width="15%"><?=my_caption('global_time')?></th>
					  <th width="25%"><?=my_caption('payment_item_name_label')?></th>
					  <th width="15%"><?=my_caption('addons_affiliate_from_ip')?></th>
					  <th width="15%"><?=my_caption('mp_currency_label')?></th>
					  <th width="15%"><?=my_caption('payment_item_price_label')?></th>
					  <th width="15%"><?=my_caption('addons_affiliate_commission')?></th>
					</tr>
				  </thead>
				</table>
		      </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>