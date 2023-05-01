<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();

class M_paystack {
	
	protected $payment_setting_array;
	
	
	
    public function __construct() {
		global $CI, $payment_setting_array;
		$payment_setting = $CI->db->get('setting', 1)->row()->payment_setting;
		$payment_setting_array = json_decode($payment_setting, 1);
    }
	
	
	
	protected function getRestURL($action) {
		$rootURL = 'https://api.paystack.co/';
		switch($action) {
			case 'transaction' :
			  $restURL = $rootURL . 'transaction/';
			  break;
			case 'plan' :
			  $restURL = $rootURL . 'plan/';
			  break;
			case 'subscription' :
			  $restURL = $rootURL . 'subscription/';
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
		  'Authorization: Bearer ' . $payment_setting_array['paystack_secret_key']
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
		$paymentArray = $this->exeCurl('GET', $this->getRestURL('transaction') . 'verify/' . $payloadArray['data']['reference'], $this->headerBuilder());
		if ($paymentArray['success']) {
			$paymentDetailArray = json_decode($paymentArray['response_body'], 1);
			$resp_array['success'] = TRUE;
			$resp_array['status'] = $paymentDetailArray['status'];
			$resp_array['identifier'] = $payloadArray['data']['reference'];
		}
		else {
			$resp_array = $paymentArray;
		}
		return $resp_array;
	}
	
	
	
	protected function createPlan($planArray) {
		return $this->exeCurl('POST', $this->getRestURL('plan'), $this->headerBuilder(), json_encode($planArray));
	}
	
	
	
	public function retrieveSubscription($subscriptionID) {
		$subscriptionArray = $this->exeCurl('GET', $this->getRestURL('subscription') . $subscriptionID, $this->headerBuilder());
		if ($subscriptionArray['success']) {
			$subscriptionDetailArray = json_decode($subscriptionArray['response_body'], 1);
			$resp_arr = array(
			  'success' => TRUE,
			  'identifier' => $subscriptionID,
			  'status' => str_replace('cancelled', 'expired', $subscriptionDetailArray['data']['status']),
			  'currentStartTime' => mdate('%Y-%m-%d %H:%i:%s', $subscriptionDetailArray['data']['start']),
			  'currentEndTime' => $subscriptionDetailArray['data']['next_payment_date'],
			  'nextBillingTime' => $subscriptionDetailArray['data']['next_payment_date'],
			  'cycleStartTime' => mdate('%Y-%m-%d %H:%i:%s', $subscriptionDetailArray['data']['start']),
			  'cycleEndTime' => '',
			  'emailToken' => $subscriptionDetailArray['data']['email_token']
			);
		}
		else {
			$resp_arr = $subscriptionArray;
		}
		return $resp_arr;
	}
	
	
	
	public function cancelSubscription($subscriptionArray) {
		$subscriptionRetrieveArray = $this->retrieveSubscription($subscriptionArray['subscriptionID']);
		if ($subscriptionRetrieveArray['success']) {
			if ($subscriptionRetrieveArray['status'] == 'active') {
				$cancelArray = array(
				  'code' => $subscriptionArray['subscriptionID'],
				  'token' => $subscriptionRetrieveArray['emailToken']
				);
				$cancelSubscriptionArray = $this->exeCurl('POST', $this->getRestURL('subscription') . 'disable', $this->headerBuilder(), json_encode($cancelArray));
				$resp_arr = $cancelSubscriptionArray;
			}
			else {
				$resp_arr = array('success'=>TRUE);
			}
		}
		else {
			$resp_arr = $subscriptionRetrieveArray;
		}
		return $resp_arr;
	}
	
	

	public function resumeSubscription($subscriptionArray) {
		$resp_array['success'] = FALSE;
		return $resp_array;
	}
	
	
	
	public function checkoutProcessing($checkoutArray, $itemRS) {
		$orderArray['amount'] = $checkoutArray['amount'] * 100;
		$orderArray['currency'] = $checkoutArray['currency'];
		$orderArray['email'] = $checkoutArray['emailAddress'];
		$orderArray['reference'] = my_random();
		$orderArray['callback_url'] = base_url('webhook/authorized/paystack/' . $orderArray['reference']);
		$resp_arr = array();
		if ($checkoutArray['type'] == 'recurring') {  //it's a recurring payment, handle the plan
			$plan_code = 0;
			$itemStuffArray = json_decode($itemRS->stuff_setting, 1);
			(array_key_exists('paystack_plan_code', $itemStuffArray)) ? $plan_code = $itemStuffArray['paystack_plan_code'] : null; //check whether plan_code is valid
			if (!$plan_code) {  //need to create a new plan
				$planArray['name'] = $checkoutArray['name'];
				$planArray['amount'] = $checkoutArray['amount']*100;
				$planArray['interval'] = $this->transitInterval($itemRS->recurring_interval, $itemRS->recurring_interval_count);
				$planArray['currency'] = $checkoutArray['currency'];
				$createdPlanArray = $this->createPlan($planArray);
				if ($createdPlanArray['success']) {
					$planDetailArray = json_decode($createdPlanArray['response_body'], 1);
					$plan_code = $planDetailArray['data']['plan_code'];
					my_save_payment_item_stuff_setting($itemRS->ids, 'paystack_plan_code', $plan_code);
				}
			}
			($plan_code) ? $orderArray['plan'] = $plan_code : $resp_arr = $createdPlanArray;
		}
		if (empty($resp_arr)) {  //no error, everything is ready
			$initTransactionArray = $this->exeCurl('POST', $this->getRestURL('transaction') . 'initialize/', $this->headerBuilder(), json_encode($orderArray));
			if ($initTransactionArray['success']) {
				$initTransactionDetailArray = json_decode($initTransactionArray['response_body'], 1);
				$resp_arr = array(
				  'success' => TRUE,
				  'processingID' => $orderArray['reference'],
				  'redirectURL' => $initTransactionDetailArray['data']['authorization_url']
				);
			}
			else {
				$resp_arr = $initTransactionArray;
			}
		}
		return $resp_arr;
	}
	
	
	
	protected function transitInterval($intervalUnit, $intervalCount) {
		switch ($intervalUnit) {
			case 'day' :
			  $interval = 'daily';
			  ($intervalCount == 7) ? $interval = 'weekly' : null;
			  ($intervalCount == 30) ? $interval = 'monthly' : null;
			  break;
			case 'week' :
			  $interval = 'weekly';
			  break;
			case 'month' :
			  $interval = 'monthly';
			  ($intervalCount == 3) ? $interval = 'quarterly' : null;
			  ($intervalCount == 6) ? $interval = 'biannually' : null;
			  break;
			case 'year' :
			  $interval = 'annually';
			   break;
		}
		return $interval;
	}
	
	
	

}