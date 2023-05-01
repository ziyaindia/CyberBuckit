<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon extends MY_Controller {
	
    public function __construct() {
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
    }
	
	
	
	public function index() {
		redirect('coupon/list_coupon');
	}
	
	
	
	public function list_coupon() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$this->check_install();
		$query = $this->db->order_by('enabled desc, id desc')->get('coupon');
		if ($query->num_rows()) {
			$data['rs'] = $query->result();
		}
		else {
			$data['rs'] = array();
		}
		my_load_view($this->setting->theme, 'Addons/coupon_list', $data);
	}
	
	
	
	public function add() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Addons/coupon_detail');
	}
	
	
	
	public function add_action() {
		$this->coupon_action('add');
	}
	
	
	
	public function edit() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$data['rs'] = $this->simple_return_rs('coupon', 'ids', my_uri_segment(3));
		if ($data['rs']) {
			my_load_view($this->setting->theme, 'Addons/coupon_detail', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function edit_action() {
		$this->coupon_action('edit');
	}
	
	
	
	protected function coupon_action($type) {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('addons_coupon_name', my_caption('addons_coupon_name'), 'trim|required|max_length[50]');
		if ($type == 'add') {
			$this->form_validation->set_rules('addons_coupon_code', my_caption('addons_coupon_code'), 'trim|required|max_length[50]|callback_check_coupon_code');
		}
		$this->form_validation->set_rules('addons_coupon_times_limit', my_caption('addons_coupon_times_limit'), 'trim|integer|greater_than[-1]');
		if (my_post('addons_coupon_discount_type') == 'A') {
			$this->form_validation->set_rules('addons_coupon_discount_amount', my_caption('addons_coupon_discount_amount'), 'trim|required|greater_than[0]|less_than_equal_to[100]');
		}
		else {
			$this->form_validation->set_rules('addons_coupon_discount_amount', my_caption('addons_coupon_discount_amount'), 'trim|required|greater_than[0]');
		}
		$this->form_validation->set_rules('addons_coupon_description', my_caption('addons_coupon_description'), 'trim|max_length[1024]');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Addons/coupon_detail');
		}
		else {
			($type == 'add') ? $ids = my_random() : $ids = my_post('addons_coupon_ids');
			(my_post('enabled') == '') ? $enabled = 0 : $enabled = 1; 
			(my_post('addons_coupon_discount_type') == 'A') ? $addons_coupon_discount_type = 'A' : $addons_coupon_discount_type = 'B';
			(my_post('addons_coupon_times_limit') != '') ? $addons_coupon_times_limit = my_post('addons_coupon_times_limit') : $addons_coupon_times_limit = 0;
			(my_post('addons_coupon_valid_from') != '') ? $addons_coupon_valid_from = my_post('addons_coupon_valid_from') : $addons_coupon_valid_from = date('Y-m-d');
			(my_post('addons_coupon_valid_till') != '') ? $addons_coupon_valid_till = my_post('addons_coupon_valid_till') : $addons_coupon_valid_till = date('Y-m-d', strtotime('+10 years'));
			$applicable_scope_array = $this->input->post('addons_coupon_applicable_scope[]');
			$applicable_scope = '';
			foreach ($applicable_scope_array as $scope) {
				$applicable_scope .= $scope . ',';
			}
			($applicable_scope == '') ? $applicable_scope = '0' : null;
			$db_data = array(
			  'name' => my_post('addons_coupon_name'),
			  'code' => my_post('addons_coupon_code'),
			  'applicable_scope' => rtrim($applicable_scope, ','),
			  'discount_type' => $addons_coupon_discount_type,
			  'discount_amount' => my_post('addons_coupon_discount_amount'),
			  'valid_from' => $addons_coupon_valid_from,
			  'valid_till' => $addons_coupon_valid_till,
			  'use_times_limit' => $addons_coupon_times_limit,
			  'enabled' => $enabled,
			  'created_time' => my_server_time(),
			  'description' => my_post('addons_coupon_description'),
			  'stuff' => '{}'
			  
			);
			if ($type == 'add') {
				$db_data['ids'] = $ids;
				$this->db->insert('coupon', $db_data);
				$this->session->set_flashdata('flash_success', my_caption('addons_coupon_add_success'));
			}
			else {
				$this->db->where('ids', $ids)->update('coupon', $db_data);
				$this->session->set_flashdata('flash_success', my_caption('addons_coupon_update_success'));
			}
			redirect('coupon/list_coupon');
		}
	}
	
	
	
	public function remove_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$this->db->where('ids', my_uri_segment(3))->delete('coupon');
		echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"'. base_url('coupon/list_coupon') . '"}';
	}
	
	
	
	public function validate_coupon() {
		echo json_encode(my_coupon_check(my_uri_segment(3), my_uri_segment(4)));
	}
	
	
	
	public function check_coupon_code($code) {
		$query = $this->db->where('code', $code)->get('coupon', 1);
		if ($query->num_rows()) {
			$this->form_validation->set_message('check_coupon_code', my_caption('addons_coupon_code_exist'));
			return FALSE;
		}
		else {
			return TRUE;
		}
	}
	
	
	
	protected function simple_return_rs($table, $field, $value) {
		$query = $this->db->where($field, $value)->get($table, 1);
		if ($query->num_rows()) {
			return $query->row();
		}
		else {
			return array();
		}
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