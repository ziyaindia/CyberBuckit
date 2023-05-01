<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once FCPATH . 'vendor/autoload.php';
use chriskacerguis\RestServer\RestController;

class Api_v1 extends RestController {
	
	public $json_array = [];
	public $throttle_switch = TRUE;
	public $setting;
	
    public function __construct() {
		parent::__construct();
		
		date_default_timezone_set($this->config->item('time_reference'));
		
		$this->load->model('user_model');
		
		// check if system is under maintenance
		$this->setting = my_global_setting('all_fields');
		if ($this->setting->maintenance_mode) {
			$resp_array = array(
			  'status' => FALSE,
			  'error' => $this->setting->maintenance_message
			);
			$this->response($resp_array, 200);
		}

		// check if api function is enabled
		if (!$this->setting->api_enabled) {
			$resp_array = array(
			  'status' => FALSE,
			  'error' => 'API is disabled.'
			);
			$this->response($resp_array, 200);
		}
		
		// check if json posted by client is a real json string
		$raw_data = html_purify($this->input->raw_input_stream);
		if (!empty($raw_data)) {
			$this->json_array = json_decode($raw_data, TRUE);
			if (json_last_error() != JSON_ERROR_NONE) {
				$resp_array = array(
				  'status' => FALSE,
				  'error' => 'Invalid Json Data'
				);
				$this->response($resp_array, 200);
			}
		}
    }
	
	
	
	public function status_get() {  //This method is no auth
		$resp_array = array('status' => TRUE, 'message' => 'The system is running');
		$this->response($resp_array, 200);
	}
	
	
	
	public function signin_get() {  //This method is no auth
		if (isset($this->json_array['username']) && isset($this->json_array['password'])) {
			$throttle_check = my_throttle_check($this->json_array['username']);
			if (!$throttle_check['result'] && $this->throttle_switch) {
				$resp_array = array(
				  'status' => FALSE,
				  'error' => $throttle_check['message']
				);
			}
			else {
				$rs = $this->user_model->signin_query_user($this->json_array['username'], $this->json_array['password']);
				if ($rs != FALSE) {
					$this->user_model->sigin_success_log($rs, 'api');
					$resp_array = array('status' => TRUE);
					$resp_array += $this->user_model->user_fileds_builder($rs);
				}
				else {
					my_throttle_log($this->json_array['username']);
					$resp_array = array(
					  'status' => FALSE,
					  'error' => my_caption('api_sigin_invalid_credential')
					);
				}
			}
		}
		else {
			$resp_array = array(
			  'status' => FALSE,
			  'error' => my_caption('api_incomplete_parameter')
			);
		}
		$this->response($resp_array, 200);
	}
	
	
	
	public function signup_put() {  //This method is no auth
		$throttle_check = my_throttle_check($this->json_array['emailAddress']);
		if (!$this->setting->signup_enabled) {  //signup disabled
			$resp_array = array(
			  'status' => FALSE,
			  'error' => my_caption('signup_register_disabled')
			);
		}
		elseif (!$throttle_check['result'] && $this->throttle_switch) {
			$resp_array = array(
			  'status' => FALSE,
			  'error' => $throttle_check['message']
			);
		}
		elseif (isset($this->json_array['emailAddress']) && isset($this->json_array['firstName']) && isset($this->json_array['lastName']) && isset($this->json_array['password'])) {
			$validate_res = my_validate_data($this->json_array['emailAddress'], 'emailAddress', 'trim|required|valid_email|max_length[50]');  //validate emailAddress
			if ($validate_res['status']) {
				$this->json_array['emailAddress'] = $validate_res['data'];
				if (my_duplicated_check('user', array('email_address'=>$this->json_array['emailAddress']))) {
					$validate_res['status'] = TRUE;
				}
				else {
					$validate_res['status'] = FALSE;
					$validate_res['message'] = my_caption('signup_email_taken');
				}
			}
			if ($validate_res['status']) {
				$validate_res = my_validate_data($this->json_array['firstName'], 'firstName', 'trim|required|max_length[50]');
			}
			if ($validate_res['status']) {
				$this->json_array['firstName'] = $validate_res['data'];
				$validate_res = my_validate_data($this->json_array['lastName'], 'lastName', 'trim|required|max_length[50]');
			}
			if ($validate_res['status']) {
				$this->json_array['lastName'] = $validate_res['data'];
				if (!empty($this->json_array['password'])) {
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
					$condition = 'trim|required|min_length[' . $min_length . ']|max_length[20]|password_strength[' . $this->setting->psr . ']';
				}
				else {
					$condition = 'trim|required';
				}
				$validate_res = my_validate_data($this->json_array['password'], 'password', $condition);
			}
			if ($validate_res['status']) {
				$this->json_array['password'] = $validate_res['data'];
				my_throttle_log('');
				$res = $this->user_model->save_user($this->setting, 'api', $this->json_array);
				if ($res['result']) {
					$resp_array = array(
					  'status' => TRUE,
					  'message' => $res['message']
					);
				}
				else {
					$resp_array = array(
					  'status' => FALSE,
					  'error' => $res['message']
					);
				}
			}
			else {
				$resp_array = array(
				  'status' => FALSE,
				  'error' => $validate_res['message']
				);
			}
		}
		else {  //parameter incomplete
			$resp_array = array(
			  'status' => FALSE,
			  'error' => my_caption('api_incomplete_parameter')
			);
		}
		$this->response($resp_array, 200);
	}
	
	
	
	public function forgot_post() {
		$throttle_check = my_throttle_check($this->json_array['emailAddress']);
		if (!$this->setting->forget_enabled) {
			$resp_array = array(
			  'status' => FALSE,
			  'error' => my_caption('forget_forget_disabled')
			);
		}
		elseif (!$throttle_check['result'] && $this->throttle_switch) {
			$resp_array = array(
			  'status' => FALSE,
			  'error' => $throttle_check['message']
			);
		}
		elseif (isset($this->json_array['emailAddress'])) {
			my_throttle_log($this->json_array['emailAddress']);
			$validate_res = my_validate_data($this->json_array['emailAddress'], 'emailAddress', 'trim|required|valid_email');  //validate emailAddress
			if ($validate_res['status']) {
				$res = $this->user_model->forget_password($this->json_array['emailAddress']);
				if ($res['result']) {
					$resp_array = array(
					  'status' => TRUE,
					  'message' => $res['message']
					);
				}
				else {
					$resp_array = array(
					  'status' => FALSE,
					  'error' => $res['message']
					);
			    }
			}
			else {
				$resp_array = array(
				  'status' => FALSE,
				  'error' => $validate_res['message']
				);
			}
		}
		else {
			$resp_array = array(
			  'status' => FALSE,
			  'error' => my_caption('api_incomplete_parameter')
			);
		}
		$this->response($resp_array, 200);
	}

}
?>