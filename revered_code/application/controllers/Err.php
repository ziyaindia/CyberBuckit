<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Err extends CI_Controller {
    public function __construct() {
		parent::__construct();
		$this->setting = my_global_setting('all_fields');
    }
	
	
	
	public function index() {
		redirect('err/my400');
	}
	
	
	
	public function my400() {
		$data['front_setting_array'] = json_decode($this->setting->front_setting, TRUE);
		my_load_view($this->setting->theme, 'Front/err', $data);
	}
	
	
}
?>