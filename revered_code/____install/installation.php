<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('inc/apl_core_configuration.php');
require_once('inc/apl_core_functions.php');

$mysql_connection = detect_db_connection($_POST["db_host"], $_POST["db_name"], $_POST["db_username"], $_POST["db_password"]);
if ($mysql_connection) {
	$purchase_code_verification = aplVerifyEnvatoPurchase(trim($_POST["purchase_code"]));
	if (empty($purchase_code_verification)) {
		if (isset($_SERVER['HTTPS'])) {
			$current_url_prefix = 'https://';
		}
		else {
			if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
				($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? $current_url_prefix = 'https://' : $current_url_prefix = 'http://';
				}
			else {
				$current_url_prefix = 'http://';
			}
		}
		$current_url = $current_url_prefix . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$base_url = str_ireplace('/install/installation.php', '', $current_url);
		$license_notifications_array = aplInstallLicense($base_url, '', trim($_POST["purchase_code"]), $mysql_connection);
		if ($license_notifications_array['notification_case']=="notification_license_ok") {
			$mysql_new_connection = detect_db_connection($_POST["db_host"], $_POST["db_name"], $_POST["db_username"], $_POST["db_password"]);
			$res = execute_db_installation($mysql_new_connection, $_POST['db_prefix']);
			if ($res === TRUE) {
				execute_db_config($_POST["db_host"], $_POST["db_username"], $_POST["db_password"], $_POST["db_name"], $_POST['db_prefix']);
				echo '{"result":true, "message":"success"}';
			}
			else {  //fail to create database
				echo '{"result":false, "message":"Fail to create tables in the database."}';
			}
		}
		else {    //fail to inital license
			echo '{"result":false, "message":"' . $license_notifications_array['notification_text'] . '"}';
		}
	}
	else {  //fail to verify envato purchase code
		echo '{"result":false, "message":"' . $purchase_code_verification['notification_text'] . '"}';
	}
}
else {
	echo '{"result":false, "message":"Unable to connect to MySQL, Please check your database credentials."}';
}
?>
