<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once FCPATH . 'vendor/autoload.php';
use Hybridauth\Hybridauth;
use Hybridauth\Exception\Exception;
use Hybridauth\HttpClient;
use Hybridauth\Storage\Session;

class Oauth extends CI_Controller {
	public $setting;
	
    public function __construct() {
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
		$this->setting = my_global_setting('all_fields');
		$this->load->model('user_model');
    }
	
	
	
	public function verify() {
		$action = my_uri_segment(3);
		$callback_url = 'oauth/verify/' . $action . '/';
		try {
			$hybridauth = new Hybridauth($this->get_oauth_setting(base_url($callback_url)));
			$storage = new Session();
			if (!empty(my_get('provider'))) {
				$storage->set('provider', my_get('provider'));
			}
			if ($provider = $storage->get('provider')) {
				$hybridauth->authenticate($provider);
				$storage->set('provider', null);
			}
			$adapter = $hybridauth->getAdapter($provider);
			$profile = $adapter->getUserProfile();
			$ids = my_random();
			$adapter->disconnect();
			$query = $this->db->where('oauth_' . strtolower($provider) . '_identifier' , $profile->identifier)->get('user', 1);
			if ($action == 'signup') {  // sign up
				if ($query->num_rows()) {
					$this->session->set_flashdata('flash_danger', my_caption('signup_oauth_fail_already_taken'));
				}
				else {
					$this->session->set_flashdata('flash_success', my_caption('signup_oauth_success'));
					$this->save_oauth_connector($ids, $provider, $profile, $action);
					$_SESSION['oauth_ids']  = $ids;
				}
				redirect(base_url('auth/' . $action));
			}
			elseif ($action == 'signin') {  //sign in
				if ($query->num_rows()) { //sign in success
					if ($this->setting->two_factor_authentication == 'disabled') {    // 2FA is disabled, direct sign in
						$this->user_model->sigin_success_log($query->row(), $provider);
						redirect(base_url('dashboard'));
					}
					else {  //2FA is enabled
					    $rs = $query->row();
						if (get_cookie('remember_2FA_' . $rs->ids, TRUE) != null) {  //local cookie found
							$query_cookie = $this->db->where('ids', get_cookie('remember_2FA_' . $rs->ids, TRUE))->where('user_ids', $rs->ids)->where('expired_time>', my_server_time())->get('session', 1);
							if ($query_cookie->num_rows()) { //local and server cookie match
								$this->user_model->sigin_success_log($query->row(), $provider);
								redirect(base_url('dashboard'));
							}
							else {  //local and server cookie not match
								$this->proceed_to_2FA($rs);
							}
						}
						else {  // local cookie not found
							$this->proceed_to_2FA($rs);
						}
					}
				}
				else {  //profile not exist
				    $_SESSION['oauth_ids']  = $ids;
				    $this->save_oauth_connector($ids, $provider, $profile, $action);
					$this->session->set_flashdata('flash_warning', my_caption('signin_oauth_bind_notice'));
					redirect(base_url('auth/signin'));
				}
			}
			else {
				$this->session->set_flashdata('flash_warning', my_caption('global_unexpected_error'));
				redirect(base_url());
			}
		} catch (Exception $e) {
			$this->session->set_flashdata('flash_danger', my_caption('signup_oauth_fail'));
			redirect(base_url('auth/' . $action));
		}
	}
	
	
	
	protected function save_oauth_connector($ids, $provider, $profile, $action) {
		(empty($profile->firstName)) ? $first_name = '' : $first_name = $profile->firstName;
		(empty($profile->lastName)) ? $last_name = '' : $last_name = $profile->lastName;
		$insert_data = array(
		  'ids' => $ids,
		  'purpose' => substr($action, 0, 6),
		  'provider' => substr($provider, 0, 50),
		  'identifier' => substr($profile->identifier, 0, 255),
		  'email_address' => substr($profile->email, 0, 50),
		  'first_name' => substr($first_name, 0, 50),
		  'last_name' => substr($last_name, 0, 50),
		  'created_time' => my_server_time()
		);
		$this->db->insert('oauth_connector', $insert_data);
		
	}	
	
	
	
	protected function get_oauth_setting($callback_url) {
		$setting = my_global_setting('all_fields');
		$oauth_setting_array = json_decode($setting->oauth_setting, TRUE);
		$oauth_config = [
		  'callback' => $callback_url,
		  'providers' => [
		    'Google' => [
			  'enabled' => $oauth_setting_array['google']['enabled'],
			  'keys' => [
			    'key' => $oauth_setting_array['google']['client_id'],
				'secret' => $oauth_setting_array['google']['client_secret'],
			  ],
			],
		    'Facebook' => [
			  'enabled' => $oauth_setting_array['facebook']['enabled'],
			  'keys' => [
			    'key' => $oauth_setting_array['facebook']['app_id'],
				'secret' => $oauth_setting_array['facebook']['app_secret'],
			  ],
			],
		    'Twitter' => [
			  'enabled' => $oauth_setting_array['twitter']['enabled'],
			  'keys' => [
			    'key' => $oauth_setting_array['twitter']['consumer_key'],
				'secret' => $oauth_setting_array['twitter']['consumer_secret'],
			  ],
			]
		  ]
		];
		return $oauth_config;
	}
	
	
	
	public function proceed_to_2FA($rs) {
		$_SESSION['two_factor_authentication'] = $rs;
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
	

}
?>