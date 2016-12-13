<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Call_model extends CI_Model {

		public function save_call($set)
		{
			$this->db->insert('call' , $set);
			return $this->db->insert_id();
		}

		public function save_file($set)
		{
			$this->db->insert('buf_file' , $set);
		}

		public function save_call_Email($set) {
			$this->db->insert('emails', $set);
			return true;
		}

		public function get_productName($id) {
			$prefix = $this->db->dbprefix;

			$this->db->select('title_' . LANG );
			$this->db->where('product_id', $id);
			$result = $this->db->get('products')->row_array(); 
			return $result['title_' . LANG];
		}

	}