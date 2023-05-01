<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
   
   public $setting;
   public $user_timezone, $user_date_format, $user_time_format, $user_dtformat, $user_language, $user_avatar;
   public $user_permission_list;
   public $new_notification_tag = 0;
   public $rs_notification;
   //public $extra_db; // connect a extra db if it's necessary
   
   
   function __construct() {
	   
	   parent::__construct();
	   
	   // check whether user signed in
	   my_check_signin();
	   
	   // set php script timezone
	   date_default_timezone_set($this->config->item('time_reference'));	   
	   
	   //get setting of the system, stored in table setting
	   $this->setting = my_global_setting('all_fields');
	   
       // check maintenance mode
	   ($this->setting->maintenance_mode && !$_SESSION['is_admin']) ? die($this->setting->maintenance_message . '<br><a href="'. base_url('generic/sign_out') .'">[Sign Out]</a>') : null;

	   //KYC is on, not super admin, kyc checking not passed
	   if ($this->setting->kyc && !$_SESSION['is_admin'] && !my_kyc_check($_SESSION['user_ids'])) {
		   $requestURI = my_uri_segment(1) . '/' . my_uri_segment(2);
		   if ($requestURI != 'user/pay_recurring' && $requestURI != 'user/pay_once' && $requestURI != 'user/pay_success' && $requestURI != 'user/pay_cancel' && $requestURI != 'user/pay_now' && $requestURI != 'user/pay_free') {
			   $this->session->set_flashdata('kyc_redirect_notice', TRUE);
			   redirect('home/pricing');
		   }
	   }
	   
	   // get current user setting
	   $query_user = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1);
	   if ($query_user->num_rows()) {
		   $rs_user = $query_user->row();
		   if ($rs_user->status == 2) {
			   redirect('generic/sign_out');
		   }
		   $this->user_timezone = $rs_user->timezone;
		   $this->user_date_format = $rs_user->date_format;
		   $this->user_time_format = $rs_user->time_format;
		   $this->user_dtformat = $this->user_date_format . ' ' . $this->user_time_format;
		   $this->user_language = $rs_user->language;
		   $this->user_currency = $rs_user->currency;
		   $this->user_balance = $rs_user->balance;
		   $this->user_avatar = $rs_user->avatar;
		   $this->user_permission_list = my_permission_list($rs_user->role_ids);
		   $this->user_role_ids = $rs_user->role_ids;
		   
		   // get ticket setting start
		   $ticket_setting_array = json_decode($this->setting->ticket_setting, 1);
		   ($ticket_setting_array['enabled']) ? $this->ticket_swtich = 1 : $this->ticket_swtich = 0;
		   // get ticket setting end
		   
		   // is front end enabled starts
		   $ticket_setting_array = json_decode($this->setting->front_setting, 1);
		   ($ticket_setting_array['enabled']) ? $this->front_end = 1 : $this->front_end = 0;
		   // is front end enabled ends
		   
		   // about payment setting starts
		   $payment_setting_array = json_decode($this->setting->payment_setting, 1);
		   ($payment_setting_array['type'] != 'disabled') ? $this->payment_swtich = 1 : $this->payment_swtich = 0;
		   ($payment_setting_array['feature'] == 'both' || $payment_setting_array['feature'] == 'subscription') ? $this->payment_subscription = 1 : $this->payment_subscription = 0;
		   // about payment setting ends
	   }
	   else {
		   redirect(base_url('generic/sign_out'));
	   }
	   
	   //check whether user has new notification
	   $user = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1)->row();
	   ($user->new_notification) ? $this->new_notification_tag = 1 : null;
	   
	   // get few notifications recordset
	   $query = $this->db->where('to_user_ids', $_SESSION['user_ids'])->or_where('to_user_ids', 'all')->order_by('id', 'desc')->get('notification', 3);
	   ($query->num_rows()) ? $this->rs_notification = $query->result() : null;
	   
	   // connect a extra db if it's necessary
	   //$this->extra_db = $this->load->database('extra', TRUE);
	   
	   //load addon helper
	   if (my_coupon_module() || my_affiliate_module()) {
		   $this->load->helper('my_coupon_affiliate');
	   }
   }
   
   
   
   	public function password_strength($field, $strength) {
		$res = my_password_strength($field, $strength);
		if ($res['status']) {
			$status = TRUE;
		}
		else {
			$status = FALSE;
			$this->form_validation->set_message('password_strength', $res['error']);
		}
		return $status;
	}
   
   
}
?>