<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Query_ca extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('m_datatables');
	}
	
	
	
	public function affiliate_member() {  //for admin
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_or_where_array(array('affiliate_enabled'=>1));
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('user');
		$this->m_datatables->set_response_fields_array(array('email_address', 'affiliate_code', 'affiliate_earning', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	public function admin_affiliate_log() {  //for admin
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('affiliate_log');
		$this->m_datatables->set_response_fields_array(array('created_time', 'item_name', 'from_ip', 'currency', 'amount', 'commission', 'ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}



	public function affiliate_log() {  //for user
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_or_where_array(array('user_ids'=>$_SESSION['user_ids']));
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('affiliate_log');
		$this->m_datatables->set_response_fields_array(array('created_time', 'item_name', 'from_ip', 'currency', 'amount', 'commission'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	public function admin_payout_log() {  //for admin
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('affiliate_payout');
		$this->m_datatables->set_response_fields_array(array('created_time', 'email_address', 'amount'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	

	public function payout_log() {  //for user
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_or_where_array(array('user_ids'=>$_SESSION['user_ids']));
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('affiliate_payout');
		$this->m_datatables->set_response_fields_array(array('created_time', 'amount'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	

	public function admin_coupon_log() {
		(!my_check_permission('Payment Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$keyword = my_get('search')['value'];
		$this->m_datatables->set_or_where_array(array('code'=>my_uri_segment(3)));
		$this->m_datatables->set_order_by('id desc');
		$this->m_datatables->set_table_name('coupon_log');
		$this->m_datatables->set_response_fields_array(array('created_time', 'item_name', 'currency', 'item_price', 'discount', 'amount', 'payment_ids'));
		$res_array = $this->m_datatables->generate_data(my_get('draw'), my_get('start'), my_get('length'), $keyword);
		echo json_encode($res_array);
	}
	
	
	
	
}