<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Loyalty_system_model extends CI_Model {

		/**
		 * Отримання статті компоненту
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_article()
		{
			return $this->db->get('component_loyalty_system')->result_array();
		}

		public function get_text()
		{
			return $this->db->get("loyalty", array('id' => 1))->row_array();
		}

	}