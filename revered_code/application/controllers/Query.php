<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Query extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('m_datatables');
	}



	public function users() {
		(!my_check_permission('User Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		if (my_uri_segment(3) != '') {
			switch (my_uri_segment(3)) {
				case 'today': 
				  $this->m_datatables->set_where_array(array('created_time>='=>my_conversion_from_local_to_server_time(date('Y-m-d'), $this->user_timezone, 'Y-m-d H:i:s')));
				  break;
				case 'pending':
				  $this->m_datatables->set_where_array(array('status'=>0));
                  break;
				case 'active' :
				  $this->m_datatables->set_where_array(array('status'=>1));
				  break;
                case 'deactived':
				  $this->m_datatables->set_where_array(array('status'=>2));
                  break;				
			}
		}
		if (my_uri_segment(4) != '') {
			$search_keyword = my_uri_segment(4);
			$this->m_datatables->set_or_where_array(array('email_address'=>$search_keyword));
		}
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_table_name('user');
		$this->m_datatables->set_response_fields_array(array('status', 'created_time', 'username', 'email_address', ['first_name', 'last_name'], 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		if ($this->config->item('my_demo_mode')) { // in demo mode, hide email address
			$i = 0;
			foreach ($res_array['data'] as $arr) {
				$res_array['data'][$i][3] = '*****' . substr($res_array['data'][$i][3], 5);
				$i++;
			}
		}
		echo json_encode($res_array);
	}
	
	
	
	public function users_activity_log() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
	    $keyword = my_get('search')['value'];
		$this->m_datatables->set_table_name('activity');
		$this->m_datatables->set_response_fields_array(array('level', 'created_time', 'event', ['user_ids', 'detail']));
		$this->m_datatables->set_connect_tag('<br>');
		echo json_encode($this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword));
	}
	
	
	
	public function my_activity_log() {
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_table_name('activity');
		$this->m_datatables->set_where_array(array('user_ids'=>$_SESSION['user_ids']));
		$this->m_datatables->set_response_fields_array(array('level', 'created_time', 'event', 'detail'));
		echo json_encode($this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword));
	}
	
	
	
	public function online_users() {
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_table_name('user');
		$this->m_datatables->set_where_array(array('online'=>1));
		$this->m_datatables->set_response_fields_array(array('email_address', 'username', 'online_time', 'login_success_detail'));
		echo json_encode($this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword));
	}
	
	
	
	public function user_notification() {
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_table_name('notification');
		$this->m_datatables->set_in_where_array(array('to_user_ids'=>'all,' . $_SESSION['user_ids']));
		$this->m_datatables->set_response_fields_array(array('is_read', 'created_time', 'subject', 'ids'));
		echo json_encode($this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword));
	}
	
	
	public function list_notification() {
		$keyword = my_get('search')['value'];
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$this->m_datatables->set_table_name('notification');
		$this->m_datatables->set_response_fields_array(array('is_read', 'created_time', 'subject', 'ids'));
		echo json_encode($this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword));
	}
	
	
	public function database_backup_log() {
		(!my_check_permission('Database Backup')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_table_name('backup_log');
		$this->m_datatables->set_response_fields_array(array('created_method', 'created_time', 'file_path', 'options'));
		echo json_encode($this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword));
	}
	
	
	
	public function payment_item() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_table_name('payment_item');
		$this->m_datatables->set_where_array(array('trash'=>'0'));
		$this->m_datatables->set_response_fields_array(array('enabled', 'type', 'item_name', ['item_currency', 'item_price'], ['recurring_interval_count', 'recurring_interval'], 'ids'));
		echo json_encode($this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword));
	}
	
	
	
	public function payment_list() {  //for admin use
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$condition_array = [];
		if (my_uri_segment(3) != 'all') {
			$condition_array += array('type'=>my_uri_segment(3));
		}
		if (my_get('user') != '') {
			$condition_array += array('user_ids'=>my_get('user'));
		}
		if (!empty($condition_array)) {
			$this->m_datatables->set_where_array($condition_array);
		}
		$this->m_datatables->set_table_name('payment_log');
		$this->m_datatables->set_order_by('created_time desc');
		$this->m_datatables->set_response_fields_array(array('redirect_status', 'callback_status', 'gateway', 'created_time', 'user_ids', 'item_name', ['currency', 'amount'], 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		$i = 0;
		foreach ($res_array['data'] as $arr) {
			$res_array['data'][$i][4] = '<a href="' . base_url('admin/edit_user/') . $arr[4] . '" target="_blank">' . my_user_setting($arr[4], 'email_address') . '</a>';
			$i++;
		}
		echo json_encode($res_array);
	}
	
	
	
	public function user_pay_list() {  //for user use
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_where_array(array('user_ids'=>$_SESSION['user_ids'], 'visible_for_user'=>1)); //limit condition: query use himself, visible ones
		$this->m_datatables->set_table_name('payment_log');
		$this->m_datatables->set_order_by('created_time desc');
		$this->m_datatables->set_response_fields_array(array('redirect_status', 'callback_status', 'type', 'gateway', 'created_time', 'item_name', ['currency', 'amount'], 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		$new_res_array_data = array();
		$i = 0;
		foreach ($res_array['data'] as $arr) {
			if ($arr[0] == 'success' && $arr[1] == 'success') {
				$pay_status = 'success';
			}
			elseif ($arr[0] == 'success' && $arr[1] == 'pending') {
				$pay_status = 'pending';
			}
			else {
				$pay_status = 'unpaid';
			}
			array_shift($res_array['data'][$i]);
			$res_array['data'][$i][0] = $pay_status;
			$res_array['data'][$i][1] = ucfirst($res_array['data'][$i][1]);
			$i++;
		}
		echo json_encode($res_array);
	}
	
	
	
	public function payment_subscription_list() {  //for admin use
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_table_name('payment_subscription');
		$this->m_datatables->set_response_fields_array(array('status', 'user_ids', 'item_ids', 'start_time', 'end_time' , 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		$query = $this->db->where('type', 'subscription')->get('payment_item');
		if ($query->num_rows() && !empty($res_array['data'])) {
			$rs = $query->result();
			foreach ($rs as $row) {
				$subscription_item_array[$row->ids] = $row->item_name; 
			}
			$i = 0;
			foreach ($res_array['data'] as $arr) {
				$query_user = $this->db->where('ids', $arr[1])->get('user', 1);
				($query_user->num_rows()) ? $user_email_address = $query_user->row()->email_address : $user_email_address = '';
				$res_array['data'][$i][1] = '<a href="' . base_url('admin/edit_user/') . $arr[1] . '">' . $user_email_address . '</a>';  //replace the user_ids with user_email
				$res_array['data'][$i][2] = '<a href="' . base_url('admin/payment_item_modify/') . $arr[2] . '">' . $subscription_item_array[$arr[2]] . '</a>';  //replace the item_ids with item_name
				$i++;
			}
		}
		echo json_encode($res_array);
	}
	
	
	
	public function user_subscription_list() { //for user use
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_where_array(array('user_ids'=>$_SESSION['user_ids'])); //limit condition: query user himself
		$this->m_datatables->set_table_name('payment_subscription');
		$this->m_datatables->set_response_fields_array(array('status', 'item_ids', 'start_time', 'end_time' , 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		$query = $this->db->where('type', 'subscription')->get('payment_item');
		if ($query->num_rows() && !empty($res_array['data'])) {
			$rs = $query->result();
			foreach ($rs as $row) {
				$subscription_item_array[$row->ids] = $row->item_name; 
			}
			$i = 0;
			foreach ($res_array['data'] as $arr) {
				$res_array['data'][$i][1] = $subscription_item_array[$arr[1]];  //replace the item_ids with item_name
				$i++;
			}
		}
		echo json_encode($res_array);
	}
	
	
	
	public function user_ticket() { //for user use
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_where_array(array('user_ids'=>$_SESSION['user_ids'], 'ids_father'=>'0')); //limit condition: query user himself
		$this->m_datatables->set_order_by('main_status desc, updated_time desc');
		$this->m_datatables->set_table_name('ticket');
		$this->m_datatables->set_response_fields_array(array('main_status', 'created_time', 'updated_time', 'subject', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	public function admin_ticket() { //for admin
	    (!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_where_array(array('ids_father'=>'0')); //limit condition: query all
		$this->m_datatables->set_order_by('main_status desc, read_status asc, updated_time desc');
		$this->m_datatables->set_table_name('ticket');
		$this->m_datatables->set_response_fields_array(array('id', 'main_status', 'read_status', 'created_time', 'updated_time', 'subject', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	public function faq_list() {  //for admin, display faq list
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('faq');
		$this->m_datatables->set_response_fields_array(array('catalog', 'subject', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	public function documentation_list() {  //for admin, display documentation list
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('documentation');
		$this->m_datatables->set_response_fields_array(array('enabled', 'catalog', 'subject', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	public function contact_form_list() {  //for admin, display contact form list
		(!my_check_permission('Support Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('contact_form');
		$this->m_datatables->set_response_fields_array(array('read_status', 'created_time', 'catalog', 'name', 'email_address', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	public function list_subscriber() {  //for admin, display subscriber's list
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('subscriber');
		$this->m_datatables->set_response_fields_array(array('created_time', 'from_ip', 'email_address', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	public function list_blog() {  //for admin, display blog's list
		(!my_check_permission('Admin Tools')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_order_by('updated_time desc');
		$this->m_datatables->set_table_name('blog');
		$this->m_datatables->set_response_fields_array(array('enabled', 'catalog', 'subject', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
}