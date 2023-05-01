<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$serverSoftware = explode('/', $_SERVER['SERVER_SOFTWARE'])[0];

//basic information for caption
$html_title = 'CyberBukit Membership Installer';
$title = 'Install CyberBukit Membership';
$notification = 'If you have any questions, shoot us an email: <b>support@cyberbukit.com</b>';
$step_title_1 = 'Requirement';
$step_title_2 = 'Database';
$step_title_3 = 'License';
$current_version = '1.8.0';

//detect enviroment
$required_php_version = '7.2';
$detect_compentent_list = array('mysqli_connect', 'mod_rewrite', 'ZipArchive', 'gd', 'curl');
$detect_directory_list = array('upload', 'backup', 'application/config', 'application/logs', 'application/cache/ci_session');


//create table
function execute_db_installation($mysql_new_connection, $prefix) {
	global $current_version;
	
	// activity
	$sql = 'create table ' . $prefix . 'activity (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  user_ids char(50) NOT NULL,
	  level varchar(11) NOT NULL,
	  event varchar(50) NOT NULL,
	  detail text NOT NULL,
	  debug_detail text NOT NULL,
	  created_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// backup_log
	$sql .= 'create table ' . $prefix . 'backup_log (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  file_path varchar(255) NOT NULL,
	  options varchar(50) NOT NULL,
	  created_method varchar(8) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// blog
	$sql .= 'create table ' . $prefix . 'blog (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  author varchar(255) NOT NULL,
	  user_ids char(50) NOT NULL,
	  slug varchar(512) NOT NULL,
	  cover_photo varchar(255) NOT NULL,
	  catalog varchar(50) NOT NULL,
	  subject varchar(255) NOT NULL,
	  keyword varchar(1024) NOT NULL,
	  body text NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  updated_time timestamp NULL DEFAULT NULL,
	  read_times int(11) NOT NULL,
	  comments text NOT NULL,
	  enabled tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// catalog
	$sql .= 'create table ' . $prefix . 'catalog (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  type varchar(50) NOT NULL,
	  name varchar(50) NOT NULL,
	  description text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// contact_form
	$sql .= 'create table ' . $prefix . 'contact_form (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  name varchar(50) NOT NULL,
	  email_address varchar(255) NOT NULL,
	  phone varchar(50) NOT NULL,
	  catalog varchar(50) NOT NULL,
	  message text NOT NULL,
	  ip_address varchar(50) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  read_status tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// documentation
	$sql .= 'create table ' . $prefix . 'documentation (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  slug varchar(512) NOT NULL,
	  catalog varchar(50) NOT NULL,
	  subject varchar(255) NOT NULL,
	  keyword varchar(1024) NOT NULL,
	  body text NOT NULL,
	  attachment text NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  updated_time timestamp NULL DEFAULT NULL,
	  enabled tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// email_template
	$sql .= 'create table ' . $prefix . 'email_template (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids varchar(50) NOT NULL,
	  purpose varchar(50) NOT NULL,
	  built_in tinyint(4) NOT NULL,
	  subject varchar(255) NOT NULL,
	  body text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	$signup_activation = '<p>Hello {{first_name}}!</p><p>Thank you for joining CyberBukit Membership!</p><p>Please click the following URL to activate your account:<br>{{verification_url}}<br></p><p>If clicking the URL above doesn\\\'t work, copy and paste the URL into a browser window.</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>';	
	$reset_password = '<p>Hello!</p><p>We\\\'ve generated a URL to reset your password. If you did not request to reset your password or if you\\\'ve changed your mind, simply ignore this email and nothing will happen.</p><p>You can reset your password by clicking the following URL:<br>{{verification_url}}</p><p>If clicking the URL above does not work, copy and paste the URL into a browser window. The URL will only be valid for a limited time and will expire.</p><p>Thank you,</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>';
	$change_email = '<p>Hello!</p><p>We\\\'ve generated a URL to change your email address. If you did not request to change your email address or if you\\\'ve changed your mind, simply ignore this email and nothing will happen.</p><p>You can change your email address by clicking the following URL:<br>{{verification_url}}</p><p>If clicking the URL above does not work, copy and paste the URL into a browser window. The URL will only be valid for a limited time and will expire.</p><p>Thank you,</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>';
	$notify_email = '<p>Hello {{first_name}}!</p><p>Welcome to CyberBukit Membership!</p><p>Your CyberBukit Membership account has been created successfully!</p><p>Here is your signin credential:<br>Email Address: {{email_address}}<br>Password: {{password}}<br>Signin URL: {{base_url}}<br>Please sign in and change your password. If you have any questions please feel free to contact us.</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>';
	$invite_email = '<p>Hello!</p><p>The administrator of CyberBukit Membership has sent you this email to invite you to sign up as our member.</p><p>Please click the following URL to activate your account:<br>{{verification_url}}</p><p>If clicking the URL above doesn\\\'t work, copy and paste the URL into a browser window.</p><p>CyberBukit Membership Support<br>https://membership.demo.cyberbukit.com</p>';
	$email_2FA = '<p>Your CyberBukit Membership verification code is {{code}}</p>';
	$ticket_notify_agent = '<p>A new ticket raised or updated. Please sign in and check.</p>';
	$ticket_notify_user = '<p>Dear customer,</p><p>You ticket has been replied by our agent(s). Please sign in and check.</p><p>CyberBukit Membership Support</p><p>https://membership.demo.cyberbukit.com</p>';
	$pay_success = '<p>Dear customer,</p><p>We have received your payment for {{purchase_item}}, The amount is {{purchase_price}}.</p><p>Thanks for your payment.</p><p>CyberBukit Membership Support</p><p>https://membership.demo.cyberbukit.com</p>';
	$sql .= 'insert into ' . $prefix . 'email_template (ids, purpose, built_in, subject, body) values
	  (\'68KCh0tH40689a5a3d773c0e66c9ebf17fa1ddfd2M5czj3tVX\', \'signup_activation\', 1, \'Activate your account\', \'' . $signup_activation . '\'),
	  (\'Sm1M50EX7c92c02f0db2d4d0b57958b7a5897ac4e6Lm2nECNP\', \'reset_password\', 1, \'Password reset\', \'' . $reset_password . '\'),
	  (\'y4Y8hpRW1f21144be085fec47b9380fca78dbdb885xvEcLYdP\', \'change_email\', 1, \'Verify your email address\', \'' . $change_email . '\'),
	  (\'jSwbWZdAeef79225efbec3e064b048f20eaa7bb9cwnYX4SWrG\', \'notify_email\', 1, \'Your account has been created\', \'' . $notify_email . '\'),
	  (\'nIQMARFoda2cade675a5876feb1bb77ad4db0086aH6MghaBPL\', \'invite_email\', 1, \'Signup Invitation\', \'' . $invite_email . '\'),
	  (\'pW5oGbDQHf7ecf4013f762df420bffe8011b18616mDIwCxvMA\', \'2FA_email\', 1, \'Two Factor Authentication\', \'' . $email_2FA . '\'),
	  (\'tzqM8x3p7174d8c2d251203aaeeaa85fe4d6ad8338BhC0Oq5W\', \'ticket_notify_agent\', 1, \'A new ticket raised\', \'' . $ticket_notify_agent . '\'),
	  (\'8HKUnZ5F24a9ce26849ee64dcba7aaa2187950d40vHQYtzcsK\', \'ticket_notify_user\', 1, \'Your ticket has been replied\', \'' . $ticket_notify_user . '\'),
	  (\'5DQa8U2Ig7810ecd10382727e3c8f9be866362547OaCI8ki3t\', \'pay_success\', 1, \'We have received your payment\', \'' . $pay_success . '\');';


	// faq
	$sql .= 'create table ' . $prefix . 'faq (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  catalog varchar(50) NOT NULL,
	  subject varchar(255) NOT NULL,
	  body text NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  enabled tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// file_manager
	$sql .= 'create table ' . $prefix . 'file_manager (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  temporary_ids varchar(50) NOT NULL,
	  catalog varchar(50) NOT NULL,
	  original_filename varchar(255) NOT NULL,
	  file_ext varchar(10) NOT NULL,
	  description text NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  trash tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// notification
	$sql .= 'create table ' . $prefix . 'notification (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids varchar(50) NOT NULL,
	  from_user_ids varchar(32) NOT NULL,
	  to_user_ids varchar(50) NOT NULL,
	  subject varchar(255) NOT NULL,
	  body text NOT NULL,
	  is_read tinyint(4) NOT NULL,
	  send_email tinyint(4) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  read_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// oauth_connector
	$sql .= 'create table ' . $prefix . 'oauth_connector (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  purpose char(6) NOT NULL,
	  provider varchar(50) NOT NULL,
	  identifier varchar(255) NOT NULL,
	  email_address varchar(50) NOT NULL,
	  first_name varchar(50) NOT NULL,
	  last_name varchar(50) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// payment_item
	$sql .= 'create table ' . $prefix . 'payment_item (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  enabled tinyint(1) NOT NULL,
	  type varchar(50) NOT NULL,
	  item_name varchar(255) NOT NULL,
	  item_description text NOT NULL,
	  item_currency char(3) NOT NULL,
	  item_price double NOT NULL,
	  recurring_interval varchar(5) NOT NULL,
	  recurring_interval_count int(11) NOT NULL,
	  stuff_setting text NOT NULL,
	  purchase_limit tinyint(4) NOT NULL,
	  access_condition text NOT NULL,
	  trash tinyint(4) NOT NULL,
	  auto_renew tinyint(4) NOT NULL,
	  access_code varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// payment_log
	$sql .= 'create table ' . $prefix . 'payment_log (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  type varchar(50) NOT NULL,
	  gateway varchar(50) NOT NULL,
	  currency char(3) NOT NULL,
	  price double NOT NULL,
	  quantity int(11) NOT NULL,
	  amount double NOT NULL,
	  gateway_identifier varchar(255) NOT NULL,
	  gateway_event_id varchar(255) NOT NULL,
	  item_ids char(50) NOT NULL,
	  item_name varchar(255) NOT NULL,
	  redirect_status varchar(50) NOT NULL,
	  callback_status varchar(50) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  callback_time timestamp NULL DEFAULT NULL,
	  visible_for_user tinyint(4) NOT NULL,
	  generate_invoice tinyint(4) NOT NULL,
	  description varchar(1024) NOT NULL,
	  coupon varchar(50) NOT NULL,
	  coupon_discount double NOT NULL,
	  tax double NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// payment_purchased
	$sql .= 'create table ' . $prefix . 'payment_purchased (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  payment_ids char(50) NOT NULL,
	  item_type varchar(12) NOT NULL,
	  item_ids char(50) NOT NULL,
	  item_name varchar(255) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  description varchar(1024) NOT NULL,
	  stuff text NOT NULL,
	  used_up tinyint(4) NOT NULL,
	  auto_renew tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// payment_subscription
	$sql .= 'create table ' . $prefix . 'payment_subscription (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  item_ids char(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  payment_gateway varchar(50) NOT NULL,
	  gateway_identifier varchar(255) NOT NULL,
	  gateway_auth_code varchar(255) NOT NULL,
	  quantity int(11) NOT NULL,
	  status varchar(50) NOT NULL,
	  start_time timestamp NULL DEFAULT NULL,
	  end_time timestamp NULL DEFAULT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  updated_time timestamp NULL DEFAULT NULL,
	  description varchar(1024) NOT NULL,
	  stuff text NOT NULL,
	  used_up tinyint(4) NOT NULL,
	  auto_renew tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// permission
	$sql .= 'create table ' . $prefix . 'permission (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  built_in tinyint(4) NOT NULL,
	  name varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	$sql .= 'insert into ' . $prefix . 'permission (ids, built_in, name) values
	  (\'clS0yImZs6d5c5313f55ce8da264739388072f066KxvhYGN6r\', 1, \'User_Management\'),
	  (\'GPb9zsStof247c17218790606d68f5b35d717a11fL9gIMAB3w\', 1, \'Roles_And_Permissions\'),
	  (\'qVkzOmGWvfbffeb1362f77a7e4907c4a406cf7d8fJ6zlgRE4b\', 1, \'Global_Settings\'),
	  (\'PltnM3iOv30c602d36754ab238e35a90d59d77713xcXwaBnhu\', 1, \'Admin_Tools\'),
	  (\'xUghnS8rX9057421bb62cc8e6800b720c0f4042beR9m7zZ3xb\', 1, \'Database_Backup\'),
	  (\'g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT\', 1, \'Payment_Management\'),
	  (\'VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm\', 1, \'Support_Management\');';
	  
	  
	// role
	$sql .= 'create table ' . $prefix . 'role (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  built_in tinyint(4) NOT NULL,
	  name varchar(50) NOT NULL,
	  permission text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	$sql .= 'insert into ' . $prefix . 'role (ids, built_in, name, permission) values
	  (\'d7DrO85Nu9a534ea1c14ea96369c921933c347f5dhMZnVfg46\', 1, \'Super_Admin\', \'{"clS0yImZs6d5c5313f55ce8da264739388072f066KxvhYGN6r":true,"GPb9zsStof247c17218790606d68f5b35d717a11fL9gIMAB3w":true,"qVkzOmGWvfbffeb1362f77a7e4907c4a406cf7d8fJ6zlgRE4b":true,"PltnM3iOv30c602d36754ab238e35a90d59d77713xcXwaBnhu":true,"xUghnS8rX9057421bb62cc8e6800b720c0f4042beR9m7zZ3xb":true,"g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT":true,"VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm":true}\'),
	  (\'wIHxFXf2od10023bde3961e6fed9c560e13ac75f2sE03pBt7v\', 1, \'Admin\', \'{"clS0yImZs6d5c5313f55ce8da264739388072f066KxvhYGN6r":true,"GPb9zsStof247c17218790606d68f5b35d717a11fL9gIMAB3w":false,"qVkzOmGWvfbffeb1362f77a7e4907c4a406cf7d8fJ6zlgRE4b":false,"PltnM3iOv30c602d36754ab238e35a90d59d77713xcXwaBnhu":false,"xUghnS8rX9057421bb62cc8e6800b720c0f4042beR9m7zZ3xb":false,"g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT":false,"VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm":false}\'),
	  (\'S4ZhmaqIO1a311dffa9b3cace791c8993964e5cd95dJi4Nj3F\', 1, \'User\', \'{"clS0yImZs6d5c5313f55ce8da264739388072f066KxvhYGN6r":false,"GPb9zsStof247c17218790606d68f5b35d717a11fL9gIMAB3w":false,"qVkzOmGWvfbffeb1362f77a7e4907c4a406cf7d8fJ6zlgRE4b":false,"PltnM3iOv30c602d36754ab238e35a90d59d77713xcXwaBnhu":false,"xUghnS8rX9057421bb62cc8e6800b720c0f4042beR9m7zZ3xb":false,"g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT":false,"VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm":false}\');';
	
	
	//  script_addons
	$sql .= 'create table ' . $prefix . 'script_addons (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  name varchar(255) NOT NULL,
	  version varchar(50) NOT NULL,
	  license_code varchar(255) NOT NULL,
	  updater_id int(11) NOT NULL,
	  updater_key varchar(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	$sql .= 'insert into ' . $prefix . 'script_addons (name, version, license_code, updater_id, updater_key) values 
	  ("CyberBukit Add-on - Coupon and Affiliate", "1.0.0", "", "18", "chfPiVWqLXeGDpO3"),
	  ("CyberBukit Add-on - Multiple Payment Gateways", "1.0.2", "", "19", "5C9Mxq1j6PEBaLhN");';
	
	
	// coupon
	$sql .= 'create table ' . $prefix . 'coupon (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids varchar(50) NOT NULL,
	  name varchar(50) NOT NULL,
	  code varchar(50) NOT NULL,
	  applicable_scope text NOT NULL,
	  discount_type char(1) NOT NULL,
	  discount_amount double NOT NULL,
	  valid_from timestamp NULL DEFAULT NULL,
	  valid_till timestamp NULL DEFAULT NULL,
	  use_times_limit int(11) NOT NULL,
	  use_times_count int(11) NOT NULL,
	  enabled tinyint(1) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  description varchar(1024) NOT NULL,
	  stuff text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// coupon_log
	$sql .= 'create table ' . $prefix . 'coupon_log (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  code varchar(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  payment_ids char(50) NOT NULL,
	  item_name varchar(50) NOT NULL,
	  currency char(3) NOT NULL,
	  item_price double NOT NULL,
	  discount double NOT NULL,
	  amount double NOT NULL,
	  created_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// affiliate_log
	$sql .= 'create table ' . $prefix . 'affiliate_log (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  user_ids_referred char(50) NOT NULL,
	  payment_ids char(50) NOT NULL,
	  item_name varchar(50) NOT NULL,
	  from_ip varchar(50) NOT NULL,
	  currency char(3) NOT NULL,
	  amount double NOT NULL,
	  commission double NOT NULL,
	  stuff text NOT NULL,
	  created_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// affiliate_payout
	$sql .= 'create table ' . $prefix . 'affiliate_payout (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	  ids char(50) NOT NULL, 
	  user_ids char(50) NOT NULL, 
	  operator_ids char(50) NOT NULL, 
	  email_address varchar(255) NOT NULL, 
	  amount varchar(1024) NOT NULL, 
	  created_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// session
	$sql .= 'create table ' . $prefix . 'session (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  type varchar(50) NOT NULL,
	  user_ids char(50) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  expired_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// setting
	$sql .= 'create table ' . $prefix . 'setting (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  sys_name varchar(50) NOT NULL,
	  theme varchar(50) NOT NULL,
	  maintenance_mode tinyint(4) NOT NULL,
	  maintenance_message varchar(255) NOT NULL,
	  signup_enabled tinyint(4) NOT NULL,
	  psr varchar(6) DEFAULT NULL,
	  tc_show tinyint(4) NOT NULL,
	  terms_conditions text NOT NULL,
	  email_verification_required tinyint(4) NOT NULL,
	  signin_before_verified tinyint(4) NOT NULL,
	  remember tinyint(4) NOT NULL,
	  kyc tinyint(4) NOT NULL,
	  forget_enabled tinyint(4) NOT NULL,
	  api_enabled tinyint(4) NOT NULL,
	  html_purify tinyint(4) NOT NULL,
	  xss_clean tinyint(4) NOT NULL,
	  throttling_policy varchar(6) NOT NULL,
	  throttling_unlock_time tinyint(4) NOT NULL,
	  recaptcha_enabled tinyint(4) NOT NULL,
	  recaptcha_detail varchar(255) NOT NULL,
	  google_analytics_id varchar(50) NOT NULL,
	  oauth_setting text NOT NULL,
	  two_factor_authentication varchar(50) NOT NULL,
	  smtp_setting text NOT NULL,
	  page_size tinyint(4) NOT NULL,
	  default_role char(50) NOT NULL,
	  default_package varchar(50) NOT NULL,
	  debug_level tinyint(4) NOT NULL,
	  last_backup_time timestamp NULL DEFAULT NULL,
	  gdpr text NOT NULL,
	  payment_setting text NOT NULL,
	  invoice_setting text NOT NULL,
	  ticket_setting text NOT NULL,
	  file_setting text NOT NULL,
	  front_setting text NOT NULL,
	  enabled_addons text NOT NULL,
	  affiliate_setting varchar(1024) NOT NULL,
	  dashboard_custom_css varchar(255) NOT NULL,
	  dashboard_custom_javascript varchar(255) NOT NULL,
	  version varchar(10) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	$sql .= 'insert into ' . $prefix . 'setting (sys_name, theme, maintenance_mode, maintenance_message, signup_enabled, psr, tc_show, terms_conditions, email_verification_required, signin_before_verified, remember, kyc, forget_enabled, api_enabled, html_purify, xss_clean, throttling_policy, throttling_unlock_time, recaptcha_enabled, recaptcha_detail, google_analytics_id, oauth_setting, two_factor_authentication, smtp_setting, page_size, default_role, default_package, debug_level, last_backup_time, gdpr, payment_setting, invoice_setting, ticket_setting, file_setting, front_setting, enabled_addons, affiliate_setting, dashboard_custom_css, dashboard_custom_javascript, version) values
	  (\'CyberBukit Membership\', \'default\', 0, \'Under Maintenance, Please try later.\', 1, \'low\', 1, \'{"title":"T&C Title","body":"T&C Body"}\', 0, 0, 1, 0, 1, 0, 0, 1, \'normal\', 15, 0, \'{"version":"v2_1","site_key":"","secret_key":""}\', \'\', \'{"google":{"enabled":0,"client_id":"","client_secret":""},"facebook":{"enabled":0,"app_id":"","app_secret":""},"twitter":{"enabled":0,"consumer_key":"","consumer_secret":""}}\', \'disabled\', \'{"host":"","port":"","is_auth":1,"username":"","password":"","crypto":"none","from_email":"","from_name":""}\', 25, \'S4ZhmaqIO1a311dffa9b3cace791c8993964e5cd95dJi4Nj3F\', 0, 0, \'2000-1-1\', \'{"enabled":1,"allow_remove":0,"cookie_message":"This website uses cookies to ensure you get the best experience on our website.","cookie_policy_link_text":"Learn more","cookie_policy_link":""}\', \'{"type":"sandbox","feature":"both","tax_rate":0,"stripe_one_time_enabled":"0","stripe_recurring_enabled":"0","stripe_publishable_key":"","stripe_secret_key":"","stripe_signing_secret":"","paypal_one_time_enabled":"0","paypal_recurring_enabled":"0","paypal_client_id":"","paypal_secret":"","paypal_webhook_id":""}\', \'{"enabled":1,"invoice_format":"pdf","company_name":"","company_number":"","tax_number":"","address_line_1":"","address_line_2":"","phone":""}\', \'{"enabled":1,"guest_ticket":0,"rating":1,"allow_upload":0,"notify_agent_list":"","notify_user":0,"close_rule":"3"}\', \'{"file_type":"jpg|jpeg|png|gif|svg|zip|rar|pdf|mp3|mp4|doc|docx|xls|xlsx","file_size":"102400"}\', \'{"enabled":0,"logo":"logo.png","company_name":"CyberBukit","email_address":"support@cyberbukit.com","html_title":"CyberBukit","html_author":"CyberBukit","html_description":"","html_keyword":"","about_us":"","pricing_enabled":1,"faq_enabled":1,"documentation_enabled":1,"blog_enabled":1,"subscriber_enabled":1,"social_facebook":"","social_twitter":"","social_linkedin":"","social_github":"","custom_css":"","custom_javascript":""}\', \'{"coupon":1,"affiliate":1,"payment":1}\', \'{"enabled":0,"commission_policy":"A","commission_rate":0, "description":"", "stuff":""}\', \'\', \'\', \'' . $current_version . '\');';

	
	// statistics
	$sql .= 'create table ' . $prefix . 'statistics (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  type varchar(50) NOT NULL,
	  value text NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	$sql .= 'insert into ' . $prefix . 'statistics (type, value) values
	  (\'signup_last_six_days\', \'{"2020-11-25":0,"2020-11-26":0,"2020-11-27":0,"2020-11-29":0,"2020-11-29":0,"2020-11-30":0}\');';
	
	
	// subscriber
	$sql .= 'create table ' . $prefix . 'subscriber (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  email_address varchar(255) NOT NULL,
	  from_ip varchar(50) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// throttling
	$sql .= 'create table ' . $prefix . 'throttling (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ip varchar(50) NOT NULL,
	  user_tag varchar(50) NOT NULL,
	  times tinyint(4) NOT NULL,
	  time timestamp NULL DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// ticket
	$sql .= 'create table ' . $prefix . 'ticket (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  ids_father varchar(50) NOT NULL,
	  source varchar(10) NOT NULL,
	  user_ids char(50) NOT NULL,
	  user_fullname varchar(100) NOT NULL,
	  main_status tinyint(4) NOT NULL,
	  read_status tinyint(6) NOT NULL,
	  catalog varchar(50) NOT NULL,
	  priority tinyint(4) NOT NULL,
	  subject varchar(255) NOT NULL,
	  description longtext NOT NULL,
	  associated_files text NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  updated_time timestamp NULL DEFAULT NULL,
	  rating tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// token
	$sql .= 'create table ' . $prefix . 'token (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  type varchar(50) NOT NULL,
	  email varchar(50) NOT NULL,
	  phone varchar(21) NOT NULL,
	  token char(50) NOT NULL,
	  reference varchar(255) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  done tinyint(4) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	
	
	// user
	$sql .= 'create table ' . $prefix . 'user (
	  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  ids char(50) NOT NULL,
	  username varchar(20) NOT NULL,
	  password varchar(255) NOT NULL,
	  api_key char(50) NOT NULL,
	  balance text NOT NULL,
	  email_address varchar(50) NOT NULL,
	  email_verified tinyint(4) NOT NULL,
	  email_address_pending varchar(50) NOT NULL,
	  phone varchar(21) NOT NULL,
	  phone_verified tinyint(4) NOT NULL,
	  phone_pending varchar(21) NOT NULL,
	  oauth_google_identifier varchar(50) NOT NULL,
	  oauth_facebook_identifier varchar(50) NOT NULL,
	  oauth_twitter_identifier varchar(50) NOT NULL,
	  signup_source varchar(50) NOT NULL,
	  first_name varchar(50) NOT NULL,
	  last_name varchar(50) NOT NULL,
	  company varchar(255) NOT NULL,
	  avatar varchar(54) NOT NULL,
	  timezone varchar(255) NOT NULL,
	  date_format varchar(20) NOT NULL,
	  time_format varchar(20) NOT NULL,
	  language varchar(50) NOT NULL,
	  currency varchar(3) NOT NULL,
	  country varchar(2) NOT NULL,
	  address_line_1 varchar(255) NOT NULL,
	  address_line_2 varchar(255) NOT NULL,
	  city varchar(50) NOT NULL,
	  state varchar(50) NOT NULL,
	  zip_code varchar(20) NOT NULL,
	  role_ids varchar(50) NOT NULL,
	  status tinyint(4) NOT NULL,
	  created_time timestamp NULL DEFAULT NULL,
	  update_time timestamp NULL DEFAULT NULL,
	  login_success_detail text DEFAULT NULL,
	  online tinyint(4) NOT NULL,
	  online_time timestamp NULL DEFAULT NULL,
	  new_notification tinyint(4) NOT NULL,
	  referral varchar(255) NOT NULL,
	  affiliate_enabled tinyint(1) NOT NULL,
	  affiliate_code varchar(50) NOT NULL,
	  affiliate_earning varchar(1024) NOT NULL,
	  affiliate_setting varchar(1024) NOT NULL,
	  company_number varchar(50) NOT NULL,
	  tax_number varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
	$sql .= 'insert into ' . $prefix . 'user (ids, username, password, api_key, balance, email_address, email_verified, email_address_pending, phone, phone_verified, phone_pending, oauth_google_identifier, oauth_facebook_identifier, oauth_twitter_identifier, signup_source, first_name, last_name, company, avatar, timezone, date_format, time_format, language, currency, country, address_line_1, address_line_2, city, state, zip_code, role_ids, status, created_time, update_time, login_success_detail, online, online_time, new_notification, referral, affiliate_enabled, affiliate_code, affiliate_earning, affiliate_setting, company_number, tax_number) values
	  (\'0bWHjn9usb065a85cc223037e3b5dff82c4c08fba2XaMlC3Gk\', \'admin\', \'$2y$12$C7CqeB92FgzuqMDwqKrCz.jSau2HFsC7CzUMh39YfGYy4BatmJWzW\', \'pljhshumN66e81b818cbdfbki90e1190206e6cf7c97gassvv\', \'{"usd":0}\', \'admin@admin.com\', 1, \'\', \'\', 0, \'\', \'\', \'\', \'\', \'\', \'Super\', \'Admin\', \'\', \'default.jpg\', \'UTC\', \'Y-m-d\', \'H:i:s\', \'English\', \'USD\', \'\', \'\', \'\', \'\', \'\', \'\', \'d7DrO85Nu9a534ea1c14ea96369c921933c347f5dhMZnVfg46\', 1, \'2020-12-1\', \'2020-12-1\', \'{"time":"","interface":"","ip_address":"","user_agent":""}\', 0, \'2000-1-1\', 0, \'{"src_from":"","referral_code":""}\', 0, \'\', \'{}\', \'\', \'\', \'\');';

	$mysql_new_connection->multi_query($sql);
	return TRUE;	
}



function detect_php_version() {
	global $required_php_version;
	$res = array();
	$detect_php_version_array = explode('.', $required_php_version);
	$server_php_version_array = explode('.', phpversion());
	if ($server_php_version_array[0] >= $detect_php_version_array[0]) {
		($server_php_version_array[1] >= $detect_php_version_array[1]) ? $res = TRUE : $res = FALSE;
	}
	else {  //main version doesn't meet requirement
		$res = FALSE;
	}
	return $res;
}



function detect_compentent($compentent) {
	switch ($compentent) {
		case 'mysqli_connect' :
		  (!function_exists('mysqli_connect')) ? $res = 'No' : $res = 'Yes';
		  break;
		case 'mod_rewrite' :
		  global $serverSoftware;
		  if ($serverSoftware == 'Apache') {
			  if (function_exists('apache_get_modules')) {
				  (!in_array('mod_rewrite', apache_get_modules())) ? $res = 'No' : $res = 'Yes';
			  }
			  else {
				  $res = 'UnKnown';
			  }
		  }
		  else {
			  $res = 'UnKnown';
		  }
		  break;
		case 'ZipArchive' :
		  (!class_exists("ZipArchive")) ? $res = 'No' : $res = 'Yes';
		  break;
		case 'gd' :
		  (!(extension_loaded('gd') && function_exists('gd_info'))) ? $res = 'No' : $res = 'Yes';
		  break;
		case 'curl' :
		  (!extension_loaded('curl')) ? $res = 'No' : $res = 'Yes';
		  break;
		case 'bcmath' :
		  (!extension_loaded('bcmath')) ? $res = 'No' : $res = 'Yes';
		  break;
		default :
		  $res = 'UnKnown';
	}
	return $res;
}



function detect_db_connection($db_host, $db_name, $db_username, $db_password = '') {
	$port = 3306;
	$db_host_array = explode(':', $db_host);
	if (count($db_host_array) == 2) {
		$db_host = $db_host_array[0];
		$port = $db_host_array[1];
	}
	mysqli_report(MYSQLI_REPORT_STRICT);
	try {
		$connection = new mysqli($db_host, $db_username, $db_password, $db_name, $port);
		return $connection;
	}
	catch (Exception $e) {
		return FALSE;
		exit(0);
	}
}



function execute_db_config($db_host, $db_username, $db_password, $db_name, $db_prefix) {
	$target_file = '../application/config/database.php';
	copy('database.php', $target_file);
	$cfg = file_get_contents($target_file);
	$cfg = str_replace('<DB_HOST>', $db_host, $cfg);
	$cfg = str_replace('<DB_USERNAME>', $db_username, $cfg);
	$cfg = str_replace('<DB_PASSWORD>', $db_password, $cfg);
	$cfg = str_replace('<DB_NAME>', $db_name, $cfg);
	$cfg = str_replace('<DB_PREFIX>', $db_prefix, $cfg);
	file_put_contents($target_file, $cfg);
	return TRUE;
}



?>