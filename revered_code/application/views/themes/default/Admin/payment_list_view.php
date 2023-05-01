<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('payment_view_payment')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('payment_view_payment')?></h6>
        </div>
        <div class="card-body">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_user')?> :</span><br>
			  <a href="<?=base_url('/admin/edit_user/' . $rs->user_ids)?>"><?=my_esc_html($user_email)?></a>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_item_name_label')?> :</span><br>
			  <?php
			    if ($rs->item_ids != 0) {
					echo '<a href="' . base_url('admin/payment_item_modify/' . $rs->item_ids) . '">' . $rs->item_name . '</a>';
				}
				else {
					echo my_esc_html($rs->item_name);
				}
			  ?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_type_label')?> :</span><br>
			  <?=my_esc_html($rs->type)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_list_payment_gateway')?> :</span><br>
			  <?=my_esc_html($rs->gateway)?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('payment_gateway_identifier')?> :</span><br>
			  <?=my_esc_html($rs->gateway_identifier)?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('payment_gateway_event_id')?> :</span><br>
			  <?=my_esc_html($rs->gateway_event_id)?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_time')?> :</span><br>
			  <?=my_esc_html($rs->created_time)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_amount')?> :</span><br>
			  <?php
			    ($rs->tax) ? $tax = ($rs->price - $rs->coupon_discount) * $rs->tax/100 : $tax = 0;
			    echo my_esc_html($rs->currency . ' ' . my_proper_price($rs->amount)) . ' (' . my_caption('global_price') . ': ' . my_proper_price($rs->price) . ', ' . my_caption('global_discount') . ': -' . my_proper_price($rs->coupon_discount) . ', ' . my_caption('global_tax') . ': ' . my_proper_price($tax) . ')';
			  ?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_list_redirect_status')?> :</span><br>
			  <?php
			    switch ($rs->redirect_status) {
					case 'success' :
					  echo my_lable('success', $rs->redirect_status);
					  break;
					case 'pending' :
					  echo my_lable('warning', $rs->redirect_status);
					  break;
					case 'cancel' :
					  echo my_lable('light', $rs->redirect_status);
					  break;
				}
			  ?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_list_callback_status')?> :</span><br>
			  <?php
			    switch ($rs->callback_status) {
					case 'success' :
					  echo my_lable('success', $rs->callback_status);
					  break;
					case 'pending' :
					  echo my_lable('warning', $rs->callback_status);
					  break;
					case 'cancel' :
					  echo my_lable('light', $rs->callback_status);
					  break;
				}
			  ?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_adjust_balance_visible_for_user')?> :</span><br>
			  <?php
			    ($rs->visible_for_user == 1) ? $visible_for_user = 'Yes' : $visible_for_user = 'No';
				echo my_esc_html($visible_for_user);
			  ?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('payment_adjust_balance_create_invoice')?> :</span><br>
			  <?php
			    ($rs->generate_invoice == 1) ? $generate_invoice = 'Yes' : $generate_invoice = 'No';
				echo my_esc_html($generate_invoice);
			  ?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('payment_description')?> :</span><br>
			  <?=my_esc_html($rs->description)?>
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