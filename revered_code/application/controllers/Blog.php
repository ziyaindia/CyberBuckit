<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	
	public function __construct() {
		parent::__construct();
		$this->setting = my_global_setting('all_fields');
		$this->setting->front_setting_array = json_decode($this->setting->front_setting, TRUE);
		($this->router->default_controller == 'home') ? $this->home_url = '' : $this->home_url = 'home/';
		if (!$this->setting->front_setting_array['enabled']) {
			redirect('auth/signin');
		}
    }
	
	
	
	public function index() {
		$data['front_setting_array'] = $this->setting->front_setting_array;
		(my_get('per_page') == '') ? $start_cursor = 0 : $start_cursor = my_get('per_page');
		$total_rows = $this->db->where('enabled', 1)->count_all('blog');
		$per_page = 6;
		$query = $this->db->where('enabled', 1)->order_by('id', 'desc')->limit($per_page, $start_cursor)->get('blog');
		if ($query->num_rows()) {
			$data['rs'] = $query->result();
		}
		else {
			$data['rs'] = array();
		}
		$config_array = array(
		  'scheme' => 'impact',
		  'base_url' => base_url('blog/'),
		  'total_rows' => $total_rows,
		  'per_page' => $per_page
		);
		$data['pagination_html'] = my_generate_pagination($config_array);
		my_load_view($this->setting->theme, 'Front/blog_list', $data);
	}	
	
	
	
	public function view() {
		$data['front_setting_array'] = $this->setting->front_setting_array;
		$query = $this->db->where('slug', my_uri_segment(3))->get('blog', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$data['rs'] = $rs;
			$data['html_keyword'] = $rs->keyword;
			$data['html_title'] = $rs->subject . ' - ' . $this->setting->front_setting_array['html_title'];
			my_load_view($this->setting->theme, 'Front/blog_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}


}
?>