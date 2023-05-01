<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends MY_Controller {
	
    public function __construct() {
		parent::__construct();
		
    }
	
	
	
	public function index() {
		redirect(base_url('files/file_manager'));
	}
	
	
	
	public function file_manager() {
		(my_uri_segment(3) == '') ? $seg3 = 'all' : $seg3 = my_uri_segment(3);
		(my_uri_segment(4) == '') ? $seg4 = 'all' : $seg4 = my_uri_segment(4);
		if ($seg3 != 'all') {  //need to list by file type
			$file_ext_list = my_get_file_ext($seg3);
			$file_ext_list_array = explode(',', $file_ext_list);
			if ($seg3 != 'other') {
				$this->db->where_in('file_ext', $file_ext_list_array);
			}
			else {  //other type
				$this->db->where_not_in('file_ext', $file_ext_list_array);
			}
		}
		if ($seg4 != 'all') {  //need to list by catalog
			$this->db->where('catalog', str_replace('_nbsp_', ' ', $seg4));
		}
		$rs = $this->db->where('user_ids', $_SESSION['user_ids'])->where('temporary_ids', '')->order_by('id', 'desc')->get('file_manager')->result();
		$data['file_list'] = $rs;
		$data['catalog_array'] = my_get_catalog('file_manager_folder');
		my_load_view($this->setting->theme, 'Generic/file_manager', $data);
	}
	


	public function file_upload() {
		$_SESSION['temporary_files_ids'] = my_random();
		$data['catalog_options'] = my_get_catalog('file_manager_folder');
		my_load_view($this->setting->theme, 'Generic/file_upload', $data);
	}
	
	
	
	public function file_upload_action() {
		if ($this->config->item('my_demo_mode')) {
			http_response_code(500);
			echo my_caption('file_upload_demo_mode');
			exit();
		}
		else {
			$file_setting_array = json_decode(my_global_setting('file_setting'), TRUE);
			$file_name = str_replace(' ', '_', html_purify($_FILES["file"]["name"]));
			$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			$file_name_saved = my_random();
			$config = array(
			  'upload_path' => $this->config->item('my_upload_directory') . 'temporary/',
			  'allowed_types' => $file_setting_array['file_type'],
			  'file_ext_tolower' => TRUE,
			  'max_size' => $file_setting_array['file_size'],
			  'file_name' => $file_name_saved . '.' . $file_ext
			);
			$this->load->library('upload', $config);
			if ($this->upload->do_upload('file')) { //upload successfully
				$insert_array = array(
				  'ids' => $file_name_saved,
				  'user_ids' => $_SESSION['user_ids'],
				  'temporary_ids' => $_SESSION['temporary_files_ids'],
				  'original_filename' => $file_name,
				  'file_ext' => $file_ext,
				  'created_time' => my_server_time(),
				  'trash' => 0
				);
				$this->db->insert('file_manager', $insert_array);
				http_response_code(200);
				exit();
			}
			else {
				http_response_code(500);
				echo my_esc_html($this->upload->display_errors('', ''));
				exit();
			}
		}	
	}
	
	
	
	public function file_upload_save_action() {
		my_check_demo_mode();  //check if it's in demo mode
		$query = $this->db->where('temporary_ids', $_SESSION['temporary_files_ids'])->get('file_manager');
		if ($query->num_rows()) {
			$catalog = my_post('file_catalog');
			$description = my_post('description');
			$rs = $query->result();
			foreach ($rs as $row) {
				$file_ext = $row->file_ext;
				$old_path = $this->config->item('my_upload_directory') . 'temporary/' . $row->ids . '.' . $file_ext;
				$new_path = $this->config->item('my_upload_directory') . $catalog . '/' . $row->ids . '.' . $file_ext;
				if (rename($old_path, $new_path)) {
					$this->db->where('id', $row->id)->update('file_manager', array('temporary_ids'=>'', 'catalog'=>$catalog, 'description'=>$description));
					if (filesize($new_path)>50000 && my_get_file_icon($file_ext) == 'img') {  //crete thumbnail
						my_generate_thumbnail($new_path, 240, 200);
					}
				}
			}
			$this->session->set_flashdata('flash_success', my_caption('file_upload_succeed_to_upload'));
			redirect(base_url('files/file_manager'));
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('file_upload_no_valid_files'));
			redirect(base_url('files/file_upload'));
		}
	}
	
	
	
	public function file_upload_remove_action() {
		if (!$this->config->item('my_demo_mode')) {
			$query = $this->db->where('original_filename',  my_uri_segment(3))->where('temporary_ids!=', '')->get('file_manager', 1);
			if ($query->num_rows()) {
				$rs = $query->row();
				$file_path = $this->config->item('my_upload_directory') . 'temporary/' . $rs->ids . '.' . $rs->file_ext;
				try {unlink($file_path);} catch(\Exception $e) {}
				$this->db->where('id', $rs->id)->delete('file_manager');
			}
		}
	}



	public function file_view() {
		$query = $this->db->where('ids', my_get('ids'))->where('temporary_ids', '')->get('file_manager', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$data_array['result'] = true;
			$data_array['catalog'] = $rs->catalog;
			$data_array['original_filename'] = $rs->original_filename;
			$data_array['filename_ext'] = $rs->file_ext;
			$data_array['file_icon'] = my_get_file_icon($rs->file_ext);
			$data_array['description'] = $rs->description;
			$data_array['created_time'] = my_conversion_from_server_to_local_time($rs->created_time, $this->user_timezone, $this->user_date_format);
			echo json_encode($data_array);
		}
		else {
			$data = '{"result":false, "message":"' . my_caption('global_no_entries_found') . '"}';
			echo json_encode($data);
		}
	}
	
	
	
	public function file_download() {
		$query = $this->db->where('ids', my_uri_segment(3))->where('temporary_ids', '')->get('file_manager', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$this->load->helper('download');
			$file_path = $this->config->item('my_upload_directory') . $rs->catalog . '/' . my_uri_segment(3) . '.' . $rs->file_ext;
			force_download($rs->original_filename, file_get_contents($file_path), TRUE);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	


	public function file_remove_action() {
		$query = $this->db->where('ids', my_uri_segment(3))->get('file_manager', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			try {
				$file_path = $this->config->item('my_upload_directory') . $rs->catalog . '/' . my_uri_segment(3) . '.' . $rs->file_ext;
				if (file_exists($file_path)) {
					unlink($file_path);
				}
				if (my_get_file_icon($rs->file_ext) == 'img') {
					$file_path = $this->config->item('my_upload_directory') . $rs->catalog . '/' . my_uri_segment(3) . '_thumb.' . $rs->file_ext;
					if (file_exists($file_path)) {
						unlink($file_path);
					}
				}
			}
			catch(\Exception $e) {}
			$this->db->where('ids', my_uri_segment(3))->delete('file_manager');
			echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"ReloadPage"}';
		}
		else {
			echo '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('global_no_entries_found') . '", "redirect":"ReloadPage"}';
		}
	}	
	
	
}
?>