<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_datatables
{
	//$table_name and $response_fields_array is required
	protected $table_name, $response_fields_array, $where_array = [], $or_where_array = [], $in_where_array = [], $connect_tag = ' ', $order_by = 'id desc';
	
	public function set_table_name($table_name_input) {  //required
		$this->table_name = $table_name_input;
	}
	
	public function set_response_fields_array($response_fields_array_input) {  //required
		$this->response_fields_array = $response_fields_array_input;
	}
	
	public function set_where_array($where_array_input) {
		$this->where_array = $where_array_input;
	}
	
	public function set_or_where_array($or_where_array_input) {
		$this->or_where_array = $or_where_array_input;
	}

	public function set_in_where_array($in_where_array_input) {
		$this->in_where_array = $in_where_array_input;
	}

	public function set_connect_tag($connect_tag_input) {
		$this->connect_tag = $connect_tag_input;
	}
	
	public function set_order_by($order_by_input) {
		$this->order_by = $order_by_input;
	}

	
	
	
	public function generate_data($draw, $start, $length, $keyword, $db = 'db') {  //$db determines which database being connected
		$CI = &get_instance();
		//security check
		if (ctype_digit($draw) && ctype_digit($start) && ctype_digit($length)) {
			// if $keyword is not empty, query using this keyword
			$keyword = trim($keyword);
			if ($keyword != '') {
				$fields = $CI->$db->field_data($this->table_name);
				foreach ($fields as $field) {
					($field->type == 'char' || $field->type == 'varchar' || $field->type == 'text') ? $keyword_array[$field->name] = $keyword : null;
				}
			}
			else {
				$keyword_array = [];
			}
			
			//query total amount of the table (meet where_array and or_where_array)
			if (!empty($this->in_where_array)) {
				foreach ($this->in_where_array as $key=>$value) {
					$CI->$db->where_in($key, explode(',', $value));
				}
			}
			$records_table_total = $CI->$db->where($this->where_array)->or_where($this->or_where_array)->count_all_results($this->table_name);
			
			// query filter amount of the table
			(!empty($keyword_array)) ? $CI->$db->group_start()->or_like($keyword_array)->group_end() : null;
			if (!empty($this->in_where_array)) {
				foreach ($this->in_where_array as $key=>$value) {
					$CI->$db->where_in($key, explode(',', $value));
				}
			}
			$records_filter_total = $CI->$db->where($this->where_array)->or_where($this->or_where_array)->count_all_results($this->table_name);
			
			// query filter recordset and excute
			(!empty($keyword_array)) ? $CI->$db->group_start()->or_like($keyword_array)->group_end() : null;
			if (!empty($this->in_where_array)) {
				foreach ($this->in_where_array as $key=>$value) {
					$CI->$db->where_in($key, explode(',', $value));
				}
			}
			$query = $CI->$db->where($this->where_array)->or_where($this->or_where_array)->order_by($this->order_by)->get($this->table_name, $length, $start);
			if ($query->num_rows()) {
				$res['draw'] = intval($draw) + 1;
				$res['recordsTotal'] = $records_table_total;
				$res['recordsFiltered'] = $records_filter_total;
				$rs = $query->result();
				foreach ($rs as $row) {
					$rs_single = [];
					foreach ($this->response_fields_array as $response_field) {
						if (is_array($response_field)) {
							$mix_value = '';
							foreach ($response_field as $response_field_sub) {
								if ($row->$response_field_sub != '') {
									$mix_value  .= $row->$response_field_sub . $this->connect_tag;
								}
								else {
									$mix_value = $row->$response_field_sub;
								}
							}
							$rs_single[]  = $mix_value;
						}
						else {
							$rs_single[] = $row->$response_field;
						}
					}
					$rs_all[] = $rs_single;
				}
				$res['data'] = $rs_all;
			}
			else {
				$res['draw'] = $draw + 1;
				$res['recordsTotal'] = $records_table_total;
				$res['recordsFiltered'] = 0;
				$res['data'] =[];
			}
			return $res;
		}
		else {
			return FALSE;
		}	
	}
	
	
	
}
?>