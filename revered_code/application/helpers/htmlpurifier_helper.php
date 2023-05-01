<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once FCPATH . 'vendor/autoload.php';

if (!function_exists('html_purify')) {
    function html_purify($input) {
		$CI = &get_instance();
		$config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $output = $purifier->purify($input);
        return $output;
    }
}
