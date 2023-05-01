<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();

class M_razorpay {
	
	protected $payment_setting_array;
	
	
	
    public function __construct() {
		global $CI, $payment_setting_array;
		$payment_setting = $CI->db->get('setting', 1)->row()->payment_setting;
		$payment_setting_array = json_decode($payment_setting, 1);
    }
	
	
	
	protected function getRestURL($action) {
		$rootURL = 'https://api.razorpay.com/v1/';
		switch($action) {
			case 'order' :
			  $restURL = $rootURL . 'orders/';
			  break;
			case 'payment' :
			  $restURL = $rootURL . 'payments/';
			  break;
			case 'plan' :
			  $restURL = $rootURL . 'plans/';
			  break;
			case 'subscription' :
			  $restURL = $rootURL . 'subscriptions/';
			  break;
			default :
			 $restURL = $rootURL;
		}
		return $restURL;
	}
	
	
	
	protected function headerBuilder() {
		$header = [
		  'content-type:application/json'
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
		curl_setopt($curl, CURLOPT_USERPWD, $payment_setting_array['razorpay_key_id'] . ':' . $payment_setting_array['razorpay_key_secret']);
		try {
			$response_body = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($http_code == 200) {
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
		$paymentArray = $this->exeCurl('GET', $this->getRestURL('payment') . $payloadArray['payload']['payment']['entity']['id'], $this->headerBuilder());
		if ($paymentArray['success']) {
			$paymentDetailArray = json_decode($paymentArray['response_body'], 1);
			$resp_array['success'] = TRUE;
			$resp_array['status'] = $paymentDetailArray['status'];
			$resp_array['currency'] = $paymentDetailArray['currency'];
			$resp_array['amount'] = $paymentDetailArray['amount'];
			$resp_array['identifier'] = $paymentDetailArray['notes']['identifier'];
		}
		else {
			$resp_array = $paymentArray;
		}
		return $resp_array;
	}
	
	
	
	protected function createOrder($orderArray) {
		return $this->exeCurl('POST', $this->getRestURL('order'), $this->headerBuilder(), json_encode($orderArray));
	}
	
	
	
	protected function createPlan($planArray) {
		return $this->exeCurl('POST', $this->getRestURL('plan'), $this->headerBuilder(), json_encode($planArray));
	}
	
	
	
	protected function createSubscription($subscriptionArray) {
		return $this->exeCurl('POST', $this->getRestURL('subscription'), $this->headerBuilder(), json_encode($subscriptionArray));
	}
	
	
	
	public function retrieveSubscription($subscriptionID) {
		$subscriptionArray = $this->exeCurl('GET', $this->getRestURL('subscription') . $subscriptionID, $this->headerBuilder());
		if ($subscriptionArray['success']) {
			$subscriptionDetailArray = json_decode($subscriptionArray['response_body'], 1);
			$resp_arr = array(
			  'success' => TRUE,
			  'identifier' => $subscriptionID,
			  'status' => $subscriptionDetailArray['status'],
			  'currentStartTime' => mdate('%Y-%m-%d %H:%i:%s', $subscriptionDetailArray['current_start']),
			  'currentEndTime' => mdate('%Y-%m-%d %H:%i:%s', $subscriptionDetailArray['current_end']),
			  'nextBillingTime' => mdate('%Y-%m-%d %H:%i:%s', $subscriptionDetailArray['charge_at']),
			  'cycleStartTime' => mdate('%Y-%m-%d %H:%i:%s', $subscriptionDetailArray['start_at']),
			  'cycleEndTime' => mdate('%Y-%m-%d %H:%i:%s', $subscriptionDetailArray['end_at'])
			);
		}
		else {
			$resp_arr = $subscriptionArray;
		}
		return $resp_arr;
	}
	
	
	
	public function cancelSubscription($subscriptionArray) {
		($subscriptionArray['cancelNow']) ? $subscriptionArray['cancel_at_cycle_end'] = 0 : $subscriptionArray['cancel_at_cycle_end'] = 1;
		$fullURL = $this->getRestURL('subscription') . $subscriptionArray['subscriptionID'] . '/cancel';
		unset($subscriptionArray['paymentGateway']);
		unset($subscriptionArray['cancelNow']);
		unset($subscriptionArray['subscriptionID']);
		return $this->exeCurl('POST', $fullURL, $this->headerBuilder(), json_encode($subscriptionArray));
	}
	
	
	
	public function resumeSubscription($subscriptionArray) {
		$resp_array['success'] = FALSE;
		return $resp_array;
	}
	
	

	public function checkoutProcessing($checkoutArray, $itemRS) {
		if ($checkoutArray['type'] == 'one-time') {  //it's one-time payment
			$orderArray['amount'] = str_replace(',', '', $checkoutArray['amount'])*100;
			$orderArray['currency'] = $checkoutArray['currency'];
			$createdOrderArray = $this->createOrder($orderArray);
			if ($createdOrderArray['success']) {
				$orderDetailArray = json_decode($createdOrderArray['response_body'], 1);
				$resp_arr = array(
				  'success' => TRUE,
				  'processingID' => $orderDetailArray['id'] //here, it's the order's ID
				);
			}
			else {
				$resp_arr = $createdOrderArray;
			}
		}
		else { //it's recurring payment
			$plan_id = 0;
			$itemStuffArray = json_decode($itemRS->stuff_setting, 1);
			(array_key_exists('razorpay_plan_id', $itemStuffArray)) ? $plan_id = $itemStuffArray['razorpay_plan_id'] : null; //check whether plan_id is valid
			if (!$plan_id) { //need to create a new plan
				($itemRS->recurring_interval == 'day') ? $planArray['period'] = 'daily' : $planArray['period'] = $itemRS->recurring_interval . 'ly';
				$planArray['interval'] = $itemRS->recurring_interval_count;
				$planArray['item']['name'] = $checkoutArray['name'];
				$planArray['item']['amount'] = str_replace(',', '', $checkoutArray['amount'])*100;
				$planArray['item']['currency'] = $checkoutArray['currency'];
				$planArray['item']['description'] = $checkoutArray['name'];
				$createdPlanArray = $this->createPlan($planArray);
				if ($createdPlanArray['success']) {
					$planDetailArray = json_decode($createdPlanArray['response_body'], 1);
					$plan_id = $planDetailArray['id'];
					my_save_payment_item_stuff_setting($itemRS->ids, 'razorpay_plan_id', $plan_id);
				}
			}
			if ($plan_id) {  //plan existed or created
				$subscriptionArray['plan_id'] = $plan_id;
				$subscriptionArray['total_count'] = $this->maximunSubscriptionCycle($itemRS->recurring_interval);
				$createdSubscriptionArray = $this->createSubscription($subscriptionArray);
				if ($createdSubscriptionArray['success']) {
					$subscriptionDetailArray = json_decode($createdSubscriptionArray['response_body'], 1);
					$resp_arr = array(
					  'success' => TRUE,
					  'processingID' => $subscriptionDetailArray['id'] //here, it's the subscription's ID
					);	
				}
				else {
					$resp_arr = $createdSubscriptionArray;  //fail to create subscription
				}
			}
			else {
				$resp_arr = $createdPlanArray;  //fail to create plan
			}
		}
		return $resp_arr;
	}
	
	
	
	protected function maximunSubscriptionCycle($period) {
		switch ($period) {
			case 'year' :
			  $totalCount = 50;
			  break;
			case 'month' :
			  $totalCount = 200;
			  break;
			case 'week' :
			  $totalCount = 800;
			  break;
			case 'day' :
			  $totalCount = 2000;
			  break;
		}
		return $totalCount;
	}
	
	
	
	
	
	

}