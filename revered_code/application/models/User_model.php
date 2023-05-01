<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {



	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
	}
	
	
	
	// This function is for signup at front end, adduser at backend.
	// And send verification email if necessary, then save data in the user table
	public function save_user($global_setting, $source = 'web', $form_data = []) {
		$pre_check = FALSE;
		$required_verification_email = TRUE;
		$email_verified = 0;
		$user_status = 0;
		$user_ids = my_random();
		if ($source == 'web') {
			$form_data['emailAddress'] = my_post('email_address');
			$form_data['password'] = my_post('password');
			$form_data['firstName'] = my_post('first_name');
			$form_data['lastName'] = my_post('last_name');
		}
		if (!empty($_SESSION['oauth_signup_ids'])) {  //sign up from oauth, retrive oauth identifier
		  $rs_oauth = $this->db->where('ids', $_SESSION['oauth_signup_ids'])->get('oauth_connector', 1)->row();
		  ($rs_oauth->provider == 'Google') ? $oauth_google_identifier = $rs_oauth->identifier : $oauth_google_identifier = '';
		  ($rs_oauth->provider == 'Facebook') ? $oauth_facebook_identifier = $rs_oauth->identifier : $oauth_facebook_identifier = '';
		  ($rs_oauth->provider == 'Twitter') ? $oauth_twitter_identifier = $rs_oauth->identifier : $oauth_twitter_identifier = '';
		  $signup_source = $rs_oauth->provider;
		  if ($form_data['emailAddress'] == $rs_oauth->email_address) {
			  $required_verification_email = FALSE;
			  $email_verified = 1;
			  $user_status = 1;
			  $pre_check = TRUE;
		  }
		}
		else {
			$signup_source = $source;
			$oauth_google_identifier = '';
			$oauth_facebook_identifier = '';
			$oauth_twitter_identifier = '';
		}
		(!empty($_SESSION['is_admin'])) ? $is_admin = $_SESSION['is_admin'] : $is_admin = 0;
		if ($global_setting->email_verification_required && !$is_admin && empty($_SESSION['invite_id'])) {  //need to send verification email
			if ($pre_check != TRUE) {  //need to send email to verify the email address
				$rs_email = $this->db->where('purpose', 'signup_activation')->get('email_template', 1)->row();
				$verification_token = my_random();
				$url = base_url() . 'auth/activate_account/' . $verification_token;
				$body = str_replace('{{first_name}}', $form_data['firstName'], str_replace('{{last_name}}', $form_data['lastName'] ,str_replace('{{verification_url}}', $url, $rs_email->body)));
				$email = array(
				  'email_to' => $form_data['emailAddress'],
				  'email_subject' => $rs_email->subject,
				  'email_body' => $body
				);
				$res = my_send_email($email);
				if ($res['result']) {
					$insert_data = array(
					  'type' => 'signup_activation',
					  'email' => $form_data['emailAddress'],
					  'token' => $verification_token,
					  'reference' => $user_ids,
					  'created_time' => my_server_time(),
					  'done' => 0
					);
					$this->db->insert('token', $insert_data);
					$pre_check = TRUE;
				}
				else {
					$message = my_caption('signup_fail') . ' ' . $res['message'];
					return array('result'=>FALSE, 'message'=>$message);
				}
			}
		}
		else {  //no need to send verification email, possible reason: system setting/administrator creates user/user signs up through invitation email
		    $required_verification_email = FALSE;
			$email_verified = 1;
			$user_status = 1;
			$pre_check = TRUE;
			if (!empty($_SESSION['invite_id'])) { $signup_source = 'invitation'; }
		}
		if ($pre_check) {  //save the user
		  $language = get_cookie('site_lang', TRUE);
		  (!$language) ? $language = $this->config->item('language') : null;
		  (!is_null(get_cookie('src_from', TRUE))) ? $referral_array['src_from'] = get_cookie('src_from', TRUE) : $referral_array['src_from'] = '';
		  (!is_null(get_cookie('referral_code', TRUE))) ? $referral_array['referral_code'] = get_cookie('referral_code', TRUE) : $referral_array['referral_code'] = '';
		  $user_array = array(
		    'ids' => $user_ids,
		    'username' => '',
		    'password' => my_hash_password($form_data['password']),
			'api_key' => my_random(),
			'balance' => '{"usd":0}',
		    'email_address' => $form_data['emailAddress'],
		    'email_verified' => $email_verified,
			'email_address_pending' => '',
			'oauth_google_identifier' => $oauth_google_identifier,
			'oauth_facebook_identifier' => $oauth_facebook_identifier,
			'oauth_twitter_identifier' => $oauth_twitter_identifier,
			'signup_source' => $signup_source,
		    'first_name' => $form_data['firstName'],
		    'last_name' => $form_data['lastName'],
			'company' => '',
		    'avatar' => 'default.jpg',
		    'timezone' => $this->config->item('time_reference'),
			'date_format' => 'Y-m-d',
			'time_format' => 'H:i:s',
			'language' => ucfirst($language),
			'country' => '',
			'currency' => 'USD',
			'address_line_1' => '',
			'address_line_2' => '',
			'city' => '',
			'state' => '',
			'zip_code' => '',
		    'role_ids' => $global_setting->default_role,
		    'status' => $user_status,
		    'created_time' => my_server_time(),
		    'update_time' => my_server_time(),
		    'login_success_detail' => '',
			'online' => 0,
			'online_time' => '',
			'new_notification' => 0,
			'referral' => json_encode($referral_array),
			'affiliate_enabled' => 0,
			'affiliate_code' => '',
			'affiliate_earning' => '{}',
			'affiliate_setting' => ''
		  );
		  $this->db->insert('user', $user_array);
		  if ($global_setting->email_verification_required && !$is_admin && empty($_SESSION['invite_id'])) {  //user signup, need to send verification email
			  ($required_verification_email) ? $message = my_caption('signup_success_with_verification') : $message = my_caption('signup_success_without_verification');
		  }
		  else {
			  if (!empty($_SESSION['invite_id'])) {  //user sign up through invitation email
			      $this->db->where('id', $_SESSION['invite_id'])->update('token', array('done'=>1));
				  unset($_SESSION['invite_id']);
				  unset($_SESSION['invite_email']);
				  $message = my_caption('signup_activate_success');
			  }
			  elseif (!$is_admin) {  //user signup, but according to system setting no need to send verification email
				  $message = my_caption('signup_success_without_verification');
			  }
			  else { //admin creates user
				  if (my_post('send_notification')) {  //need to send notification email
					  $rs_email = $this->db->where('purpose', 'notify_email')->get('email_template', 1)->row();
					  $body = str_replace('{{first_name}}', $form_data['firstName'], str_replace('{{last_name}}', $form_data['lastName'] ,str_replace('{{base_url}}', $this->config->item('base_url'), $rs_email->body)));
					  $body = str_replace('{{password}}', $form_data['password'], str_replace('{{email_address}}', $form_data['emailAddress'], $body));
					  $email = array(
					    'email_to' => $form_data['emailAddress'],
						'email_subject' => $rs_email->subject,
						'email_body' => $body
					  );
					  $res = my_send_email($email);
					  if ($res['result']) {
						  $message = my_caption('user_create_success_with_email');
					  }
					  else {
						  $message = my_caption('user_create_success_with_email_but_sent_fail');
					  }
				  }
				  else {  //no need to send notification email
					  $message = my_caption('user_create_success_without_email');
				  }
			  }
		  }
		  if (!empty($_SESSION['oauth_signup_ids'])) { unset($_SESSION['oauth_signup_ids']); }
		  if (!empty($_SESSION['invite_id'])) { unset($_SESSION['invite_id']); }
		  if ($this->setting->two_factor_authentication != 'disabled') { $this->save_cookie('remember_2FA_' . $user_ids, $user_ids);}
		  if ($this->setting->default_package != '0') {
			  $query_item = $this->db->where('ids', $this->setting->default_package)->get('payment_item', 1);
			  if ($query_item->num_rows()) {
				  $this->save_free_package($user_ids, $query_item->row());
			  }
		  }
		  // *** log started
		  $log_detail = my_ua() + array('email_address' => $form_data['emailAddress'], 'user_status' => $user_status);
		  my_log($user_ids, 'Information', 'signup-success', json_encode($log_detail));
		  // *** log ended
		  set_cookie(array('name'=>'referral_code', 'value'=>'', 'domain'=>$_SERVER['HTTP_HOST'], 'expire'=>365*86400));
		  return array('result'=>TRUE, 'message'=>$message);
		}
	}
	
	
	
	//This function is used for checking user's credential when signin
	public function signin_check() {
		if (!empty($_SESSION['two_factor_authentication'])) {
			$rs = $_SESSION['two_factor_authentication'];
			unset($_SESSION['two_factor_authentication']);
		}
		else {
			$rs = $this->signin_query_user(my_post('username'), my_post('password'));
		}
		if ($rs) { // auth pass
			if ($rs->status == 0 && $this->setting->signin_before_verified == 0) { //pending and not allowed to signin before activated
				return array('result'=>FALSE, 'message'=>my_caption('signin_account_pending'));
			}
			elseif ($rs->status == 2) { //deactivated
				return array('result'=>FALSE, 'message'=>my_caption('signin_account_deactivated'));
			}	
			else {   //signin allowed
				if (my_post('choose_action') == 'signin' && !empty($_SESSION['oauth_ids'])) {  //bind the account to social media profile at sign in page if user choose to do so
					$rs_oauth = $this->db->where('ids', $_SESSION['oauth_ids'])->get('oauth_connector', 1)->row();
					$update_array = array('oauth_' . $rs_oauth->provider . '_identifier'=>$rs_oauth->identifier);
					$this->db->where('id', $rs->id)->update('user', $update_array);
				}
				if (!empty($_SESSION['oauth_ids'])) { unset($_SESSION['oauth_ids']); }
				// cookie
				(my_post('remember')) ? $this->save_cookie('remember_signin', $rs->ids) : $this->remove_cookie('remember_signin', get_cookie('remember_signin', TRUE));
				//session and log
				return $this->sigin_success_log($rs, 'web');
			}		
		}
		else {
			// *** log started
			$log_detail = my_ua() + array('username' => my_post('username'));
			my_log('', 'Warning', 'signin-failed', json_encode($log_detail));
			// *** log ended
			my_throttle_log(my_post('username'));
			return array('result'=>FALSE, 'message'=>my_caption('signin_credential_error'));
		}
	}
	
	
	
	public function signin_query_user($username, $password) {
		$this->db->where('username', $username)->or_where('email_address', $username);
		$query = $this->db->get('user', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if (password_verify($password, $rs->password)) {
				return $rs;
			}
			else {
				return FALSE;
			}
		}
		else {
			return FALSE;
		}
	}
	
	
	
	public function sigin_success_log($rs, $interface) {
		$rs_role = $this->db->where('ids', $rs->role_ids)->get('role', 1)->row();
		if ($rs_role->name == 'Super_Admin') {
			my_clic();
			$is_admin = 1;
		}
		else {
			$is_admin = 0;
		}
		
		if ($interface != 'api') { //if sign in from browser, handle session & cookie
			// set session
			$session_array = array(
			  'user_ids' => $rs->ids,
			  'is_admin' => $is_admin,
			  'full_name' => $rs->first_name . ' ' . $rs->last_name
			);
			if (!empty($_SESSION['impersonate'])) { unset($_SESSION['impersonate']); }
			($interface == 'impersonate') ? $session_array['impersonate'] = $_SESSION['user_ids'] : null;
			$this->session->set_userdata($session_array);
		    
			// read language setting from user profile and set language of interface
			$language_cookie = array(
			  'name' => 'site_lang',
			  'value' => strtolower($rs->language),
			  'domain' => $_SERVER['HTTP_HOST'],
			  'expire' => 365*86400
			);
			set_cookie($language_cookie);
		}
		
		// update user
		$login_detail = array(
		  'time' => my_server_time() . ' ' . $this->config->item('time_reference'),
		  'interface' => $interface,
		  'ip_address' => $this->input->ip_address(),
		  'user_agent' => $this->agent->browser() . ' ' . $this->agent->version()
		);
		$update_array = array(
		  'login_success_detail' => json_encode($login_detail),
		  'online' => 1,
		  'online_time' => my_server_time()
		);
		$this->db->where('ids', $rs->ids)->update('user', $update_array);
		
		// ***log started
		my_log($rs->ids, 'Information', 'signin-success', json_encode(my_ua()));
		// *** log ended
		
		return array('result'=>TRUE, 'message'=>'');
	}
	
	
	
	// This function is used for check and send reset password link
	public function forget_password($email_address = '') {
		($email_address == '') ? $email_address = my_post('email_address') : null;
		$query = $this->db->where('email_address', $email_address)->get('user', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->status == 1) {
				$rs_email = $this->db->where('purpose', 'reset_password')->get('email_template', 1)->row();
				$verification_token = my_random();
				$url = base_url() . 'auth/reset_password/' . $verification_token;
				$body = str_replace('{{first_name}}', $rs->first_name, str_replace('{{last_name}}', $rs->last_name ,str_replace('{{verification_url}}', $url, $rs_email->body)));
				$email = array(
				  'email_to' => $email_address,
				  'email_subject' => $rs_email->subject,
				  'email_body' => $body
				);
				$res = my_send_email($email);
				if ($res['result']) {
					$insert_data = array(
					  'type' => 'reset_password',
				      'email' => $email_address,
				      'token' => $verification_token,
				      'reference' => $rs->ids,
				      'created_time' => my_server_time(),
				      'done' => 0
				    );
				    $this->db->insert('token', $insert_data);
					return array('result'=>TRUE, 'message'=>my_caption('forget_reset_email_sent'));
				}
				else {
					return array('result'=>FALSE, 'message'=>$res['message']);
				}
			}
			else {
				return array('result'=>FALSE, 'message'=>my_caption('forget_user_disabled'));
			}
		}
		else {
			return array('result'=>FALSE, 'message'=>my_caption('forget_email_address_not_exist'));
		}	
	}
	
	
	
	public function update_profile($ids, $avatar_file, $update_from = 'form', $form_data = []) {
		//if update request is from form, need to build the $form_data, if it's from api, $form_data can be used directly
		if ($update_from == 'form') {
			$form_data = array(
			  'username' => my_post('username'),
			  'emailAddress' => my_post('email_address'),
			  'phone' => my_post('phone'),
			  'firstName' => my_post('first_name'),
			  'lastName' => my_post('last_name'),
			  'company' => my_post('company'),
			  'company_number' => substr(my_post('company_number'), 0, 50),
			  'tax_number' => substr(my_post('tax_number'), 0 ,50),
			  'timezone' => my_post('timezone'),
			  'dateFormat' => my_post('date_format'),
			  'timeFormat' => my_post('time_format'),
			  'language' => my_post('language'),
			  'currency' => my_post('currency'),
			  'country' => my_post('country'),
			  'addressLine1' => my_post('address_line_1'),
			  'addressLine2' => my_post('address_line_2'),
			  'city' => my_post('city'),
			  'state' => my_post('state'),
			  'zipCode' => my_post('zip_code')
			);
		}
		$query = $this->db->where('ids', $ids)->get('user', 1);
		$global_setting = my_global_setting('all_fields');
		$pending_email_address = '';
		$update_array = [];
		if ($query->num_rows()) {
			$rs = $query->row();
			$pre_check = FALSE;
			(isset($_SESSION['is_admin'])) ? $is_admin = $_SESSION['is_admin'] : $is_admin = FALSE;
			if ($is_admin && my_uri_segment(3) != '') {  //if admin modifies user's email address, no need to verify the new email address
				$update_array = array(
				  'email_address' => $form_data['emailAddress'],
				  'email_verified' => 1
				);
				$pre_check = TRUE;
			}
			elseif ($rs->email_address != $form_data['emailAddress']) {  // user modifies his email address
				if ($global_setting->email_verification_required) {
					$pending_email_address = $form_data['emailAddress'];
					$rs_email = $this->db->where('purpose', 'change_email')->get('email_template', 1)->row();
					$verification_token = my_random();
					$url = base_url() . 'auth/change_email/' . $verification_token;
					$body = str_replace('{{first_name}}', $form_data['firstName'], str_replace('{{last_name}}', $form_data['lastName'] ,str_replace('{{verification_url}}', $url, $rs_email->body)));
					$email = array(
					  'email_to' => $pending_email_address,
					  'email_subject' => $rs_email->subject,
					  'email_body' => $body
				    );
					$res = my_send_email($email);
					if ($res['result']) {
						$insert_data = array(
						  'type' => 'change_email',
						  'email' => $pending_email_address,
						  'token' => $verification_token,
						  'reference' => $rs->ids,
						  'created_time' => my_server_time(),
						  'done' => 0
						);
						$this->db->insert('token', $insert_data);
						$pre_check = TRUE;
					}
					else {
						$message = my_caption('mp_fail') . ' ' . $res['message'];
						return array('result'=>FALSE, 'message'=>$res['message']);
					}
				}
				else {  //no need to verify the email duo to the system setting
					$update_array = array(
					  'email_address' => $form_data['emailAddress'],
					  'email_verified' => 1
					);
					$pre_check = TRUE;
				}
			}
			else {  //email not changed, no need to send confirmation email
				$pre_check = TRUE;
			}
			if ($pre_check) {
				$update_array += array(
				  'email_address_pending' => $pending_email_address,
				  'phone' => $form_data['phone'],
				  'username' => $form_data['username'],
				  'first_name' => $form_data['firstName'],
				  'last_name' => $form_data['lastName'],
				  'company' => $form_data['company'],
				  'company_number' => $form_data['company_number'],
				  'tax_number' => $form_data['tax_number'],
				  'date_format' => $form_data['dateFormat'],
				  'time_format' => $form_data['timeFormat'],
				  'timezone' => $form_data['timezone'],
				  'language' => $form_data['language'],
				  'country' => $form_data['country'],
				  'currency' => $form_data['currency'],
				  'address_line_1' => $form_data['addressLine1'],
				  'address_line_2' => $form_data['addressLine2'],
				  'city' => $form_data['city'],
				  'state' => $form_data['state'],
				  'zip_code' => $form_data['zipCode'],
				  'update_time' => my_server_time()
				);
				($avatar_file != '') ? $update_array['avatar'] = $avatar_file : null;
				//set current language if update is from form
				if ($update_from == 'form' && my_uri_segment(3) == '') {
					$language_cookie = array(
					  'name' => 'site_lang',
					  'value' => strtolower($form_data['language']),
					  'domain' => $_SERVER['HTTP_HOST'],
					  'expire' => 365*86400
					);
					set_cookie($language_cookie);
					$_SESSION['full_name'] = $form_data['firstName'] . ' ' . $form_data['lastName'];
				}

				// ***log started
				($global_setting->debug_level == 1) ? $debug_detail = json_encode($update_array) : $debug_detail = '';
				my_log($ids, 'Information', 'update-user-profile', json_encode(my_ua()), $debug_detail);
				// *** log ended
				
				$this->db->where('ids', $ids)->update('user', $update_array);  // update tbl
				if ($pending_email_address != '') {
					return array('result'=>TRUE, 'message'=>my_caption('mp_update_success_with_email'));
				}
				else {
					return array('result'=>TRUE, 'message'=>my_caption('mp_update_success_without_email'));
				}
			}
		}
		else {
			return array('result'=>FALSE, 'message'=>my_caption('mp_no_such_user'));
		}
	}
	
	
	
	public function payment_log($payment_gateway, $gateway_identifier, $rs_item, $amount, $tax) {
		$coupon_code = my_uri_segment(5);
		$coupon_discount = 0;
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('item_ids', $rs_item->ids)->where('coupon', $coupon_code)->where('gateway', $payment_gateway)->where('redirect_status!=', 'success')->where('callback_status!=', 'success')->get('payment_log', 1);
		if ($query->num_rows()) {  //update the exist one
			$update_array = array(
			  'gateway_identifier' => $gateway_identifier,
			  'redirect_status' => 'pending',
			  'created_time'=>my_server_time()
			);
			$this->db->where('id', $query->row()->id)->update('payment_log', $update_array);
		}
		else {  //create an new one
		    if (my_coupon_module() && $coupon_code != '') {
				$coupon_result_array = my_coupon_check($rs_item->ids, $coupon_code);
				($coupon_result_array['result']) ? $coupon_discount = $coupon_result_array['discount'] : $coupon_code = '';
			}
			$insert_data = array(
			  'ids' => my_random(),
			  'user_ids' => $_SESSION['user_ids'],
			  'type' => $rs_item->type,
			  'gateway' => $payment_gateway,
			  'currency' => $rs_item->item_currency,
			  'price' => $rs_item->item_price,
			  'quantity' => 1,
			  'amount' => $amount,
			  'gateway_identifier' => $gateway_identifier,
			  'gateway_event_id' => '',
			  'item_ids' => $rs_item->ids,
			  'item_name' => $rs_item->item_name,
			  'redirect_status' => 'pending',
			  'callback_status	' => 'pending',
			  'created_time' => my_server_time(),
			  'visible_for_user' => 1,
			  'generate_invoice' => 1,
			  'description' => '',
			  'coupon' => $coupon_code,
			  'coupon_discount' => $coupon_discount,
			  'tax' => $tax
			);
			$this->db->insert('payment_log', $insert_data);
		}
		return TRUE;
	}
	
	
	public function send_2FA($user_ids, $type, $send_target) {
		if ($type == 'email') {  // current only email is supported
			$rs_email_template = $this->db->where('purpose', '2FA_email')->get('email_template', 1)->row();
			$code = random_string('numeric', 6);
			$body = str_replace('{{code}}', $code, $rs_email_template->body);
			$email = array(
			  'email_to' => $send_target,
			  'email_subject' => $rs_email_template->subject,
			  'email_body' => $body
			);
			$res = my_send_email($email);
			if ($res['result']) {
				$insert_data = array(
				  'type' => '2FA',
				  'email' => $send_target,
				  'token' => $code,
				  'reference' => $user_ids,
				  'created_time' => my_server_time(),
				  'done' => 0
				);
				$this->db->insert('token', $insert_data);
				return array('result'=>TRUE, 'message'=>my_caption('signin_2fa_sent_notice') . $send_target);
			}
			else {
				return array('result'=>FALSE, 'message'=>$res['message']);
			}
		}
	}
	
	
	
	public function save_cookie($cookie_name, $user_ids) {
		$cookie_ids = my_random();
		$local_cookie = array(
		  'name' => $cookie_name,
		  'value' => $cookie_ids,
		  'expire' => 365*86400,
		  'domain' => $_SERVER['SERVER_NAME']
		);
		set_cookie($local_cookie);
		
		$server_cookie = array(
		  'ids' => $cookie_ids,
		  'type' => $cookie_name,
		  'user_ids' => $user_ids,
		  'created_time' => my_server_time(),
		  'expired_time' => date('Y-m-d H:i:s', strtotime('+1 years'))
		);
		$this->db->insert('session', $server_cookie);
		return TRUE;
	}
	
	
	
	public function remove_cookie($cookie_name, $cookie_ids) {
		delete_cookie('cookie_name');
		$this->db->delete('session', array('ids'=>$cookie_ids));
	}
	
	
	
	public function user_fileds_builder($rs) {
		return array(
		  'message' => array(
		    'ids' => $rs->ids,
			'status' => $rs->status,
		    'username ' => $rs->username,
			'balance' => $rs->balance,
			'emailAddress' => $rs->email_address,
			'phone' => $rs->phone,
			'firstName' => $rs->first_name,
			'lastName' => $rs->last_name,
			'timezone' => $rs->timezone,
			'dateFormat' => $rs->date_format,
			'timeFormat' => $rs->time_format,
			'language' => $rs->language,
			'currency' => $rs->currency,
			'country' => $rs->country,
			'addressLine1' => $rs->address_line_1,
			'addressLine2' => $rs->address_line_2,
			'city' => $rs->city,
			'state' => $rs->state,
			'zipCode' => $rs->zip_code,
			'status' => $rs->status,
			'createdTime' => my_conversion_from_server_to_local_time($rs->created_time, $rs->timezone, $rs->date_format . ' ' . $rs->time_format),
			'updateTime' => my_conversion_from_server_to_local_time($rs->update_time, $rs->timezone, $rs->date_format . ' ' . $rs->time_format)
          )	  
		);
	}
	
	
	
	public function save_ticket() {
		$ids = my_random();
		$insert_array = array(
		  'ids' => $ids,
		  'ids_father' => 0,
		  'source' => 'user',
		  'user_ids' => $_SESSION['user_ids'],
		  'user_fullname' => $_SESSION['full_name'],
		  'main_status' => 2,
		  'read_status' => 0,
		  'catalog' => my_post('ticket_catalog'),
		  'priority' => my_post('ticket_priority'),
		  'subject' => my_post('ticket_subject'),
		  'description' => my_post('ticket_description'),
		  'associated_files' => '',
		  'created_time' => my_server_time(),
		  'updated_time' => my_server_time(),
		  'rating' => 0
		);
		$this->db->insert('ticket', $insert_array);
		//notify the agent through email if necessary starts
		$ticket_setting_array = json_decode($this->setting->ticket_setting, 1);
		if ($ticket_setting_array['notify_agent_list'] != '') {
			$rs_email = $this->db->where('purpose', 'ticket_notify_agent')->get('email_template', 1)->row();
			$email = array(
			  'email_to' => $ticket_setting_array['notify_agent_list'],
			  'email_subject' => $rs_email->subject,
			  'email_body' => $rs_email->body
		    );
			my_send_email($email);
		}
		//notify the agent through email if necessary ends
		return $ids;
	}
	
	
	
	public function update_ticket($type) {
		$ids_father = my_post('ids_father');
		$ticket_setting_array = json_decode($this->setting->ticket_setting, 1);
		switch ($type) {
			case 'user_update' :
			  $source = 'user';
			  $main_status = 2;
			  if ($ticket_setting_array['notify_agent_list'] != '') {  //ticket updated by user, notify agent if necessary
				  $rs_email = $this->db->where('purpose', 'ticket_notify_agent')->get('email_template', 1)->row();
				  $email = array(
				    'email_to' => $ticket_setting_array['notify_agent_list'],
					'email_subject' => $rs_email->subject,
					'email_body' => $rs_email->body
				  );
				  my_send_email($email);
			  }
			  break;
			case 'agent_update' :
			  $source = 'agent';
			  $main_status = 1;
			  if ($ticket_setting_array['notify_user']) {  //ticket updated by agent, notify user if necessary
				  $rs_email = $this->db->where('purpose', 'ticket_notify_user')->get('email_template', 1)->row();
				  $user_ids = $this->db->where('ids', $ids_father)->get('ticket', 1)->row()->user_ids;
				  $email = array(
				    'email_to' => my_user_setting($user_ids, 'email_address'),
					'email_subject' => $rs_email->subject,
					'email_body' => $rs_email->body
				  );
				  my_send_email($email);
			  }
			  break;
		}
		$insert_array = array(
		  'ids' => my_random(),
		  'ids_father' => $ids_father,
		  'source' => $source,
		  'user_ids' => $_SESSION['user_ids'],
		  'user_fullname' => $_SESSION['full_name'],
		  'main_status' => 0,
		  'read_status' => 0,
		  'catalog' => '',
		  'priority' => 0,
		  'subject' => '',
		  'description' => my_post('ticket_reply'),
		  'associated_files' => '',
		  'created_time' => my_server_time(),
		  'updated_time' => my_server_time(),
		  'rating' => 0
		);
		$this->db->insert('ticket', $insert_array);
		$this->db->where('ids', $ids_father)->update('ticket', array('main_status'=>$main_status, 'read_status'=>0, 'updated_time'=>my_server_time()));
		return $ids_father;
	}
	
	
	
	public function save_free_package($user_ids, $rs_item) {
		if ($rs_item->type == 'purchase') {
			$insert_data = array(
			  'ids' => my_random(),
			  'user_ids' => $user_ids,
			  'payment_ids' => str_repeat('0', 50),
			  'item_type' => 'purchase',
			  'item_ids' => $rs_item->ids,
			  'item_name' => $rs_item->item_name,
			  'created_time' => my_server_time(),
			  'description' => '',
			  'stuff' => $rs_item->stuff_setting,
			  'used_up' => 0,
			  'auto_renew' => $rs_item->auto_renew
			);
			$this->db->insert('payment_purchased', $insert_data);
		}
		elseif ($rs_item->type == 'subscription') {
			$current_time = my_server_time();
			$end_time = date('Y-m-d H:i:s', strtotime($rs_item->recurring_interval_count . ' ' . $rs_item->recurring_interval, strtotime($current_time)));
			$insert_data = array(
			  'ids' => my_random(),
			  'item_ids' => $rs_item->ids,
			  'user_ids' => $user_ids,
			  'payment_gateway' => 'free',
			  'gateway_identifier' => '',
			  'quantity' => 1,
			  'status' => 'active',
			  'start_time' => $current_time,
			  'end_time' => $end_time,
			  'created_time' => $current_time,
			  'updated_time' => $current_time,
			  'description' => '',
			  'stuff' => $rs_item->stuff_setting,
			  'used_up' => 0,
			  'auto_renew' => $rs_item->auto_renew
			);
			$this->db->insert('payment_subscription', $insert_data);
		}
		return TRUE;
	}

}