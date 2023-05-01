<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_upload
{
	protected $upload_path, $allowed_types, $file_name, $max_size = 1000, $max_width = 1920, $max_height = 1080, $remove_file_name = '';
	
	public function set_upload_path($path) {
		$this->upload_path = $path;
	}

	public function set_allowed_types($types) {
		$this->allowed_types = $types;
	}
	
	public function set_file_name($name) {
		$this->file_name = $name;
	}
	
	public function set_max_size($maxSize) {
		$this->max_size = $maxSize;
	}
	
	public function set_max_width($maxWidth) {
		$this->max_width = $maxWidth;
	}
	
	public function set_max_height($maxHeight) {
		$this->max_height = $maxHeight;
	}
	
	public function set_remove_file($removeFileName) {
		$this->remove_file_name = $$removeFileName;
	}
	
	
	public function upload_done() {
		$CI = &get_instance();
		$config = array(
		  'upload_path' => FCPATH . $this->upload_path,
		  'allowed_types' => $this->allowed_types,
		  'file_name' => $this->file_name,
		  'overwrite' => TRUE,
		  'file_ext_tolower' => TRUE,
		  'max_size' => $this->max_size,
		  'max_width' => $this->max_width,
		  'max_height' => $this->max_height
		);
		$CI->load->library('upload', $config);
		if ($CI->upload->do_upload()) {
			if ($this->remove_file_name != '') {
				unlink(FCPATH . $upload_path . $this->remove_file_name);
			}
            return array('status'=>TRUE);
        }
        else {
			return array('status'=>FALSE, 'error'=>$CI->upload->display_errors());
        }
		
	}
	
	
	
}
?>