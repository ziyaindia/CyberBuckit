<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
		parent::__construct();

    }



	public function index() {
		//
	}



	public function my_profile() {
		$data['rs'] = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1)->row();
		my_load_view($this->setting->theme, 'User/my_profile', $data);
	}



	public function my_profile_action() {
		my_check_demo_mode();  //check if it's in demo mode
		if ($_SESSION['is_admin'] && my_uri_segment(3) != '') {  // admin modifies user's profile
		    my_check_demo_mode();  //check if it's in demo mode
			$ids = my_uri_segment(3);
		}
		else { //user modify his own profile
			$ids = $_SESSION['user_ids'];
		}
		// when update this part, don't forget to update api model to sync the same rule

		$data['rs'] = $this->db->where('ids', $ids)->get('user', 1)->row();
		$avatar_file = '';
		$this->form_validation->set_rules('email_address', my_caption('global_email_address'), 'trim|required|valid_email|max_length[50]|callback_email_duplicated_check[' . $ids . ']');
		if (my_post('username') != '') { $this->form_validation->set_rules('username', my_caption('global_username'), 'trim|min_length[5]|max_length[20]|alpha_dash|callback_username_duplicated_check[' . $ids . ']'); }
		$this->form_validation->set_rules('first_name', my_caption('mp_first_name_label'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('last_name', my_caption('mp_last_name_label'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('company', my_caption('mp_company_label'), 'trim|max_length[255]');
		$this->form_validation->set_rules('date_format', my_caption('mp_date_format_label'), 'trim|max_length[20]');
		$this->form_validation->set_rules('time_format', my_caption('mp_time_format_label'), 'trim|max_length[20]');
		$this->form_validation->set_rules('timezone', my_caption('mp_timezone_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('language', my_caption('mp_language_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('country', my_caption('mp_county_label'), 'trim|max_length[2]');
		$this->form_validation->set_rules('currency', my_caption('mp_currency_label'), 'trim|max_length[3]');
		$this->form_validation->set_rules('address_line_1', my_caption('mp_address_line_1_label'), 'trim|max_length[255]');
		$this->form_validation->set_rules('address_line_2', my_caption('mp_address_line_2_label'), 'trim|max_length[255]');
		$this->form_validation->set_rules('city', my_caption('mp_city_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('state', my_caption('mp_state_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('zip_code', my_caption('mp_zip_code_label'), 'trim|max_length[20]');
		$this->form_validation->set_rules('phone', my_caption('mp_phone_label'), 'trim|max_length[21]');
		if (isset($_FILES['userfile']['name'])) {
			if ($_FILES['userfile']['name'] != '') {
				$this->form_validation->set_rules('userfile', 'Upload File', 'callback_avatar_upload');
				$avatar_file = $_SESSION['user_ids'] . '.' . pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
				}
		}
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('flash_danger', my_caption('global_something_failed'));
			if ($_SESSION['is_admin'] && my_uri_segment(3) != '') {
				my_load_view($this->setting->theme, 'Admin/edit_user', $data);
			}
			else {
				my_load_view($this->setting->theme, 'User/my_profile', $data);
			}
		}
		else {
			$this->load->model('user_model');
			$res = $this->user_model->update_profile($ids, $avatar_file);
			if ($res['result']) {
				$this->session->set_flashdata('flash_success', $res['message']);
			}
			else {
				$this->session->set_flashdata('flash_danger', $res['message']);
			}
			if ($_SESSION['is_admin'] && my_uri_segment(3) != '') {
				redirect(base_url('admin/edit_user/' . my_uri_segment(3)));
			}
			else {
				redirect(base_url('user/my_profile'));
			}
		}
	}



	public function my_profile_impersonate_action() {
		$this->my_profile_action();
	}



	public function change_password() {
		my_load_view($this->setting->theme, 'User/change_password');
	}



	public function change_password_action() {
		my_check_demo_mode();  //check if it's in demo mode
		$throttle_check = my_throttle_check($_SESSION['user_ids']);
		if (!$throttle_check['result']) {
			$this->session->set_flashdata('flash_danger', $throttle_check['message']);
			redirect(base_url('user/change_password'));
		}
		else {
			$this->form_validation->set_rules('old_password', my_caption('cp_old_password_label'), 'trim|required|callback_check_old_password');
			if (!empty(my_post('new_password'))) {
				switch ($this->setting->psr) {
					case 'medium' :
					  $min_length = 8;
					  break;
					case 'strong' :
					  $min_length = 12;
					  break;
					default :
					  $min_length = 6;
				}
				$condition = 'trim|required|min_length[' . $min_length . ']|max_length[20]|callback_password_strength[' . $this->setting->psr . ']';
			}
			else {
				$condition = 'trim|required';
			}
			$this->form_validation->set_rules('new_password', my_caption('cp_new_password_label'), $condition);
			$this->form_validation->set_rules('new_password_confirm', my_caption('cp_new_password_confirm_label'), 'trim|required|matches[new_password]');
			if ($this->form_validation->run() == FALSE) {
				my_load_view($this->setting->theme, 'User/change_password');
			}
			else {
				my_log($_SESSION['user_ids'], 'Information', 'update-password', json_encode(my_ua()));  // log
				$this->db->where('ids', $_SESSION['user_ids'])->update('user', array('password'=>my_hash_password(my_post('new_password'))));
				$this->session->set_flashdata('flash_success', my_caption('cp_success'));
				redirect(base_url('user/change_password'));
			}
		}
	}



	public function my_activity_log() {
		my_load_view($this->setting->theme, 'User/my_activity');
	}



	public function my_notification() {
		($this->new_notification_tag) ? $this->db->where('ids', $_SESSION['user_ids'])->update('user', array('new_notification'=>0)) : null;
		my_load_view($this->setting->theme, 'User/my_notification');
	}



	public function my_notification_view() {
		$query = $this->db->where('ids', my_uri_segment(3))->group_start()->where('to_user_ids', $_SESSION['user_ids'])->or_where('to_user_ids', 'all')->group_end()->get('notification', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			($this->new_notification_tag) ? $this->db->where('ids', $_SESSION['user_ids'])->update('user', array('new_notification'=>0)) : null;
			($data['rs']->is_read == 0) ? $this->db->where('ids', $data['rs']->ids)->update('notification', array('is_read'=>1)) : null;
			my_load_view($this->setting->theme, 'Generic/view_notification', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}



	public function pay_now() {
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		if (!empty($_SESSION['access_code'])) {
			$data['rs'] = $this->db->where('enabled', 1)->where('access_code', $_SESSION['access_code'])->order_by('id', 'asc')->get('payment_item')->result();
		}
		else {
			$data['rs'] = $this->db->where('enabled', 1)->where('access_code', '')->order_by('id', 'asc')->get('payment_item')->result();
		}
		$data['payment_gateway_stripe_one_time'] = $payment_setting_array['stripe_one_time_enabled'];
		$data['payment_gateway_stripe_recurring'] = $payment_setting_array['stripe_recurring_enabled'];
		$data['payment_gateway_paypal_one_time'] = $payment_setting_array['paypal_one_time_enabled'];
		$data['payment_gateway_paypal_recurring'] = $payment_setting_array['paypal_recurring_enabled'];
		$data['payment_gateway_one_time'] = 0;
		$data['payment_gateway_recurring'] = 0;
		$data['payment_gateway_name'] = my_caption('payment_pay_now');
		if (array_key_exists('addon_gateway', $payment_setting_array)) {
			$gateway = $payment_setting_array['addon_gateway'];
			if ($gateway) {
				array_key_exists($gateway . '_name', $payment_setting_array) ? $data['payment_gateway_name'] = my_caption('addons_payment_pay_with') . $payment_setting_array[$gateway . '_name'] : null;
				array_key_exists($gateway . '_one_time_enabled', $payment_setting_array) ? $data['payment_gateway_one_time'] = $payment_setting_array[$gateway . '_one_time_enabled'] : null;
				array_key_exists($gateway . '_recurring_enabled', $payment_setting_array) ? $data['payment_gateway_recurring'] = $payment_setting_array[$gateway . '_recurring_enabled'] : null;
			}
		}
		(!empty($payment_setting_array['tax_rate'])) ? $data['payment_tax_rate'] = $payment_setting_array['tax_rate'] : $data['payment_tax_rate'] = 0;
		my_load_view($this->setting->theme, 'User/pay_now', $data);
	}



	public function pay_retry() {
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('ids', my_uri_segment(3))->where('redirect_status!=', 'success')->where('callback_status!=', 'success')->get('payment_log', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			($rs->type == 'purchase' || $rs->type == 'top-up') ? $pay_type = 'pay_once' : $pay_type = 'pay_recurring';
			($rs->coupon == '') ? $coupon = '' : $coupon = $rs->coupon . '/';
			($rs->gateway == 'paypal' || $rs->gateway == 'stripe') ? $paymentGateway = $rs->gateway : $paymentGateway = 'gateway';
			redirect(base_url('user/' . $pay_type . '/' . $paymentGateway . '/' . $rs->item_ids . '/' . $coupon . '?quantity=' . $rs->quantity));
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('payment_repay_unavailable'));
			redirect(base_url('user/pay_list'));
		}
	}



	public function pay_subscription_list() {
		my_load_view($this->setting->theme, 'User/pay_subscription_list');
	}



	public function pay_subscription_list_view() {
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('ids', my_uri_segment(3))->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$data['rs'] = $rs;
			$data['rs_item'] = $this->db->where('ids', $rs->item_ids)->get('payment_item', 1)->row();
			my_load_view($this->setting->theme, 'User/pay_subscription_list_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}



	public function pay_subscription_action() {
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$action = my_uri_segment(3);
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('ids', my_uri_segment(4))->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->payment_gateway == 'stripe' || $rs->payment_gateway == 'paypal' || $rs->payment_gateway == 'manual' || $rs->payment_gateway == 'free') {  //legacy problem, will change soon
				$result = my_payment_gateway_subscription_action($action, $rs, 'user/pay_subscription_list');
			}
			else {
				$this->load->library('m_payment');
				$subscriptionArray['subscriptionID'] = $rs->gateway_identifier;
				$subscriptionArray['paymentGateway'] = $rs->payment_gateway;
				if ($action == 'cancel' || $action == 'cancel_now') {
					($action == 'cancel') ? $subscriptionArray['cancelNow'] = FALSE : $subscriptionArray['cancelNow'] = TRUE;
					$cancelledSubscriptionArray = $this->m_payment->cancelSubscription($subscriptionArray);
					if ($cancelledSubscriptionArray['success']) {
						($action == 'cancel_now') ? $status = 'expired' : $status = 'pending_cancellation';
						$this->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>$status));
						$result = '{"result":true, "title":"' . my_caption('global_cancelled_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url('user/pay_subscription_list') .'"}';
					}
					else {  // fail to cancel
						$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"CallBack"}';
					}
				}
				elseif ($action == 'resume') {
					$resumedSubscriptionArray = $this->m_payment->resumeSubscription($subscriptionArray);
					if ($resumedSubscriptionArray['success']) {
						$this->db->where('gateway_identifier', $rs->gateway_identifier)->update('payment_subscription', array('status'=>'active'));
						$result = '{"result":true, "title":"' . my_caption('global_resumed_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url('user/pay_subscription_list') .'"}';
					}
					else {
						$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"CallBack"}';
					}
				}
				else {  // unrecignized command
					$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"CallBack"}';
				}
			}
		}
		else {  //no entry
			$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('global_no_entries_found') . '", "redirect":"CallBack"}';
		}
		echo my_esc_html($result);
	}



	public function pay_list() {
		my_load_view($this->setting->theme, 'User/pay_list');
	}



	public function pay_once() {
		my_check_demo_mode();  //check if it's in demo mode
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$quantity = html_purify(my_get('quantity'));
		(!is_int($quantity)) ? $quantity = 1 : $quantity = abs($quantity);
		$query = $this->db->where('ids', my_uri_segment(4))->where('enabled', 1)->group_start()->where('type', 'top-up')->or_where('type', 'purchase')->group_end()->get('payment_item', 1);
		if (!$query->num_rows()) {
			echo my_caption('global_no_entries_found');
		}
		else {
			$rs = $query->row();
			$this->check_pay_condition($rs);
			$this->load->model('user_model');
			$item_price = $rs->item_price;
			if (my_coupon_module() && my_uri_segment(5) != '') {  //coupon enabled, calculate the new price if necessary
				$coupon_array = my_coupon_check($rs->ids, my_uri_segment(5));
				($coupon_array['result']) ? $item_price = $coupon_array['amount'] : null;
			}
			$payment_setting_array = json_decode($this->setting->payment_setting, 1);
			if (!empty($payment_setting_array['tax_rate'])) {
				$tax = $payment_setting_array['tax_rate'];
				if (strtolower($rs->item_currency) == 'jpy') {
					($tax) ? $item_price = round($item_price * (1 + $tax/100), 2) : null;  //tax
				}
				else {
					($tax) ? $item_price = number_format(round($item_price * (1 + $tax/100), 2), 2) : null;  //tax
				}
			}
			else {
				$tax = 0;
			}
			if (my_uri_segment(3) == 'stripe' && $payment_setting_array['stripe_one_time_enabled']) {
				\Stripe\Stripe::setApiKey($payment_setting_array['stripe_secret_key']);
				try {
					$stripe_amount = $item_price * 100;
					(strtolower($rs->item_currency) == 'jpy') ? $stripe_amount = intval($item_price) : null;   // for Japanese Yen only
					$checkout_session = \Stripe\Checkout\Session::create([
					  'success_url' => base_url('/user/pay_success/{CHECKOUT_SESSION_ID}'),
					  'cancel_url' => base_url('/user/pay_cancel/{CHECKOUT_SESSION_ID}'),
					  'payment_method_types' => ['card'],
					  'mode' => 'payment',
					  'line_items' => [[
					    'name' => $rs->item_name,
						'description' => $rs->item_description,
						'amount' => $stripe_amount,
						'currency' => strtolower($rs->item_currency),
						'quantity' => 1,
					  ]]
					]);
					$data['publishable_key'] = $payment_setting_array['stripe_publishable_key'];
					$data['checkout_session'] = $checkout_session['id'];
					$this->user_model->payment_log('stripe', $checkout_session['id'], $rs, $item_price, $tax);
					my_load_view($this->setting->theme, 'User/pay_stripe', $data); //redirect to the payment page
				}
				catch (\Exception $e) {
					log_message('error', $e->getMessage());
					die(my_caption('payment_exception'));
				}
			}
			elseif (my_uri_segment(3) == 'paypal' && $payment_setting_array['paypal_one_time_enabled']) {
				  $paypal_clientid = $payment_setting_array['paypal_client_id'];
				  $paypal_secret = $payment_setting_array['paypal_secret'];
				  ($payment_setting_array['type'] == 'sandbox') ? $paypal_environment = new \PayPalCheckoutSdk\Core\SandboxEnvironment($paypal_clientid, $paypal_secret) : $paypal_environment = new \PayPalCheckoutSdk\Core\ProductionEnvironment($paypal_clientid, $paypal_secret);
				  $paypal_client = new \PayPalCheckoutSdk\Core\PayPalHttpClient($paypal_environment);
				  $paypal_request = new \PayPalCheckoutSdk\Orders\OrdersCreateRequest();
				  $paypal_request->prefer('return=representation');
				  $paypal_request->body = [
				    'intent' => 'CAPTURE',
                    'purchase_units' => [[
					  'reference_id' => my_random(),
                      'amount' => [
					    'value' => $item_price,
                        'currency_code' => strtolower($rs->item_currency)
                      ]
                    ]],
                    'application_context' => [
					  'cancel_url' => base_url('/user/pay_cancel/'),
                      'return_url' => base_url('/user/pay_success/')
                    ]
				  ];
				  try {
					  $paypal_response = $paypal_client->execute($paypal_request);
					  $paypal_order_result = $paypal_response->result;
					  $this->user_model->payment_log('paypal', $paypal_order_result->id, $rs, $item_price, $tax);
					  header('Location: ' . $paypal_order_result->links[1]->href);
				  }
				  catch(\Exception $e) {
					  die(my_caption('payment_exception'));
				  }
			}
			elseif (my_uri_segment(3) == 'gateway') { //other payment gateways, it only works with the Payment Gateway Add-on
				$this->load->library('m_payment');
				$this->m_payment->pay('one-time', $rs, $item_price, $tax);  //item_price depends on coupon, tax denpends on coupon and tax setting, so item_price is different from the item's original price
			}
			else {
				echo my_caption('payment_payment_gateway_unavailable');
			}
		}
	}



	public function pay_recurring() {
		my_check_demo_mode();  //check if it's in demo mode
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$quantity = html_purify(my_get('quantity'));
		(!is_int($quantity)) ? $quantity = 1 : $quantity = abs($quantity);
		$query = $this->db->where('ids', my_uri_segment(4))->where('enabled', 1)->where('type', 'subscription')->get('payment_item', 1);
		if (!$query->num_rows()) {
			echo my_caption('global_no_entries_found');
		}
		else {
			$rs = $query->row();
			$this->check_pay_condition($rs);
			$this->load->model('user_model');
			$item_price = $rs->item_price;
			if (my_coupon_module() && my_uri_segment(5) != '') {  //coupon enabled, calculate the new price if necessary
				$coupon_array = my_coupon_check($rs->ids, my_uri_segment(5));
				($coupon_array['result']) ? $item_price = $coupon_array['amount'] : null;
			}
			$payment_setting_array = json_decode($this->setting->payment_setting, 1);
			$item_stuff_array = json_decode($rs->stuff_setting, 1);
			if (!empty($payment_setting_array['tax_rate'])) {
				$tax = $payment_setting_array['tax_rate'];
				if (strtolower($rs->item_currency) == 'jpy') {
					($tax) ? $item_price = round($item_price * (1 + $tax/100), 2) : null;  //tax
				}
				else {
					($tax) ? $item_price = number_format(round($item_price * (1 + $tax/100), 2), 2) : null;  //tax
				}
			}
			else {
				$tax = 0;
			}
			if (my_uri_segment(3) == 'stripe' && $payment_setting_array['stripe_recurring_enabled']) {
				\Stripe\Stripe::setApiKey($payment_setting_array['stripe_secret_key']);
				//There are 3 steps in the subscription process
				//step 1: try to retrieve product from stripe, Create one if it doesn't exist, The product should exist at the end of this step.
				(!array_key_exists('stripe_product_id', $item_stuff_array)) ? $product_id = 'foo' : $product_id = $item_stuff_array['stripe_product_id'];
				try {
					$product = \Stripe\Product::retrieve($product_id);
					($product->active) ? $product_id = $product->id : null;
				}
				catch (\Exception $e) {
					$product_id = 'foo';
                }
				if ($product_id == 'foo') { //create product at stripe if product doesn't exist
					try {
						$product = \Stripe\Product::create(['name'=>$rs->item_name]);
						$product_id = $product->id;
						my_save_payment_item_stuff_setting(my_uri_segment(4), 'stripe_product_id', $product_id);
					}
					catch (\Exception $e) {
						die(my_caption('payment_exception'));
					}
				}
				//step 2: try to retrieve the price related to the product from stripe, Create one if it doesn't exist, The price should exist at the end of this step.
				(!array_key_exists('stripe_price_id', $item_stuff_array)) ? $price_id = 'foo' : $price_id = $item_stuff_array['stripe_price_id'];
				try {
					$price = \Stripe\Price::retrieve($price_id);
					($price->unit_amount == $item_price * 100) ? $price_id = $price->id : $price_id = 'foo';
				}
				catch (\Exception $e) {
					$price_id = 'foo';
				}
				if ($price_id == 'foo') { //create price at stripe if price doesn't exist
					try {
						$stripe_amount = $item_price * 100;
						(strtolower($rs->item_currency) == 'jpy') ? $stripe_amount = intval($item_price) : null;   // for Japanese Yen only
						$price = \Stripe\Price::create([
						  'unit_amount' => $stripe_amount,
						  'currency' => strtolower($rs->item_currency),
						  'recurring' => [
						    'interval' => $rs->recurring_interval,
							'interval_count' => $rs->recurring_interval_count
						  ],
						  'product' => $product_id
						]);
						$price_id = $price->id;
						my_save_payment_item_stuff_setting(my_uri_segment(4), 'stripe_price_id', $price_id);
					}
					catch (\Exception $e) {
						log_message('error', $e->getMessage());
						die(my_caption('payment_exception'));
					}
				}
				//step 3: create subscription and redirect to stripe checkout
				try {
					$checkout_session = \Stripe\Checkout\Session::create([
					  'payment_method_types' => ['card'],
					  'line_items' => [[
					    'price' => $price_id,
						'quantity' => 1
					  ]],
					  'mode' => 'subscription',
					  'success_url' => base_url('/user/pay_success/{CHECKOUT_SESSION_ID}'),
					  'cancel_url' => base_url('/user/pay_cancel/{CHECKOUT_SESSION_ID}'),
					]);
				}
				catch (\Exception $e) {
					die(my_caption('payment_exception'));
				}
				$data['publishable_key'] = $payment_setting_array['stripe_publishable_key'];
				$data['checkout_session'] = $checkout_session['id'];
				$this->user_model->payment_log('stripe', $checkout_session['id'], $rs, $item_price, $tax);
				my_load_view($this->setting->theme, 'User/pay_stripe', $data); //redirect to payment page
			}
			elseif (my_uri_segment(3) == 'paypal' && $payment_setting_array['paypal_recurring_enabled']) {
				$this->load->library('m_paypal');
				//handle product
				(!array_key_exists('paypal_product_id', $item_stuff_array)) ? $product_id = 'foo' : $product_id = $item_stuff_array['paypal_product_id'];
				if ($product_id != 'foo') {
					$check_result_array = $this->m_paypal->retrieveProduct($product_id);
					(!$check_result_array['success']) ? $product_id = 'foo': null;
				}
				if ($product_id == 'foo') {  //need to create a new product
					$check_result_array = $this->m_paypal->newProduct(array('name'=>$rs->item_name));
					if ($check_result_array['success']) {
						$product_id = $check_result_array['product_id'];
						my_save_payment_item_stuff_setting(my_uri_segment(4), 'paypal_product_id', $product_id); //new generated, save the product_id
					}
				}
				//handle plan
				(!array_key_exists('paypal_plan_id', $item_stuff_array)) ? $plan_id = 'foo' : $plan_id = $item_stuff_array['paypal_plan_id'];
				if ($plan_id != 'foo') {
					$check_result_array = $this->m_paypal->retrievePlan($plan_id);
					if ($check_result_array['success']) {
						($check_result_array['plan_price'] != $item_price) ? $plan_id = 'foo' : null;
					}
					else {
						$plan_id = 'foo';
					}
				}
				if ($plan_id == 'foo') {  //need to create a new plan
				    $planArray = array(
					  'product_id' => $product_id,
					  'product_name' => $rs->item_name,
					  'interval_unit' => strtoupper($rs->recurring_interval),
					  'interval_count' => $rs->recurring_interval_count,
					  'price' => $item_price,
					  'currency' => strtoupper($rs->item_currency)
					);
					$check_result_array = $this->m_paypal->newPlan($planArray);
					if ($check_result_array['success']) {
						$plan_id = $check_result_array['plan_id'];
						my_save_payment_item_stuff_setting(my_uri_segment(4), 'paypal_plan_id', $plan_id);  //new generated, save the plan_id
					}
				}
				//submit subscription
				$subscriptionArray = array(
				  'plan_id' => $plan_id,
				  'return_url' => base_url('/user/pay_success/'),
				  'cancel_url' => base_url('/user/pay_cancel/')
				);
				$subscribe_result_array = $this->m_paypal->newSubscription($subscriptionArray);
				if (!array_key_exists('subscription_id', $subscribe_result_array)) {
					echo 'Unable to handle the payment, Please check the api credentials or network connection.';
				}
				else {
					$this->user_model->payment_log('paypal', $subscribe_result_array['subscription_id'], $rs, $item_price, $tax);
					redirect($subscribe_result_array['redirectURL']);
				}
			}
			elseif (my_uri_segment(3) == 'gateway') { //other payment gateways, it only works with the Payment Gateway Add-on
				$this->load->library('m_payment');
				$this->m_payment->pay('recurring', $rs, $item_price, $tax);  //item_price depends on coupon, tax denpends on coupon and tax setting, so item_price is different from the item's original price
			}
			else {
				echo my_caption('payment_payment_gateway_unavailable');
			}
		}
	}



	public function pay_free() {
		$query = $this->db->where('ids', my_uri_segment(3))->where('enabled', 1)->where('item_price', 0)->get('payment_item', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->purchase_limit != '0') {  //limit the purchase times
				if (my_check_purchase_by_item($rs->ids)) { //already bought
					$this->session->set_flashdata('flash_danger', my_caption('payment_purchase_times_limit'));
					redirect('user/pay_now');
				}
				if (my_check_subscription_by_item($rs->ids, TRUE)) { //already subscribe
					$this->session->set_flashdata('flash_danger', my_caption('payment_purchase_times_limit'));
					redirect('user/pay_now');
				}
			}
			$this->load->model('user_model');
			$this->user_model->save_free_package($_SESSION['user_ids'], $rs);
			redirect('user/pay_success/');
		}
		else {  //package is not existed
			echo my_caption('global_no_entries_found');
		}

	}



	public function pay_success() {
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$this->session->set_flashdata('flash_success', my_caption('payment_payment_success'));
		$this->db->where('gateway_identifier', my_uri_segment(3))->or_where('gateway_identifier', my_get('token'))->or_where('gateway_identifier', my_get('subscription_id'))->update('payment_log', array('redirect_status'=>'success'));
		redirect(base_url('user/pay_now'));
	}



	public function pay_cancel() {
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$this->session->set_flashdata('flash_warning', my_caption('payment_payment_cancel'));
		$this->db->where('gateway_identifier', my_uri_segment(3))->or_where('gateway_identifier', my_get('token'))->or_where('gateway_identifier', my_get('subscription_id'))->update('payment_log', array('redirect_status'=>'cancel'));
		redirect(base_url('user/pay_now'));
	}



	public function remove_self() {
		my_check_demo_mode();  //check if it's in demo mode
		$gdpr_array = json_decode($this->setting->gdpr, TRUE);
		if (!$_SESSION['is_admin'] && $gdpr_array['allow_remove']) {
			my_remove_user($_SESSION['user_ids']);
			redirect(base_url('generic/sign_out'));
		}
		else {
			redirect('user/my_profile');
		}
	}



	public function gdpr_export() {
		$rs = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1)->row();
		$export_arr = array(
		  'identifier' => $rs->ids,
		  'emailAddress' => $rs->email_address,
		  'firstName' => $rs->first_name,
		  'lastName' => $rs->last_name,
		  'company' => $rs->company,
		  'addressLine1' => $rs->address_line_1,
		  'addressLine2' => $rs->address_line_2,
		  'city' => $rs->city,
		  'state' => $rs->state,
		  'zipCode' => $rs->zip_code,
		  'phoneNumber' => $rs->phone,
		  'createdTime' => $rs->created_time,
		  'lastUpdateTime' => $rs->update_time,
		  'agreementTime' => $rs->created_time,
		  'lastOnlineTime' => $rs->online_time
		);
		$this->load->helper('download');
		force_download('myData.txt', json_encode($export_arr, JSON_PRETTY_PRINT));
	}



	//This method is used to generate invoice
	public function invoice() {
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('ids', my_uri_segment(3))->get('payment_log', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if (!$rs->generate_invoice) {
				$this->session->set_flashdata('flash_warning', my_caption('payment_invoice_not_applicable'));
				redirect(base_url('user/pay_list'));
			}
			else {
				$rs_item = $this->db->where('ids', $rs->item_ids)->get('payment_item', 1)->row();
				$rs_user = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1)->row();
				$issued_to = my_user_setting($_SESSION['user_ids'], 'company');
				if (empty($issued_to)) {
					$issued_to = $_SESSION['full_name'];
					$agency = FALSE;
				}
				else {
					$agency = TRUE;
				}
				$data = array(
				  'agency' => $agency,
				  'invoice_no' => strtoupper(substr($_SESSION['user_ids'], -10) . strtotime($rs->created_time)),
				  'generated_date' => my_conversion_from_server_to_local_time($rs->callback_time, $this->user_timezone, $this->user_date_format),
				  'issued_to' => $issued_to,
				  'address_line_1' => $rs_user->address_line_1,
				  'address_line_1' => $rs_user->address_line_2,
				  'city' => $rs_user->city,
				  'state' => $rs_user->state,
				  'country' => $rs_user->country,
				  'zip_code' => $rs_user->zip_code,
				  'payment_method' => ucfirst($rs->gateway),
				  'transaction_no' => substr($rs->gateway_identifier, -10),
				  'item' => $rs->item_name,
				  'currency' => $rs->currency,
				  'price' => $rs->price,
				  'quantity' => $rs->quantity,
				  'discount' => $rs->coupon_discount,
				  'tax_rate' => $rs->tax . '%',
				  'tax' => ($rs->price - $rs->coupon_discount) * ($rs->tax/100),
				  'amount' => $rs->amount  //currently only support one item so it's same as amount
				);
				if ($agency) {
					$data['company_no'] = $rs_user->company_number;
					$data['tax_no'] = $rs_user->tax_number;
				}
				$invoice_setting = json_decode($this->setting->invoice_setting, TRUE);
				if ($invoice_setting['invoice_format'] == 'html') {
					my_load_view($this->setting->theme, 'User/invoice', $data);
				}
				else {
					$html = my_load_view($this->setting->theme, 'User/invoice', $data, TRUE);
					$dompdf = new Dompdf\Dompdf();
					$invoice_css = '<style>' . file_get_contents(FCPATH . 'assets/themes/' . $this->setting->theme . '/css/invoice_default.css') . '</style>';
					$dompdf->loadHtml($invoice_css . $html);
					$dompdf->setPaper('A4', 'portrait');
					$dompdf->render();
					$dompdf->stream(my_uri_segment(3) . '.pdf', array('Attachment' => 0));
				}
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}

	// List youtube
	public function youtube() {
		my_load_view($this->setting->theme, 'User/youtube_list');
	}


// New youtube
public function youtube_new() {
	$data['catalog_options'] = my_get_catalog('support_ticket');
	my_load_view($this->setting->theme, 'User/youtube_new', $data);
}

public function frontent_lang() {
	my_load_view($this->setting->theme, 'User/my_youtube');
}
	// List ticket
	public function ticket() {
		my_load_view($this->setting->theme, 'User/ticket_list');
	}



	// New ticket
	public function ticket_new() {
		$data['catalog_options'] = my_get_catalog('support_ticket');
		my_load_view($this->setting->theme, 'User/ticket_new', $data);
	}



	// New ticket action
	public function ticket_new_action() {
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('ticket_catalog', my_caption('global_catalog'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('ticket_priority', my_caption('support_ticket_priority'), 'trim|required|integer');
		$this->form_validation->set_rules('ticket_subject', my_caption('support_ticket_subject'), 'trim|required|max_length[255]');
		$this->form_validation->set_rules('ticket_description', my_caption('support_ticket_description'), 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$data['catalog_options'] = my_get_catalog('support_ticket');
			my_load_view($this->setting->theme, 'User/ticket_new', $data);
		}
		else {
			$this->load->model('user_model');
			$res_ids = $this->user_model->save_ticket();
			$this->session->set_flashdata('flash_success', my_caption('support_ticket_submit_success'));
			redirect(base_url('user/ticket_view/' . $res_ids));
		}
	}



	// View ticket
	public function ticket_view() {
		$res = $this->get_ticket(my_uri_segment(3));
		if ($res == FALSE) {
			echo my_caption('global_no_entries_found');
		}
		else {
			if ($res['rs']->main_status == 1) {
				$this->db->where('ids', my_uri_segment(3))->where('read_status', 0)->update('ticket', array('read_status'=>1));  //update the follow-up ticket's read status
			}
			my_load_view($this->setting->theme, 'Generic/view_ticket', $res);
		}
	}



	// Followup ticket
	public function ticket_view_action() {
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('ticket_reply', my_caption('global_reply'), 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$res = $this->get_ticket(my_post('ids_father'));
			my_load_view($this->setting->theme, 'Generic/view_ticket', $res);
		}
		else {
			$query = $this->db->where('ids', my_post('ids_father'))->where('user_ids', $_SESSION['user_ids'])->get('ticket', 1);
			if ($query->num_rows()) {
				$this->load->model('user_model');
				$ids = $this->user_model->update_ticket('user_update');
				$this->session->set_flashdata('flash_success', my_caption('support_ticket_notice_reply_saved'));
				redirect(base_url('user/ticket_view/' . $ids));
			}
			else {
				echo my_caption('global_no_entries_found');
			}
		}
	}



	public function ticket_close_action() {
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$this->db->where('ids', my_uri_segment(3))->where('main_status!=', 0)->where('user_ids', $_SESSION['user_ids'])->update('ticket', array('main_status'=>0, 'read_status'=>1, 'updated_time'=>my_server_time()));
		echo '{"result":true, "title":"", "text":"'. my_caption('support_ticket_notice_close') . '", "redirect":"' . base_url('user/ticket_view/' . my_uri_segment(3)) . '"}';
	}



	public function ticket_rating_action() {
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->where('user_ids', $_SESSION['user_ids'])->get('ticket', 1);
		if ($query->num_rows()) {
			(my_uri_segment(4) != '1' && my_uri_segment(4) != '2' && my_uri_segment(4) != '3') ? $rating = 3 : $rating = my_uri_segment(4);
			$this->db->where('ids', my_uri_segment(3))->update('ticket', array('rating'=>$rating));
		}
		echo '{"result":true, "title":"", "text":"'. my_caption('support_ticket_notice_rating_saved') . '", "redirect":"' . base_url('user/ticket_view/' . my_uri_segment(3)) . '"}';
	}



	// reset the user's api key
	public function reset_api_key() {
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$this->db->where('ids', $_SESSION['user_ids'])->update('user', array('api_key'=>my_random()));
		echo '{"result":true, "title":"", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"'. base_url('user/my_profile') . '"}';
	}



	// callback for form_validation
	public function check_old_password($old_password) {
		$query = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1);
		if ($query->num_rows()) {
			if (password_verify($old_password, $query->row()->password)) {
				return TRUE;
			}
			else {
				my_throttle_log($_SESSION['user_ids']);
				$this->form_validation->set_message('check_old_password', my_caption('cp_old_password_error'));
				return FALSE;
			}
		}
		else {
			my_throttle_log($_SESSION['user_ids']);
			$this->form_validation->set_message('check_old_password', my_caption('cp_old_password_error'));
			return FALSE;
		}
	}



	// callback for form_validation
	public function email_duplicated_check($email_address, $ids) {
		if (my_duplicated_check('user', array('email_address'=>$email_address), $ids)) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('email_duplicated_check', my_caption('mp_email_taken'));
			return FALSE;
		}
	}


	// callback for form_validation
	public function username_duplicated_check($username, $ids) {
		if (my_duplicated_check('user', array('username'=>$username), $ids)) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('username_duplicated_check', my_caption('mp_username_taken'));
			return FALSE;
		}
	}


    public function avatar_upload() {
		$this->load->library('m_upload');
		$this->m_upload->set_upload_path('/' . '/' . $this->config->item('my_upload_directory') . 'avatar/');
		$this->m_upload->set_allowed_types('png|gif|jpg|jpeg');
		$this->m_upload->set_file_name($_SESSION['user_ids']);
		//$this->my_upload->set_remove_file();
		$res = $this->m_upload->upload_done();
		if ($res['status']) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('avatar_upload', $res['error']);
			return FALSE;
		}
	}



	protected function get_ticket($ids) {
		$query = $this->db->where('ids', $ids)->where('user_ids', $_SESSION['user_ids'])->where('ids_father', 0)->get('ticket', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();  //one record only
			$data['rs_follow'] = $this->db->where('ids_father', $ids)->order_by('created_time', 'asc')->get('ticket')->result();  //full recordset
			return $data;
		}
		else {
			return FALSE;
		}
	}



	protected function check_pay_condition($rs) {
		if ($rs->purchase_limit != '0') {  //limit the purchase times
			if (my_check_purchase_by_item($rs->ids) || my_check_subscription_by_item($rs->ids)) { //already bought/subscribed
				$this->session->set_flashdata('flash_danger', my_caption('payment_purchase_times_limit'));
				redirect('user/pay_now');
			}
		}
		if ($rs->access_condition != '0') {  //limit the condition of purchase/subscription, if someone wants to buy this item, he should buy the basic one first.
			$access_condition_array = explode(',', $rs->access_condition);
			if ($access_condition_array[0] != '0') {
				$check_result = FALSE;
				foreach ($access_condition_array as $condition) {
					if (my_check_purchase_by_item($condition) || my_check_subscription_by_item($condition)) {  //purchased/subscribed detected
						$check_result = TRUE;
						break;
					}
				}
				if (!$check_result) {
					$this->session->set_flashdata('flash_danger', my_caption('payment_purchase_violate_condition'));
					redirect('user/pay_now');
				}
			}
		}
		return TRUE;
	}

}
?>