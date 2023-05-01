<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	
	public $setting;
	
    public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		date_default_timezone_set($this->config->item('time_reference'));
		$this->setting = my_global_setting('all_fields');
    }
	
	
	
	public function index() {
		$front_enabled = json_decode($this->setting->front_setting, TRUE);
		if ($front_enabled['enabled']) {
			redirect(base_url('home'));
		}
		else {
			(isset($_SESSION['user_ids'])) ? redirect('dashboard') : null;
			$this->signin();
		}
		
	}
	
	
	
	public function signin() {
		(isset($_SESSION['user_ids'])) ? redirect('dashboard') : null;
		if (get_cookie('remember_signin', TRUE) != null) {
			$query = $this->db->where('ids', get_cookie('remember_signin', TRUE))->where('expired_time>', my_server_time())->get('session', 1);
			if ($query->num_rows()) {
				$query_user = $this->db->where('ids', $query->row()->user_ids)->get('user', 1);
				if ($query_user->num_rows()) {
					$this->user_model->sigin_success_log($query_user->row(), 'web-remember');
					redirect(base_url('dashboard'));
				}
				else {
					my_load_view($this->setting->theme, 'Auth/signin');
				}
			}
			else {
				my_load_view($this->setting->theme, 'Auth/signin');
			}
		}
		else {
			my_load_view($this->setting->theme, 'Auth/signin');
		}
	}
	
	
	
	public function signup() {
		(isset($_SESSION['user_ids'])) ? redirect('dashboard') : null;
		if ($this->setting->signup_enabled) {  //if sign up from social media
		    $data = [];
			if (!empty($_SESSION['oauth_ids'])) {
				$query = $this->db->where('ids', $_SESSION['oauth_ids'])->get('oauth_connector', 1);
				if ($query->num_rows()) {
					$data['oauth_rs'] = $query->row();
				}
				unset($_SESSION['oauth_ids']);
			}
			my_load_view($this->setting->theme, 'Auth/signup', $data);
		}
		else {
			echo my_caption('signup_register_disabled');
		}
	}
	
	
	
	public function signup_action() {
		if ($this->setting->signup_enabled) {
			$this->form_validation->set_rules('first_name', my_caption('signup_first_name_label'), 'trim|required|max_length[50]');
			$this->form_validation->set_rules('last_name', my_caption('signup_last_name_label'), 'trim|required|max_length[50]');
			$this->form_validation->set_rules('email_address', my_caption('global_email_address'), 'trim|required|valid_email|max_length[50]|callback_email_duplicated_check');
			if ($this->setting->recaptcha_enabled) {$this->form_validation->set_rules('g-recaptcha-response', 'Google Recaptcha', 'callback_google_recaptcha');}
			if (!empty(my_post('password'))) {
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
			$this->form_validation->set_rules('password', my_caption('signup_password_label'), $condition);
			$this->form_validation->set_rules('confirm_password', my_caption('signup_confirm_label'), 'trim|required|min_length[6]|max_length[20]|matches[password]');
			$data = [];
			if (!empty($_SESSION['oauth_signup_ids'])) {
				$query = $this->db->where('ids', $_SESSION['oauth_signup_ids'])->get('oauth_connector', 1);
				if ($query->num_rows()) {
					$data['oauth_rs'] = $query->row();
				}
			}
			if ($this->setting->tc_show) {
				$this->form_validation->set_rules('tc_agree', my_caption('signup_tc_agree'), 'required');
			}
			if ($this->form_validation->run() == FALSE) {
				my_load_view($this->setting->theme, 'Auth/signup', $data);
			}
			else {
				$check_invitation_pass = FALSE;
				if (!empty($_SESSION['invite_email'])) {  //check whether invited email and input email is same
					($_SESSION['invite_email'] == my_post('email_address')) ? $check_invitation_pass = TRUE : $check_invitation_pass = FALSE;
				}
				else {
					$check_invitation_pass = TRUE;
				}
				if (!$check_invitation_pass) {
					$this->session->set_flashdata('flash_danger', my_caption('signup_invitation_email_not_verified'));
					my_load_view($this->setting->theme, 'Auth/signup', $data);
				}
				else {
					$res = $this->user_model->save_user($this->setting);
					if ($res['result']) {  //sign up success
						$this->session->set_flashdata('flash_success', $res['message']);
						redirect(base_url('auth/signin'));
					}
					else {
						$this->session->set_flashdata('flash_danger', $res['message']);
						my_load_view($this->setting->theme, 'Auth/signup', $data);
					}
				}
			}
		}
		else {
			echo my_caption('signup_register_disabled');
		}
	}
	
	
	
	public function activate_account() {
		$query = $this->db->where('type', 'signup_activation')->where('token', my_uri_segment(3))->get('token', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if (!$rs->done) {
				$this->db->where('id', $rs->id)->update('token', array('done'=>1));
				$this->db->where('ids', $rs->reference)->where('status!=', 2)->update('user', array('email_verified'=>1, 'status'=>1));
				$this->session->set_flashdata('flash_success', my_caption('signup_activate_success'));
				// *** log started
				my_log($rs->reference, 'Information', 'activate_account', json_encode(my_ua()));
				// *** log ended
			}
			else {
				$this->session->set_flashdata('flash_warning', my_caption('signup_activate_duplicated'));
			}
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('signup_activate_failed'));
		}
		my_load_view($this->setting->theme, 'Auth/signin');
	}
	
	
	
	public function invite_user() {
		$query = $this->db->where('type', 'invite_activation')->where('token', my_uri_segment(3))->get('token', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if (!$rs->done) {
				$session_array = array(
				  'invite_email' => $rs->email,
				  'invite_id' => $rs->id
				);
				$this->session->set_userdata($session_array);
				$this->session->set_flashdata('flash_success', my_caption('signup_invitation_success'));
				my_load_view($this->setting->theme, 'Auth/signup');
			}
			else {
				$this->session->set_flashdata('flash_warning', my_caption('signup_activate_duplicated'));
				my_load_view($this->setting->theme, 'Auth/signin');
			}
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('signup_invitation_failed'));
			my_load_view($this->setting->theme, 'Auth/signup');
		}
	}
	
	
	
	public function signin_action() {
		$this->form_validation->set_rules('username', my_caption('signin_username_label'), 'trim|required');
		$this->form_validation->set_rules('password', my_caption('signin_password_label'), 'trim|required');
		if ($this->setting->recaptcha_enabled) {$this->form_validation->set_rules('g-recaptcha-response', 'Google Recaptcha', 'callback_google_recaptcha');}
		if ($this->form_validation->run() == FALSE) {
			my_load_view($this->setting->theme, 'Auth/signin');
		}
		else {
			$throttle_check = my_throttle_check(my_post('username'));
			if (!$throttle_check['result']) {  //throttling checking
				$this->session->set_flashdata('flash_danger', $throttle_check['message']);
				redirect(base_url('auth/signin'));
			}
			elseif ($this->setting->two_factor_authentication == 'email' && my_check_smtp_setting()) {  //2FA is enabled and email gateway is set(but ignore whether it's available)
				$rs = $this->user_model->signin_query_user(my_post('username'), my_post('password'));
				$_SESSION['two_factor_authentication'] = $rs;
				if ($rs) {
					if (get_cookie('remember_2FA_' . $rs->ids, TRUE) != null) {
						$query_cookie = $this->db->where('ids', get_cookie('remember_2FA_' . $rs->ids, TRUE))->where('user_ids', $rs->ids)->where('expired_time>', my_server_time())->get('session', 1);
						if ($query_cookie->num_rows()) {
							$this->user_model->signin_check();
							redirect(base_url('dashboard'));
						}
						else {  // cookie doestn't match between server side and local computer
							$this->proceed_to_2FA($rs);
						}
					}
					else {  // cookie not found at local computer
						$this->proceed_to_2FA($rs);
					}
				}
				else {
					$this->session->set_flashdata('flash_danger', my_caption('signin_credential_error'));
					redirect(base_url('auth/signin'));
				}
			}
			else {  // direct sign in
				$res = $this->user_model->signin_check();
				if ($res['result']) {
					redirect(base_url('dashboard'));
				}
				else {
					$this->session->set_flashdata('flash_danger', $res['message']);
					my_load_view($this->setting->theme, 'Auth/signin');
				}
			}
		}
	}
	
	
	
	public function proceed_to_2FA($rs) {
		($this->setting->two_factor_authentication == 'email') ? $send_target = $rs->email_address : $send_target = $rs->phone;
		$res = $this->user_model->send_2FA($rs->ids, $this->setting->two_factor_authentication, $send_target);
		if ($res['result']) {
			$this->session->set_flashdata('flash_success', $res['message']);
		}
		else {
			$this->session->set_flashdata('flash_danger', $res['message']);
		}
		redirect(base_url('auth/two_factor_authentication')); //redirect to 2FA entry
	}
	
	
	
	public function two_factor_authentication() {
		if (!empty($_SESSION['two_factor_authentication'])) {
			my_load_view($this->setting->theme, 'Auth/two_factor_authentication');
		}
		else {
			redirect(base_url('auth/signin'));
		}
	}
	
	
	
	public function two_factor_authentication_action() {
		if (!empty($_SESSION['two_factor_authentication'])) {
			$this->form_validation->set_rules('code', my_caption('twofa_code_label'), 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				my_load_view($this->setting->theme, 'Auth/two_factor_authentication');
			}
			else {
				$throttle_check = my_throttle_check($_SESSION['two_factor_authentication']->ids);
				if (!$throttle_check['result']) {
					$this->session->set_flashdata('flash_danger', $throttle_check['message']);
					redirect(base_url('auth/signin'));
				}
				else {
					$this->db->group_start()->where('email', $_SESSION['two_factor_authentication']->email_address)->or_where('phone', $_SESSION['two_factor_authentication']->phone)->group_end();
					$query = $this->db->where('token', my_post('code'))->where('type', '2FA')->where('done', 0)->get('token', 1);
					if ($query->num_rows()) {
						$rs_2FA = $query->row();
						$this->user_model->signin_check();
						$this->db->where('id', $rs_2FA->id)->update('token', array('done'=>1));
						if (my_post('remember')) {
							$this->user_model->save_cookie('remember_2FA_' . $rs_2FA->reference, $rs_2FA->reference);
						}
						else {
							delete_cookie('remember_2FA_' . $rs_2FA->reference, get_cookie('remember_2FA', TRUE));
						}
						redirect(base_url('dashboard'));  // impossible to get failure return at this point
					}
					else {
						my_throttle_log($_SESSION['two_factor_authentication']->ids);
						$this->session->set_flashdata('flash_danger', my_caption('twofa_code_error'));
						my_load_view($this->setting->theme, 'Auth/two_factor_authentication');
					}
				}
			}
		}
		else {
			redirect(base_url('auth/signin'));
		}
	}
	
	
	
	public function forget() {
		(isset($_SESSION['user_ids'])) ? redirect('dashboard') : null;
		if ($this->setting->forget_enabled) {
			my_load_view($this->setting->theme, 'Auth/forget');
		}
		else {
			echo my_caption('forget_forget_disabled');
		}
	}
	
	
	
	public function forget_action() {
		my_check_demo_mode();  //check if it's in demo mode
		$throttle_check = my_throttle_check(my_post('email_address'));
		if (!$throttle_check['result']) {
			$this->session->set_flashdata('flash_danger', $throttle_check['message']);
			redirect(base_url('Auth/forget'));
		}
		else {
			if ($this->setting->forget_enabled) {
				$this->form_validation->set_rules('email_address', my_caption('forget_email_address_label'), 'trim|required|valid_email');
				if ($this->setting->recaptcha_enabled) {$this->form_validation->set_rules('g-recaptcha-response', 'Google Recaptcha', 'callback_google_recaptcha');}
				if ($this->form_validation->run() == FALSE) {
					my_load_view($this->setting->theme, 'Auth/forget');
				}
				else {
					my_throttle_log(my_post('email_address'));
					$res = $this->user_model->forget_password();
					if ($res['result']) {
						$this->session->set_flashdata('flash_success', $res['message']);
					}
					else {
						$this->session->set_flashdata('flash_danger', $res['message']);
					}
					my_load_view($this->setting->theme, 'Auth/forget');
				}
			}
			else {
				echo my_caption('forget_forget_disabled');
			}
		}		
	}
	
	
	
	public function reset_password() {
		if ($this->setting->forget_enabled) {
			$query = $this->db->where('type', 'reset_password')->where('done', 0)->where('token', my_uri_segment(3))->get('token', 1);
			if ($query->num_rows()) {
				my_load_view($this->setting->theme, 'Auth/reset');
			}
			else {
				$this->session->set_flashdata('flash_danger', my_caption('reset_invaild_link'));
				redirect(base_url('auth/forget'));
			}
		}
		else {
			echo my_caption('forget_forget_disabled');
		}
	}
	
	
	
	public function reset_password_action() {
		if ($this->setting->forget_enabled) {
			if (!empty(my_post('password'))) {
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
			$this->form_validation->set_rules('password', my_caption('reset_password_label'), $condition);
			$this->form_validation->set_rules('confirm_password', my_caption('reset_confirm_password_label'), 'trim|required|matches[password]');
			if ($this->form_validation->run() == FALSE) {
				my_load_view($this->setting->theme, 'Auth/reset');
			}
			else {
				$query = $this->db->where('type', 'reset_password')->where('done', 0)->where('token', my_uri_segment(3))->get('token', 1);
				if ($query->num_rows()) {
				    $rs = $query->row();
					$this->db->where('id', $rs->id)->update('token', array('done'=>1));
					$this->db->where('ids', $rs->reference)->update('user', array('password'=>my_hash_password(my_post('password'))));
					$this->session->set_flashdata('flash_success', my_caption('reset_success'));
					redirect(base_url('auth/signin'));
				}
                else {
				    $this->session->set_flashdata('flash_danger', my_caption('reset_invaild_link'));
					redirect(base_url('auth/forget'));
				}
			}
		}
		else {
			echo my_caption('forget_forget_disabled');
		}
	}
	
	
	
	public function change_email() {
		$query = $this->db->where('type', 'change_email')->where('token', my_uri_segment(3))->where('done', 0)->get('token', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$query_user = $this->db->where('ids', $rs->reference)->where('email_address_pending', $rs->email)->where('status', 1)->get('user', 1);
			if ($query_user->num_rows()) {
				$this->db->where('id', $rs->id)->update('token', array('done'=>1));
				$this->db->where('ids', $rs->reference)->update('user', array('email_address'=>$rs->email, 'email_address_pending'=>''));
				$this->session->set_flashdata('flash_success', my_caption('auth_email_changed_success'));
			}
			else {
				$this->session->set_flashdata('flash_danger', my_caption('auth_email_changed_fail'));
			}
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('auth_email_changed_fail'));
		}
		redirect(base_url('auth/signin'));
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
	
	
	
	public function google_recaptcha($recaptchaResponse) {
		$recaptcha_array = json_decode(my_global_setting('all_fields')->recaptcha_detail, TRUE);
        $url="https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_array['secret_key']."&response=".$recaptchaResponse."&remoteip=".my_remote_info('ip');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $status = json_decode($output, TRUE);
        if ($status['success']) {
			return TRUE;
		}else{
			$this->form_validation->set_message('google_recaptcha', my_caption('auth_google_recaptcha_error'));
			return FALSE;
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
