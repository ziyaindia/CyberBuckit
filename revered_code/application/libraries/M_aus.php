<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(FCPATH . 'updater/software_updater.php');
require_once(FCPATH . 'updater/software_updater_config.php');

class M_aus {
	
	public function getAllVersions($my_aus_product_id = AUS_PRODUCT_ID, $my_aus_product_key = AUS_PRODUCT_KEY) {
		return ausGetAllVersions($my_aus_product_id, $my_aus_product_key);
	}
	
	
	
	public function getLatestVersion($version = '', $my_aus_product_id = AUS_PRODUCT_ID, $my_aus_product_key = AUS_PRODUCT_KEY) {
		return ausGetVersion($version, $my_aus_product_id, $my_aus_product_key);
	}
	
	
	
	public function downloadFile($license_code, $file_type, $version, $my_aus_product_id = AUS_PRODUCT_ID, $my_aus_product_key = AUS_PRODUCT_KEY) {
		return ausDownloadFile($license_code, $file_type, $version, $my_aus_product_id, $my_aus_product_key);
	}

	
	
	public function fetchQuery($license_code, $query_type, $version, $my_aus_product_id = AUS_PRODUCT_ID, $my_aus_product_key = AUS_PRODUCT_KEY) {
		return ausFetchQuery($license_code, $query_type, $version, $my_aus_product_id, $my_aus_product_key);
	}
	
	
}
?>