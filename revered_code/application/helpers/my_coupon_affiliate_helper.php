<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if (!function_exists('my_coupon_check')) {
	function my_coupon_check($item_ids, $code) {
		$CI = &get_instance();
		$query = $CI->db->where('code', $code)->where('enabled', 1)->where('valid_from<=', my_server_time())->where('valid_till>', my_server_time())->get('coupon', 1);
		if ($query->num_rows()) { //code is coupon code and it is valid
			$rs = $query->row();
			if ($rs->use_times_limit == 0 || ($rs->use_times_limit > $rs->use_times_count)) { //no limit or limit not exceed, coupon is valid
				$resp_array = my_coupon_calculator($item_ids, $rs);
			}
			else {  // exceed limit
				$resp_array = array(
				  'result' => FALSE,
				  'message' => my_caption('addons_coupon_unavailable_invalid')  //no longer available
				);
			}
		}
		else {
			$resp_array = array(  //not available
			  'result' => FALSE,
			  'message' => my_caption('addons_coupon_unavailable')
		    );
		}
		return $resp_array;
	}
}



if (!function_exists('my_coupon_calculator')) {
	function my_coupon_calculator($item_ids, $rs_code) {
		$CI = &get_instance();
		$rs_item = $CI->db->where('ids', $item_ids)->get('payment_item', 1)->row();
		if ($rs_code->discount_type == 'A') {  //discount by percentage
			$dicount = doubleval($rs_item->item_price*($rs_code->discount_amount/100));
			$new_price = $rs_item->item_price - $dicount;
		}
		else {  //discount by fix amount
			$dicount = doubleval($rs_code->discount_amount);
			$new_price = $rs_item->item_price - $rs_code->discount_amount;
		}
		if (substr($rs_code->applicable_scope, 0, 1) != '0') { //only applicable for some item
			(!my_check_str_contains($rs_code->applicable_scope, $item_ids)) ? $new_price = -1 : null;
		}
		if ($new_price < 0) {  //finally price<0 or not applicable to the item
			$resp_array = array(  //not available
			  'result' => FALSE,
			  'message' => my_caption('addons_coupon_unavailable_for_item')
		    );
		}
		else {
			$resp_array = array(
			  'result' => TRUE,
			  'message' => 'valid',
			  'discount' => $dicount,
			  'amount' => $new_price
		    );
		}
		return $resp_array;
	}
}



if (!function_exists('my_coupon_applied')) {
	function my_coupon_applied($rs_payment, $coupon_code) {
		if (my_coupon_module() && $coupon_code != '') {
			$coupon_check_array = my_coupon_check($rs_payment->item_ids, $coupon_code);
			if ($coupon_check_array['result']) {  //applied successfully
				$CI = &get_instance();
                $currency = $rs_payment->currency;			
				$insert_array = array(
				  'ids' => my_random(),
				  'code' => $coupon_code,
				  'user_ids' => $rs_payment->user_ids,
				  'payment_ids' => $rs_payment->ids,
				  'item_name' => my_get_payment_item($rs_payment->item_ids, 'item_name'),
				  'currency' => $currency,
				  'item_price' => my_get_payment_item($rs_payment->item_ids, 'item_price'),
				  'discount' => $coupon_check_array['discount'],
				  'amount' => $coupon_check_array['amount'],
				  'created_time' => my_server_time()
				);
				$CI->db->insert('coupon_log', $insert_array);
				$CI->db->where('code', $coupon_code)->set('use_times_count', '`use_times_count`+1', FALSE)->update('coupon');
			}
		}
		return TRUE;
	}
}



if (!function_exists('my_affiliate_applied')) {
	function my_affiliate_applied($rs_payment) {
		if (my_affiliate_module()) {
			$CI = &get_instance();
			$calculate_required = FALSE;
			$user_referral_array = json_decode(my_user_setting($rs_payment->user_ids, 'referral'), TRUE);
			if ($user_referral_array['referral_code'] != '') {
				$query = $CI->db->where('affiliate_code', $user_referral_array['referral_code'])->get('user', 1);
				if ($query->num_rows()) {
					$rs = $query->row();
					if ($rs->affiliate_enabled) {
						$affiliate_setting_array = json_decode($rs->affiliate_setting, TRUE);
						if ($affiliate_setting_array['commission_policy'] == 'A') {  //only calculator for the first time
							$success_count = $CI->db->where('user_ids', $rs_payment->user_ids)->where('redirect_status', 'success')->where('callback_status', 'success')->count_all_results('payment_log');
							if ($success_count == 1) {  //it's the first time
								$calculate_required = TRUE;
							}
						}
						else {
							$calculate_required = TRUE;
						}
						
					}
				}
			}
			if ($calculate_required) {
				$paid_price = $rs_payment->price - $rs_payment->coupon_discount;
				$commission = doubleval($paid_price*($affiliate_setting_array['commission_rate']/100));
				$insert_array = array(
				  'ids' => my_random(),
				  'user_ids' => $rs->ids,
				  'user_ids_referred' => $rs_payment->user_ids,
				  'payment_ids' => $rs_payment->ids,
				  'item_name' => my_get_payment_item($rs_payment->item_ids, 'item_name'),
				  'from_ip' => my_remote_info('ip'),
				  'currency' => $rs_payment->currency,
				  'amount' => $paid_price,
				  'commission' => $commission,
				  'stuff' => '',
				  'created_time' => my_server_time()
				);
				$CI->db->insert('affiliate_log', $insert_array);
				// update earning
				$affiliate_earning_array = json_decode($rs->affiliate_earning, TRUE);
				if (array_key_exists($rs_payment->currency, $affiliate_earning_array)) {
					$affiliate_earning_array[$rs_payment->currency] += doubleval($commission);
				}
				else {
					$affiliate_earning_array[$rs_payment->currency] = doubleval($commission);
				}
				$CI->db->where('ids', $rs->ids)->update('user', array('affiliate_earning' => json_encode($affiliate_earning_array)));	
			}
		}
		return TRUE;
	}
}


?>