<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Redirect extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
        date_default_timezone_set($this->config->item('time_reference'));		
	}
	
	
	
	public function index() {
		$_SESSION['access_code'] = my_get('from');
		$this->set_specific_cookie('src_from', my_get('from'));
		$this->set_specific_cookie('coupon_code', my_get('code'));
		$this->set_specific_cookie('referral_code', my_get('id'));
		redirect(base_url());
	}
	
	
	
	protected function set_specific_cookie($key, $code) {
		$specific_code = array(
		  'name' => $key,
		  'value' => substr($code, 0, 50),
		  'domain' => $_SERVER['HTTP_HOST'],
		  'expire' => 365*86400
		);
		set_cookie($specific_code);
		return TRUE;
	}
	
}