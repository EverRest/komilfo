<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Benefit_services_model extends CI_Model {

		/**
		 * Отримання статті компоненту
		 *
		 */
		public function get_article()
		{
			return $this->db->get('component_services')->row_array();
		}

		public function get_data()
		{

			$result = $this->db->get('component_services')->row_array();

			return $result;
		}

	}