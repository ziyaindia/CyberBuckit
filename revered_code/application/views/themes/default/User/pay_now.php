<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
  <div class="row">
    <div class="col-lg-12 text-left">
	  <h1 class="h3 text-gray-800"><?=my_caption('payment_pay_now')?></h1>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <section class="pricing py-4">
	    <div class="row">
		  <?php
		    foreach ($rs as $row) {  //only allow to purchase 1 time, and purchaed
				if ($row->purchase_limit != '0' && ((my_check_purchase_by_item($row->ids) && $row->type=='purchase') || (my_check_subscription_by_item($row->ids, TRUE) && $row->type=='subscription'))) {
					$pay_url_prefix = '';
					$show_stripe = FALSE;
					$show_paypal = FALSE;
					$show_gateway = FALSE;
					$show_free = FALSE;
					$show_purchased = TRUE;
				}
				elseif ($row->item_price == 0) {  //price is zero
					$pay_url_prefix = 'pay_free';
					$show_stripe = FALSE;
					$show_paypal = FALSE;
					$show_gateway = FALSE;
					$show_free = TRUE;
					$show_purchased = FALSE;
				}
				elseif ($row->type == 'subscription') {  //subscription
					$pay_url_prefix = 'pay_recurring';
					$show_stripe = $payment_gateway_stripe_recurring;
					$show_paypal = $payment_gateway_paypal_recurring;
					$show_gateway = $payment_gateway_recurring;
					$show_free =FALSE;
					$show_purchased = FALSE;
				}
				else {  //purchase and top-up
					$pay_url_prefix = 'pay_once';
					$show_stripe = $payment_gateway_stripe_one_time;
					$show_paypal = $payment_gateway_paypal_one_time;
					$show_gateway = $payment_gateway_one_time;
					$show_free = FALSE;
					$show_purchased = FALSE;
				}
		  ?>
		  <div class="col-lg-3">
		    <div class="card mb-5">
			  <div class="card-body">
			    <h5 class="card-title text-muted text-uppercase text-center"><?=my_esc_html($row->item_name)?></h5>
				<h6 class="card-price text-center"><?php echo '<small>' . $row->item_currency . '</small>';?><span id="pay_now_price_<?=$row->ids?>"><?=$row->item_price?></span></h6>
				<?php if ($payment_tax_rate) { echo '<p class="text-center mt-3">' . my_caption('global_tax') . ' ' . $payment_tax_rate . '%, ' . my_caption('global_exclusive') . '</p>'; } ?>
				<p class="text-center">
				  <?php
				    if ($row->type == 'subscription') {
						if (!$row->auto_renew) {
							echo my_caption('payment_expire_in') . $row->recurring_interval_count . ' ' . my_caption('payment_' . 'interval_' . $row->recurring_interval);
						}
						else {
							echo my_caption('payment_recurring_every') . $row->recurring_interval_count . ' ' . my_caption('payment_' . 'interval_' . $row->recurring_interval);
						}
					}
					elseif ($row->type == 'purchase') {
						echo my_caption('payment_one_time');
					}
					elseif ($row->type == 'top-up') {
						echo my_caption('payment_top_up');
					}
					?>
				</p>
			    <hr>
				<ul class="fa-ul mb-4">
				  <?php
					$description_array = explode("\n", $row->item_description);
					foreach ($description_array as $description) {
				  ?>
				    <li class="text-muted"><span class="fa-li"><i class="fas fa-check"></i></span><?=my_esc_html($description)?></li>
				  <?php 
				    }
				  ?>
				</ul>
				
				<?php
				  //start - This part works only when the coupon add-ons in installed - start
				  if (my_coupon_module() && !$show_purchased & !$show_free) {
				?>
				<div class="row mb-4">
				  <div class="col-lg-8 offset-1">
				  <?php
					$data = array(
					  'name' => 'pay_now_coupon_code_' . $row->ids,
					  'id' => 'pay_now_coupon_code_' . $row->ids,
					  'placeholder' => my_caption('addons_coupon_coupon_code'),
					  'value' => get_cookie('coupon_code', TRUE),
					  'class' => 'form-control'
					);
					echo form_input($data);
				  ?>
				  <small id="pay_now_coupon_alert_<?=$row->ids?>" class="text-danger"></small>
				  </div>
				  <div class="col-lg-3">
				    <i id="btn_pay_now_coupon_apply_<?=$row->ids?>" name="<?=$row->ids?>" class="far fa-hand-point-left fa-2x text-primary hand-cursor"></i>
				  </div>
				</div>
				<?php
				  }
				  else {
					  echo '<br>';
				  }
				  // end - This part works only when the coupon add-ons in installed - end
				?>
				
				<?php
				if ($show_stripe) {
				?>
				  <a id="pay_now_button_stripe_<?=$row->ids?>" href="<?=base_url('user/' . $pay_url_prefix . '/stripe/' . $row->ids)?>" name="<?=$row->ids?>" target="_blank" class="btn btn-block btn-primary mb-3"><?=my_caption('payment_pay_with_stripe')?></a>
				<?php
				}
				if ($show_paypal) {
				?>
				  <a id="pay_now_button_paypal_<?=$row->ids?>" href="<?=base_url('user/' . $pay_url_prefix . '/paypal/' . $row->ids)?>" name="<?=$row->ids?>" target="_blank" class="btn btn-block btn-primary mb-3"><?=my_caption('payment_pay_with_paypal')?></a>
				<?php
				}
				if ($show_gateway) {
				?>
				  <a id="pay_now_button_gateway_<?=$row->ids?>" href="<?=base_url('user/' . $pay_url_prefix . '/gateway/' . $row->ids)?>" name="<?=$row->ids?>" target="_blank" class="btn btn-block btn-primary mb-3"><?=$payment_gateway_name?></a>
				<?php
				}
				if ($show_free) {
				?>
				  <a href="<?=base_url('user/' . $pay_url_prefix . '/' . $row->ids)?>" name="<?=$row->ids?>" class="btn btn-block btn-primary mb-3"><?=my_caption('payment_pay_with_free')?></a>
				<?php
				}
				if ($show_purchased) {
				?>
				  <a href="javascript:void(0)" class="btn btn-block btn-danger mb-3"><?=my_caption('payment_pay_purchased')?></a>
				<?php
				}
				?>
			  </div>
			</div>
		  </div>
		  <?php
		    }
		  ?>
		</div>
	  </section>
	</div>
  </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>