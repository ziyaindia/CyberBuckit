<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();

class M_yoomoney {
	
	protected $payment_setting_array;
	
	
	
    public function __construct() {
		global $CI, $payment_setting_array;
		$payment_setting = $CI->db->get('setting', 1)->row()->payment_setting;
		$payment_setting_array = json_decode($payment_setting, 1);
    }
	
	
	
	protected function getRestURL($action) {
		$rootURL = 'https://api.yookassa.ru/v3/';
		switch($action) {
			case 'payments' :
			  $restURL = $rootURL . 'payments/';
			  break;
			default :
			 $restURL = $rootURL;
		}
		return $restURL;
	}
	
	
	
	protected function headerBuilder() {
		global $payment_setting_array;
		$header = [
		  'content-type:application/json',
		  'Cache-Control: no-cache',
		  'Idempotence-Key:' . my_random()
		];
		return $header;
	}
	
	
	
	protected function exeCurl($method, $url, $header, $sendData = '') {
		global $payment_setting_array;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $sendData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, $payment_setting_array['yoomoney_shop_id'] . ':' . $payment_setting_array['yoomoney_secret_key']);
		try {
			$response_body = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($http_code == 200 || $http_code == 201) {
				$resp_arr = array(
				  'success' => TRUE,
				  'response_body' => $response_body
				);
			}
			else {
				log_message('error', 'HTTP_CODE: ' . $http_code . ', RESPONSE_BODY: ' .$response_body);  //log it for debug
				$resp_arr = array(
				  'success' => FALSE,
				  'message' => 'Processing error, please contact the administrator.'
				);
			}
		}
		catch (Exception $e) {  //it's the network error
			log_message('error', $e->getMessage());  //log it for debug
			$resp_arr = array(
			  'success' => FALSE,
			  'message' => 'Connection error, please contact the administrator.'
			);
		}
		return $resp_arr;
	}

	
	
	public function verifyPayment($payloadArray) {
		$paymentArray = $this->exeCurl('GET', $this->getRestURL('payments') . $payloadArray['object']['id'], $this->headerBuilder());
		if ($paymentArray['success']) {
			$paymentDetailArray = json_decode($paymentArray['response_body'], 1);
			if ($paymentDetailArray['paid']) {
				$resp_array['success'] = TRUE;
			}
			else {
				$resp_array['success'] = FALSE;
			}
		}
		else {
			$resp_array = $paymentArray;
		}
		return $resp_array;
	}
	
	
	
	public function retrieveSubscription($subscriptionID) {  //this scenario is not existed in yoomoney, it's fabricated
		global $CI;
		$query_subscription = $CI->db->where('gateway_identifier', $subscriptionID)->get('payment_subscription', 1);
		if ($query_subscription->num_rows()) { //subscription exists
			$rs_subscription = $query_subscription->row();
			$resp_array['success'] = TRUE;
			$resp_array['status'] = 'active';
			$resp_array['identifier'] = $subscriptionID;
			$resp_array['currentStartTime'] = $rs_subscription->start_time;
			$resp_array['currentEndTime'] = date('Y-m-d H:i:s', strtotime(my_get_payment_item($rs_subscription->item_ids, 'recurring_interval_count') . ' ' . my_get_payment_item($rs_subscription->item_ids, 'recurring_interval'), strtotime($rs_subscription->end_time)));
			$resp_array['cycleStartTime'] = $rs_subscription->created_time;
		}
		else { //new subscription
			$query_log = $CI->db->where('gateway_identifier', $subscriptionID)->get('payment_log', 1);
			if ($query_log->num_rows()) {
				$rs_log = $query_log->row(); 
				$resp_array['success'] = TRUE;
				$resp_array['status'] = 'active';
				$resp_array['identifier'] = $subscriptionID;
				$resp_array['currentStartTime'] = my_server_time();
				$resp_array['currentEndTime'] = date('Y-m-d H:i:s', strtotime(my_get_payment_item($rs_log->item_ids, 'recurring_interval_count') . ' ' . my_get_payment_item($rs_log->item_ids, 'recurring_interval'), strtotime(my_server_time())));
				$resp_array['cycleStartTime'] = my_server_time();
			}
			else {
				$resp_array['success'] = FALSE;
			}
		}
		return $resp_array;
	}
	
	
	
	public function cancelSubscription($subscriptionArray) {  //this scenario is not existed in yoomoney, it's fabricated
		return array('success'=>TRUE);
	}
	
	
	
	public function resumeSubscription($subscriptionArray) {
		global $CI;
		$query = $CI->db->where('gateway_identifier', $subscriptionArray['subscriptionID'])->where('status', 'pending_cancellation')->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$resp_array['success'] = TRUE;
		}
		else {
			$resp_array['success'] = FALSE;
		}
		return $resp_array;
	}
	
	
	
	public function checkoutProcessing($checkoutArray, $itemRS) {
		global $CI;
		$orderArray['amount']['value'] = $checkoutArray['amount'];
		$orderArray['amount']['currency'] = $checkoutArray['currency'];
		$orderArray['capture'] = TRUE;
		$orderArray['confirmation']['type'] = 'redirect';
		$orderArray['confirmation']['return_url'] = base_url('webhook/authorized/yoomoney/');
		$orderArray['description'] = $checkoutArray['name'];
		if ($checkoutArray['type'] == 'recurring') {  //for recurring only
			$orderArray['payment_method_data']['type'] = 'bank_card';
			$orderArray['save_payment_method'] = TRUE;
		}
		$resp_arr = array();
		$createdPaymentArray = $this->exeCurl('POST', $this->getRestURL('payments'), $this->headerBuilder(), json_encode($orderArray));
		if ($createdPaymentArray['success']) {
			$paymentDetailArray = json_decode($createdPaymentArray['response_body'], 1);
			$resp_arr = array(
			  'success' => TRUE,
			  'processingID' => $paymentDetailArray['id'],
			  'redirectURL' => $paymentDetailArray['confirmation']['confirmation_url']
			);
			$CI->session->set_userdata(array('yoomoneyProcessingID' => $paymentDetailArray['id']));
		}
		else {
			$resp_arr = $createdPaymentArray;
		}
		return $resp_arr;
	}
	
	
	
	public function recurringProcessing($recurringArray) {  //extend the recurring, yoomoney only
		$orderArray['amount']['value'] = $recurringArray['amount'];
		$orderArray['amount']['currency'] = $recurringArray['currency'];
		$orderArray['capture'] = TRUE;
		$orderArray['payment_method_id'] = $recurringArray['paymentMethodID'];
		$orderArray['description'] = $recurringArray['name'];
		$resp_arr = array();
		$createdPaymentArray = $this->exeCurl('POST', $this->getRestURL('payments'), $this->headerBuilder(), json_encode($orderArray));
		if ($createdPaymentArray['success']) {
			$resp_array['success'] = TRUE;
		}
		else {
			$resp_array['success'] = FALSE;
		}
		return $resp_array;
	}
	
	

}