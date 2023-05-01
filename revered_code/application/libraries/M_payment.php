<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();

class M_payment {
	
	protected $payment_setting_array, $paymentGateway;
	
	
    public function __construct() {
		global $CI, $payment_setting_array, $paymentGateway;
		$payment_setting = $CI->db->get('setting', 1)->row()->payment_setting;
		$payment_setting_array = json_decode($payment_setting, 1);
		$paymentGateway = $payment_setting_array['addon_gateway'];
    }
	
	
	
	public function pay($type, $itemRS, $amount, $tax) {
		global $CI, $payment_setting_array, $paymentGateway;
		$checkoutArray = array(
		  'type' => $type,
		  'emailAddress' => my_user_setting($_SESSION['user_ids'], 'email_address'),
		  'name' => $itemRS->item_name,
		  'description' => $itemRS->item_description,
		  'amount' => $amount,
		  'currency' => $itemRS->item_currency
		);
		$libraryName = 'm_' . $paymentGateway;
		$CI->load->library($libraryName);
		$resp_array = $CI->$libraryName->checkoutProcessing($checkoutArray, $itemRS);
		if ($resp_array['success']) {
			$CI->load->model('user_model');
			$CI->user_model->payment_log($paymentGateway, $resp_array['processingID'], $itemRS, $amount, $tax);
			$viewPath = 'themes/' . $CI->setting->theme . '/';
			switch($paymentGateway) {
				case 'paypal' : //add as the same solution later
				  break;
				case 'stripe' : //add as the same solution later
				  break;
				case 'razorpay' :
				  $front_setting_array = json_decode($CI->setting->front_setting, TRUE);
				  $logoURL = base_url('upload/' . $front_setting_array['logo']);
				  $data = $checkoutArray;
				  $data['amount'] *= 100;
				  $data['key_id'] = $payment_setting_array['razorpay_key_id'];
				  $data['image'] = $logoURL;
				  $data['callback_url'] = base_url('webhook/authorized/razorpay/' . $resp_array['processingID']);
				  $data['processing_id'] = $resp_array['processingID'];
				  $CI->load->view($viewPath . 'Addons/payment_razorpay', $data, FALSE);
				  break;
				case 'paystack' :
				  redirect($resp_array['redirectURL']);
				  break;
				case 'yoomoney' :
				  redirect($resp_array['redirectURL']);
				  break;
				default:
				  echo 'Unexpected Payment Gateway';
				}
			}
		else {
			echo $resp_array['message'];
		}
	}
	
	
	
	public function retrieveSubscription($paymentGateway, $subscriptionID) {
		global $CI;
		$libraryName = 'm_' . $paymentGateway;
		$CI->load->library($libraryName);
		return $CI->$libraryName->retrieveSubscription($subscriptionID);	
	}
	
	
	
	public function cancelSubscription($subscriptionArray) {
		global $CI;
		$libraryName = 'm_' . $subscriptionArray['paymentGateway'];
		$CI->load->library($libraryName);
		return $CI->$libraryName->cancelSubscription($subscriptionArray);
	}
	
	
	
	public function resumeSubscription($subscriptionArray) {
		global $CI;
		$libraryName = 'm_' . $subscriptionArray['paymentGateway'];
		$CI->load->library($libraryName);
		return $CI->$libraryName->cancelSubscription($subscriptionArray);
	}
	
	
	
	public function verifyPayment($paymentGateway, $payloadArray) {
		global $CI;
		$libraryName = 'm_' . $paymentGateway;
		$CI->load->library($libraryName);
		return $CI->$libraryName->verifyPayment($payloadArray);
	}
	
	

}