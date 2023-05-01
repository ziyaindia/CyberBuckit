<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Affiliate extends MY_Controller {
	
    public function __construct() {
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
    }
	
	
	
	public function index() {
		
	}
	
	
	
	public function setting() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$this->check_install();
		my_load_view($this->setting->theme, 'Addons/affiliate_setting');
	}
	
	
	
	public function setting_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('commission_rate', my_caption('addons_affiliate_basic_commission_rate'), 'trim|required|greater_than_equal_to[0]|less_than_equal_to[100]');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Addons/affiliate_setting');
		}
		else {
			$update_array = array(
			  'commission_policy' => my_post('commission_policy'),
			  'commission_rate' => my_post('commission_rate'),
			  'description' => my_post('affiliate_description'),
			  'stuff' => ''
			);
			$this->db->update('setting', array('affiliate_setting'=>json_encode($update_array)));
			$this->session->set_flashdata('flash_success', my_caption('addons_affiliate_setting_saved'));
			redirect(base_url('affiliate/setting'));
		}
	}
	
	
	
	public function affiliate_view() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('affiliate_log', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Addons/affiliate_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function affiliate_reverse() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('affiliate_log', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->commission > 0) {  // not reverse before
				$user_earning_array = json_decode(my_user_setting($rs->user_ids, 'affiliate_earning'), TRUE);
				if (array_key_exists($rs->currency, $user_earning_array)) {
					if ($user_earning_array[$rs->currency] >=  $rs->commission) {  //currently, earning > commission
						$user_earning_array[$rs->currency] -= doubleval($rs->commission);  //deduct the earning
						$this->db->where('ids', $rs->user_ids)->update('user', array('affiliate_earning'=>json_encode($user_earning_array)));  //reverse the user's earning
						$this->db->where('ids', my_uri_segment(3))->update('affiliate_log', array('commission'=>0));
						$this->session->set_flashdata('flash_success', my_caption('addons_affiliate_reversed_success'));
					}
					else {
						$this->session->set_flashdata('flash_danger', my_caption('addons_affiliate_reversed_fail_earning_balance'));
					}
				}
				else {
					$this->session->set_flashdata('flash_danger', my_caption('addons_affiliate_reversed_fail_earning_balance'));
				}
			}
			else {
				$this->session->set_flashdata('flash_danger', my_caption('addons_affiliate_reversed_before'));
			}
			redirect('affiliate/affiliate_view/' . my_uri_segment(3));
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function member() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Addons/affiliate_member');
	}



	public function member_new() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Addons/affiliate_member_new');
	}
	
	
	
	public function member_new_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('email_address', my_caption('addons_affiliate_member_email_address'), 'trim|required|valid_email');
		$this->form_validation->set_rules('commission_rate', my_caption('addons_affiliate_basic_commission_rate'), 'trim|required|greater_than_equal_to[0]|less_than_equal_to[100]');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Addons/affiliate_member_new');
		}
		else {
			$query = $this->db->where('email_address', my_post('email_address'))->get('user', 1);
			if ($query->num_rows()) {
				$rs = $query->row();
				if ($rs->affiliate_enabled) {
					$this->session->set_flashdata('flash_warning', my_caption('addons_affiliate_member_user_exist'));
					my_load_view($this->setting->theme, 'Addons/affiliate_member_new');
				}
				else {
					$referral_code = $this->check_referral_code(my_post('referral_code'));
					$update_array = array(
					  'affiliate_enabled' => 1,
					  'affiliate_code' => $referral_code,
					  'affiliate_setting' => $this->affiliate_setting_bulider(my_post('commission_policy'), my_post('commission_rate'))
					);
					$this->db->where('id', $rs->id)->update('user', $update_array);
					$this->session->set_flashdata('flash_success', my_caption('addons_affiliate_member_added'));
					redirect('affiliate/member');
				}
			}
			else {
				$this->session->set_flashdata('flash_danger', my_caption('addons_affiliate_member_email_not_exist'));
				my_load_view($this->setting->theme, 'Addons/affiliate_member_new');
			}
		}
	}



	public function member_view() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Addons/affiliate_member_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function member_view_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('ids', my_post('ids'))->get('user', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$this->form_validation->set_rules('commission_rate', my_caption('addons_affiliate_basic_commission_rate'), 'trim|required|greater_than_equal_to[0]|less_than_equal_to[100]');
			if ($this->form_validation->run() == FALSE) {
				$data['rs'] = $rs;
				my_load_view($this->setting->theme, 'Addons/affiliate_member_view', $data);
			}
			else {
				(my_post('affiliate_enabled') == '1') ? $enabled = 1 : $enabled = 0;
				$affiliate_setting_array = json_decode($rs->affiliate_setting, TRUE);
				$affiliate_setting_array['commission_policy'] = my_post('commission_policy');
				$affiliate_setting_array['commission_rate'] = my_post('commission_rate');
				$update_array = array(
				  'affiliate_enabled' => $enabled,
				  'affiliate_setting' => json_encode($affiliate_setting_array)
				);
				$this->db->where('ids', my_post('ids'))->update('user', $update_array);
				$this->session->set_flashdata('flash_success', my_caption('addons_affiliate_member_view_edit_success'));
				redirect('affiliate/member_view/' . my_post('ids'));
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function payout_all() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->affiliate_earning != '{}') {
				$insert_array = array(
				  'ids' => my_random(),
				  'user_ids' => $rs->ids,
				  'operator_ids' => $_SESSION['user_ids'],
				  'email_address' => $rs->email_address,
				  'amount' => $rs->affiliate_earning,
				  'created_time' => my_server_time()
				);
				$this->db->insert('affiliate_payout', $insert_array);
				$this->db->where('id', $rs->id)->update('user', array('affiliate_earning'=>'{}'));
				$this->session->set_flashdata('flash_success', my_caption('addons_affiliate_commission_paid_success'));
			}
			else {
				$this->session->set_flashdata('flash_danger', my_caption('addons_affiliate_commission_paid_fail'));
			}
			redirect('affiliate/member_view/' . my_uri_segment(3));
			
		}
	}
	
	
	
	public function payout() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Addons/affiliate_payout');
	}
	
	
	
	public function my_affiliate() {
		if (my_user_setting($_SESSION['user_ids'], 'affiliate_enabled')) {
			my_load_view($this->setting->theme, 'Addons/affiliate_my');
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	

	public function my_affiliate_action() {
		my_check_demo_mode();  //check if it's in demo mode
		if (my_user_setting($_SESSION['user_ids'], 'affiliate_enabled')) {
			$affiliate_setting_array = json_decode(my_user_setting($_SESSION['user_ids'], 'affiliate_setting'), TRUE);
			$affiliate_setting_array['payout_information'] = my_post('bank_account');
			$this->db->where('ids', $_SESSION['user_ids'])->update('user', array('affiliate_setting'=>json_encode($affiliate_setting_array)));
			$this->session->set_flashdata('flash_success', my_caption('addons_affiliate_changes_saved'));
			redirect('affiliate/my_affiliate');
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function my_payout() {
		if (my_user_setting($_SESSION['user_ids'], 'affiliate_enabled')) {
			my_load_view($this->setting->theme, 'Addons/affiliate_payout_my');
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	protected function affiliate_setting_bulider($commission_policy, $commission_rate) {
		$builder_array = array(
		  'commission_policy' => $commission_policy,
		  'commission_rate' => $commission_rate,
		  'payout_information' => ''
		);
		return json_encode($builder_array);
	}
	
	
	
	protected function check_referral_code($code) {
		$query = $this->db->where('affiliate_code', $code)->get('user', 1);
		if ($query->num_rows()) {
			for ($i=0; $i<10000; $i++) {
				$code = random_string('alnum', 8);
				$query_check = $this->db->where('affiliate_code', $code)->get('user', 1);
				if (!$query_check->num_rows()) {
					break;
				}
			}
		}
		return $code;
	}
	
	
	
	protected function check_install() {
		$query = $this->db->where('updater_id', '18')->get('script_addons', 1);
		if (!$query->num_rows()) {
			redirect('coupon/install');
		}
		return TRUE;
	}
	
	
}
?>