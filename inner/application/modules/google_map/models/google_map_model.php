<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Google_map_model extends CI_Model {

		/**
		 * Отримання карти компоненту
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_map($component_id)
		{
			$this->db->select('center_lat, center_lng, zoom, marker_lat, marker_lng, title_' . LANG . ' as title, description_' . LANG . ' as description');
			$this->db->where('component_id', $component_id);
			return $this->db->get('google_map')->row_array();
		}

	}