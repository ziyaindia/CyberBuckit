<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('addons_affiliate_member')?></h1>
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
	  <div class="row">
	    <div class="col-lg-12">
		  <div class="card shadow mb-4">
		    <div class="card-header py-3">
			  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_affiliate_member')?></h6>
			</div>
			<div class="card-body">
			  <div class="table-responsive">
			    <table id="dataTable_affiliate_member" class="table table-bordered" width="100%">
				  <thead>
				    <tr>
					  <th width="30%"><?=my_caption('global_email_address')?></th>
					  <th width="25%"><?=my_caption('addons_affiliate_member_referral_code')?></th>
					  <th width="25%"><?=my_caption('addons_affiliate_member_current_earning')?></th>
					  <th width="20%"><?=my_caption('global_actions')?></th>
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