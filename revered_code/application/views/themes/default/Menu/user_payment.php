<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  $user_payment_menu = ',"menu_user_payment_affiliate" : {
	  "display" : ' . my_user_setting($_SESSION['user_ids'], 'affiliate_enabled') . ',
	  "name" : "' . my_caption('addons_affiliate_user') . '",
	  "link" : "affiliate/my_affiliate",
	  "active_condition" : "affiliate/my_affiliate,affiliate/my_payout"
  }';
  
  echo my_esc_html($user_payment_menu);
  
?>