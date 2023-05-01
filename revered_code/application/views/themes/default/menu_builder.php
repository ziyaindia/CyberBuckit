<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  // This is the siderbar menu builder
  // To add or remove certain menu, You just need to adjust the "menu_x" item
  $user_new_ticket = my_pending_counter('ticket_user');
  ($user_new_ticket > 0) ? $user_new_ticket = '<span class=\"badge badge-danger badge-counter ml-2 mt-1\">' . $user_new_ticket . '</span>' : $user_new_ticket = '';
  $menu_user_panel = '{
	  "display" : 1,' . my_hook_menu('user_global') . '
	  "menu_user_profile" : {
		  "display" : 1,
		  "name" :"' . my_caption('menu_sidebar_topbar_my_profile') . '",
		  "icon" : "fa fa-user",
		  "link" : "user/my_profile",
		  "active_condition" : "user/my_profile,user/my_profile_action",
		  "child_menu" : {}
	  },
	  "menu_user_password" : {
		  "display" : 1,
		  "name" : "' . my_caption('menu_sidebar_topbar_change_password') . '",
		  "icon" : "fa fa-key",
		  "link" : "user/change_password",
		  "active_condition" : "user/change_password,user/change_password_action",
		  "child_menu" : {}
	  },
	  "menu_user_ticket" : {
		  "display" : ' . $this->ticket_swtich . ',
		  "name" : "' . my_caption('menu_sidebar_support_ticket') . $user_new_ticket . '",
		  "icon" : "fas fa-tag",
		  "link" : "user/ticket",
		  "active_condition" : "user/ticket,user/ticket_new,user/ticket_new_action,user/ticket_view,user/ticket_view_action",
		  "child_menu" : {}
	  },
	  "menu_user_youtube" : {
		"display" : ' . $this->ticket_swtich . ',
		"name" : "' . my_caption('menu_sidebar_support_youtube') . $user_new_ticket . '",
		"icon" : "fab fa-youtube",
		"link" : "user/youtube",
		"active_condition" : "user/youtube,user/youtube_new,youtube/edit/",
		"child_menu" : {}
	},
	  "menu_user_payment" : {
		  "display" : ' . $this->payment_swtich . ',
		  "name" : "' . my_caption('menu_sidebar_my_payment') . '",
		  "icon" : "fa fa-dollar-sign",
		  "link" : "#",
		  "active_condition" : "",
		  "child_menu" : {
			  "menu_user_payment_pay" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_my_payment_pay_now') . '",
				  "link" : "user/pay_now",
				  "active_condition" : "user/pay_now"
			  },
			  "menu_user_payment_subscription" : {
				  "display" : ' . $this->payment_subscription . ',
				  "name" : "' . my_caption('menu_sidebar_my_payment_subscription') . '",
				  "link" : "user/pay_subscription_list",
				  "active_condition" : "user/pay_subscription_list,user/pay_subscription_list_view"
			  },
			  "menu_user_payment_list" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_my_payment_payment_list') . '",
				  "link" : "user/pay_list",
				  "active_condition" : "user/pay_list"
			  }' . my_hook_menu('user_payment') . '
		  }
	  },
	  "menu_user_tools" : {
		  "display" : 1,
		  "name" : "' . my_caption('menu_sidebar_user_my_tools') . '",
		  "icon" : "fas fa-wrench",
		  "link" : "#",
		  "active_condition" : "",
		  "child_menu" : {
			  "menu_user_tools_notification" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_user_tools_notification') . '",
				  "link" : "user/my_notification",
				  "active_condition" : "user/my_notification,user/my_notification_view"
			  },
			  "menu_user_tools_activity" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_user_tools_activity') . '",
				  "link" : "user/my_activity_log",
				  "active_condition" : "user/my_activity_log"
			  }
		  }
	  }
  }';

  // Here, we determine whether need to show the admin panel
  // Current there 6 built-in permissions which require admin panel to be shown
  // If you have added more admin features, Some changes must be made here
  (my_check_permission('User Management') || my_check_permission('Roles And Permissions') || my_check_permission('Global Settings') || my_check_permission('Admin Tools') || my_check_permission('Database Backup') || my_check_permission('Payment Management') || my_check_permission('Support Management')) ? $menu_admin_panel_display = 1 : $menu_admin_panel_display = 0;
  (my_check_permission('Roles And Permissions') || my_check_permission('Global Settings')) ? $menu_admin_panel_global_setting_display = 1 : $menu_admin_panel_global_setting_display = 0;
  (my_check_permission('Admin Tools') || my_check_permission('Database Backup')) ? $menu_admin_panel_tool_display = 1 : $menu_admin_panel_tool_display = 0;
  $menu_admin_panel_tool_sub_display = my_check_permission('Admin Tools');
  $admin_new_support = '';
  $admin_new_ticket = '';
  $admin_new_contact_form = '';
  if (my_check_permission('Support Management')) {
	  $admin_new_support = my_pending_counter('support_admin');
	  $admin_new_ticket = my_pending_counter('ticket_admin');
	  $admin_new_contact_form = my_pending_counter('contact_form');
	  ($admin_new_support == 'New') ? $admin_new_support = '<span class=\"badge badge-danger badge-counter ml-1 mt-1\">New</span>' : null;
	  ($admin_new_ticket > 0) ? $admin_new_ticket = '<span class=\"badge badge-danger badge-counter ml-2 mt-1\">' . $admin_new_ticket . '</span>' : $admin_new_ticket = '';
	  ($admin_new_contact_form > 0) ? $admin_new_contact_form = '<span class=\"badge badge-danger badge-counter ml-2 mt-1\">' . $admin_new_contact_form . '</span>' : $admin_new_contact_form = '';
  }
  $menu_admin_panel = '{
	  "display" : ' . $menu_admin_panel_display . ',
	  "menu_admin_user" : {
		  "display" : ' . my_check_permission('User Management') . ',
		  "name" : "' . my_caption('menu_sidebar_list_user') . '",
		  "icon" : "fa fa-users",
		  "link" : "admin/list_user",
		  "active_condition" : "admin/list_user,admin/new_user,admin/new_user_action,admin/edit_user,admin/edit_user_action,admin/invite_user,admin/invite_user_action,admin/edit_user_setting_action,user/my_profile_impersonate_action,admin/payment_adjust_balance,admin/payment_adjust_balance_action,admin/payment_add_subscription,admin/payment_add_subscription_action,admin/payment_add_purchase,admin/payment_add_purchase_action",
		  "child_menu" : {}
	  },
	  "menu_admin_global" : {
		  "display" : ' . $menu_admin_panel_global_setting_display . ',
		  "name" : "' . my_caption('menu_sidebar_global_settings') . '",
		  "icon" : "fa fa-cog",
		  "link" : "#",
		  "active_condition" : "",
		  "child_menu" : {
			  "menu_admin_global_setting" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_general_settings') . '",
				  "link" : "admin/general_setting",
				  "active_condition" : "admin/general_setting,admin/general_setting_action,admin/general_setting_tc,admin/general_setting_tc_action"
			  },
			  "menu_admin_front_end_setting" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_front_end_settings') . '",
				  "link" : "admin/front_setting",
				  "active_condition" : "admin/front_setting,admin/front_setting_action"
			  },
			  "menu_admin_global_auth" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_auth_integration') . '",
				  "link" : "admin/auth_integration",
				  "active_condition" : "admin/auth_integration,admin/auth_integration_action"
			  },
			  "menu_admin_global_role" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_roles') . '",
				  "link" : "admin/role",
				  "active_condition" : "admin/role,admin/role_action"
			  },
			  "menu_admin_global_permission" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_permissions') . '",
				  "link" : "admin/permission",
				  "active_condition" : "admin/permission,admin/permission_action"
			  },
			  "menu_admin_global_smtp" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_smtp_settings') . '",
				  "link" : "admin/smtp_setting",
				  "active_condition" : "admin/smtp_setting,admin/smtp_setting_action"
			  },
			  "menu_admin_global_email" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_email_template') . '",
				  "link" : "admin/email_template",
				  "active_condition" : "admin/email_template,admin/email_template_new,admin/email_template_new_action,admin/email_template_edit,admin/email_template_edit_action"
			  },
			  "menu_admin_catalog" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_catalog') . '",
				  "link" : "admin/catalog",
				  "active_condition" : "admin/catalog"
			  },
			  "menu_admin_global_misc" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_miscellaneous') . '",
				  "link" : "admin/miscellaneous",
				  "active_condition" : "admin/miscellaneous,admin/miscellaneous_action"
			  }
		  }
	  },
	  "menu_admin_support" : {
		  "display" : ' . my_check_permission('Support Management') . ',
		  "name" : "' . my_caption('menu_sidebar_support') . $admin_new_support . '",
		  "icon" : "fas fa-tags",
		  "link" : "#",
		  "active_condition" : "",
		  "child_menu" : {
			  "menu_support_ticket" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_support_ticket') . $admin_new_ticket . '",
				  "link" : "admin/ticket_list",
				  "active_condition" : "admin/ticket_list,admin/ticket_view,admin/ticket_view_action"
			  },
			  "menu_support_contact" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_support_contact') . $admin_new_contact_form . '",
				  "link" : "admin/contact_form_list",
				  "active_condition" : "admin/contact_form_list,admin/contact_form_view"
			  },
			  "menu_support_faq" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_support_faq') . '",
				  "link" : "admin/faq_list",
				  "active_condition" : "admin/faq_list,admin/faq_new,admin/faq_edit,admin/faq_action"
			  },
			  "menu_support_documentation" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_support_documentation') . '",
				  "link" : "admin/documentation_list",
				  "active_condition" : "admin/documentation_list,admin/documentation_new,admin/documentation_edit,admin/documentation_action"
			  },
			  "menu_support_setting" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_support_setting') . '",
				  "link" : "admin/support_setting",
				  "active_condition" : "admin/support_setting,admin/support_setting_action"
			  }
		  }
	  },
	  "menu_admin_payment" : {
		  "display" : ' . my_check_permission('Payment Management') . ',
		  "name" : "' . my_caption('menu_sidebar_payment') . '",
		  "icon" : "fas fa-piggy-bank",
		  "link" : "#",
		  "active_condition" : "",
		  "child_menu" : {
			  "menu_admin_payment_list" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_payment_list') . '",
				  "link" : "admin/payment_list",
				  "active_condition" : "admin/payment_list,admin/payment_list_view"
			  },
			  "menu_admin_payment_subscriptions" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_payment_subscription') . '",
				  "link" : "admin/payment_subscription_list",
				  "active_condition" : "admin/payment_subscription_list,admin/payment_subscription_list_view"
			  },
			  "menu_admin_payment_item" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_payment_item') . '",
				  "link" : "admin/payment_item_list",
				  "active_condition" : "admin/payment_item_list,admin/payment_item_add,admin/payment_item_add_action,admin/payment_item_modify,admin/payment_item_modify_action"
			  },
			  "menu_admin_payment_setting" : {
				  "display" : 1,
				  "name" : "' . my_caption('menu_sidebar_payment_setting') . '",
				  "link" : "admin/payment_setting",
				  "active_condition" : "admin/payment_setting,admin/payment_setting_action"
			  },
			  "admin_addon_menu_payment" : {
				  "display" : 1,
				  "name" : "' . my_caption('addons_admin_menu_payment') . '",
				  "link" : "payment/setting",
				  "active_condition" : "payment/setting,payment/setting_action"
			  },
			  "admin_addon_menu_coupon" : {
				  "display" : 1,
				  "name" : "' . my_caption('addons_admin_menu_coupon') . '",
				  "link" : "coupon/list_coupon",
				  "active_condition" : "coupon/list_coupon,coupon/add,coupon/add_action,coupon/edit,coupon/edit_action,coupon/install,coupon/install_action"
			  },
			  "admin_addon_menu_affiliate" : {
				  "display" : 1,
				  "name" : "' . my_caption('addons_admin_menu_affiliate') . '",
				  "link" : "affiliate/setting",
				  "active_condition" : "affiliate/setting,affiliate/setting_action,affiliate/member,affiliate/member_new,affiliate/member_new_action,affiliate/member_view,affiliate/member_view_action,affiliate/payout,affiliate/affiliate_view"
			  }
		  }
	  },
	  "menu_admin_tool" : {
		  "display" : ' . $menu_admin_panel_tool_display . ',
		  "name" : "' . my_caption('menu_sidebar_global_tool') . '",
		  "icon" : "fas fa-tools",
		  "link" : "#",
		  "active_condition" : "",
		  "child_menu" : {
			  "menu_admin_tool_notification" : {
				  "display" : ' . $menu_admin_panel_tool_sub_display . ',
				  "name" : "' . my_caption('menu_sidebar_notification') . '",
				  "link" : "admin/list_notification",
				  "active_condition" : "admin/list_notification,admin/send_notification,admin/send_notification_action,admin/list_notification_view"
			  },
			  "menu_admin_tool_subscriber" : {
				  "display" : ' . $menu_admin_panel_tool_sub_display . ',
				  "name" : "' . my_caption('menu_sidebar_subscriber_manager') . '",
				  "link" : "admin/subscriber",
				  "active_condition" : "admin/subscriber,admin/subscriber_action"
			  },
			  "menu_admin_tool_blog" : {
				  "display" : ' . $menu_admin_panel_tool_sub_display . ',
				  "name" : "' . my_caption('menu_sidebar_blog_manager') . '",
				  "link" : "admin/blog",
				  "active_condition" : "admin/blog,admin/blog_new,admin/blog_new_action,admin/blog_edit,admin/blog_edit_action"
			  },
			  "menu_admin_tool_file" : {
				  "display" : ' . $menu_admin_panel_tool_sub_display . ',
				  "name" : "' . my_caption('menu_sidebar_file_manager') . '",
				  "link" : "files/file_manager",
				  "active_condition" : "files/file_manager,files/file_upload,files/file_upload_save_action"
			  },
			  "menu_admin_tool_online" : {
				  "display" : ' . $menu_admin_panel_tool_sub_display . ',
				  "name" : "' . my_caption('menu_sidebar_who_is_online') . '",
				  "link" : "admin/list_online",
				  "active_condition" : "admin/list_online"
			  },
			  "menu_admin_tool_activity" : {
				  "display" : ' . $menu_admin_panel_tool_sub_display . ',
				  "name" : "' . my_caption('menu_sidebar_activity_log') . '",
				  "link" : "admin/users_activity_log",
				  "active_condition" : "admin/users_activity_log"
			  },
			  "menu_admin_tool_backup" : {
				  "display" : ' . my_check_permission('Database Backup') . ',
				  "name" : "' . my_caption('menu_sidebar_database_backup') . '",
				  "link" : "admin/database_backup",
				  "active_condition" : "admin/database_backup,admin/database_backup_action"
			  },
			  "menu_admin_tool_usage" : {
				  "display" : ' . +my_check_role('Super Admin') . ',
				  "name" : "' . my_caption('menu_sidebar_usage_example') . '",
				  "link" : "admin/usage_example",
				  "active_condition" : "admin/usage_example"
			  },
			  "menu_admin_upgrade_software" : {
				  "display" : ' . +my_check_role('Super Admin') . ',
				  "name" : "' . my_caption('menu_sidebar_upgrade_software') . '",
				  "link" : "admin/upgrade_software",
				  "active_condition" : "admin/upgrade_software,admin/upgrade_software_view,admin/upgrade_software_action"
			  },
			  "menu_admin_software_license" : {
				  "display" : ' . +my_check_role('Super Admin') . ',
				  "name" : "' . my_caption('menu_sidebar_software_license') . '",
				  "link" : "admin/software_license",
				  "active_condition" : "admin/software_license,admin/software_license_action"
			  }
		  }
	  }
  }';

?>