<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();

class M_paypal {
	
	protected $payment_setting_array;
	
    public function __construct() {
		global $CI, $payment_setting_array;
		$payment_setting = $CI->db->get('setting', 1)->row()->payment_setting;
		$payment_setting_array = json_decode($payment_setting, 1);
    }
	
	
	
	protected function getRestURL($action, $sn = '') {  //return the rest url according to action
		global $payment_setting_array;
		($payment_setting_array['type'] == 'sandbox') ? $url = 'https://api-m.sandbox.paypal.com/' : $url = 'https://api-m.paypal.com/';
		switch ($action) {
			case 'getAccessToken' :
			  $rest_url = $url . 'v1/oauth2/token';
			  break;
			case 'newProduct' :
			  $rest_url = $url . 'v1/catalogs/products';
			  break;
			case 'retrieveProduct' :
			  $rest_url = $url . 'v1/catalogs/products/';
			  break;
			case 'newPlan' :
			  $rest_url = $url . 'v1/billing/plans';
			  break; 
			case 'retrievePlan' :
			  $rest_url = $url . 'v1/billing/plans/';
			  break;
			case 'newSubscription' :
			  $rest_url = $url . 'v1/billing/subscriptions';
			  break;
			case 'retrieveSubscription' :
			  $rest_url = $url . 'v1/billing/subscriptions/';
			  break;
			case 'suspendSubscription' :
			  $rest_url = $url . 'v1/billing/subscriptions/' . $sn . '/suspend/';
			  break;
			case 'activateSubscription' :
			  $rest_url = $url . 'v1/billing/subscriptions/' . $sn . '/activate/';
			  break;
			case 'cancelSubscription' :
			  $rest_url = $url . 'v1/billing/subscriptions/' . $sn . '/cancel/';
			  break;
		}
		return $rest_url;
	}
	
	
	
	protected function headerBuilder($action, $access_token = '') {  //return the header according to action
		$header = [
		  'Accept: application/json',
		  'Accept-Language: en_US'
		];
		switch ($action) {
			case 'getAccessToken' :
			  array_push($header, 'Content-Type: application/x-www-form-urlencoded');
			  break;
			default :
			  array_push($header, 'Content-Type: application/json');
			  array_push($header, 'Authorization: Bearer ' . $access_token);
			  break;
		}
		($action == 'newPlan' || $action == 'newSubscription') ? array_push($header, 'Prefer: return=representation') : null;
		return $header;
	}
	
	
	
	protected function exeCurl($action, $url, $header, $sendData) {
		global $payment_setting_array;
		($action == 'getAccessToken' || $action == 'newProduct' || $action == 'newPlan' || $action == 'newSubscription'|| $action == 'suspendSubscription'|| $action == 'activateSubscription'|| $action == 'cancelSubscription') ? $method = 'POST' : $method = 'GET';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $sendData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if ($action == 'getAccessToken') {
			curl_setopt($curl, CURLOPT_USERPWD, $payment_setting_array['paypal_client_id'] . ':' . $payment_setting_array['paypal_secret']);
		}
		try {
			$response_body = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$resp_arr = array(
			  'success' => TRUE,
			  'http_code' => $http_code,
			  'response_body' => $response_body
		    );
		}
		catch (Exception $e) { //it's the network error
			log_message('error', $e->getMessage());
			$resp_arr = array(
			  'success' => FALSE,
			  'message' => 'connection error, please check the log'
			);
		}
		return $resp_arr;
	}
	
	
	
	protected function analyseCurl($action, $curl_resp_arr) {
		if ($curl_resp_arr['success']) {
			$response_body_arr = json_decode($curl_resp_arr['response_body'], TRUE);
			switch ($action) {
				case 'getAccessToken' :
				  ($curl_resp_arr['http_code'] == 200) ? $analyse_result_arr = array('success'=>TRUE, 'access_token'=>$response_body_arr['access_token']) : $analyse_result_arr = array('success'=>FALSE, 'message'=>$response_body_arr['error_description']);
				  break;
				case 'newProduct' :
				  ($curl_resp_arr['http_code'] == 201) ? $analyse_result_arr = array('success'=>TRUE, 'product_id'=>$response_body_arr['id']) : $analyse_result_arr = array('success'=>FALSE, 'message'=>$response_body_arr['message']);
				  break;
				case 'retrieveProduct' :
				  ($curl_resp_arr['http_code'] == 200) ? $analyse_result_arr = array('success'=>TRUE) : $analyse_result_arr = array('success'=>FALSE, 'message'=>$response_body_arr['message']);
				  break;
				case 'newPlan' :
				  ($curl_resp_arr['http_code'] == 201) ? $analyse_result_arr = array('success'=>TRUE, 'plan_id'=>$response_body_arr['id']) : $analyse_result_arr = array('success'=>FALSE, 'message'=>$response_body_arr['message']);
				  break;
				case 'retrievePlan' :
				  ($curl_resp_arr['http_code'] == 200) ? $analyse_result_arr = array('success'=>TRUE, 'plan_price'=>$response_body_arr['billing_cycles'][0]['pricing_scheme']['fixed_price']['value']) : $analyse_result_arr = array('success'=>FALSE, 'message'=>$response_body_arr['message']);
				  break;
				case 'newSubscription' :
				  ($curl_resp_arr['http_code'] == 201) ? $analyse_result_arr = array('success'=>TRUE, 'subscription_id'=>$response_body_arr['id'], 'redirectURL'=>$response_body_arr['links'][0]['href']) : $analyse_result_arr = array('success'=>FALSE, 'message'=>$response_body_arr['message']);
				  break;
				case 'retrieveSubscription' :
				  if ($curl_resp_arr['http_code'] == 200) {
					  $start_time = new DateTime($response_body_arr['start_time']);
					  $end_time = new DateTime($response_body_arr['billing_info']['next_billing_time']);
					  $analyse_result_arr = array(
					    'success' => TRUE,
						'status' => $response_body_arr['status'],
						'start_time' => $start_time->format('Y-m-d h:i:s'),
						'end_time' => $end_time->format('Y-m-d') . ' 23:59:59'
					  );
				  }
				  else {
					  $analyse_result_arr = array('success'=>FALSE, 'message'=>$response_body_arr['message']);
				  }
				  break;
				case 'suspendSubscription' :
				case 'activateSubscription' :
				case 'cancelSubscription' :
				  ($curl_resp_arr['http_code'] == 204) ? $analyse_result_arr = array('success'=>TRUE) : $analyse_result_arr = array('success'=>FALSE, 'message'=>$response_body_arr['message']);
				  break;
			}
		}
		else {
			$analyse_result_arr = $curl_resp_arr;
		}
		return $analyse_result_arr;
	}
	
	
	
	protected function getAccessToken() {
		$action = 'getAccessToken';
		$url = $this->getRestURL($action);
		$header = $this->headerBuilder($action);
		$sendData = 'grant_type=client_credentials';
		$curl_resp_arr = $this->exeCurl($action, $url, $header, $sendData);
		return $this->analyseCurl($action, $curl_resp_arr);
	}
	
	
	
	//All Available Function For Calling Starts
	public function newProduct($productArray) {
		//simplest accepted productArray: ['name'=>'theProductName']
		$accessTokenArray = $this->getAccessToken();
		if ($accessTokenArray['success']) {
			$action = 'newProduct';
			$url = $this->getRestURL($action);
			$header = $this->headerBuilder($action, $accessTokenArray['access_token']);
			$sendData = json_encode($productArray);
			$curl_resp_arr = $this->exeCurl($action, $url, $header, $sendData);
			$resp = $this->analyseCurl($action, $curl_resp_arr);
		}
		else {
			$resp = $accessTokenArray;
		}
		return $resp;
	}
	
	
	
	public function retrieveProduct($productID) {  //return TRUE or FALSE indicating whether the product is available
		$accessTokenArray = $this->getAccessToken();
		if ($accessTokenArray['success']) {
			$action = 'retrieveProduct';
			$url = $this->getRestURL($action) . $productID;
			$header = $this->headerBuilder($action, $accessTokenArray['access_token']);
			$curl_resp_arr = $this->exeCurl($action, $url, $header, '');
			$resp = $this->analyseCurl($action, $curl_resp_arr);
		}
		else {
			$resp = $accessTokenArray;
		}
		return $resp;
	}
	
	
	
	public function newPlan($planArray) {
		// simplest accepted planArray:
		// [
		//   'product_id' => '',
		//   'product_name' => '',
		//   'interval_unit' => '',
		//   'interval_count' => '',
		//   'price' => '',
		//   'currency' => '',
		//  ]
		$accessTokenArray = $this->getAccessToken();
		if ($accessTokenArray['success']) {
			$action = 'newPlan';
			$url = $this->getRestURL($action);
			$header = $this->headerBuilder($action, $accessTokenArray['access_token']);
			$sendData_arr['product_id'] = $planArray['product_id'];
			$sendData_arr['name'] = $planArray['product_name'];
			$sendData_arr['billing_cycles'][0]['frequency']['interval_unit'] = $planArray['interval_unit'];
			$sendData_arr['billing_cycles'][0]['frequency']['interval_count'] = $planArray['interval_count'];
			$sendData_arr['billing_cycles'][0]['tenure_type'] = 'REGULAR';
			$sendData_arr['billing_cycles'][0]['sequence'] = 1;
			$sendData_arr['billing_cycles'][0]['total_cycles'] = 0;
			$sendData_arr['billing_cycles'][0]['pricing_scheme']['fixed_price']['value'] = $planArray['price'];
			$sendData_arr['billing_cycles'][0]['pricing_scheme']['fixed_price']['currency_code'] = $planArray['currency'];
			$sendData_arr['payment_preferences']['auto_bill_outstanding'] = TRUE;
			$curl_resp_arr = $this->exeCurl($action, $url, $header, json_encode($sendData_arr));
			$resp = $this->analyseCurl($action, $curl_resp_arr);
		}
		else {
			$resp = $accessTokenArray;
		}
		return $resp;
	}
	
	
	
	public function retrievePlan($planID) {
		$accessTokenArray = $this->getAccessToken();
		if ($accessTokenArray['success']) {
			$action = 'retrievePlan';
			$url = $this->getRestURL($action) . $planID;
			$header = $this->headerBuilder($action, $accessTokenArray['access_token']);
			$curl_resp_arr = $this->exeCurl($action, $url, $header, '');
			$resp = $this->analyseCurl($action, $curl_resp_arr);
		}
		else {
			$resp = $accessTokenArray;
		}
		return $resp;
	}
	
	
	
	public function newSubscription($subscriptionArray) {
		// simplest accepted subscriptionArray:
		// [
		//   'plan_id' => '',
		//   'return_url' => '',
		//   'cancel_url' => ''
		//  ]
		$accessTokenArray = $this->getAccessToken();
		if ($accessTokenArray['success']) {
			$action = 'newSubscription';
			$url = $this->getRestURL($action);
			$header = $this->headerBuilder($action, $accessTokenArray['access_token']);
			$sendData_arr['plan_id'] = $subscriptionArray['plan_id'];
			$sendData_arr['application_context']['user_action'] = 'SUBSCRIBE_NOW';
			$sendData_arr['application_context']['payment_method']['payer_selected'] = 'PAYPAL';
			$sendData_arr['application_context']['payment_method']['payee_preferred'] = 'IMMEDIATE_PAYMENT_REQUIRED';
			$sendData_arr['application_context']['return_url'] = $subscriptionArray['return_url'];
			$sendData_arr['application_context']['cancel_url'] = $subscriptionArray['cancel_url'];
			$curl_resp_arr = $this->exeCurl($action, $url, $header, json_encode($sendData_arr));
			$resp = $this->analyseCurl($action, $curl_resp_arr);
		}
		else {
			$resp = $accessTokenArray;
		}
		return $resp;
	}
	
	
	
	public function retrieveSubscription($subscriptionID) {
		$accessTokenArray = $this->getAccessToken();
		if ($accessTokenArray['success']) {
			$action = 'retrieveSubscription';
			$url = $this->getRestURL($action) . $subscriptionID;
			$header = $this->headerBuilder($action, $accessTokenArray['access_token']);
			$curl_resp_arr = $this->exeCurl($action, $url, $header, '');
			$resp = $this->analyseCurl($action, $curl_resp_arr);
		}
		else {
			$resp = $accessTokenArray;
		}
		return $resp;
	}
	
	
	
	public function modifySubscription($action, $subscriptionID) {
		$accessTokenArray = $this->getAccessToken();
		if ($accessTokenArray['success']) {
			$url = $this->getRestURL($action, $subscriptionID);
			$header = $this->headerBuilder($action, $accessTokenArray['access_token']);
			$curl_resp_arr = $this->exeCurl($action, $url, $header, '');
			$resp = $this->analyseCurl($action, $curl_resp_arr);
		}
		else {
			$resp = $accessTokenArray;
		}
		return $resp;
	}
	

}







?>