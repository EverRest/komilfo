<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Frequent_model extends CI_Model {

		/**
		 * Отримання статті компоненту
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_article()
		{
			return $this->db->get('component_frequent')->result_array();
		}

	}