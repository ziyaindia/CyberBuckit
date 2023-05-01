<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
  my_load_view($this->setting->theme, 'header');
  $current_method = my_uri_segment(3);
  switch ($current_method) {
	  case 'purchase' :
	    $list_type_caption = my_caption('payment_list_type_purchase');
		$list_type = 'purchase';
	    break;
	  case 'topup' :
	    $list_type_caption = my_caption('payment_list_type_topup');
		$list_type = 'top-up';
	    break;
	  case 'subscription' :
	    $list_type_caption = my_caption('payment_list_type_subscription');
		$list_type = 'subscription';
	    break;
	  default :
	    $list_type_caption = my_caption('payment_list_type_all');
		$list_type = 'all';
  }
  
?>
<input type="hidden" id="list_type" name="list_type" value="<?=my_esc_html($list_type)?>">
<input type="hidden" id="user" name="user" value="<?=my_get('user')?>">
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('payment_list_payment_title')?> ( <?=my_esc_html($list_type_caption)?> )</h1>
	</div>
    <div class="col-lg-8 text-right">
	  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=my_caption('global_toggle')?></button>
      <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
	    <a class="dropdown-item" href="<?=base_url('admin/payment_list')?>"><?=my_caption('payment_toggle_all_payment')?></a>
		<a class="dropdown-item" href="<?=base_url('admin/payment_list/purchase')?>"><?=my_caption('payment_toggle_purchase')?></a>
		<a class="dropdown-item" href="<?=base_url('admin/payment_list/topup')?>"><?=my_caption('payment_toggle_topup')?></a>
		<a class="dropdown-item" href="<?=base_url('admin/payment_list/subscription')?>"><?=my_caption('payment_toggle_subscription')?></a>
	  </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('payment_list_payment_title')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_payment_admin" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="10%"><?=my_caption('payment_list_redirect_status')?></th>
				  <th width="10%"><?=my_caption('payment_list_callback_status')?></th>
				  <th width="10%"><?=my_caption('payment_list_payment_gateway')?></th>
				  <th width="13%"><?=my_caption('global_time')?></th>
				  <th width="16%"><?=my_caption('payment_user')?></th>
				  <th width="27%"><?=my_caption('payment_list_item')?></th>
				  <th width="8%"><?=my_caption('payment_amount')?></th>
				   <th width="6%"><?=my_caption('global_actions')?></th>
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
