<?php
class LanguageLoader {
	function initialize() {
		$CI =& get_instance();
		$CI->load->helper('language');
		$site_lang = $CI->input->cookie('site_lang', TRUE);
		if ($site_lang) {
           $CI->lang->load('global', $site_lang);
		   $CI->lang->load('payment', $site_lang);
		   $CI->lang->load('file', $site_lang);
		   $CI->lang->load('front', $site_lang);
		   if (file_exists(FCPATH . 'application/language/' . $site_lang . '/addon_lang.php')) {
			   $CI->lang->load('addon', $site_lang);
		   }
       } else {
           $CI->lang->load('global', $CI->config->item('language'));
		   $CI->lang->load('payment', $CI->config->item('language'));
		   $CI->lang->load('file', $CI->config->item('language'));
		   $CI->lang->load('front', $CI->config->item('language'));
		   if (file_exists(FCPATH . 'application/language/' . $CI->config->item('language') . '/addon_lang.php')) {
			   $CI->lang->load('addon', $CI->config->item('language'));
		   }
		   
	   }
	}
}
?>