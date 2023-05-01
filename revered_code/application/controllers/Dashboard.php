<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
    public function __construct() {
		parent::__construct();
		
    }
	
	
	
	public function index() {
		$rs = $this->db->order_by('id', 'desc')->get('user', 8)->result();
		$data['rs_user'] = $rs;
		$data += $this->dashboard_statistics();
		my_load_view($this->setting->theme, 'dashboard', $data);
		
	}
	
	
	
	public function search_action() {
		redirect(base_url('admin/list_user/search' . '/' . my_post('keyword')));
	}
	
	
	
	protected function dashboard_statistics() {
		$statistics['users_amount'] = $this->db->count_all_results('user');
		$statistics['user_pending_amount'] = $this->db->where('status', 0)->count_all_results('user');
		$statistics['user_today_amount'] = $this->db->where('created_time>=', my_conversion_from_local_to_server_time(date('Y-m-d'), $this->user_timezone, 'Y-m-d H:i:s'))->count_all_results('user');
		$statistics['user_online_amount'] = $this->db->where('online', 1)->count_all_results('user');
		
		$rs = $this->db->where('type', 'signup_last_six_days')->get('statistics', 1)->row();
		$signup_last_six_days  = json_decode($rs->value, TRUE);
		$signup_last_six_days_date = '';
		$signup_last_six_days_amount = '';
		foreach ($signup_last_six_days as $date=>$amount) {
			$signup_last_six_days_date .= my_conversion_from_server_to_local_time($date, $this->user_timezone, $this->user_date_format) . ',';
			$signup_last_six_days_amount .= $amount . ',';
		}
		$signup_last_six_days_date .= 'Today';
		$signup_last_six_days_amount .= $statistics['user_today_amount'];
		$statistics['signup_last_six_days_date'] = $signup_last_six_days_date;
		$statistics['signup_last_six_days_amount'] = $signup_last_six_days_amount;
		
		return $statistics;
	}
	

}
?>