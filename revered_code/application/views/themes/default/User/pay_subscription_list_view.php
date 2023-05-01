<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('payment_subscription_view')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('payment_subscription_view')?></h6>
        </div>
        <div class="card-body">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_item_name_label')?> :</span><br>
			  <?=my_esc_html($rs_item->item_name)?>
			</div>
			<div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_item_price_label')?> :</span><br>
			  <?php echo my_esc_html($rs_item->item_currency . ' ' . my_proper_price($rs_item->item_price) . ' per ' . $rs_item->recurring_interval_count . ' ' . $rs_item->recurring_interval . '(s)'); ?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_list_payment_gateway')?> :</span><br>
			  <?=my_esc_html($rs->payment_gateway)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_status')?> :</span><br>
			  <?php
			    switch ($rs->status) {
					case 'active' :
					  echo '<span class="badge badge-success">Active</span>';
					  break;
					case 'pending_cancellation' :
					  echo '<span class="badge badge-warning">Pending Cancellation</span>';
					  break;
					case 'expired' :
					  echo '<span class="badge badge-danger">Expired</span>';
					  break;
				}
			  ?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_subscription_created_time')?> :</span><br>
			  <?=my_conversion_from_server_to_local_time($rs->created_time, $this->user_timezone, $this->user_dtformat)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_subscription_updated_time')?> :</span><br>
			  <?=my_conversion_from_server_to_local_time($rs->updated_time, $this->user_timezone, $this->user_dtformat)?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_subscription_start_time')?> :</span><br>
			  <?=my_conversion_from_server_to_local_time($rs->start_time, $this->user_timezone, $this->user_dtformat)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_subscription_end_time')?> :</span><br>
			  <?=my_conversion_from_server_to_local_time($rs->end_time, $this->user_timezone, $this->user_dtformat)?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('payment_subscription_recurring_notice')?> :</span><br>
			  <?=my_caption('payment_subscription_recurring_notice_body')?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <button type="button" class="btn btn-primary" onclick="window.history.back()"><?=my_caption('global_go_back')?></button>
			</div>
		  </div>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>