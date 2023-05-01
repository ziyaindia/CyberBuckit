<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<input type="hidden" name="captain_payment_pay_now" id="captain_payment_pay_now" value="<?=my_caption('payment_pay_now')?>">
<input type="hidden" name="captain_payment_list_get_invoice" id="captain_payment_list_get_invoice" value="<?=my_caption('payment_list_get_invoice')?>">
<input type="hidden" name="captain_global_no_action_required" id="captain_global_no_action_required" value="<?=my_caption('global_no_action_required')?>">
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('payment_list_payment_title')?></h1>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('payment_list_payment_title')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_payment" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="8%"><?=my_caption('global_status')?></th>
				  <th width="10%"><?=my_caption('global_type')?></th>
				  <th width="12%"><?=my_caption('payment_list_payment_gateway')?></th>
				  <th width="15%"><?=my_caption('global_time')?></th>
				  <th width="33%"><?=my_caption('payment_list_item')?></th>
				  <th width="10%"><?=my_caption('payment_amount')?></th>
				   <th width="12%"><?=my_caption('global_actions')?></th>
			    </tr>
			  </thead>
			</table>
          </div>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>