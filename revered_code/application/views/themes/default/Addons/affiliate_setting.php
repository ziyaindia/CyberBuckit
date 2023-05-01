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
		$affiliate_setting_array = json_decode($this->setting->affiliate_setting, TRUE);
	  ?>
	  <div class="row mb-4">
	    <div class="col-lg-12">
	      <div class="card shadow mb-4">
		    <div class="card-header py-3">
		      <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_setting')?></h6>
		    </div>
			<div class="card-body">
			  <?php echo form_open(base_url('affiliate/setting_action'), ['method'=>'POST']);?>
			  <div class="row form-group mt-2 mb-4">
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
			  <div class="row form-group mt-2 mb-4">
			    <div class="col-lg-12">
				  <label><?=my_caption('addons_affiliate_affiliate_guideline')?></label>
				  <?php
				    (set_value('affiliate_description') == '') ? $affiliate_description = $affiliate_setting_array['description'] : $affiliate_description = set_value('affiliate_description');
					$data = array(
					  'name' => 'affiliate_description',
					  'id' => 'affiliate_description',
					  'value' => my_reverse_from_html_for_input($affiliate_description),
					  'class' => 'form-control'
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
	  </div>
	  <div class="row">
	    <div class="col-lg-12">
		  <div class="card shadow mb-4">
		    <div class="card-header py-3">
			  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_log')?></h6>
			</div>
			<div class="card-body">
			  <div class="table-responsive">
			    <table id="dataTable_affiliate_log_admin" class="table table-bordered" width="100%">
				  <thead>
				    <tr>
					  <th width="15%"><?=my_caption('global_time')?></th>
					  <th width="25%"><?=my_caption('payment_item_name_label')?></th>
					  <th width="15%"><?=my_caption('addons_affiliate_from_ip')?></th>
					  <th width="10%"><?=my_caption('mp_currency_label')?></th>
					  <th width="10%"><?=my_caption('payment_item_price_label')?></th>
					  <th width="10%"><?=my_caption('addons_affiliate_commission')?></th>
					  <th width="15%"><?=my_caption('global_actions')?></th>
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