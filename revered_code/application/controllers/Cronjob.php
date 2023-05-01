<?php
defined('BASEPATH') OR exit('No direct script access allowed');
set_time_limit(1800);

class Cronjob extends CI_Controller {
	
    public function __construct() {
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
    }
	
	
	
	public function index() {

	}
	
	
	
	public function daily() {
		//execute only on the first day of the month
		if(date('j') == '1' || date('j') == '01') {
			//reset the purchased item's usage
			$query_purchased = $this->db->where('auto_renew', 1)->get('payment_purchased');
			if ($query_purchased->num_rows()) {
				$rs_purchased = $query_purchased->result();
				foreach ($rs_purchased as $purchased) {
					$query_item = $this->db->where('ids', $purchased->item_ids)->get('payment_item', 1);
					if ($query_item->num_rows()) {
						$rs_item = $query_item->row();
						$update_data = array(
						  'stuff' => $rs_item->stuff_setting,
						  'used_up' => 0
						);
						$this->db->where('ids', $purchased->ids)->update('payment_purchased', $update_data);
					}
				}
			}
		}
		
		//handle statistics
		$old_signup_last_six_days_array = json_decode($this->db->where('type', 'signup_last_six_days')->get('statistics', 1)->row()->value, TRUE);
		$today = my_server_time();
		$yeaterday = date('Y-m-d', strtotime('-1 day', strtotime($today)));
		for ($i = -6; $i <= -2; $i++) {
			$previous_date = date('Y-m-d', strtotime($i . ' day', strtotime($today)));
			if (isset($old_signup_last_six_days_array[$previous_date])) {
				$new_signup_last_six_days_array[$previous_date] = $old_signup_last_six_days_array[$previous_date];
			}
			else {
				$cnt = $this->db->where('created_time>=', $previous_date)->where('created_time<', date('Y-m-d', strtotime('+1 day', strtotime($previous_date))))->count_all_results('user');
				$new_signup_last_six_days_array[$previous_date] = $cnt;
			}
		}
		$cnt_yeaterday = $this->db->where('created_time>=', $yeaterday)->where('created_time<', my_server_time($this->config->item('time_reference'), 'Y-m-d'))->count_all_results('user');
		$new_signup_last_six_days_array[$yeaterday] = $cnt_yeaterday;
		$this->db->update('statistics', array('value'=>json_encode($new_signup_last_six_days_array)));
		
		//handle subscription of manual adding
		$query_subscription = $this->db->where('end_time<', my_server_time('UTC', 'Y-m-d'))->where('payment_gateway=', 'manual')->where('status', 'active')->get('payment_subscription');
		if ($query_subscription->num_rows()) {
			$rs_subscription = $query_subscription->result();
			foreach ($rs_subscription as $row) {
				$rs_subscription_item = $this->db->where('ids', $row->item_ids)->get('payment_item', 1)->row();
				$res = my_user_reload($row->user_ids, 'Cut', $rs_subscription_item->item_currency, $rs_subscription_item->item_price);
				$res_array = json_decode($res, 1);
				if ($res_array['result']) {
					$end_date = date('Y-m-d H:i:s', strtotime($rs_subscription_item->recurring_interval_count . ' ' . $rs_subscription_item->recurring_interval, strtotime($row->end_time)));
					$this->db->where('ids', $row->ids)->update('payment_subscription', array('end_time'=>$end_date, 'updated_time'=>my_server_time()));
				}
			}
		}

		//handle yoomoney subscription
		$query_yoomoney = $this->db->where('end_time<', my_server_time('UTC', 'Y-m-d'))->where('payment_gateway', 'yoomoney')->where('status', 'active')->get('payment_subscription');
		if ($query_yoomoney->num_rows()) {
			$rs_yoomoney = $query_yoomoney->result();
			$this->load->library('m_yoomoney');
			foreach ($rs_yoomoney as $row) {
				$query_log = $this->db->where('gateway_identifier', $row->gateway_identifier)->where('callback_status', 'success')->get('payment_log', 1);
				if ($query_log->num_rows()) {
					$rs_log = $query_log->row();
					$recurringArray = array(
					  'amount' => $rs_log->amount,
					  'currency' => $rs_log->currency,
					  'paymentMethodID' => $row->gateway_identifier,
					  'name' => $rs_log->item_name
					);
					$recurringProcessedArray = $this->m_yoomoney->recurringProcessing($recurringArray);
					if ($recurringProcessedArray['success']) { //charge successfully
						$end_time = date('Y-m-d H:i:s', strtotime(my_get_payment_item($row->item_ids, 'recurring_interval_count') . ' ' . my_get_payment_item($row->item_ids, 'recurring_interval'), strtotime($row->end_time)));
						$this->db->where('gateway_identifier', $row->gateway_identifier)->update('payment_subscription', array('end_time'=>$end_time));
					}
				}
			}
		}
		
		//handle free subscription, subscribe automatically
		$query_subscription = $this->db->where('end_time<', my_server_time('UTC', 'Y-m-d'))->where('gateway_identifier=', '')->where('status', 'active')->where('auto_renew', 1)->get('payment_subscription');  //gateway_identifier=='' means free
		if ($query_subscription->num_rows()) {
			$rs_subscription = $query_subscription->result();
			foreach ($rs_subscription as $row) {
				$rs_subscription_item = $this->db->where('ids', $row->item_ids)->get('payment_item', 1)->row();
				$end_date = date('Y-m-d H:i:s', strtotime($rs_subscription_item->recurring_interval_count . ' ' . $rs_subscription_item->recurring_interval, strtotime($row->end_time)));
				$update_array = array(
				  'end_time' => $end_date,
				  'updated_time' => my_server_time(),
				  'stuff' => $rs_subscription_item->stuff_setting,
				  'used_up' => 0
				);
				$this->db->where('ids', $row->ids)->update('payment_subscription', $update_array);
			}
		}
		
		//handle subscription that is going to expired
		$this->db->where('end_time<', my_server_time('UTC', 'Y-m-d'))->group_start()->where('status', 'active')->or_where('status', 'pending_cancellation')->group_end()->update('payment_subscription', array('status'=>'expired', 'updated_time'=>my_server_time()));

		//handle ticket
		$ticket_setting_array = json_decode(my_global_setting('ticket_setting'), 1);
		$close_rule = intval($ticket_setting_array['close_rule']);
		if ($close_rule > 0) {  //need to close ticket automatically
			$this->db->where('main_status', 1)->where('updated_time<', date('Y-m-d H:i:s', strtotime('-' . 1440 * $close_rule . ' minutes', now($this->config->item('time_reference')))))->update('ticket', array('main_status'=>'0', 'read_status'=>'1'));
		}
		
		//handle expired uploaded file
		$query = $this->db->where('temporary_ids!=', '')->where('created_time<', date('Y-m-d H:i:s', strtotime('-1440 minutes', now($this->config->item('time_reference')))))->get('file_manager');
		if ($query->num_rows()) {
			$rs = $query->result();
			foreach ($rs as $row) {
				$file_path = $this->config->item('my_upload_directory') . 'temporary/' . $row->ids . '.' . $row->file_ext;
				try {unlink($file_path);} catch(\Exception $e) {}
				$this->db->where('id', $row->id)->delete('file_manager');
			}
		}
		
		echo 'success';
	}
	
	
	
	public function quarterly() {
		$query = $this->db->where('online', 1)->where('online_time<', date('Y-m-d H:i:s', strtotime('-15 minutes', now($this->config->item('time_reference')))))->get('user');
		if ($query->num_rows()) {
			$rs = $query->result();
			foreach ($rs as $row) {
				$this->db->where('id', $row->id)->update('user', array('online'=>0));
			}
		}
		echo 'success';
	}
	
	
	
}
?>