<?php
  defined('BASEPATH') OR exit('No direct script access allowed'); 
  
  
  $sub_menu_list = '';
  
  
  $enabled_addons_array = json_decode($this->setting->enabled_addons, TRUE);
  if (array_key_exists('coupon', $enabled_addons_array)) {
	  if ($enabled_addons_array['coupon']) {
		  $sub_menu_list .= '"admin_addon_menu_coupon" : {
			  "display" : 1,
			  "name" : "' . my_caption('addons_admin_menu_coupon') . '",
			  "link" : "coupon/list_coupon",
			  "active_condition" : "coupon/list_coupon,coupon/add,coupon/add_action,coupon/edit,coupon/edit_action,coupon/install,coupon/install_action"
		  },';
	  }
  }
  if (array_key_exists('affiliate', $enabled_addons_array)) {
	  if ($enabled_addons_array['affiliate']) {
		  $sub_menu_list .= '"admin_addon_menu_affiliate" : {
			  "display" : 1,
			  "name" : "' . my_caption('addons_admin_menu_affiliate') . '",
			  "link" : "affiliate/setting",
			  "active_condition" : "affiliate/setting,affiliate/setting_action,affiliate/member,affiliate/member_new,affiliate/member_new_action,affiliate/member_view,affiliate/member_view_action,affiliate/payout,affiliate/affiliate_view"
		  },';
	  }
  }
  if (array_key_exists('payment', $enabled_addons_array)) {
	  if ($enabled_addons_array['payment']) {
		  $sub_menu_list .= '"admin_addon_menu_payment" : {
			  "display" : ' . my_check_permission('Payment Management') . ',
			  "name" : "' . my_caption('addons_admin_menu_payment') . '",
			  "link" : "payment/setting",
			  "active_condition" : "payment/setting,payment/setting_action"
		  },';
	  }
  }


  if ($sub_menu_list == '') {
	  $sub_menu_list = '"admin_addon_menu_no_addons" : {
		  "display" : 1,
		  "name" : "' . my_caption('addons_admin_menu_no_addons') . '",
		  "link" : "dashboard",
		  "active_condition" : ""
	  }';
  }
  else {
	  $sub_menu_list = rtrim($sub_menu_list, ",");
  }
  
  
  $admin_addon_menu = ',"admin_addon_menu" : {
	  "display" : 1,
	  "name" : "' . my_caption('addons_admin_menu_title') . '",
	  "icon" : "fa fa-puzzle-piece",
	  "link" : "#",
	  "active_condition" : "",
	  "child_menu" : {' . $sub_menu_list . '}
  }';
  
  
  echo $admin_addon_menu;
?>