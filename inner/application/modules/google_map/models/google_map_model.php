<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Google_map_model extends CI_Model {

		/**
		 * Отримання статті компоненту
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_google_map($component_id)
		{
			return $this->db->select('information_ua as information, schedule_ua as schedule, sale_ua as sale, marker_lat, marker_lng, center_lat, center_lng, zoom, wide')->get_where('component_google_map', array('component_id' => $component_id))->row_array();
		}

	}