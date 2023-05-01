<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generic extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
        date_default_timezone_set($this->config->item('time_reference'));
		$this->setting = my_global_setting('all_fields');		
	}
	
	

	
	
	// This is used for switch language, selected language is saved in cookie, the cookie name is "site_lang".
	// It'll redirect back to current url after choosed.
	public function switchLang($language = "") {
		my_set_language_cookie($language);
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	
	
	public function sign_out() {
		$this->load->model('user_model');
		(get_cookie('remember_signin', TRUE) != '') ? $this->user_model->remove_cookie('remember_signin', get_cookie('remember_signin', TRUE)) : null;
		$this->db->where('ids', $_SESSION['user_ids'])->update('user', array('online'=>0));
		$this->session->sess_destroy();
		redirect(base_url());
	}	
	
	
	
	public function terms_conditions() {
		$setting = my_global_setting('all_fields');
		$data['setting'] = $setting;
		my_load_view($setting->theme, 'Auth/terms_conditions', $data);
	}
	
	
	
	public function online() {
		$this->db->where('ids', my_uri_segment(3))->update('user', array('online'=>1, 'online_time'=>my_server_time()));
		echo '0';
	}

	
	
}