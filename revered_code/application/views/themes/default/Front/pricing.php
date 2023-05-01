<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
      <section class="section-header pb-8 pb-lg-13 mb-4 mb-lg-6 bg-primary text-white">
	    <div class="container">
          <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center">
              <h1 class="display-2 mb-3"><?=my_caption('front_pricing_title')?></h1>
			  <?php (!empty($this->session->flashdata('kyc_redirect_notice'))) ? $slogan = my_caption('signup_payment_redirect_notice') : $slogan = my_caption('front_pricing_slogan');?>
			  <p class="lead"><?=$slogan?></p>
            </div>
          </div>
        </div>
        <div class="pattern bottom"></div>
      </section>
      
	  <section class="section section-lg pt-0">
	    <div class="container mt-n7 mt-lg-n13 z-2">
		  <div class="row justify-content-center">
		    <?php
			  if (!empty($rs)) {
				  foreach ($rs as $pricing) {
					  if ($pricing->purchase_limit != '0' && ((my_check_purchase_by_item($pricing->ids) && $pricing->type=='purchase') || (my_check_subscription_by_item($pricing->ids, TRUE) && $pricing->type=='subscription'))) {
						  $pay_url_prefix = '';
						  $show_stripe = FALSE;
						  $show_paypal = FALSE;
						  $show_gateway = FALSE;
						  $show_signup = FALSE;
						  $show_free = FALSE;
						  $show_purchased = TRUE;
					  }
					  elseif ($pricing->item_price == 0) {
						  $pay_url_prefix = '';
						  $show_stripe = FALSE;
						  $show_paypal = FALSE;
						  $show_gateway = FALSE;
						  $show_signup = TRUE;
						  $show_free = TRUE;
						  $show_purchased = FALSE;
					  }
					  elseif ($pricing->type == 'subscription') {
						  $pay_url_prefix = 'pay_recurring';
						  $show_stripe = $payment_gateway_stripe_recurring;
						  $show_paypal = $payment_gateway_paypal_recurring;
						  $show_gateway = $payment_gateway_recurring;
						  $show_signup = FALSE;
						  $show_free = FALSE;
						  $show_purchased = FALSE;
					  }
					  else {
						  $pay_url_prefix = 'pay_once';
						  $show_stripe = $payment_gateway_stripe_one_time;
						  $show_paypal = $payment_gateway_paypal_one_time;
						  $show_gateway = $payment_gateway_one_time;
						  $show_signup = FALSE;
						  $show_free = FALSE;
						  $show_purchased = FALSE;
					  }
					  
			?>
		    <div class="col-12 col-lg-4 mb-5">
			  <div class="card shadow-soft bg-white border-light animate-up-3 text-gray py-4 mb-5 mb-lg-0">
			    <div class="card-header text-center pb-0">
				  <div class="icon icon-shape icon-shape-primary rounded-circle mb-3">
				    <i class="fas fa-receipt"></i>
                  </div>
                  <h4 class="text-black"><?=my_esc_html($pricing->item_name)?></h4>
                  <p>
				    <?=my_esc_html($pricing->item_currency)?> <span id="pay_now_price_<?=$pricing->ids?>"><?=my_esc_html($pricing->item_price)?></span>
					<br>
					<?php if ($payment_tax_rate) { echo '<p class="text-center">' . my_caption('global_tax') . ' ' . $payment_tax_rate . '%, ' . my_caption('global_exclusive') . '</p>'; } ?>
					<?php
					if ($pricing->type == 'subscription') {
						if (!$pricing->auto_renew) {
							echo my_caption('payment_expire_in') . $pricing->recurring_interval_count . ' ' . my_caption('payment_' . 'interval_' . $pricing->recurring_interval);
						}
						else {
							echo my_caption('payment_recurring_every') . $pricing->recurring_interval_count . ' ' . my_caption('payment_' . 'interval_' . $pricing->recurring_interval);
						}
					}
					elseif ($pricing->type == 'purchase') {
						echo my_caption('payment_one_time');
					}
					elseif ($pricing->type == 'top-up') {
						echo my_caption('payment_top_up');
					}
					?>
				  </p>
                </div>
                <div class="card-body">
                  <ul class="list-group">
					<?php
					  $description_array = explode("\n", $pricing->item_description);
					  foreach ($description_array as $description) {
					?>
                    <li class="list-group-item d-flex px-0 pt-0 pb-2">
                      <div class="icon icon-sm icon-success mr-4">
                        <i class="far fa-check-circle"></i>
                      </div>
					  <div><?=my_esc_html($description)?></div>
                    </li>
				    <?php } ?>
				  </ul>
				  
				  <?php
				    //start - This part works only when the coupon add-ons in installed - start
				    if (my_coupon_module() && !$show_purchased & !$show_free) {
				  ?>
				  <div class="row mb-4">
				    <div class="col-lg-10 col-md-8 col-sm-6 mt-2">
					  <?php
					    $data = array(
					      'name' => 'pay_now_coupon_code_' . $pricing->ids,
					      'id' => 'pay_now_coupon_code_' . $pricing->ids,
					      'placeholder' => my_caption('addons_coupon_coupon_code'),
					      'value' => get_cookie('coupon_code', TRUE),
					      'class' => 'form-control'
					    );
					    echo form_input($data);
				      ?>
				      <small id="pay_now_coupon_alert_<?=$pricing->ids?>" class="text-danger"></small>
				    </div>
				    <div class="col-lg-2 col-md-4 col-sm-6 mt-2">
				      <i id="btn_pay_now_coupon_apply_<?=$pricing->ids?>" name="<?=$pricing->ids?>" class="far fa-hand-point-left fa-2x text-primary hand-cursor"></i>
				    </div>
				  </div>
				  <?php
				    }
				    else {
						echo '<br>';
				    }
				    // end - This part works only when the coupon add-ons in installed - end
				  ?>

				  <hr>
				  <div class="text-center">
				  <?php if ($show_stripe) {?>
				    <a id="pay_now_button_stripe_<?=$pricing->ids?>" name="<?=$pricing->ids?>" href="<?=base_url('user/' . $pay_url_prefix . '/stripe/' . $pricing->ids)?>" target="_blank" class="btn btn-primary mt-2">
				      <i class="fas fa-external-link-alt mr-2"></i>
					  <?=my_caption('front_pricing_purchase_stripe')?>
				    </a>
				  <?php 
				    }
				    if ($show_paypal) {
				  ?>
				    <a id="pay_now_button_paypal_<?=$pricing->ids?>" name="<?=$pricing->ids?>" href="<?=base_url('user/' . $pay_url_prefix . '/paypal/' . $pricing->ids)?>" target="_blank" class="btn btn-primary mt-2">
				      <i class="fas fa-external-link-alt mr-2"></i>
					  <?=my_caption('front_pricing_purchase_paypal')?>
				    </a>
				  <?php 
				    }
				    if ($show_gateway) {
				  ?>
				    <a id="pay_now_button_gateway_<?=$pricing->ids?>" name="<?=$pricing->ids?>" href="<?=base_url('user/' . $pay_url_prefix . '/gateway/' . $pricing->ids)?>" target="_blank" class="btn btn-primary mt-2">
				      <i class="fas fa-external-link-alt mr-2"></i>
					  <?=$payment_gateway_name?>
				    </a>
				  <?php
				    }
					if ($show_signup) {
				  ?>
				    <a id="pay_now_button_stripe_<?=$pricing->ids?>" name="<?=$pricing->ids?>" href="<?=base_url('user/pay_free/') . $pricing->ids?>" target="_blank" class="btn btn-md btn-success mt-2">
					  <i class="fas fa-cart-plus mr-2"></i> <?=my_caption('payment_pay_with_free')?>
					</a>
				  <?php
				    }
					if ($show_purchased) {
				  ?>
				    <a href="javascript:void(0)" class="btn btn-md btn-danger mt-2">
					  <i class="fas fa-cart-arrow-down mr-2"></i>
					  <?=my_caption('payment_pay_purchased')?>
					</a>
				  <?php } ?>
				  </div>
                </div>
              </div>
            </div>
			<?php
				  }
			  }
			  else {
				  echo '<div class="text-white">' . my_caption('front_pricing_no_item') . '</div>';
			  }
			?>
          </div>
        </div>
      </section>
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>