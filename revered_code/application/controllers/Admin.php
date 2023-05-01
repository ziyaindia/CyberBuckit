<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
	
    public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
		date_default_timezone_set($this->config->item('time_reference'));
    }
	
	
	
	public function index() {

	}
	
	
	
	public function list_user() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/list_user');
	}
	
	
	
	public function remove_user() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			if ($query->row()->id == 1) {  //first super admin can not be removed
				echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('user_first_super_admin_delete_not_allowed') . '", "redirect":""}';
			}
			else {
				$query_subscription = $this->db->where('user_ids', my_uri_segment(3))->where('status!=', 'expired')->get('payment_subscription', 1);
				if ($query_subscription->num_rows()) {
					echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('user_subscription_active_delete_not_allowed') . '", "redirect":""}';
				}
				else {
					my_remove_user(my_uri_segment(3));
					echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
				}
			}
		}
	}
	
	
	
	public function new_user() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/new_user');
	}
	
	
	
	public function new_user_action() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('first_name', my_caption('signup_first_name_label'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('last_name', my_caption('signup_last_name_label'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('email_address', my_caption('global_email_address'), 'trim|required|valid_email|max_length[50]|callback_email_duplicated_check');
		$this->form_validation->set_rules('password', my_caption('signup_password_label'), 'trim|required|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('confirm_password', my_caption('signup_confirm_label'), 'trim|required|min_length[6]|max_length[20]|matches[password]');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/new_user');
		}
		else {
			$this->load->model('user_model');
			$res = $this->user_model->save_user($this->setting);
			if ($res['result']) {
				$this->session->set_flashdata('flash_success', $res['message']);
				redirect(base_url('admin/list_user'));
			}
			else {
				$this->session->set_flashdata('flash_danger', $res['message']);
				my_load_view($this->setting->theme, 'Admin/new_user');
			}
		}
		
		
	}
	
	
	
	public function invite_user() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/invite_user');
	}
	
	
	
	public function invite_user_action() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('email_address', my_caption('global_email_address'), 'trim|required|valid_email|max_length[50]');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/invite_user');
		}
		else {
			$res = $this->admin_model->invite_user(my_post('email_address'));
			if ($res['result']) {
				$this->session->set_flashdata('flash_success', $res['message']);
				redirect(base_url('admin/invite_user'));
			}
			else {
				$this->session->set_flashdata('flash_danger', $res['message']);
				my_load_view($this->setting->theme, 'Admin/invite_user');
			}
		}
	}
	
	
	
	public function edit_user() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Admin/edit_user', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function edit_user_setting_action() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
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
				$condition = 'trim|min_length[' . $min_length . ']|max_length[20]|callback_password_strength[' . $this->setting->psr . ']';
				$this->form_validation->set_rules('new_password', my_caption('user_edit_setting_password'), $condition);
				$this->form_validation->set_rules('confirm_new_password', my_caption('user_edit_setting_password_confirm'), 'trim|matches[new_password]');
			}
			$this->form_validation->set_rules('act', '', 'in_list[user_setting]');
			if ($this->form_validation->run() == FALSE) {
				my_load_view($this->setting->theme, 'Admin/edit_user', $data);
			}
			else {
				$update_array = array(
				  'status' => my_post('user_status'),
				  'role_ids' => my_post('user_role'),
				  'update_time' => my_server_time()
				);
				(my_post('new_password') != '') ? $update_array['password'] = my_hash_password(my_post('new_password')) : null;
				
				// *** log started
				($this->setting->debug_level == 1) ? $debug_detail = json_encode($update_array) : $debug_detail = '';
				my_log(my_uri_segment(3), 'Information', 'update-user-setting', json_encode(my_ua()), $debug_detail);
				// *** log ended
				
				$this->db->where('ids', my_uri_segment(3))->update('user', $update_array);
				$this->session->set_flashdata('flash_success', my_caption('user_edit_setting_success'));
				redirect(base_url('admin/edit_user/' . my_uri_segment(3)));
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function role() {
		(!my_check_permission('Roles And Permissions')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$data['rs'] = $this->db->order_by('id', 'asc')->get('role')->result();
		my_load_view($this->setting->theme, 'Admin/list_role', $data);
	}
	
	
	
	public function rp_new_action() {
		(!my_check_permission('Roles And Permissions')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('simple_json');  //check if it's in demo mode
		if (!is_integer(strpos('role,permission', my_post('sim_hidden')))) {
			die('{"result":false, "message":"' . my_caption('global_invalid_request') . '"}');
		}
		$this->form_validation->set_rules('sim_value', my_caption('rp_name'), 'trim|required|max_length[50]|callback_check_underscore');
		if ($this->form_validation->run() == FALSE) {
			echo '{"result":false, "message":"' . strip_tags($this->form_validation->error('sim_value')) . '"}';
		}
		else {
			$query = $this->db->where('name', str_replace(' ', '_', my_post('sim_value')))->get(my_post('sim_hidden'), 1);
			if ($query->row()) {
				echo '{"result":false, "message":"' . my_caption('rp_duplicated_notice') . '"}';
			}
			else {
				$ids = my_random();
				$this->db->insert(my_post('sim_hidden'), array('ids'=>$ids, 'built_in'=>0, 'name'=>str_replace(' ', '_', my_post('sim_value'))));
				(my_post('sim_hidden') == 'permission') ? $builde_type = 'permission_created' : $builde_type = 'role_created';
				$this->admin_model->permissionBuilder($builde_type, $ids);
				echo '{"result":true, "message":"' . my_caption('rp_insert_success') . '"}';
			}
		}
	}
	
	
	
	public function rp_edit_action() {
		(!my_check_permission('Roles And Permissions')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('simple_json');  //check if it's in demo mode
		if (!is_integer(strpos('role,permission', my_uri_segment(3)))) {
			die('{"result":false, "message":"'. my_caption('global_invalid_request') . '"}');
		}
		$this->form_validation->set_rules('sim_value', my_caption('rp_name'), 'trim|required|max_length[50]|callback_check_underscore');
		if ($this->form_validation->run() == FALSE) {
			echo '{"result":false, "message":"'.strip_tags($this->form_validation->error('sim_value')).'"}';
		}
		else {
			$query = $this->db->where('ids!=', my_post('sim_hidden'))->where('name', str_replace(' ', '_', my_post('sim_value')))->get(my_uri_segment(3), 1);
			if ($query->num_rows()) {
				echo '{"result":false, "message":"'. my_caption('rp_duplicated_notice') . '"}';
			}
			else {
				$this->db->where('ids', my_post('sim_hidden'))->update(my_uri_segment(3), array('name'=>str_replace(' ', '_', my_post('sim_value'))));
				echo '{"result":true, "message":"'. my_caption('rp_name_update_success') . '"}';
			}
		}
	}
	
	
	
	public function rp_remove_action() {
		(!my_check_permission('Roles And Permissions')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		if (!is_integer(strpos('role,permission', my_uri_segment(3)))) {
			die('{"result":false, "message":"'. my_caption('global_invalid_request') . '"}');
		}
		$query = $this->db->where('ids', my_uri_segment(4))->where('built_in', 0)->get(my_uri_segment(3), 1);
		if ($query->num_rows()) {
			if (my_uri_segment(3) == 'role') {  //change user's role to the default one, whose role belongs to this removed role
				$this->db->where('role_ids', my_uri_segment(4))->update('user', array('role_ids'=>$this->setting->default_role));
			}
			$this->db->delete(my_uri_segment(3), array('ids'=>my_uri_segment(4)));
			if (my_uri_segment(3) == 'permission') {
				$this->admin_model->permissionBuilder('permission_removed', my_uri_segment(4));
			}
			echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"'. base_url('admin/' . my_uri_segment(3)) . '"}';
		}
		else {
			die(my_caption('rp_not_found'));
		}
	}
	
	
	
	public function permission() {
		(!my_check_permission('Roles And Permissions')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$data['rs'] = $this->db->order_by('id', 'asc')->get('permission')->result();
		$data['rs_role'] = $this->db->order_by('id', 'asc')->get('role')->result();
		my_load_view($this->setting->theme, 'Admin/list_permission', $data);
	}
	
	
	
	public function permission_update() {
		(!my_check_permission('Roles And Permissions')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->admin_model->permissionBuilder('update_all');
		$this->session->set_flashdata('flash_success', my_caption('rp_permission_update_success'));
		redirect(base_url('admin/permission'));
	}
	
	
	
	public function general_setting() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/general_setting');
	}
	
	
	
	public function general_setting_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('system_name', my_caption('gs_system_name'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('maintenance_information', my_caption('gs_maintenance_information'), 'trim|required|max_length[255]');
		$this->form_validation->set_rules('google_analytics_id', my_caption('gs_google_analytics'), 'trim|max_length[50]');
		$this->form_validation->set_rules('dashboard_custom_css', my_caption('gs_custom_setting_css'), 'trim|max_length[512]');
		$this->form_validation->set_rules('dashboard_custom_javascript', my_caption('gs_custom_setting_js'), 'trim|max_length[512]');
		if (isset($_FILES['userfile']['name'])) {
			if ($_FILES['userfile']['name'] != '') {
				$this->form_validation->set_rules('userfile', 'Upload File', 'callback_icon_upload');
				}
		}
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/general_setting');
		}
		else {
			$this->admin_model->update_general_settings();
			$this->session->set_flashdata('flash_success', my_caption('gs_update_success'));
			redirect(base_url('admin/general_setting'));
		}
	}
	
	
	
	public function auth_integration() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/auth_integration');
	}
	
	
	
	public function auth_integration_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		if (my_post('google_recaptcha_enabled') == '1') {
			$this->form_validation->set_rules('google_recaptcha_site_key', 'Site Key', 'trim|required');
			$this->form_validation->set_rules('google_recaptcha_secret_key', 'Secret Key', 'trim|required');
		}
		if (my_post('google_login_enabled') == '1') {
			$this->form_validation->set_rules('google_client_id', 'Client ID', 'trim|required');
			$this->form_validation->set_rules('google_client_secret', 'Client Secret', 'trim|required');
		}
		if (my_post('facebook_login_enabled') == '1') {
			$this->form_validation->set_rules('facebook_app_id', 'App ID', 'trim|required');
			$this->form_validation->set_rules('facebook_app_secret', 'App Secret', 'trim|required');
		}
		if (my_post('twitter_login_enabled') == '1') {
			$this->form_validation->set_rules('twitter_consumer_key', 'Consumer Key', 'trim|required');
			$this->form_validation->set_rules('twitter_consumer_secret', 'Consumer Secret', 'trim|required');
		}
		$this->form_validation->set_rules('act', '', 'in_list[auth_integration]');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/auth_integration');
		}
		else {
			$this->admin_model->update_auth_integration();
			$this->session->set_flashdata('flash_success', my_caption('ai_update_success'));
			redirect(base_url('admin/auth_integration'));
		}
	}
	
	
	
	public function smtp_setting() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/smtp_setting');
	}
	
	
	
	public function smtp_setting_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('host', my_caption('smtp_setting_host_label'), 'trim|required');
		$this->form_validation->set_rules('encryption', my_caption('smtp_setting_encryption_label'), 'trim|required|in_list[none,ssl,tls]');
		$this->form_validation->set_rules('port', my_caption('smtp_setting_port_label'), 'trim|required|integer');
		$this->form_validation->set_rules('username', my_caption('smtp_setting_username_label'), 'trim|required');
		$this->form_validation->set_rules('password', my_caption('smtp_setting_password_label'), 'trim|required');
		$this->form_validation->set_rules('from_email', my_caption('smtp_setting_from_email_label'), 'trim|required');
		$this->form_validation->set_rules('from_name', my_caption('smtp_setting_sender_name_label'), 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/smtp_setting');
		}
		else {
			$smtp_array = array(
			  'host' => my_post('host'),
			  'port' => my_post('port'),
			  'is_auth' => 1,
			  'username' => my_post('username'),
			  'password' => $this->input->post('password', TRUE),
			  'crypto' => my_post('encryption'),
			  'from_email' => my_post('from_email'),
			  'from_name' => my_post('from_name')
			);
			$this->db->update('setting', array('smtp_setting'=>json_encode($smtp_array)));
			$this->session->set_flashdata('flash_success', my_caption('smtp_update_success'));
			redirect(base_url('admin/smtp_setting'));
		}
	}
	
	
	
	public function send_test_email () {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('simple_json');  //check if it's in demo mode
		$this->form_validation->set_rules('sim_value', my_caption('global_email_address'), 'trim|required|valid_email');
		if ($this->form_validation->run() == FALSE) {
			echo '{"result":false, "message":"'.strip_tags($this->form_validation->error('sim_value')).'"}';
		}
		else {
			$email = array(
			  'email_to' => my_post('sim_value'),
			  'email_subject' => 'Test Email',
			  'email_body' => 'This is a test email, sent at: ' . my_server_time() . ' ' . $this->config->item('time_reference')
			);
			$res = my_send_email($email);
			if ($res['result']) {
				echo '{"result":true, "message":"'. my_caption('smtp_test_email_sent_success') . '"}';
			}
			else {
				echo '{"result":false, "message":"'. my_caption('smtp_test_email_sent_fail') . '"}';
			}
		}
	}
	
	
	
	public function email_template() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$data['rs'] = $this->db->order_by('built_in', 'desc')->get('email_template')->result();
		my_load_view($this->setting->theme, 'Admin/email_template', $data);
	}
	
	
	
	public function email_template_new() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/email_template_edit');
	}
	
	
	
	public function email_template_new_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('email_purpose', my_caption('et_email_purpose'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('email_subject', my_caption('et_email_subject'), 'trim|required|max_length[255]');
		$this->form_validation->set_rules('email_body', my_caption('et_email_body'), 'trim|required|min_length[30]');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/email_template_edit');
		}
		else {
			$insert_array = array(
			  'ids' => my_random(),
			  'purpose' => my_post('email_purpose'),
			  'built_in' => 0,
			  'subject' => my_post('email_subject'),
			  'body' => my_post('email_body')
			);
			$this->db->insert('email_template', $insert_array);
			$this->session->set_flashdata('flash_success', my_caption('et_email_create_success'));
			redirect(base_url('admin/email_template/'));
		}
	}
	
	
	
	public function email_template_edit() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('email_template', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Admin/email_template_edit', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
		
	}
	
	
	
	public function email_template_remove() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->db->delete('email_template', array('ids'=>my_uri_segment(3)));
		echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url('admin/email_template') . '"}';
	}
	
	
	
	public function email_template_edit_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('email_template', 1);
		if ($query->num_rows()) {
			$this->form_validation->set_rules('email_purpose', my_caption('et_email_purpose'), 'trim|required|max_length[50]');
			$this->form_validation->set_rules('email_subject', my_caption('et_email_subject'), 'trim|required|max_length[255]');
			$this->form_validation->set_rules('email_body', my_caption('et_email_body'), 'trim|required|min_length[30]');
			if ($this->form_validation->run() == FALSE) {
				$data['rs'] = $query->row();
				my_load_view($this->setting->theme, 'Admin/email_template_edit', $data);
			}
			else {
				$this->db->where('ids', my_uri_segment(3))->update('email_template', array('purpose'=>my_post('email_purpose'), 'subject'=>my_post('email_subject'), 'body'=>my_post('email_body')));
				$this->session->set_flashdata('flash_success', my_caption('et_email_change_success'));
				redirect(base_url('admin/email_template_edit/' . my_uri_segment(3)));
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function payment_list() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/payment_list');
	}
	
	
	
	public function payment_list_remove_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('payment_log', 1);
		if ($query->num_rows()) {
			$this->db->where('ids', my_uri_segment(3))->delete('payment_log');
			echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
		}
		else {
			echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('global_no_entries_found') . '", "redirect":"CallBack"}';
		}
	}
	
	
	
	public function payment_list_view() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('payment_log', 1);
		if ($query->num_rows()) {
			$payment_setting_array = json_decode($this->setting->payment_setting, 1);
			$rs = $query->row();
		    $data['user_email'] = $this->db->where('ids', $rs->user_ids)->get('user', 1)->row()->email_address;
			$data['rs'] = $rs;
			my_load_view($this->setting->theme, 'Admin/payment_list_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function payment_subscription_list() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/payment_subscription_list');
	}
	
	
	
	public function payment_subscription_list_view() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$data['rs'] = $rs;
			$data['user_email'] = my_user_setting($rs->user_ids, 'email_address');
			$data['item_name'] = $this->db->where('ids', $rs->item_ids)->get('payment_item', 1)->row()->item_name;
			my_load_view($this->setting->theme, 'Admin/payment_subscription_list_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}



	public function payment_subscription_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$action = my_uri_segment(3);
		$query = $this->db->where('ids', my_uri_segment(4))->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->payment_gateway == 'stripe' || $rs->payment_gateway == 'paypal' || $rs->payment_gateway == 'manual' || $rs->payment_gateway == 'free') {  //legacy problem, will change soon
				$result = my_payment_gateway_subscription_action($action, $rs, 'admin/payment_subscription_list');
			}
			else {
				$this->load->library('m_payment');
				$subscriptionArray['subscriptionID'] = $rs->gateway_identifier;
				if ($action == 'cancel' || $action == 'cancel_now') {
					$subscriptionArray['paymentGateway'] = $rs->payment_gateway;
					($action == 'cancel') ? $subscriptionArray['cancelNow'] = FALSE : $subscriptionArray['cancelNow'] = TRUE;
					$cancelledSubscriptionArray = $this->m_payment->cancelSubscription($subscriptionArray);
					if ($cancelledSubscriptionArray['success']) {
						($action == 'cancel_now') ? $status = 'expired' : $status = 'pending_cancellation';
						$this->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>$status));
						$result = '{"result":true, "title":"' . my_caption('global_cancelled_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url('admin/payment_subscription_list') .'"}';
					}
					else {  // fail to cancel
						$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"CallBack"}';
					}
				}
				else {  // unrecignized command
					$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"CallBack"}';
				}
			}
		}
		else {
			$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('global_no_entries_found') . '", "redirect":"CallBack"}';
		}
		echo my_esc_html($result);
	}
	
	
	
	public function payment_subscription_sync() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$result = TRUE;
		$query = $this->db->where('status!=', 'expired')->get('payment_subscription');
		if ($query->num_rows()) {
			$rs = $query->result();
			try {
				$payment_setting_array = json_decode($this->setting->payment_setting, 1);
				\Stripe\Stripe::setApiKey($payment_setting_array['stripe_secret_key']);
				$this->load->library('m_paypal');
				$this->load->library('m_payment');
				foreach ($rs as $row) {
					if ($row->payment_gateway == 'stripe') {  //a legacy scheme, will improve later
						$subscription = \Stripe\Subscription::retrieve($row->gateway_identifier);
						($subscription->status == 'active') ? $status = $row->status : $status = $subscription->status;
						$update_array = array(
						  'status' => $status,
						  'start_time' => mdate('%Y-%m-%d %H:%i:%s', $subscription->current_period_start),
						  'end_time' => mdate('%Y-%m-%d %H:%i:%s', $subscription->current_period_end),
						  'updated_time' => my_server_time()
						);
						$this->db->where('ids', $row->ids)->update('payment_subscription', $update_array);
					}
					elseif ($row->payment_gateway == 'paypal') { //a legacy scheme, will improve later
						$subscription = $this->m_paypal->retrieveSubscription($row->gateway_identifier);
						if ($subscription['success']) {
							($subscription['status'] == 'ACTIVE') ? $status = 'active' : $status = strtolower($subscription['status']);
							$update_array = array(
							  'status' => str_replace('cancelled', 'expired', $status),
							  'start_time' => $subscription['start_time'],
							  'end_time' => $subscription['end_time'],
							  'updated_time' => my_server_time()
							);
							$this->db->where('ids', $row->ids)->update('payment_subscription', $update_array);
						}
					}
					elseif ($row->payment_gateway == 'manual') {
						//ignore
					}
					else {  //here is the new solution, a better solution comparing to the former's
						$subscriptionArray = $this->m_payment->retrieveSubscription($row->payment_gateway, $row->gateway_identifier);
						if ($subscriptionArray['success']) {
							$updateArray = array(
							  'status' => $subscriptionArray['status'],
							  'start_time' => $subscriptionArray['currentStartTime'],
							  'end_time' => $subscriptionArray['currentEndTime'],
							  'updated_time' => my_server_time()
							);
							$this->db->where('ids', $row->ids)->update('payment_subscription', $updateArray);
						}
					}
				}
			}
			catch (\Exception $e) {
				$result = FALSE;
			}
		}
		if ($result) {
			$this->session->set_flashdata('flash_success', my_caption('payment_subscription_sync_success'));
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('payment_subscription_sync_fail'));
		}
		redirect(base_url('admin/payment_subscription_list'));
	}
	
	
	
	public function payment_subscription_remove_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->status == 'expired') {
				$this->db->where('ids', my_uri_segment(3))->delete('payment_subscription');
				echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
			}
			else {
				echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_remove_subscription_failed_not_cancel') . '", "redirect":""}';
			}
		}
		else {
			echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
		}
	}
	
	
	
	public function payment_adjust_balance() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$data['rs'] = $rs;
			my_load_view($this->setting->theme, 'Admin/payment_adjust_balance', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function payment_adjust_balance_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('ids', my_post('user_ids'))->get('user', 1);
		$this->form_validation->set_rules('adjust_amount', my_caption('payment_amount'), 'trim|required|numeric|greater_than_equal_to[0.01]');
		if ($this->form_validation->run() == FALSE) {
			if ($query->num_rows()) {
				$rs = $query->row();
				$data['rs'] = $rs;
				my_load_view($this->setting->theme, 'Admin/payment_adjust_balance', $data);
			}
			else {
				echo my_caption('global_no_entries_found');
			}
		}
		else {
			if ($query->num_rows()) {
				$res_array = json_decode(my_user_reload(my_post('user_ids'), my_post('adjust_balance_type'), my_post('adjust_currency'), my_post('adjust_amount')), 1);
				if ($res_array['result']) {
					$this->admin_model->payment_log_insert();
					$this->session->set_flashdata('flash_success', $res_array['message']);
				}
				else {
					$this->session->set_flashdata('flash_danger', $res_array['message']);
				}
				redirect(base_url('admin/payment_adjust_balance/' . my_post('user_ids')));
			}
			else {
				echo my_caption('global_no_entries_found');
			}
		}
	}
	
	
	
	public function payment_add_purchase() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			$data['rs_user'] = $query->row();
			$data['rs_purchase'] = $this->db->where('enabled', 1)->where('type', 'purchase')->get('payment_item')->result();
			my_load_view($this->setting->theme, 'Admin/payment_add_purchase', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function payment_add_purchase_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query_user = $this->db->where('ids', my_post('user_ids'))->get('user', 1);
		if ($query_user->num_rows()) {
			$this->form_validation->set_rules('purchase', my_caption('payment_add_purchase_item'), 'trim|required');
			$this->form_validation->set_rules('description', my_caption('payment_description'), 'trim|max_length[1024]');
			if ($this->form_validation->run() == FALSE) {
				$data['rs_user'] = $query_user->row();
				$data['rs_purchase'] = $this->db->where('enabled', 1)->where('type', 'purchase')->get('payment_item')->result();
				my_load_view($this->setting->theme, 'Admin/payment_add_purchase', $data);
			}
			else {
				$query_purchase = $this->db->where('enabled', 1)->where('type', 'purchase')->where('ids', my_post('purchase'))->get('payment_item', 1);
				if ($query_purchase->num_rows()) {
					$rs_purchase = $query_purchase->row();
					$res = my_user_reload(my_post('user_ids'), 'Cut', $rs_purchase->item_currency, $rs_purchase->item_price);  //deduct from balance
					$res_array = json_decode($res, 1);
					if ($res_array['result']) {  //user balance is enough
						// add purchase
						$insert_data = array(
						  'ids' => my_random(),
						  'user_ids' => my_post('user_ids'),
						  'payment_ids' => str_repeat('0', 50),
						  'item_type' => 'purchase',
						  'item_ids' => $rs_purchase->ids,
						  'item_name' => $rs_purchase->item_name,
						  'created_time' => my_server_time(),
						  'description' => my_post('description'),
						  'stuff' => $rs_purchase->stuff_setting,
						  'used_up' => 0,
						  'auto_renew' => $rs_purchase->auto_renew
						);
						$this->db->insert('payment_purchased', $insert_data);
						$this->session->set_flashdata('flash_success', my_caption('payment_add_purchase_add') . '(' . $rs_purchase->item_name . ')' . my_caption('global_successfully') . '!');
						redirect('admin/payment_add_purchase/' . my_post('user_ids'));
					}
					else {
						$this->session->set_flashdata('flash_danger', my_caption('payment_add_purchase_balance_not_enough'));
						redirect('admin/payment_add_purchase/' . my_post('user_ids'));
					}
				}
				else {
					echo my_caption('global_no_entries_found');
				}
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
		
	}
	
	
	
	public function payment_add_subscription() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			$data['rs_user'] = $query->row();
			$data['rs_subscription'] = $this->db->where('enabled', 1)->where('type', 'subscription')->get('payment_item')->result();
			my_load_view($this->setting->theme, 'Admin/payment_add_subscription', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
		
	}
	
	
	
	public function payment_add_subscription_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query_user = $this->db->where('ids', my_post('user_ids'))->get('user', 1);
		if ($query_user->num_rows()) {
			$this->form_validation->set_rules('subscription', my_caption('payment_type_subscription_item'), 'trim|required');
			$this->form_validation->set_rules('description', my_caption('payment_description'), 'trim|max_length[1024]');
			if ($this->form_validation->run() == FALSE) {
				$data['rs_user'] = $query_user->row();
				$data['rs_subscription'] = $this->db->where('enabled', 1)->where('type', 'subscription')->get('payment_item')->result();
				my_load_view($this->setting->theme, 'Admin/payment_add_subscription', $data);
			}
			else {
				$query_subscription = $this->db->where('enabled', 1)->where('type', 'subscription')->where('ids', my_post('subscription'))->get('payment_item', 1);
				$rs_user = $query_user->row();
				if ($query_subscription->num_rows()) {
					$rs_subscription = $query_subscription->row();
					$res = my_user_reload(my_post('user_ids'), 'Cut', $rs_subscription->item_currency, $rs_subscription->item_price);  //deduct from balance
					$res_array = json_decode($res, 1);
					if ($res_array['result']) {  //user balance is enough
						//add subscription
						$current_time = my_server_time();
						$end_date = date('Y-m-d H:i:s', strtotime($rs_subscription->recurring_interval_count . ' ' . $rs_subscription->recurring_interval, strtotime($current_time)));
						$insert_data = array(
							'ids' => my_random(),
							'item_ids' => my_post('subscription'),
							'user_ids' => my_post('user_ids'),
							'payment_gateway' => 'manual',
							'gateway_identifier' => '0',
							'quantity' => 1,
							'status' => 'active',
							'start_time' => $current_time,
							'end_time' => $end_date,
							'created_time' => $current_time,
							'updated_time' => $current_time,
							'description' => my_post('description'),
							'stuff' => $rs_subscription->stuff_setting,
							'used_up' => 0,
							'auto_renew' => $rs_subscription->auto_renew
						);
						$this->db->insert('payment_subscription', $insert_data);
						$this->session->set_flashdata('flash_success', my_caption('payment_add_subscription_add') . '(' . $rs_subscription->item_name . ')' . my_caption('global_successfully') . '!');
						redirect('admin/payment_add_subscription/' . my_post('user_ids'));
					}
					else {
						$this->session->set_flashdata('flash_danger', my_caption('payment_add_subscription_balance_not_enough'));
						redirect('admin/payment_add_subscription/' . my_post('user_ids'));
					}
				}
				else {
					echo my_caption('global_no_entries_found');
				}
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
		
	}
	
	
	
	public function payment_item_list() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/payment_item_list');
	}
	
	
	
	public function payment_item_add() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/payment_item_detail');
	}
	
	
	
	public function payment_item_add_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('item_name', my_caption('payment_item_name_label'), 'trim|required|max_length[255]');
		$this->form_validation->set_rules('access_code', my_caption('payment_item_access_code_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('item_price', my_caption('payment_item_price_label'), 'trim|required|numeric|greater_than_equal_to[0]');
		$this->form_validation->set_rules('purchase_times', my_caption('payment_item_purchase_times_limit'), 'trim|required|integer');
		$this->form_validation->set_rules('access_condition[]', my_caption('payment_item_access_condition'), 'trim|required');
		$this->form_validation->set_rules('item_description', my_caption('payment_item_description_label'), 'trim|required');
		if (my_post('item_type') == 'subscription') {
			$this->form_validation->set_rules('recurring_interval_count', my_caption('payment_item_recurring_interval_count_label'), 'trim|required|integer|greater_than[0]');
			$this->form_validation->set_rules('recurring_interval', my_caption('payment_item_recurring_interval_label'), 'trim|required|in_list[day,week,month,year]');
		}
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/payment_item_detail');
		}
		else {
			$this->admin_model->payment_item_add();
			$this->session->set_flashdata('flash_success', my_caption('payment_item_add_success'));
			redirect(base_url('admin/payment_item_list/'));
		}
	}
	
	
	
	public function payment_item_remove_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('payment_item', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->type = 'subscription') {  //if the type of the item is subscription, need to check wheather have active subscription
				$query_subscription = $this->db->where('item_ids', $rs->ids)->get('payment_subscription', 1);
				if ($query_subscription->num_rows()) {
					echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_item_delete_failure_still_subscribed') . '", "redirect":"CallBack"}';
				}
				else {
					$this->db->where('ids', my_uri_segment(3))->delete('payment_item');
					echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
				}
			}
			else {
				$this->db->where('ids', my_uri_segment(3))->delete('payment_item');
				echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
			}
			
		}
		else {
			echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('global_no_entries_found') . '", "redirect":"CallBack"}';
		}
	}
	
	
	
	public function payment_item_modify() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('payment_item', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Admin/payment_item_detail', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}



	public function payment_item_modify_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('ids', my_post('ids'))->get('payment_item', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$stuff_setting = '';
			$this->form_validation->set_rules('item_name', my_caption('payment_item_name_label'), 'trim|required|max_length[255]');
			$this->form_validation->set_rules('item_price', my_caption('payment_item_price_label'), 'trim|required|greater_than_equal_to[0]');
			$this->form_validation->set_rules('purchase_times', my_caption('payment_item_purchase_times_limit'), 'trim|required|integer');
			$this->form_validation->set_rules('access_condition[]', my_caption('payment_item_access_condition'), 'trim|required');
			$this->form_validation->set_rules('item_description', my_caption('payment_item_description_label'), 'trim|required');
			if ($rs->type == 'subscription') {
				$this->form_validation->set_rules('recurring_interval_count', my_caption('payment_item_recurring_interval_count_label'), 'trim|required|integer|greater_than[0]');
				$this->form_validation->set_rules('recurring_interval', my_caption('payment_item_recurring_interval_label'), 'trim|required|in_list[day,week,month,year]');
			}
			if ($this->form_validation->run() == FALSE) {
				$data['rs'] = $rs;
				my_load_view($this->setting->theme, 'Admin/payment_item_detail', $data);
			}
			else {
				if ($rs->type == 'subscription') {
					if ($rs->stuff_setting == '{}') {
						$stuff_setting = '{}';
					}
					else {
						$subscription_query = $this->db->where('item_ids', $rs->ids)->group_start()->where('status', 'active')->or_where('status', 'pending_cancellation')->group_end()->get('payment_subscription', 1);
						if ($subscription_query->num_rows()) { $stuff_setting = ''; } else { $stuff_setting = '{}'; }  //alreay has active/pending_cancellation subscription
					}
				}
				else {  // top-up or purchase
					$stuff_setting = $rs->stuff_setting;
				}
				if ($stuff_setting != '') {
					$this->admin_model->payment_item_modify($rs->type, $stuff_setting);
					$this->session->set_flashdata('flash_success', my_caption('payment_item_modify_success'));
				}
				else {
					$this->session->set_flashdata('flash_danger', my_caption('payment_item_modify_fail'));
				}
				redirect(base_url('admin/payment_item_modify/' . my_post('ids')));
			}	
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function payment_setting() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/payment_setting');
	}
	
	
	
	public function payment_setting_action() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		if (my_post('stripe_one_time_enabled') == '1' || my_post('stripe_recurring_enabled') == '1') {
			$this->form_validation->set_rules('stripe_publishable_key', 'Publishable Key', 'trim|required');
			$this->form_validation->set_rules('stripe_secret_key', 'Secret Key', 'trim|required');
			$this->form_validation->set_rules('stripe_signing_secret', 'Signing Secret', 'trim|required');
		}
		if (my_post('paypal_one_time_enabled') == '1' || my_post('paypal_recurring_enabled') == '1') {
			$this->form_validation->set_rules('paypal_client_id', 'Client ID', 'trim|required');
			$this->form_validation->set_rules('paypal_secret', 'Secret', 'trim|required');
			$this->form_validation->set_rules('paypal_webhook_id', 'Webhook ID', 'trim|required');
		}
		$this->form_validation->set_rules('payment_tax_rate', my_caption('payment_tax_rate'), 'trim|required|greater_than_equal_to[0]');
		$this->form_validation->set_rules('act', '', 'in_list[payment_setting]');
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('flash_danger', my_caption('global_something_failed'));
			my_load_view($this->setting->theme, 'Admin/payment_setting');
		}
		else {
			$res = $this->admin_model->payment_setting_update();
			$this->session->set_flashdata('flash_success', my_caption('payment_update_success'));
			redirect(base_url('admin/payment_setting'));
		}
	}
	
	
	
	public function users_activity_log() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/list_activity');
	}
	
	
	
	public function users_activity_log_delete() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		switch (my_uri_segment(3)) {
			case 'A' :
			  $offset = '-3 day';
			  break;
			case 'B' :
			  $offset = '-14 day';
			  break;
			case 'C' :
			  $offset = '-1 month';
			  break;
			case 'D' :
			  $offset = '-3 month';
			  break;
			case 'E' :
			  $offset = '-6 month';
			  break;
			case 'F' :
			  $offset = '-12 month';
			  break;
			case 'G' :
			  $offset = '-1 second';
			  break;			  
		}
		$before_this_time = date('Y-m-d H:i:s', strtotime($offset, strtotime(my_server_time())));
		$this->db->where('created_time<=', $before_this_time)->delete('activity');
		echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
	}
	
	
	
	public function list_online() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/list_online');
	}
	
	
	
	public function send_notification() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/send_notification');
	}
	
	
	
	public function send_notification_action() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('notification_subject', my_caption('notification_subject'), 'trim|required|max_length[255]');
		$this->form_validation->set_rules('notification_body', my_caption('notification_body'), 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/send_notification');
		}
		else {
			$res = $this->admin_model->send_notification($this->setting);
			$this->session->set_flashdata('flash_success', my_caption('notification_send_success'));
			redirect(base_url('admin/send_notification'));
		}
	}
	
	
	
	public function list_notification() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/list_notification');
	}
	
	
	
	public function list_notification_view() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('notification', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Generic/view_notification', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function general_setting_tc() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/terms_conditions');
	}
	
	
	
	public function database_backup() {
		(!my_check_permission('Database Backup')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/database_backup');
	}
	
	
	
	public function database_backup_action() {
		(!my_check_permission('Database Backup')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		if (my_post('backup_range') == 'A') {  //only backup key table
			$prefs = array('tables' => explode(',', $this->config->item('my_key_table')));
			$options = 'Partially';
		}
		else {  //backup whole database
			$prefs = array('tables' => array());
			$options = 'Fully';
		}
		$backup_filename = 'backup_' . $options . '_' . my_server_time('UTC', 'YmdHis') . '_' . random_string('alnum', 6) . '.zip';
		$prefs += array(
		  'format' => 'zip',
		  'filename' => $backup_filename
		);
		$this->db->update('setting', array('last_backup_time'=>my_server_time()));
		$this->load->dbutil();
		$backup = $this->dbutil->backup($prefs);
		if (my_post('backup_action') == 'A') {  //save on server-side
			$options .= ', Save';
			$this->load->helper('file');
			(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? $file_full_path = FCPATH . 'backup\\' . $backup_filename : $file_full_path = FCPATH . 'backup/' . $backup_filename;
			write_file($file_full_path, $backup);
			$message = my_caption('database_backup_success') . $file_full_path ;
			$this->session->set_flashdata('flash_success', $message);
		}
		else {  //download the file
		    $options .= ' Download';
			$file_full_path = $backup_filename;
			$this->load->helper('download');
		}
		$log_array = array(
		  'ids' => my_random(),
		  'file_path' => $file_full_path,
		  'options' => $options,
		  'created_method' => 'Manual',
		  'created_time' => my_server_time()
		);
		$this->db->insert('backup_log', $log_array);
		(my_post('backup_action') == 'A') ? redirect(base_url('admin/database_backup')) : force_download($backup_filename, $backup);
	}
	
	
	
	public function general_setting_tc_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('tc_title', my_caption('gs_tc_Title'), 'trim|required');
		$this->form_validation->set_rules('tc_body', my_caption('gs_tc_body'), 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/terms_conditions');
		}
		else {
			$tc_array['title'] = my_post('tc_title');
			$tc_array['body'] = my_post('tc_body');
			$this->db->update('setting', array('terms_conditions'=>json_encode($tc_array)));
			$this->session->set_flashdata('flash_success', my_caption('gs_tc_update_success'));
			redirect(base_url('admin/general_setting_tc'));
		}
	}	
	
	
	
	public function signin_as_user() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('user', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$rs_role = $this->db->where('ids', $rs->role_ids)->get('role', 1)->row();
			if (my_uri_segment(3) == $_SESSION['user_ids']) {
				$this->session->set_flashdata('flash_danger', my_caption('user_impersonate_self_not_allow'));
				redirect($_SERVER['HTTP_REFERER']);
			}
			elseif ($rs_role->name == 'Super_Admin') {
				$this->session->set_flashdata('flash_danger', my_caption('user_impersonate_superadmin_not_allow'));
				redirect($_SERVER['HTTP_REFERER']);
			}
			else {
				$this->load->model('user_model');
				$this->user_model->sigin_success_log($rs, 'impersonate');
				redirect(base_url('dashboard'));
			}
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('user_impersonate_invalid_user'));
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
	
	
	
	public function stop_impersonating() {
		if (!empty($_SESSION['impersonate'])) {
			$this->db->where('ids', $_SESSION['user_ids'])->update('user', array('online'=>0));
			$rs = $this->db->where('ids', $_SESSION['impersonate'])->get('user', 1)->row();
			$this->load->model('user_model');
			$this->user_model->sigin_success_log($rs, 'stop_impersonating');
			redirect(base_url('admin/list_user'));
		}
		else {
			my_caption('global_not_enough_permission');
		}	
	}
	
	
		
	public function miscellaneous() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/miscellaneous');
	}
	
	
	
	public function miscellaneous_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('file_type', my_caption('file_manager_file_type'), 'trim|required');
		$this->form_validation->set_rules('file_size', my_caption('file_manager_file_size'), 'trim|required|numeric');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/miscellaneous');
		}
		else {
			$res = $this->admin_model->miscellaneous();
			if ($res['result']) {
				$this->session->set_flashdata('flash_success', $res['message']);
			}
			else {
				$this->session->set_flashdata('flash_danger', $res['message']);
			}
			redirect(base_url('admin/miscellaneous'));
		}
	}
	
	
	
	public function usage_example() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/usage_example');
	}
	
	
	
	public function support_setting() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/support_setting');
	}
	
	
	
	public function support_setting_action() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		if (my_post('support_ticket_notification_email_address') != '') {
			$this->form_validation->set_rules('support_ticket_notification_email_address', my_caption('global_email_address'), 'trim|valid_emails');
		}
		$this->form_validation->set_rules('act', '', 'in_list[support_setting]');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/support_setting');
		}
		else {
			$this->admin_model->support_setting_update();
			$this->session->set_flashdata('flash_success', my_caption('support_setting_update_success'));
			redirect(base_url('admin/support_setting'));
		}
	}
	
	
	
	public function ticket_list() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/ticket_list');
	}
	
	
	
	public function ticket_view() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$res = $this->get_ticket(my_uri_segment(3));
		if ($res ==  FALSE) {
			echo my_caption('global_no_entries_found');
		}
		else {
			if ($res['rs']->main_status == 2) {
				$this->db->where('ids', my_uri_segment(3))->where('read_status', 0)->update('ticket', array('read_status'=>1)); //update the topic ticket's read status
				$this->db->where('ids_father', my_uri_segment(3))->where('source', 'user')->where('read_status', 0)->update('ticket', array('read_status'=>1));  //update the follow-up ticket's read status
			}
			my_load_view($this->setting->theme, 'Generic/view_ticket', $res);
		}
	}
	
	
	
	public function ticket_view_action () {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('ticket_reply', my_caption('global_reply'), 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$res = $this->get_ticket(my_post('ids_father'));
			my_load_view($this->setting->theme, 'Generic/view_ticket', $res);
		}
		else {
			$query = $this->db->where('ids', my_post('ids_father'))->get('ticket', 1);
			if ($query->num_rows()) {
				$this->load->model('user_model');
				$ids = $this->user_model->update_ticket('agent_update');
				$this->session->set_flashdata('flash_success', my_caption('support_ticket_notice_reply_saved'));
				redirect(base_url('admin/ticket_view/' . $ids));
			}
			else {
				echo my_caption('global_no_entries_found');
			}
		}
	}
	
	
	
	public function ticket_close_action() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$this->db->where('ids', my_uri_segment(3))->where('main_status!=', 0)->update('ticket', array('main_status'=>0, 'read_status'=>1, 'updated_time'=>my_server_time()));
		echo '{"result":true, "title":"", "text":"'. my_caption('support_ticket_notice_close') . '", "redirect":"' . base_url('admin/ticket_view/' . my_uri_segment(3)) . '"}';
	}
	
	
	
	public function ticket_remove() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		if (my_uri_segment(3) == 'full') {  //remove the topic ticket and follow-up ticket
			$this->db->where('ids', my_uri_segment(4))->delete('ticket');
			$this->db->where('ids_father', my_uri_segment(4))->delete('ticket');
			$redirect = base_url('admin/ticket_list');
		}
		else {  //remove follow-up ticket only
			$this->db->where('ids', my_uri_segment(5))->delete('ticket');
			$redirect = base_url('admin/ticket_view/' . my_uri_segment(4));
		}
		echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . $redirect . '"}';
	}
	
	
	
	public function catalog() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->order_by('type', 'asc')->order_by('id', 'desc')->get('catalog');
		if ($query->num_rows()) {
			$data['rs'] = $query->result();
		}
		else {
			$data['rs'] = '';
		}
		my_load_view($this->setting->theme, 'Admin/list_catalog', $data);
	}
	
	
	
	public function catalog_add_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('simple_json');  //check if it's in demo mode
		$this->form_validation->set_rules('catalog_name', my_caption('catalog_name'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('catalog_description', my_caption('catalog_description'), 'trim|max_length[1024]');
		if ($this->form_validation->run() == FALSE) {
			echo '{"result":false, "message":"' . strip_tags($this->form_validation->error('catalog_name')) . '"}';
		}
		else {
			$query = $this->db->where('type', my_post('catalog_type'))->where('name', my_post('catalog_name'))->get('catalog', 1);
			if ($query->num_rows()) {
				echo '{"result":false, "message":"' . my_caption('catalog_duplicated_notice') . '"}';
			}
			else {
				$process_result = TRUE;
				if (my_post('catalog_type') == 'file_manager_folder') {  //create sub folder in "upload" folder when catalog type is file_manager_folder
					if (!is_dir($this->config->item('my_upload_directory') . my_post('catalog_name'))) {
						(mkdir($this->config->item('my_upload_directory') . my_post('catalog_name'), 0777, TRUE)) ? $process_result = TRUE : $process_result = FALSE;  //mkdir
					}
				}
				if ($process_result) {
					$data = array(
					  'ids' => my_random(),
					  'type' => my_post('catalog_type'),
					  'name' => my_post('catalog_name'),
					  'description' => my_post('catalog_description')
					);
					$this->db->insert('catalog', $data);
					echo '{"result":true, "message":"' . my_caption('catalog_created') . '"}';
				}
				else {
					echo '{"result":false, "message":"' . my_caption('catalog_folder_failed_to_perform') . '"}';
				}
			}
		}
	}
	
	
	
	public function catalog_edit_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('simple_json');  //check if it's in demo mode
		$this->form_validation->set_rules('catalog_name', my_caption('catalog_name'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('catalog_description', my_caption('catalog_description'), 'trim|max_length[1024]');
		if ($this->form_validation->run() == FALSE) {
			echo '{"result":false, "message":"' . strip_tags($this->form_validation->error('catalog_name')) . '"}';
		}
		else {
			$query = $this->db->where('type', my_post('catalog_type'))->where('name', my_post('catalog_name'))->where('ids!=', my_post('catalog_hidden_ids'))->get('catalog', 1);
			if ($query->num_rows()) {
				echo '{"result":false, "message":"' . my_caption('catalog_duplicated_notice') . '"}';
			}
			else {
				$rs = $this->db->where('ids', my_post('catalog_hidden_ids'))->get('catalog', 1)->row();
				$process_result = TRUE;
				if (my_post('catalog_type') == 'file_manager_folder') {
					if ($rs->name != my_post('catalog_name')) {  //folder name changed
						if (!is_dir($this->config->item('my_upload_directory') . my_post('catalog_name'))) {  //directory exists
							$this->db->where('catalog', $rs->name)->update('file_manager', array('catalog'=>my_post('catalog_name')));
							(rename($this->config->item('my_upload_directory') . $rs->name, $this->config->item('my_upload_directory') . my_post('catalog_name'))) ? $process_result = TRUE : $process_result = FALSE;
						}
						else {
							$process_result = FALSE;
						}
					}
				}
				elseif (my_post('catalog_type') == 'support_faq') {
					$this->db->where('catalog', $rs->name)->update('faq', array('catalog'=>my_post('catalog_name')));
				}
				elseif (my_post('catalog_type') == 'support_documentation') {
					$this->db->where('catalog', $rs->name)->update('documentation', array('catalog'=>my_post('catalog_name')));
				}
				elseif (my_post('catalog_type') == 'blog_catalog') {
					$this->db->where('catalog', $rs->name)->update('blog', array('catalog'=>my_post('catalog_name')));
				}
				if ($process_result) {
					$this->db->where('ids', my_post('catalog_hidden_ids'))->update('catalog', array('name'=>my_post('catalog_name'), 'description'=>my_post('catalog_description')));
					echo '{"result":true, "message":"' . my_caption('catalog_updated') . '"}';
				}
				else {
					echo '{"result":false, "message":"' . my_caption('catalog_folder_failed_to_perform') . '"}';
				}
			}
		}
	}
	
	
	
	public function catalog_remove_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('catalog', 1);
		if ($query->num_rows()) {
			$catalog_name = $query->row()->name;
			$catalog_type = $query->row()->type;
			switch ($catalog_type) {
				case 'file_manager_folder' :
				  $dir_path = $this->config->item('my_upload_directory') . $catalog_name;
				  array_map('unlink', glob($dir_path . '/*.*'));
				  rmdir($dir_path);
				  break;
				case 'support_faq' :
				  $this->db->where('catalog', $catalog_name)->delete('faq');
				  break;
				case 'support_documentation' :
				  $this->db->where('catalog', $catalog_name)->delete('documentation');
				  break;
			}
			$this->db->where('catalog', $query->row()->name)->where('user_ids', $_SESSION['user_ids'])->delete('file_manager');
			$this->db->where('ids', my_uri_segment(3))->delete('catalog');
		}
		echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url('admin/catalog') . '"}';
	}
	
	
	
	public function faq_list() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/faq_list');
	}
	
	
	
	public function faq_new() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/faq_detail');
	}
	
	
	
	public function faq_edit() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('faq', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Admin/faq_detail', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function faq_action() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$action = my_uri_segment(3);
		if ($action == 'new') { //
		    my_check_demo_mode();  //check if it's in demo mode
			$this->form_validation->set_rules('faq_subject', my_caption('support_faq_subject'), 'trim|required|max_length[255]');
			$this->form_validation->set_rules('faq_catalog', my_caption('support_faq_catalog'), 'trim|required|max_length[50]');
			$this->form_validation->set_rules('faq_body', my_caption('support_faq_body'), 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				my_load_view($this->setting->theme, 'Admin/faq_detail');
			}
			else {
				$insert_array = array(
				  'ids' => my_random(),
				  'user_ids' => $_SESSION['user_ids'],
				  'catalog' => my_post('faq_catalog'),
				  'subject' => my_post('faq_subject'),
				  'body' => my_post('faq_body'),
				  'created_time' => my_server_time(),
				  'enabled' => 1
				);
				$this->db->insert('faq', $insert_array);
				$this->session->set_flashdata('flash_success', my_caption('support_faq_add_success'));
				redirect(base_url('admin/faq_list/'));
			}
		}
		else {  //edit or remove
		    $ids = my_uri_segment(4);
			$query = $this->db->where('ids', $ids)->get('faq', 1);
			if ($query->num_rows()) {
				$rs = $query->row();
				if ($action == 'edit') {  //edit
				    my_check_demo_mode();  //check if it's in demo mode
					$this->form_validation->set_rules('faq_subject', my_caption('support_faq_subject'), 'trim|required|max_length[255]');
					$this->form_validation->set_rules('faq_catalog', my_caption('support_faq_catalog'), 'trim|required|max_length[50]');
					$this->form_validation->set_rules('faq_body', my_caption('support_faq_body'), 'trim|required');
					if ($this->form_validation->run() == FALSE) {
						$data['rs'] = $rs;
						my_load_view($this->setting->theme, 'Admin/faq_detail', $data);
					}
					else {
						$update_array = array(
						  'catalog' => my_post('faq_catalog'),
						  'subject' => my_post('faq_subject'),
						  'body' => my_post('faq_body')
						);
						$this->db->where('ids', $ids)->update('faq', $update_array);
						$this->session->set_flashdata('flash_success', my_caption('support_faq_edit_success'));
						redirect(base_url('admin/faq_edit/' . $ids));
					}
				}
				else {  //remove
				    my_check_demo_mode('alert_json');  //check if it's in demo mode
				    $this->db->where('ids', $ids)->delete('faq');
					echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
				}
			}
			else {
				echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('global_no_entries_found') . '", "redirect":"CallBack"}';
			}
		}
	}
	
	
	
	public function documentation_list() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/documentation_list');
	}
	
	
	
	public function documentation_new() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/documentation_detail');
	}
	
	
	
	public function documentation_edit() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('documentation', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Admin/documentation_detail', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	

	public function documentation_action() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$action = my_uri_segment(3);
		if ($action == 'new') { //
		    my_check_demo_mode();  //check if it's in demo mode
			$this->form_validation->set_rules('documentation_subject', my_caption('support_documentation_subject'), 'trim|required|max_length[255]');
			$this->form_validation->set_rules('documentation_catalog', my_caption('support_documentation_catalog'), 'trim|required|max_length[50]');
			$this->form_validation->set_rules('documentation_keyword', my_caption('global_keyword'), 'trim|max_length[1024]');
			$this->form_validation->set_rules('documentation_body', my_caption('support_documentation_body'), 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				my_load_view($this->setting->theme, 'Admin/documentation_detail');
			}
			else {
				$slug = my_generate_slug('documentation', 'slug', my_post('documentation_subject'));
				(my_post('documentation_enabled') == '1') ? $documentation_enabled = 1 : $documentation_enabled = 0;
				$insert_array = array(
				  'ids' => my_random(),
				  'user_ids' => $_SESSION['user_ids'],
				  'slug' => $slug,
				  'catalog' => my_post('documentation_catalog'),
				  'subject' => my_post('documentation_subject'),
				  'keyword' => my_post('documentation_keyword'),
				  'body' => $this->input->post('documentation_body', FALSE),
				  'attachment' => '',
				  'created_time' => my_server_time(),
				  'updated_time' => my_server_time(),
				  'enabled' => $documentation_enabled
				);
				$this->db->insert('documentation', $insert_array);
				$this->session->set_flashdata('flash_success', my_caption('support_documentation_add_success'));
				redirect(base_url('admin/documentation_list/'));
			}
		}
		else {  //edit or remove
		    $ids = my_uri_segment(4);
			$query = $this->db->where('ids', $ids)->get('documentation', 1);
			if ($query->num_rows()) {
				$rs = $query->row();
				if ($action == 'edit') {  //edit
				    my_check_demo_mode();  //check if it's in demo mode
					$this->form_validation->set_rules('documentation_subject', my_caption('support_documentation_subject'), 'trim|required|max_length[255]');
					$this->form_validation->set_rules('documentation_catalog', my_caption('support_documentation_catalog'), 'trim|required|max_length[50]');
					$this->form_validation->set_rules('documentation_keyword', my_caption('global_keyword'), 'trim|max_length[1024]');
					$this->form_validation->set_rules('documentation_body', my_caption('support_documentation_body'), 'trim|required');
					if ($this->form_validation->run() == FALSE) {
						$data['rs'] = $rs;
						my_load_view($this->setting->theme, 'Admin/documentation_detail', $data);
					}
					else {
						(my_post('documentation_enabled') == '1') ? $documentation_enabled = 1 : $documentation_enabled = 0;
						$update_array = array(
						  'catalog' => my_post('documentation_catalog'),
						  'subject' => my_post('documentation_subject'),
						  'keyword' => my_post('documentation_keyword'),
						  'body' => $this->input->post('documentation_body', FALSE),
						  'updated_time' => my_server_time(),
						  'enabled' => $documentation_enabled
						);
						$this->db->where('ids', $ids)->update('documentation', $update_array);
						$this->session->set_flashdata('flash_success', my_caption('support_documentation_edit_success'));
						redirect(base_url('admin/documentation_edit/' . $ids));
					}
				}
				else {  //remove
				    my_check_demo_mode('alert_json');  //check if it's in demo mode
				    $this->db->where('ids', $ids)->delete('documentation');
					echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
				}
			}
			else {
				echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('global_no_entries_found') . '", "redirect":"CallBack"}';
			}
		}
	}
	
	
	
	public function contact_form_list() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/contact_form_list');
	}
	
	
	
	public function contact_form_view() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('contact_form', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			$this->db->where('ids', my_uri_segment(3))->where('read_status', 0)->update('contact_form', array('read_status'=>1));
			my_load_view($this->setting->theme, 'Admin/contact_form_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function contact_form_action() {
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		if (my_uri_segment(3) == 'remove') {
			$this->db->where('ids', my_uri_segment(4))->delete('contact_form');
			echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
		}
		
	}
	
	
	
	public function front_setting() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/front_setting');
	}
	
	
	
	public function front_setting_action() {
		(!my_check_permission('Global Settings')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('act', 'act', 'trim|in_list[front_setting]');
		$this->form_validation->set_rules('email_address', my_caption('global_email_address'), 'trim|valid_email');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/front_setting');
		}
		else {
			$front_setting = json_decode($this->setting->front_setting, TRUE);
			$logo_filename = $front_setting['logo'];
			if (isset($_FILES['userfile']['name'])) {
				if ($_FILES['userfile']['name'] != '') {
					$config = array(
					  'upload_path' => FCPATH . $this->config->item('my_upload_directory'),
					  'allowed_types' => 'png|jpg|jpeg|gif|svg',
					  'encrypt_name' => TRUE
					);
					$this->load->library('upload', $config);
					if ($this->upload->do_upload()) {
						try {unlink(FCPATH . $this->config->item('my_upload_directory') . $logo_filename);} catch(\Exception $e) {} //remove old file
						$logo_filename = $this->upload->data('file_name');
					}
				}
			}
			$this->admin_model->front_setting_update($logo_filename);
			$this->session->set_flashdata('flash_success', my_caption('front_setting_update_success'));
			redirect(base_url('admin/front_setting'));
		}
	}
	
	
	
	public function subscriber() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/list_subscriber');
	}
	
	
	
	public function subscriber_action() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		if (my_uri_segment(3) == 'remove') {
			my_check_demo_mode('alert_json');  //check if it's in demo mode
			$this->db->where('ids', my_uri_segment(4))->delete('subscriber');
			echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
		}
		elseif (my_uri_segment(3) == 'download') {
			my_check_demo_mode();  //check if it's in demo mode
			$query = $this->db->order_by('id', 'asc')->get('subscriber');
			if ($query->num_rows()) {
				$rs = $query->result();
				$data = '';
				foreach ($rs as $row) {
					$data .= $row->email_address . "\r\n";
				}
				$this->load->helper('download');
				force_download('subscriber.txt', $data);
			}
			else {
				$this->session->set_flashdata('flash_danger', my_caption('subscriber_no_subscriber_to_download'));
				redirect('admin/subscriber');
			}
		}
	}
	
	
	
	public function blog() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/blog_list');
	}
	
	
	
	public function blog_new() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Admin/blog_detail');
	}
	
	
	
	public function blog_new_action() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$this->form_validation->set_rules('blog_catalog', my_caption('blog_catalog'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('blog_subject', my_caption('blog_subject'), 'trim|required|max_length[255]');
		$this->form_validation->set_rules('blog_keyword', my_caption('global_keyword'), 'trim|max_length[1024]');
		$this->form_validation->set_rules('blog_body', my_caption('blog_body'), 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Admin/blog_detail');
		}
		else {
			$ids = $this->admin_model->blog_save();
			$this->session->set_flashdata('flash_success', my_caption('blog_new_success'));
			redirect(base_url('admin/blog_edit/' . $ids));
		}
		
	}
	
	
	
	public function blog_edit() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('blog', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Admin/blog_detail', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function blog_edit_action() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		$ids = my_uri_segment(3);
		$query = $this->db->where('ids', $ids)->get('blog', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$this->form_validation->set_rules('blog_catalog', my_caption('blog_catalog'), 'trim|required|max_length[50]');
			$this->form_validation->set_rules('blog_subject', my_caption('blog_subject'), 'trim|required|max_length[255]');
			$this->form_validation->set_rules('blog_keyword', my_caption('global_keyword'), 'trim|max_length[1024]');
			$this->form_validation->set_rules('blog_body', my_caption('blog_body'), 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$data['rs'] = $rs;
				my_load_view($this->setting->theme, 'Admin/blog_detail', $data);
			}
			else {
				$this->admin_model->blog_update($ids);
				$this->session->set_flashdata('flash_success', my_caption('blog_edit_success'));
				redirect(base_url('admin/blog_edit/' . $ids));
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function blog_remove() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$query = $this->db->where('ids', my_uri_segment(3))->get('blog', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->cover_photo != '') {
				$file_full_path = FCPATH . $this->config->item('my_upload_directory') . 'blog/' . $rs->cover_photo;
				(file_exists($file_full_path)) ? unlink($file_full_path) : null;
			}
		}
		$this->db->where('ids', my_uri_segment(3))->delete('blog');
		echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"CallBack"}';
	}
	
	
	
	public function reset_api_key() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$this->db->where('ids', my_uri_segment(3))->update('user', array('api_key'=>my_random()));
		echo '{"result":true, "title":"", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"'. base_url('admin/edit_user/') . my_uri_segment(3) . '"}';
	}
	
	
	
	public function upgrade_software() {
		(!my_check_role('Super Admin')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$this->load->library('m_aus');
		// handle main script version, do have
		$latest_version = $this->setting->version;
		$script_name = $this->setting->sys_name;
		$latest_version_array = $this->m_aus->getLatestVersion();
		if ($latest_version_array['notification_case'] == 'notification_operation_ok') {
			$latest_version = $latest_version_array['notification_data']['version_number'];
			$script_name = $latest_version_array['notification_data']['product_title'];
		}
		$main_version_array = array('type'=>'Main Script', 'name'=>$script_name, 'current_version'=>$this->setting->version, 'latest_version'=>$latest_version);
		
		// handle addons script versions, depends on situation
		$addons_version_array = array();
		$query = $this->db->order_by('name', 'asc')->get('script_addons');
		if ($query->num_rows()) {
			$rs = $query->result();
			foreach ($rs as $row) {
				$latest_version = $row->version;
				$script_name = $row->name;
				$latest_version_array = $this->m_aus->getLatestVersion('', $row->updater_id, $row->updater_key);
				if ($latest_version_array['notification_case'] == 'notification_operation_ok') {
					$latest_version = $latest_version_array['notification_data']['version_number'];
					$script_name = $latest_version_array['notification_data']['product_title'];
				}
				array_push($addons_version_array, array('type'=>'Add-ons', 'id'=>$row->id, 'name'=>$script_name, 'current_version'=>$row->version, 'latest_version'=>$latest_version));
			}
		}
		
		$data['main_version'] = $main_version_array;
		$data['addons_version'] = $addons_version_array;
		my_load_view($this->setting->theme, 'Admin/upgrade_software', $data);
	}
	
	
	
	public function upgrade_software_view() {
		(!my_check_role('Super Admin')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$this->load->library('m_aus');
		if (my_uri_segment(3) == '') {
			$data['script_ids'] = 0;
			$data['current_version'] = $this->setting->version;
			$data['script_type'] = 'Main Script';
			$data['script_name'] = $this->setting->sys_name;
			$all_version_array = $this->m_aus->getAllVersions();
		}
		else {
			$query = $this->db->where('id', my_uri_segment(3))->get('script_addons', 1);
			if ($query->num_rows()) {
				$rs = $query->row();
				$data['script_ids'] = my_uri_segment(3);
				$data['current_version'] = $rs->version;
				$data['script_type'] = 'Add-ons';
				$data['script_name'] = $rs->name;
				$all_version_array = $this->m_aus->getAllVersions($rs->updater_id, $rs->updater_key);
			}
			else {
				echo my_caption('global_no_entries_found');
				die();
			}
		}
		// handle version information
		if ($all_version_array['notification_case'] == 'notification_operation_ok') {
			$data['script_name'] = $all_version_array['notification_data']['product_title'];
			$data['latest_version'] = $all_version_array['notification_data']['version_number'];
			$data['latest_version_date'] = $all_version_array['notification_data']['version_date'];
			$version_array = array_reverse($all_version_array['notification_data']['product_versions']);
			$current_version_array = explode('.', $data['current_version']);
			$current_version_main = $current_version_array[0];
			$current_version_db = $current_version_array[1];
			$current_version_file =$current_version_array[2];
			$i = 0;
			foreach ($version_array as $version) {
				$remote_version_array = explode('.', $version['version_number']);
				$remote_version_main = $remote_version_array[0];
				$remote_version_db = $remote_version_array[1];
				$remote_version_file = $remote_version_array[2];
				if ($current_version_main == $remote_version_main && $current_version_db == $remote_version_db && $current_version_file == $remote_version_file) {
					break;
				}
				$i++;
			}
			if ($i < (count($version_array)-1)) {
				$data['ready_version'] = $version_array[($i+1)]['version_number'];
				$data['total_version'] = count($version_array) - $i - 1;
			}
			else {
				$data['ready_version'] = $data['current_version'];
				$data['total_version'] = 0;
			}
			if ($data['ready_version'] != $data['current_version']) {
				($data['script_type'] == 'Main Script') ? $ready_version_info_array = $this->m_aus->getLatestVersion($data['ready_version']) : $ready_version_info_array = $this->m_aus->getLatestVersion($data['ready_version'], $rs->updater_id, $rs->updater_key);
				if ($ready_version_info_array['notification_case'] == 'notification_operation_ok') {
					$data['ready_version_date'] = $ready_version_info_array['notification_data']['version_date'];
					$data['ready_version_changelog'] = $ready_version_info_array['notification_data']['version_changelog'];
				}
				else {
					$data['ready_version_changelog'] = 'UnKnown';
				}
			}
			else {
				$data['ready_version_date'] = '';
				$data['ready_version_changelog'] = '';
			}
		}
		else {  //unexpected
			$data['latest_version'] = 'UnKnown';
			$data['latest_version_date'] = 'UnKnown';
			$data['ready_version'] = $data['current_version'];
			$data['ready_version_date'] = 'UnKnown';
			$data['total_version'] = 0;
			$data['ready_version_changelog'] = 'UnKnown';
		}
		// check if the root directory is writable, check several directories but it's not actually enough
		$dir_controller = FCPATH . 'application/controllers';
		$dir_view = FCPATH . 'application/views';
		$dir_helper = FCPATH . 'application/helpers';
		$dir_asset = FCPATH . 'assets';
		$dir_library = FCPATH . 'application/libraries';
		$dir_vendor = FCPATH . 'vendor';
		$dir_hook = FCPATH . 'application/hooks';
		$dir_language = FCPATH . 'application/language';
		if (is_writable(FCPATH) && is_writable($dir_controller) && is_writable($dir_view) && is_writable($dir_helper) && is_writable($dir_asset) && is_writable($dir_library) && is_writable($dir_vendor) && is_writable($dir_hook) && is_writable($dir_language)) {
			$data['writeable'] = TRUE;
		}
		else {
			$data['writeable'] = FALSE;
		}
		my_load_view($this->setting->theme, 'Admin/upgrade_software_view', $data);
	}
	
	
	
	public function upgrade_software_action() {
		(!my_check_role('Super Admin')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		//my_check_demo_mode();  //check if it's in demo mode
		$current_version_checking = TRUE;
		//prevent error version upgrade
		if (my_post('script_ids') == '0') {
			if (my_post('current_version') != $this->setting->version) {
				$this->session->set_flashdata('flash_danger', my_caption('upgrade_software_version_error'));
				$current_version_checking = FALSE;
			}
		}
		else {
			$rs = $this->db->where('id', my_post('script_ids'))->get('script_addons', 1)->row();
			if (my_post('current_version') != $rs->version) {
				$this->session->set_flashdata('flash_danger', my_caption('upgrade_software_version_error'));
				$current_version_checking = FALSE;
			}
		}
		if ($current_version_checking) {
			$this->load->library('m_aus');
			if (my_post('script_ids') == '0') { //main script
				$license_code = $this->db->query("SELECT * FROM script_license limit 1")->row()->LICENSE_CODE;
				$res = $this->m_aus->downloadFile($license_code, 'version_upgrade_file', my_post('ready_version'));
				if ($res['notification_case'] == 'notification_operation_ok') {
					$res = $this->m_aus->fetchQuery($license_code, 'upgrade', my_post('ready_version'));
				}
			}
			else {  //add-ons
			    //$rs = $this->db->where('id', my_post('script_ids'))->get('script_addons', 1)->row(); //This query do exist as it already execute on the upper lines
				$res = $this->m_aus->downloadFile($rs->license_code, 'version_upgrade_file', my_post('ready_version'), $rs->updater_id, $rs->updater_key);
				if ($res['notification_case'] == 'notification_operation_ok') {
					$res = $this->m_aus->fetchQuery($rs->license_code, 'upgrade', my_post('ready_version'), $rs->updater_id, $rs->updater_key);
				}
			}
			if ($res['notification_case'] == 'notification_operation_ok') {  //file downloaded an extract successfully
				mysqli_multi_query($this->db->conn_id, str_replace('MY_DB_PREFIX', $this->db->dbprefix, $res['notification_data']));
				$message = my_caption('upgrade_software_success_header') . my_post('ready_version') . my_caption('upgrade_software_success_tail');
				$this->session->set_flashdata('flash_success', $message);
			}
			else {
				$message = my_caption('upgrade_software_fail') . $res['notification_text'];
				$this->session->set_flashdata('flash_danger', $message);
			}
		}
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	
	
	public function software_license() {
		(!my_check_role('Super Admin')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		if ($this->config->item('my_demo_mode')) {
			$data['purchase_code'] = '********-***-***-***-************';
		}
		else {
			$data['purchase_code'] = $this->db->query("SELECT * FROM script_license limit 1")->row()->LICENSE_CODE;
		}	
		my_load_view($this->setting->theme, 'Admin/software_license', $data);
	}
	
	
	
	public function software_license_action() {
		(!my_check_role('Super Admin')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_check_demo_mode();  //check if it's in demo mode
		if (my_ulic()) {
			$this->session->sess_destroy();
			die(my_caption('software_license_notice_uninstall_success'));
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('software_license_notice_uninstall_fail'));
			redirect('admin/software_license');
		}
	}
	
	
	
	public function email_duplicated_check($email_address) {
		if (my_duplicated_check('user', array('email_address'=>$email_address))) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('email_duplicated_check', my_caption('signup_email_taken'));
			return FALSE;
		}
	}
	
	
	
	public function check_underscore() {
		if (strstr(my_post('sim_value'), '_')) {
			$this->form_validation->set_message('check_underscore', my_caption('rp_underscore_not_allowed'));
			return FALSE;
		}
		else {
			return TRUE;
		}
	}
	
	
	
    public function icon_upload() {
		$this->load->library('m_upload');
		$this->m_upload->set_upload_path('/' . $this->config->item('my_upload_directory'));
		$this->m_upload->set_allowed_types('ico');
		$this->m_upload->set_file_name('favicon');
		$res = $this->m_upload->upload_done();
		if ($res['status']) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('icon_upload', $res['error']);
			return FALSE;
		}
	}
	
	
	
	protected function get_ticket($ids) {
		$query = $this->db->where('ids', $ids)->where('ids_father', 0)->get('ticket', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();  //one record only
			$data['rs_follow'] = $this->db->where('ids_father', $ids)->order_by('created_time', 'asc')->get('ticket')->result();  //full recordset
			return $data;
		}
		else {
			return FALSE;
		}
	}
	
	
}
?>