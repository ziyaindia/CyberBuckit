<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('apl/apl_core_configuration.php');
require_once('apl/apl_core_functions.php');

class M_apl {
	
	public function apl() {
		$CI = &get_instance();
		return aplVerifyLicense($CI->db->conn_id, 0);
	}
	
	
	
	public function uninstall() {
		$CI = &get_instance();
		return aplUninstallLicense($CI->db->conn_id);
	}
	
}
?>