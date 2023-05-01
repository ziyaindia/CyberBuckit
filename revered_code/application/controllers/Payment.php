<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller {
	
    public function __construct() {
		parent::__construct();
		
    }
	
	
	
	public function index() {
		redirect(base_url('files/file_manager'));
	}
	
	
	
	public function setting() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Addons/payment_setting');
		
		
	}
	
	
	
	public function setting_action() {
		my_check_demo_mode();  //check if it's in demo mode
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		(my_post('addons_pg_razorpay_one_time') == '1') ? $addons_pg_razorpay_one_time = '1' : $addons_pg_razorpay_one_time = 0;
		(my_post('addons_pg_razorpay_recurring') == '1') ? $addons_pg_razorpay_recurring = '1' : $addons_pg_razorpay_recurring = 0;
		(my_post('addons_pg_paystack_one_time') == '1') ? $addons_pg_paystack_one_time = '1' : $addons_pg_paystack_one_time = 0;
		(my_post('addons_pg_paystack_recurring') == '1') ? $addons_pg_paystack_recurring = '1' : $addons_pg_paystack_recurring = 0;
		(my_post('addons_pg_yoomoney_one_time') == '1') ? $addons_pg_yoomoney_one_time = '1' : $addons_pg_yoomoney_one_time = 0;
		(my_post('addons_pg_yoomoney_recurring') == '1') ? $addons_pg_yoomoney_recurring = '1' : $addons_pg_yoomoney_recurring = 0;
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$payment_setting_array['addon_gateway'] = my_post('addons_pg_active');
		$payment_setting_array['razorpay_name'] = 'Razorpay';
		$payment_setting_array['razorpay_one_time_enabled'] = $addons_pg_razorpay_one_time;
		$payment_setting_array['razorpay_recurring_enabled'] = $addons_pg_razorpay_recurring;
		$payment_setting_array['razorpay_key_id'] = my_post('addons_pg_razorpay_key_id');
		$payment_setting_array['razorpay_key_secret'] = my_post('addons_pg_razorpay_key_secret');
		$payment_setting_array['razorpay_webhook_secret'] = my_post('addons_pg_razorpay_webhook_secret');
		$payment_setting_array['paystack_name'] = 'Paystack';
		$payment_setting_array['paystack_one_time_enabled'] = $addons_pg_paystack_one_time;
		$payment_setting_array['paystack_recurring_enabled'] = $addons_pg_paystack_recurring;
		$payment_setting_array['paystack_secret_key'] = my_post('addons_pg_paystack_secret_key');
		$payment_setting_array['paystack_public_key'] = my_post('addons_pg_paystack_public_key');
		$payment_setting_array['yoomoney_name'] = 'Yoomoney';
		$payment_setting_array['yoomoney_one_time_enabled'] = $addons_pg_yoomoney_one_time;
		$payment_setting_array['yoomoney_recurring_enabled'] = $addons_pg_yoomoney_recurring;
		$payment_setting_array['yoomoney_shop_id'] = my_post('addons_pg_yoomoney_shop_id');
		$payment_setting_array['yoomoney_secret_key'] = my_post('addons_pg_yoomoney_secret_key');
		$this->db->update('setting', array('payment_setting'=>json_encode($payment_setting_array)));
		$this->session->set_flashdata('flash_success', my_caption('payment_update_success'));
		redirect('payment/setting');
	}
	
	
	
}
?>