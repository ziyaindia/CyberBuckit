<?php
defined('BASEPATH') OR exit('No direct script access allowed');


// This function is used to check whether user is signed in.
if (!function_exists('my_check_signin')) {
	function my_check_signin() {
		$CI = &get_instance();
		if (!isset($_SESSION['user_ids'])) {
			$CI->session->sess_destroy();
			redirect(base_url('auth/signin'));
		}
		else {
			date_default_timezone_set($CI->config->item('time_reference'));
			$CI->db->where('ids', $_SESSION['user_ids'])->update('user', array('online'=>1, 'online_time'=>my_server_time()));
		}
	}
}



// This function is used to load different themes.
if (!function_exists('my_load_view')) {
	function my_load_view($theme, $uri, $data='', $callback = FALSE) {
		$CI = &get_instance();
		$full_uri = 'themes/' . $theme . '/' . $uri;
		if ($callback) {
			$html = $CI->load->view($full_uri, $data, TRUE);
			return $html;
		}
		else {
			$CI->load->view($full_uri, $data, FALSE);
		}
	}
}



// This function is used to retrive the global configurations of the site. Global configurations are saved in the database, the table name is "setting".
// If the value of $field is "all_fields" then returns the recordset object, else returns a specific field's value.
// If record is missed, return false. 
if (!function_exists('my_global_setting')) {
	function my_global_setting($field) {
		$CI = &get_instance();
		$query = $CI->db->get('setting',1);
		if ($query->num_rows()) {
			($field == 'all_fields') ? $data = $query->row() : $data = $query->row()->$field;
		}
		else {
			$data = FALSE;
		}
		return $data;
	}
}



// This function is used to retrieve all fields or a certain field from the table 'user';
if (!function_exists('my_user_setting')) {
	function my_user_setting($ids, $field) {
		$CI = &get_instance();
		$query = $CI->db->where('ids', $ids)->get('user',1);
		if ($query->num_rows()) {
			($field == 'all_fields') ? $data = $query->row() : $data = $query->row()->$field;
		}
		else {
			$data = FALSE;
		}
		return $data;
	}
}



// This function is a multiple language helper which is used to get a specific caption from translation file.
if (!function_exists('my_caption')) {
	function my_caption($field) {
		$CI = &get_instance();
		$caption = $CI->lang->line($field);
		if (!$caption) {  //if $filed doesn't exist, return the default value of $field.
			$caption = $field;
		}
		return $caption;
	}
}



// This function is used to check duplicated in a certain table.
// Function accepts table and and an array, Every key->value in the array represents column->value in the table.
// return TRUE for pass, or FALSE for not pass
if (!function_exists('my_duplicated_check')) {
	function my_duplicated_check($table_name, $check_array, $itself_ids = '0') {
		$CI = &get_instance();
		try {
			foreach ($check_array as $key => $value) {
				$CI->db->where($key, $value);
			}
			$query = $CI->db->where('ids!=', $itself_ids)->get($table_name, 1);
			($query->num_rows()) ? $result = FALSE : $result = TRUE;
		}
		catch (Exception $e) {
			$result = FALSE;
		}
		return $result;
	}
}



// This function is a short form for $this->input->post('some_data', TRUE);
if (!function_exists('my_post')) {
	function my_post($field) {
		$CI = &get_instance();
		if ($CI->setting->xss_clean == 1) {  // xxs clean is on
			if ($CI->setting->html_purify == 1) { //html purify is on
				return html_purify($CI->input->post($field, TRUE));
			}
			else {  //html purify is off
				return $CI->input->post($field, TRUE);
			}
		}
		else { // xxs clean is off
			if ($CI->setting->html_purify == 1) { //html purify is on
				return html_purify($CI->input->post($field, FALSE));
			}
			else {  //html purify is off
				return $CI->input->post($field, FALSE);
			}
		}
	}
}



// This function is a short form for $this->input->get('some_data', TRUE);
if (!function_exists('my_get')) {
	function my_get($field) {
		$CI = &get_instance();
		return $CI->input->get($field, TRUE);
	}
}


if (!function_exists('my_uri_segment')) {
	function my_uri_segment($index) {
		$CI = &get_instance();
		if ($CI->setting->xss_clean == 1) {  // xxs clean is on
			if ($CI->setting->html_purify == 1) { //html purify is on
				return html_purify($CI->security->xss_clean($CI->uri->segment($index)));
			}
			else {  //html purify is off
				return $CI->security->xss_clean($CI->uri->segment($index));
			}
		}
		else { // xxs clean is off
			if ($CI->setting->html_purify == 1) { //html purify is on
				return html_purify($CI->uri->segment($index));
			}
			else {  //html purify is off
				return $CI->uri->segment($index);
			}
		}
	}
}



// This function generates a string with 50 characters based on current UTC timestamp;
if (!function_exists('my_random')) {
	function my_random() {
		$random_str = random_string('alnum',9) . hash('md5', now('UTC')) . random_string('alnum',9);
		return $random_str;
	}
}



// This function returns current server time
// Default timezone is based on the settting of $config['time_reference'] in /application/config/config.php
// Default datetime format is based on the settting of $config['log_date_format'] in /application/config/config.php
// It also accepts any other valid timezone and datetime format
if (!function_exists('my_server_time')) {
	function my_server_time($time_zone = '', $dt_format = '') {
		$CI = &get_instance();
		($time_zone == '') ? $time_zone = $CI->config->item('time_reference') : null;
		($dt_format == '') ? $dt_format = $CI->config->item('log_date_format') : null;
		$ts = now($time_zone);
		$dt = new DateTime("@$ts");
		return $dt->format($dt_format);
	}
}



if (!function_exists('my_conversion_from_server_to_local_time')) {
	function my_conversion_from_server_to_local_time($server_dt, $local_timezone, $dt_format) {
		$original_dt = new DateTime($server_dt);
		$new_timezone = new DateTimeZone($local_timezone);
		$original_dt->setTimezone($new_timezone);
		return $original_dt->format($dt_format);
	}
}



if (!function_exists('my_conversion_from_local_to_server_time')) {
	function my_conversion_from_local_to_server_time($local_dt, $local_timezone, $dt_format) {
		$CI = &get_instance();
		$offset = my_timezone_offset($local_timezone, $CI->config->item('time_reference'));
		$server_timestamp = strtotime($offset . ' seconds', strtotime(date('Y-m-d')));
		return date('Y-m-d H:i:s', $server_timestamp);
	}
}



// This function generates a success/danger/warning/info card for showing information to visitor.
// $type should be success/danger/warning/info
if (!function_exists('my_card')) {
	function my_card($type, $info) {
		$data = '<div class="card mb-4 py-3 border-left-'. $type . '">';
		  $data .= '<div class="card-body">';
		    $data .= $info;
		  $data .= '</div>';
		$data .= '</div>';
		return $data;
	}
}



if (!function_exists('my_lable')) {
	function my_lable($type, $info) {
		switch ($type) {
			case 'success' :
			  $span_type = '<span class="badge badge-success">';
			  break;
			case 'warning' :
			  $span_type = '<span class="badge badge-warning">';
			  break;
			case 'danger' :
			  $span_type = '<span class="badge badge-danger">';
			  break;
			case 'info' :
			  $span_type = '<span class="badge badge-info">';
			  break;
			case 'light' :
			  $span_type = '<span class="badge badge-light">';
			  break;
		}
		return $span_type . $info . '</span>';
	}
}



// check lic
if (!function_exists('my_clic')) {
	function my_clic() {
		$CI = &get_instance();
		$CI->load->library('m_apl');
		$res = $CI->m_apl->apl();
		$status = $res['notification_case'];
		if ($status != 'notification_license_ok' && $status != 'notification_invalid_response') {
			die('Invalid Script License');
		}
	}
}



// This function returns client's side infomation.
// It should be ip address, browser
if (!function_exists('my_remote_info')) {
    function my_remote_info($info_type) {
		$CI = &get_instance();
		switch ($info_type) {
			case "ip" :
			  return $CI->input->ip_address();
			  break;
			case "browser" :
			  return $CI->agent->browser();
			  break;
		}
	}
}



// This function is used to send email through smtp
if (!function_exists('my_send_email')) {
	function my_send_email($email) {
		$email_setting_array = json_decode(my_global_setting('smtp_setting'), TRUE);
		if ($email_setting_array['host'] != '') {
			$CI = &get_instance();
			$config = array(
			  'protocol' => 'smtp',
			  'smtp_host' => $email_setting_array['host'],
			  'smtp_port' => $email_setting_array['port'],
			  'smtp_auth' => $email_setting_array['is_auth'],
			  'smtp_user' => $email_setting_array['username'],
			  'smtp_pass' => $email_setting_array['password'],
			  'smtp_crypto' => $email_setting_array['crypto'],
			  'mailtype' => 'html',
			  'smtp_timeout' => '30',
			  'charset' => 'utf-8',
			  'wordwrap' => TRUE
			);
			$CI->load->library('email');
			$CI->email->initialize($config);
			$CI->email->from($email_setting_array['from_email'], $email_setting_array['from_name']);
			$CI->email->to($email['email_to']);
			$CI->email->subject($email['email_subject']);
			$CI->email->message($email['email_body']);
			$CI->email->set_newline("\r\n");
			try {
				$send_result = $CI->email->send();
			}
			catch (AwsException $e) {
				log_message('error', $e->getMessage());
			}
			if (!$send_result) {
				return array('result'=>FALSE, 'message'=>my_caption('global_failed_to_send_smtp'));
			}
			else {
				return array('result'=>TRUE, 'message'=>'');
			}
		}
		else {
			return array('result'=>FALSE, 'message'=>my_caption('global_email_setting_invalid'));
		}
	}
}



// return supported language list
// This function actually return the folder names exist in application/language
if (!function_exists('my_supported_language')) {
    function my_supported_language() {
		$CI = &get_instance();
		$CI->load->helper('directory');
		$name_array = directory_map(APPPATH . 'language', 1, 0);
		$folder_array = [];
		foreach ($name_array as $name) {
			if (substr($name, -1) == '\\' || substr($name, -1) == '/') {
				$lang = ucfirst(substr($name, 0, -1));
				$folder_array[$lang] = $lang;
			}
		}
		return $folder_array;
	}
}



// js escape
if (!function_exists('my_js_escape')) {
	function my_js_escape($str) {
		$escaped_str = str_replace('\'', '\\\'', $str);
		return $escaped_str;
	}
}



// hash password
if (!function_exists('my_hash_password')) {
	function my_hash_password($password) {
		return password_hash($password, PASSWORD_DEFAULT, array('cost'=>12));
	}
}



// return role list as array
if (!function_exists('my_role_list')) {
	function my_role_list() {
		$CI = &get_instance();
		$rs = $CI->db->get('role')->result();
		foreach ($rs as $row) {
			$data[$row->ids] = str_replace('_', ' ', $row->name);
		}
		return $data;
	}
}



// get user's ua information, return an array including ip address, browser
if (!function_exists('my_ua')) {
	function my_ua() {
		$CI = &get_instance();
		$ua_array = array(
		  'ip' => $CI->input->ip_address(),
		  'is_mobile' => $CI->agent->is_mobile(),
		  'is_browser' => $CI->agent->is_browser(),
		  'browser_name' => $CI->agent->browser(),
		  'browser_version' => $CI->agent->version(),
		  'platform' => $CI->agent->platform()
		);
		return $ua_array;
	}
}


// log activity
if (!function_exists('my_log')) {
	function my_log($user_ids, $level, $event, $detail, $debug_detail = '') {
		$CI = &get_instance();
		$insert_data = array(
		  'user_ids' => $user_ids,
		  'level' => $level,
          'event' => $event,
          'detail' => $detail,
		  'debug_detail' => $debug_detail,
          'created_time' => my_server_time()		  
		);
		$CI->db->insert('activity', $insert_data);
		return TRUE;
	}
}



// query permission, return permission list of $role_ids
// return array, role real name as key, true or false as value
if (!function_exists('my_permission_list')) {
	function my_permission_list($role_ids) {
		$CI = &get_instance();
		$rs_permission = $CI->db->get('permission')->result();
		foreach ($rs_permission as $row) {
			$rs_permission_array[$row->ids] = $row->name;  // build a full permission array, relation between ids and name
		}
		$role_permission = $CI->db->where('ids', $role_ids)->get('role', 1)->row()->permission;
		$role_permission_array = json_decode($role_permission, TRUE);
		foreach ($role_permission_array as $key => $value) {
			$permission_real_name = $rs_permission_array[$key];
			$new_permission_array[$permission_real_name] = $value;
		}
		return $new_permission_array;	
	}
}



// query one permission for the user himself
if (!function_exists('my_check_permission')) {
	function my_check_permission($permission_name) {
		$CI = &get_instance();
		$rs = $CI->db->where('ids', $_SESSION['user_ids'])->get('user', 1)->row();
		$permission_list = my_permission_list($rs->role_ids);
		if ($permission_list[str_replace(' ', '_', $permission_name)]) {
			return 1;
		}
		else {
			return 0;
		}
	}
}



// query wheather one user belong to one role
if (!function_exists('my_check_role')) {
	function my_check_role($role_name) {
		$CI = &get_instance();
		$rs = $CI->db->where('name', str_replace(' ', '_', $role_name))->get('role', 1)->row();
		if ($rs->ids == $CI->user_role_ids) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
}



if (!function_exists('my_remove_user')) {
	function my_remove_user($user_ids) {
		$CI = &get_instance();
		$rs = $CI->db->where('ids', $user_ids)->get('user', 1)->row();
		if ($rs->id != 1) {
			$CI->db->where('user_ids', $user_ids)->delete('activity'); //activity table
			$CI->db->where('provider', 'facebook')->where('identifier', $rs->oauth_facebook_identifier)->delete('oauth_connector'); //oauth_connector table
			$CI->db->where('provider', 'twitter')->where('identifier', $rs->oauth_twitter_identifier)->delete('oauth_connector'); //oauth_connector table
			$CI->db->where('provider', 'google')->where('identifier', $rs->oauth_google_identifier)->delete('oauth_connector'); //oauth_connector table
			$CI->db->where('to_user_ids', $user_ids)->delete('notification');  //notification table
			$CI->db->where('user_ids', $user_ids)->delete('session');  //session table
			$CI->db->where('email', $rs->email_address)->delete('token'); //token table by email address
			$CI->db->where('phone', $rs->phone)->delete('token'); //token table by phone
			$CI->db->where('ids', $user_ids)->delete('user'); //user table
		}
		return TRUE;
	}
}



// It's used to calculator the offset between two timezone, returns seconds
if (!function_exists('my_timezone_offset')) {
	function my_timezone_offset($dt_1, $dt_2) {
		$dt_utc = date_create('now', timezone_open('UTC'));
		$offset_1 = timezone_offset_get(timezone_open($dt_1), $dt_utc);
		$offset_2 = timezone_offset_get(timezone_open($dt_2), $dt_utc);
		$offset = $offset_2 - $offset_1;
		return $offset;
	}
}



// check if a json string is valid, return true or false
if (!function_exists('my_isJson')) {
	function my_isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}



// check rule for data
// available for these rules: trim/required/valid_email/valid_timezone/valid_language/valid_country/valid_currency/max_length/min_length/in_list
// return an array, including the original data in it
// will add more rule ,slater if necessary
if (!function_exists('my_validate_data')) {
	function my_validate_data($data, $field_name, $rule_list) {
		$status = TRUE;
		$message = '';
		$rule_array = explode('|', $rule_list);
		foreach ($rule_array as $rule) {
			if ($rule == 'trim') {
				$data = trim($data);
			}
			elseif ($rule == 'required') {
				if ($data == '') {
					$status = FALSE;
					$message = $field_name . my_caption('helper_validate_required');  
					break;
				}
			}
			elseif ($rule == 'valid_email') {
				if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
					$status = FALSE;
					$message = $field_name . my_caption('helper_validate_not_valid_email'); 
					break;
				}
			}
			elseif ($rule == 'valid_timezone' || $rule == 'valid_language' || $rule == 'valid_country' || $rule == 'valid_currency') {  // timzone/language/country/currency
				$CI = &get_instance();
				$CI->load->helper('my_basic_data');
				switch ($rule) {
					case 'valid_timezone' :
					  $value_array = my_timezone_list();
					  break;
					case 'valid_language' :
					  $value_array = my_supported_language();
					  break;
					case 'valid_country' :
					  $value_array = my_country_list();
					  break;
					case 'valid_currency' :
					  $value_array = my_currency_list();
					  break;
				}
				if (!array_key_exists($data, $value_array)) {
					$status = FALSE;
					$message = $field_name . my_caption('helper_validate_not_valid_value');
					break;
				}
			}
			elseif (substr($rule, 0, 10) == 'max_length') {
				$max_length = intval(preg_replace("/[^0-9]/", "", $rule));
				if (strlen($data) > $max_length) {
					$status = FALSE;
					$message = my_caption('helper_validate_bigger_than_max_length') . $field_name . my_caption('helper_validate_is') . $max_length;
					break;
				}
			}
			elseif (substr($rule, 0, 10) == 'min_length') {
				$min_length = intval(preg_replace("/[^0-9]/", "", $rule));
				if (strlen($data) < $min_length) {
					$status = FALSE;
					$message = my_caption('helper_validate_smaller_than_min_length') . $field_name . my_caption('helper_validate_is') . $min_length;
					break;
				}
			}
			elseif (substr($rule, 0, 7) == 'in_list') {
				preg_match_all("/\\[(.*?)\\]/", $rule, $matches); 
				$list_array = explode(',', $matches[1][0]);
				$found = FALSE;
				foreach ($list_array as $item) {
					if ($data == $item) {
						$found = TRUE;
						break; //break the inner foreach
					}
				}
				if ($found == FALSE) {
					$status = FALSE;
					$message = $field_name . my_caption('helper_validate_must_be_one_of') . $matches[1][0];
					break;  //break the outter foreach
				}
			}
			elseif (substr($rule, 0, 17) == 'password_strength') {
				preg_match_all("/\[([^\]]*)\]/", $rule, $matches);
				$res = my_password_strength($data, $matches[1][0]);
				$status = $res['status'];
				$message = $res['error'];
			}
		}
		return array(
		  'status' => $status,
		  'message' => $message,
		  'data' => $data
		);
	}
}



//show the left side menu
if (!function_exists('my_menu_display')) {
	function my_menu_display($menu_configuration, $menu_type) {
		$menu_output = '';
		$active = '';
		$collapse_order = 1;
		$CI = &get_instance();
		$menu_array = json_decode($menu_configuration, 1);
		if (my_uri_segment(3) != '' && strlen(my_uri_segment(3)) < 50) {  //combination of current controller and method and 1 segment
			$current_cm = $CI->router->fetch_class() . '/' . $CI->router->fetch_method() . '/' . my_uri_segment(3);
		}
		else {
			$current_cm = $CI->router->fetch_class() . '/' . $CI->router->fetch_method();
		}
		switch ($CI->setting->theme) {
			case 'adminlte' :
			  // coming soon...
			  break;
			default :  //default theme(SB Admin 2)
			  if ($menu_array['display']) {
				  unset($menu_array['display']);
				  foreach ($menu_array as $menu) {
					  if ($menu['display']) {
						  if ($menu['active_condition'] != '') {
							  $active_condition = $menu['active_condition'];
						  }
						  else {
							  $active_condition = '';
							  foreach ($menu['child_menu'] as $child_menu) {
								  $active_condition .= $child_menu['active_condition'] . ',';
							  }
							  $active_condition = rtrim($active_condition, ',');
						  }
						  if (in_array($current_cm, explode(",", $active_condition))) {
							  $active = ' active';
							  $show = ' show';
							  $collapsed = '';
						  }
						  else {
							  $active = '';
							  if (array_key_exists('expanded', $menu)) {
								  if ($menu['expanded']) {
								      $show = ' show';
									  $collapsed = '';
								  }
								  else {
									  $show = '';
									  $collapsed = ' collapsed';
								  }
							  }
							  else {
								  $show = '';
								  $collapsed = ' collapsed';
							  }
						  }
						  if (!empty($menu['child_menu'])) {
							  $menu_output .= '
							    <li class="nav-item' . $active . '">
							      <a class="nav-link' . $collapsed . '" href="#" data-toggle="collapse" data-target="#collapsePages_' . $menu_type . '_' . $collapse_order . '" aria-expanded="true" aria-controls="collapsePages_' . $menu_type . '_' . $collapse_order . '">
								    <i class="' . $menu['icon'] . '"></i>
								    <span>' . $menu['name'] . '</span>
								  </a>
								  <div id="collapsePages_' . $menu_type . '_' . $collapse_order . '" class="collapse' . $show . '" aria-labelledby="headingPages" data-parent="#accordionSidebar">
								    <div class="bg-white py-2 collapse-inner rounded">';
							  foreach ($menu['child_menu'] as $child_menu) {
								  if ($child_menu['display']) {
									  (in_array($current_cm, explode(",", $child_menu['active_condition']))) ? $child_active =' active' : $child_active = '';
									  (substr($child_menu['link'], 0, 4) == 'http') ? $link = $child_menu['link'] . '" target="_blank"' : $link = base_url($child_menu['link']) . '"';
									  $menu_output .= '<a class="collapse-item' . $child_active . '" href="' . $link . '>' . $child_menu['name'] . '</a>';
								  }
							  }
							  $menu_output .= '</div></div></li>';
							  $collapse_order++;
						  }
						  else {
							  (substr($menu['link'], 0, 4) == 'http') ? $link = $menu['link'] . '" target="_blank"' : $link = base_url($menu['link']) . '"';
							  $menu_output .= '<li class="nav-item' . $active . '"><a class="nav-link" href="'. $link . '><i class="' . $menu['icon'] . '"></i> <span>' . $menu['name'] . '</span></a></li>';
						  }
					  }
				  }
			  }
			  break;
		}
		return $menu_output;
	}
}



// Detect the strength of password
if (!function_exists('my_password_strength')) {
	function my_password_strength($password, $required_strength_level) {
		$error = '';
		switch ($required_strength_level) {
			case 'normal' :
			  if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password)) {
				  $status = TRUE;
			  }
			  else {
				  $status = FALSE;
				  $error = my_caption('gs_psr_normal_error');
			  }
			  break;
			case 'medium' :
			  if (preg_match('#[0-9]#', $password) && preg_match('#[A-Z]#', $password) && preg_match('#[a-z]#', $password)) {
				  $status = TRUE;
			  }
			  else {
				  $status = FALSE;
				  $error = my_caption('gs_psr_medium_error');
			  }
			  break;
			case 'strong' :
			  if (preg_match('#[0-9]#', $password) && preg_match('#[a-zA-Z]#', $password) && preg_match('/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/', $password)) {
				  $status = TRUE;
			  }
			  else {
				  $status = FALSE;
				  $error = my_caption('gs_psr_strong_error');
			  }
			  break;
			default :
			  $status = TRUE;
		}
		return array('status'=>$status, 'error'=>$error);
	}
}



// log throttling
if (!function_exists('my_throttle_log')) {
	function my_throttle_log($user_tag) {
		$setting = my_global_setting('all_fields');
		if ($setting->throttling_policy != 'off') {
			$CI = &get_instance();
			$ip = $_SERVER['REMOTE_ADDR'];
			$query = $CI->db->where('ip', $ip)->or_where('user_tag', $user_tag)->get('throttling', 1);
			if ($query->num_rows()) {
				$times = $query->row()->times + 1;
				$CI->db->where('id', $query->row()->id)->update('throttling', array('times'=>$times));
			}
			else {
				$CI->db->insert('throttling', array('ip'=>$ip, 'times'=>1, 'user_tag'=>$user_tag, 'time'=>my_server_time()));
			}
		}
		return TRUE;
	}
}



// trottle check
if (!function_exists('my_throttle_check')) {
	function my_throttle_check($user_tag) {
		$setting = my_global_setting('all_fields');
		if ($setting->throttling_policy != 'off') {
			$CI = &get_instance();
			$ip = $_SERVER['REMOTE_ADDR'];
			$now = my_server_time();
			$delete_before_this_time = date("Y-m-d H:i:s", strtotime("$now - " . $setting->throttling_unlock_time . " minutes"));;
			$CI->db->where('time<', $delete_before_this_time)->delete('throttling');
			$query = $CI->db->where('ip', $ip)->or_where('user_tag', $user_tag)->get('throttling', 1);
			if ($query->num_rows()) {
				$rs = $query->row();
				($setting->throttling_policy == 'normal') ? $allow_times = 20 : $allow_times = 5;
				if ($rs->times >= $allow_times) {
					$next_attemp_minutes = $setting->throttling_unlock_time - intval((time() - strtotime($rs->time))/60);
					$result = array(
					  'result'=>FALSE,
					  'message'=>my_caption('global_too_frequent_attempts') . $next_attemp_minutes . ' ' . my_caption('global_minutes')
					);
				}
				else {
					$result = array('result'=>TRUE);
				}
			}
			else {
				$result = array('result'=>TRUE);
			}
		}
		else {
			$result = array('result'=>TRUE);
		}
		return $result;	
	}
}



// check if it's under demo mode
if (!function_exists('my_check_demo_mode')) {
	function my_check_demo_mode($response_type = 'redirect') {
		$CI = &get_instance();
		if ($CI->config->item('my_demo_mode')) {
			if ($response_type == 'redirect') {
				$CI->session->set_flashdata('flash_danger', my_caption('global_in_demo_mode'));
				redirect($_SERVER['HTTP_REFERER']);
			}
			elseif ($response_type == 'simple_json') {
				die('{"result":false, "message":"'. my_caption('global_in_demo_mode') . '"}');
			}
			else {  //alert_json
				die('{"result":false, "title":"", "text":"'. my_caption('global_in_demo_mode') . '", "redirect":""}');
			}
		}
		return TRUE;
	}
}



// save sth to stuff_setting in table payment_item
if (!function_exists('my_save_payment_item_stuff_setting')) {
	function my_save_payment_item_stuff_setting($ids, $field, $value) {
		$CI = &get_instance();
		$stuff_setting = $CI->db->where('ids', $ids)->get('payment_item', 1)->row()->stuff_setting;
		$stuff_setting_array = json_decode($stuff_setting, 1);
		$stuff_setting_array[$field] = $value;
		$CI->db->where('ids', $ids)->update('payment_item', array('stuff_setting'=>json_encode($stuff_setting_array)));
		return TRUE;
	}
}



// operations of payment gateway for subscription
if (!function_exists('my_payment_gateway_subscription_action')) {
	function my_payment_gateway_subscription_action($action, $rs, $redirect_uri) {
		$CI = &get_instance();
		$result = FALSE;
		$payment_setting_array = json_decode($CI->setting->payment_setting, 1);
		if ($rs->payment_gateway == 'stripe') {
			\Stripe\Stripe::setApiKey($payment_setting_array['stripe_secret_key']);
			try {
				if ($action == 'cancel' && $rs->status == 'active') {
					\Stripe\Subscription::update($rs->gateway_identifier, ['cancel_at_period_end' => true]);
					$subscription = \Stripe\Subscription::retrieve($rs->gateway_identifier);
					if ($subscription->cancel_at_period_end && $subscription->status == 'active') {
						$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'pending_cancellation'));
					    $result = TRUE;
					}
				}
				elseif ($action == 'cancel_now' && ($rs->status == 'active' || $rs->status == 'pending_cancellation')) {
					$subscription = \Stripe\Subscription::retrieve($rs->gateway_identifier);
					$subscription->cancel();
					$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'expired'));
					$result = TRUE;
				}
				elseif ($action == 'resume' && $rs->status == 'pending_cancellation') {
					\Stripe\Subscription::update($rs->gateway_identifier, ['cancel_at_period_end' => false]);
					$subscription = \Stripe\Subscription::retrieve($rs->gateway_identifier);
					if (!$subscription->cancel_at_period_end && $subscription->status == 'active') {
						$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'active'));
						$result = TRUE;
					}
				}
			}
			catch (\Exception $e) {
				$result = FALSE;
			}
		}
		elseif ($rs->payment_gateway == 'paypal') {
			$CI->load->library('m_paypal');
			if ($action == 'cancel' && $rs->status == 'active') {  //suspend
				$subscription = $CI->m_paypal->modifySubscription('suspendSubscription', $rs->gateway_identifier);
				if ($subscription['success']) {
					$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'suspended'));
					$result = TRUE;
				}
			}
			elseif ($action == 'cancel_now' && ($rs->status == 'active' || $rs->status == 'suspended')) {  //cancel permanently
			    $subscription = $CI->m_paypal->modifySubscription('cancelSubscription', $rs->gateway_identifier);
				if ($subscription['success']) {
					$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'expired'));
					$result = TRUE;
				}
			}
			elseif ($action == 'resume' && $rs->status == 'suspended') {  //resume
			    $subscription = $CI->m_paypal->modifySubscription('activateSubscription', $rs->gateway_identifier);
				if ($subscription['success']) {
					$subscription = $CI->m_paypal->retrieveSubscription($rs->gateway_identifier);
					if ($subscription['success']) {
						$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'active', 'start_time'=>$subscription['start_time'], 'end_time'=>$subscription['end_time']));
					}
					$result = TRUE;
				}
			}
		}
		else {
			if ($action == 'cancel' && $rs->status == 'active') {
				$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'pending_cancellation'));
			}
			elseif ($action == 'cancel_now' && ($rs->status == 'active' || $rs->status == 'pending_cancellation')) {
				$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'expired'));
			}
			elseif ($action == 'resume' && $rs->status == 'pending_cancellation') {
				$CI->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>'active'));
			}
			$result = TRUE;
		}
		if ($result) {
			if ($action == 'cancel' || $action == 'cancel_now') {
				$CI->session->set_flashdata('flash_success', my_caption('payment_subscription_cancelled'));
				$json = '{"result":true, "title":"' . my_caption('global_cancelled_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url($redirect_uri) .'"}';
			}
			elseif ($action == 'resume') {
				$CI->session->set_flashdata('flash_success', my_caption('payment_subscription_resumed'));
				$json = '{"result":true, "title":"' . my_caption('global_resumed_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url($redirect_uri) .'"}';
			}
		}
		else {
			$CI->session->set_flashdata('flash_danger', my_caption('payment_subscription_perform_fail'));
			$json = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"' . base_url($redirect_uri) .'"}';
		}
		return $json;
	}
}



// always show 2 decimal for a given price
if (!function_exists('my_proper_price')) {
	function my_proper_price($price) {
		return number_format((float)$price, 2, '.', '');
	}
}	



// check subscription by subscription's sn
if (!function_exists('my_check_subscription')) {
	function my_check_subscription($ids) {  //subscription's sn
		$CI = &get_instance();
		$query = $CI->db->where('ids', $ids)->where('status!=', 'expired')->where('end_time>=', my_server_time('UTC', 'Y-m-d'))->get('payment_subscription', 1);
		if ($query->num_rows()) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
}



// uninstall lic
if (!function_exists('my_ulic')) {
	function my_ulic() {
		$CI = &get_instance();
		$CI->load->library('m_apl');
		$res = $CI->m_apl->uninstall();
		$status = $res['notification_case'];
		if ($status == 'notification_license_ok') {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
}



// check subscription by item's sn
if (!function_exists('my_check_subscription_by_item')) {
	function my_check_subscription_by_item($ids, $strict = FALSE) {  //ids is item's sn
	    if (empty($_SESSION['user_ids'])) {
			$result = FALSE;
		}
		else {
			$CI = &get_instance();
			if ($strict) {
				$query = $CI->db->where('item_ids', $ids)->where('user_ids', $_SESSION['user_ids'])->get('payment_subscription', 1);
			}
			else {
				$query = $CI->db->where('item_ids', $ids)->where('user_ids', $_SESSION['user_ids'])->where('status!=', 'expired')->where('end_time>=', my_server_time('UTC', 'Y-m-d'))->get('payment_subscription', 1);
			}
			($query->num_rows()) ? $result = TRUE : $result = FALSE;
		}
		return $result;
	}
}



// check purchase by item's sn
if (!function_exists('my_check_purchase_by_item')) {
	function my_check_purchase_by_item($item_ids) { //ids is item's sn
	    if (empty($_SESSION['user_ids'])) {
			$result = FALSE;
		}
		else {
		  $CI = &get_instance();
		  $query = $CI->db->where('user_ids', $_SESSION['user_ids'])->where('item_ids', $item_ids)->get('payment_purchased');
		  ($query->num_rows()) ? $result = TRUE : $result = FALSE;
		}
		return $result;
	}
}



// Add or Cut Balance for a certain user
if (!function_exists('my_user_reload')) {
	function my_user_reload($user_ids, $type, $currency, $amount) {
		$CI = &get_instance();
		$success = FALSE;
		$user_balance = $CI->db->where('ids', $user_ids)->get('user', 1)->row()->balance;
		$user_balance_array = json_decode($user_balance, 1);
		if (array_key_exists(strtolower($currency), $user_balance_array)) { //Currency exists
			if ($type == 'Add') {  //Success, Add Balance, Currency Exists
				$user_balance_array[strtolower($currency)] = doubleval($user_balance_array[strtolower($currency)]) + doubleval($amount);
				$success = TRUE;
				$message = my_caption('payment_adjust_balance_adjust_successfully') . my_caption('payment_adjust_balance_add') . ': ' . $currency . ' ' . $amount . ', ' . my_caption('payment_adjust_balance_new') . ': ' . $currency . ' ' . $user_balance_array[strtolower($currency)];
				$result_json = '{"result":true, "message":"' . $message . '"}';
			}
			else {  //Cut Balance
				$new_balance = doubleval($user_balance_array[strtolower($currency)]) - doubleval($amount);
				if ($new_balance >= 0) {  //Success, Cut Balance
					$user_balance_array[strtolower($currency)] = $new_balance;
					$success = TRUE;
					$message = my_caption('payment_adjust_balance_adjust_successfully') . my_caption('payment_adjust_balance_cut') . ': ' . $currency . ' ' . $amount . ', ' . my_caption('payment_adjust_balance_new') . ': ' . $currency . ' ' . $new_balance;
					$result_json = '{"result":true, "message":"' . $message . '"}';
				}
				else { //Fail, Cut Balance, Balance is less than zero
					$result_json = '{"result":false, "message":"' . my_caption('payment_adjust_balance_fail_below') . '"}';  //unable to cut because the result is less than zero
				}
			}
		}
		else {  //Currency not exists
			if ($type == 'Add') {   //Success, Add Balance, No Currency but added
				$user_balance_array[strtolower($currency)] = doubleval($amount);  //create a new kind of currency
				$success = TRUE;
				$message = my_caption('payment_adjust_balance_adjust_successfully') . my_caption('payment_adjust_balance_add') . ': ' . $currency . ' ' . $amount . ', ' . my_caption('payment_adjust_balance_new') . ': ' . $currency . ' ' . $amount;
				$result_json = '{"result":true, "message":"' . $message . '"}';
			}
			else {  //Fail, Cut Balance, No Currency
				$result_json = '{"result":false, "message":"' . my_caption('payment_adjust_balance_fail_currency_not_exists') . '"}';  //unable to cut because currency is not existed
			}
		}
		if ($success) {
			$CI->db->where('ids', $user_ids)->update('user', array('balance'=>json_encode($user_balance_array)));
		}
		return $result_json;
	}
}



// Get catalog
if (!function_exists('my_get_catalog')) {
	function my_get_catalog($type, $user_ids='', $return_ids = FALSE) {
		$CI = &get_instance();
		if ($user_ids != '') {
			$query = $this->db->where('user_ids', $user_ids);
		}
		$query = $CI->db->where('type', $type)->order_by('id', 'asc')->get('catalog');
		if ($query->num_rows()) {
			$rs = $query->result();
			foreach ($rs as $row) {
				($return_ids) ? $temp_array[$row->ids] = $row->name : $temp_array[$row->name] = $row->name;
			}
			return $temp_array;
		}
		elseif ($type == 'support_contact_form' or $type == 'support_ticket') {
			return array('General'=>'General');
		}
		else {
			return array();
		}
	}
}



// Get file icon according to its type
if (!function_exists('my_get_file_icon')) {
	function my_get_file_icon($type) {
		if ($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
			$icon = 'img';
		}
		elseif ($type == 'doc' || $type == 'docx') {
			$icon = 'fa-file-word';
		}
		elseif ($type == 'xls' || $type == 'xlsx') {
			$icon = 'fa-file-excel';
		}
		elseif ($type == 'ppt' || $type == 'pptx') {
			$icon = 'fa-file-powerpoint';
		}
		elseif ($type == 'pdf') {
			$icon = 'fa-file-pdf';
		}
		elseif ($type == 'txt') {
			$icon = 'fa-sticky-note';
		}
		elseif ($type == 'zip' || $type == 'rar' || $type == '7z') {
			$icon = 'fa-copy';
		}
		elseif ($type == 'mp3' || $type == 'wmv' || $type == 'wav') {
			$icon = 'fa-play-circle';
		}
		elseif ($type == 'mp4') {
			$icon = 'fa-file-video';
		}
		else {
			$icon = 'fa-file';
		}
		return $icon;
	}
}



// Get file ext according to file type
if (!function_exists('my_get_file_ext')) {
	function my_get_file_ext($type) {
		switch ($type) {
			case 'image' :
			  $ext_list = 'gif,png,jpg,jpeg,svg,psd,ai';
			  break;
			case 'av' :
			  $ext_list = 'mp3,mp4,wmv,wav';
			  break;
			case 'document' :
			  $ext_list = 'doc,docx,xls,xlsx,ppt,pptx,pdf,txt';
			  break;
			case 'other' :
			  $ext_list = 'gif,png,jpg,jpeg,svg,psd,ai,mp3,mp4,wmv,wav,doc,docx,xls,xlsx,ppt,pptx,pdf,txt';
			  break;
			default :
			  $ext_list = '';
		}
		return $ext_list;
	}
}



// Generate thumbnail
if (!function_exists('my_generate_thumbnail')) {
	function my_generate_thumbnail($path, $width, $height) {
		$CI = &get_instance();
		$config['image_library'] = 'gd2';
		$config['source_image'] = $path;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$CI->load->library('image_lib', $config);
		$CI->image_lib->resize();
		return TRUE;
	}
}



// Generate slug
if (!function_exists('my_generate_slug')) {
	function my_generate_slug($tbl, $field, $subject) {
		$CI = &get_instance();
		$slug = str_replace(' ', '-', preg_replace("/[^a-zA-Z0-9\s]/", "", $subject));
		if (mb_check_encoding($slug, 'ASCII') && preg_match("/^([-a-z0-9_-])+$/i", $slug)) {
			for ($i = 0; $i <= 10000; $i++) {
				$query = $CI->db->where($field, $slug)->get($tbl, 1);
				if (!$query->num_rows()) {
					break;
				}
				else {
					$slug = str_replace(' ', '-', preg_replace("/[^a-zA-Z0-9\s]/", "", $subject)) . '-' . $i;
				}
			}
		}
		else {
			$slug = my_random();
		}
		return strtolower($slug);
	}
}



// Get certain words from sentence
if (!function_exists('my_get_words')) {
	function my_get_words($sentence, $count = 10) {
		if (mb_detect_encoding($sentence, 'ASCII', true)) {
			preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
			$result = $matches[0];
		}
		else {
			$result = implode(' ', array_slice(explode(' ', $sentence), 0, $count));
		}
		return $result;
	}
}



// Get all payment items as array
if (!function_exists('my_get_all_payment_items')) {
	function my_get_all_payment_items($free = FALSE) {
		$CI = &get_instance();
		if ($free) {
			$CI->db->where('item_price', 0);
		}
		$query = $CI->db->where('enabled', 1)->where('trash', 0)->get('payment_item');
		if ($query->num_rows()) {
			$rs = $query->result();
			foreach ($rs as $row) {
				$item_array[$row->ids] = ucfirst($row->type) . ' -> ' . $row->item_name;
			}
			return $item_array;
		}
		else {
			return [];
		}
	}
}



// convert html tag from textarea in order to show correctly
if (!function_exists('my_reverse_from_html_for_input')) {
	function my_reverse_from_html_for_input($text, $double_quote = FALSE) {
		if ($double_quote) {
			$text = str_replace("<br>", "\n", str_replace("<br>", "\r\n", $text));  //handle '<br>'
		}
		else {
			$text = str_replace('<br>', '\n', str_replace('<br>', '\r\n', $text));  //handle '<br>'
		}
		$text = str_replace('&amp;', '&', $text);  //handle '&'
		$text = str_replace('&lt;', '<', $text);  //handle '<'
		$text = str_replace('&gt;', '>', $text);  //handle '>'
		return $text;
	}
}



// convert html tag from textarea in order to show correctly
if (!function_exists('my_reverse_from_input_for_html')) {
	function my_reverse_from_input_for_html($text, $double_quote = FALSE) {
		if ($double_quote) {
			$text = str_replace("\n", "<br>", str_replace("\r\n", "<br>", $text));  //handle '\r\n'
		}
		else {
			$text = str_replace('\n', '<br>', str_replace('\r\n', '<br>', $text));  //handle '\r\n'
		}
		return $text;
	}
}



// get payment item information by item ids from payment_item
if (!function_exists('my_get_payment_item')) {
	function my_get_payment_item($ids, $field) {
		$CI = &get_instance();
		$query = $CI->db->where('ids', $ids)->get('payment_item', 1);
		if ($query->num_rows()) {
			return $query->row()->$field;
		}
		else {
			return FALSE;
		}
	}
}



// generate pagination
if (!function_exists('my_generate_pagination')) {
	function my_generate_pagination($config_array) {
		$CI = &get_instance();
		$CI->load->library('pagination');
		if ($config_array['scheme'] == 'impact') {  //default front-end
			$config['full_tag_open'] = '<ul class="pagination pagination-md">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link']  = my_caption('global_first');
			$config['first_tag_open'] = '<li class="page-item">';
			$config['first_tag_close'] = '</li>';
			$config['prev_link']  = my_caption('global_previous');
			$config['prev_tag_open'] = '<li class="page-item">';
			$config['prev_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="page-item page-link active">';
			$config['cur_tag_close'] = '</li>';
			$config['num_tag_open'] = '<li class="page-item">';
			$config['num_tag_close'] = '</li>';
			$config['next_link']  = my_caption('global_next');
			$config['next_tag_open'] = '<li class="page-item ">';
			$config['next_tag_close'] = '</li>';
			$config['last_link']  = my_caption('global_last');
			$config['last_tag_open'] = '<li class="page-item">';
			$config['last_tag_close'] = '</li>';
			$config['attributes'] = array('class' => 'page-link');
		}
		elseif ($config_array['scheme'] == 'sbadmin2') {  //default back-end
			//bala bala
		}
		$config['base_url'] = $config_array['base_url'];
		$config['total_rows'] = $config_array['total_rows'];
		$config['per_page'] = $config_array['per_page'];
		$config['page_query_string'] = TRUE;
		$CI->pagination->initialize($config);
		return $CI->pagination->create_links();
	}
}



// check whether smtp setting is emtyp
if (!function_exists('my_check_smtp_setting')) {
	function my_check_smtp_setting() {
		$smtp_setting_array = json_decode(my_global_setting('smtp_setting'), TRUE);
		if ($smtp_setting_array['host'] != '') {
			return TRUE;
		}
		else {  //smtp is not set
			return FALSE;
		}
	}
}



// check whether the user has purchased at least one item
if (!function_exists('my_kyc_check')) {
	function my_kyc_check($user_ids) {
		$CI = &get_instance();
		$query = $CI->db->where('user_ids', $user_ids)->where('callback_status', 'success')->get('payment_log', 1); //any of the three types purchased
		$result = FALSE;
		if ($query->num_rows()) {
			$result = TRUE;
		}
		else {
			$query_purchase = $CI->db->where('user_ids', $user_ids)->get('payment_purchased', 1); //purchase type
			if ($query_purchase->num_rows()) {
				$result = TRUE;
			}
			else {
				$query_subscription = $CI->db->where('user_ids', $user_ids)->get('payment_subscription', 1); //subscription type
				if ($query_subscription->num_rows()) {
					$result = TRUE;
				}
			}
		}
		return $result;
	}
}




// escape html according to setting, not all echo needs to be escaped
// This function is used in all output like "echo" and "=", so it's easy for you to add your own rules to escape the html filed
// Currently, There is no behaviour by default
// Make sure you test carefully after you change the behavious of this function
// We will add further rules here for some specific purposes
if (!function_exists('my_esc_html')) {
	function my_esc_html($text, $execute = FALSE) {
		if ($execute) {
			return html_escape($text);
		}
		else { //echo original
			return $text;
		}
	}
}



// check maintenance, redirect to maintenance page
if (!function_exists('my_check_maintenance')) {
	function my_check_maintenance() {
		$CI = &get_instance();
		if ($CI->setting->maintenance_mode && !$_SESSION['is_admin']) {
			redirect(base_url('home/maintenance'));
		}
		else {
			return TRUE;
		}
	}
}



// pending task counter for multiple modules
if (!function_exists('my_pending_counter')) {
	function my_pending_counter($counter_type) {
		$CI = &get_instance();
		switch ($counter_type) {
			case 'support_admin' :  //for admin, currently, we need to check ticket/contact_form
			  $query = $counter = $CI->db->where('ids_father', '0')->where('main_status', '2')->where('read_status', '0')->get('ticket', 1); 
			  if ($query->num_rows()) {
				  $counter = 'New';
			  }
			  else {
				  $query = $CI->db->where('read_status', '0')->get('contact_form', 1);
				  ($query->num_rows()) ? $counter = 'New' : $counter = '';
			  }
			  break;
			case 'ticket_admin' :  //for admin
			  $counter = $CI->db->where('ids_father', '0')->where('main_status', '2')->where('read_status', '0')->get('ticket')->num_rows();
			  break;
			case 'ticket_user' :  //for user
			  $counter = $CI->db->where('user_ids', $_SESSION['user_ids'])->where('ids_father', '0')->where('main_status', '1')->where('read_status', '0')->get('ticket')->num_rows();
			  break;
			case 'contact_form' :  //for admin
			  $counter = $CI->db->where('read_status', '0')->get('contact_form')->num_rows();
			  break;
		}
		return $counter;
	}
}



// load menu. generally use for add-ons
if (!function_exists('my_hook_menu')) {
	function my_hook_menu($menu_type) {
		$CI = &get_instance();
		$file_path = FCPATH . 'application/views/themes/' . $CI->setting->theme . '/Menu/' . $menu_type . '.php';
		if (file_exists($file_path)) {
			return my_load_view($CI->setting->theme, 'Menu/' . $menu_type, '', TRUE);
		}
		else {
			return '';
		}
	}
}



// check string contains string
if (!function_exists('my_check_str_contains')) {
	function my_check_str_contains($full_str, $check_str, $case_sensitive = TRUE) {
		$result = FALSE;
		if ($full_str == $check_str) {
			$result = TRUE;
		}
		else {
			if (strpos($full_str, $check_str) !== false) {
				$result = TRUE;
			}
		}
		return $result;
	}
}



// set cookie of language
if (!function_exists('my_set_language_cookie')) {
	function my_set_language_cookie($language) {
		$CI = &get_instance();
		$language = ($language != '') ? $language : $CI->config->item('language');
		$language_cookie = array(
		  'name' => 'site_lang',
		  'value' => $language,
		  'domain' => $_SERVER['HTTP_HOST'],
		  'expire' => 365*86400
		);
		set_cookie($language_cookie);
		return TRUE;
	}
}



// enhanced safety 
// this function will be updated continuously if it's necessary.
// it's used to prompt and handle the possible safety problem to super admin.
if (!function_exists('my_enhanced_safety')) {
	function my_enhanced_safety() {
		$result = TRUE;
		if (file_exists(FCPATH . 'vendor/phpunit')) {
			if (is_writable(FCPATH . 'vendor/phpunit')) {
				array_map('unlink', glob(FCPATH . 'vendor/phpunit/*.*'));
				rmdir(FCPATH . 'vendor/phpunit');
			}
		}
		(file_exists(FCPATH . 'vendor/phpunit')) ? $result = 'For safety reason, please remove the directory: "vendor/phpunit"' : null;
		return $result;
	}
}



// ------ for addons starts -----

// check whether coupon is enabled
if (!function_exists('my_coupon_module')) {
	function my_coupon_module() {
		$CI = &get_instance();
		$enabled_addons_array = json_decode($CI->db->get('setting', 1)->row()->enabled_addons, TRUE);
		if (file_exists(FCPATH . 'application/controllers/Coupon.php') && !empty($enabled_addons_array['coupon'])) {
			$result = TRUE;
		}
		else {
			$result = FALSE;
		}
		return $result;
	}
}



// for affiliate starts
if (!function_exists('my_affiliate_module')) {
	function my_affiliate_module() {
		$CI = &get_instance();
		$enabled_addons_array = json_decode($CI->db->get('setting', 1)->row()->enabled_addons, TRUE);
		if (file_exists(FCPATH . 'application/controllers/Affiliate.php') && !empty($enabled_addons_array['affiliate'])) {
			$result = TRUE;
		}
		else {
			$result = FALSE;
		}
		return $result;
	}
}

// ----- for addons ends -----

?>




