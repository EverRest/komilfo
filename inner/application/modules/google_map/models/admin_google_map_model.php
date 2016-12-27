<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_google_map_model extends CI_Model
	{

		/**
		 * Отримання статті компоненту
		 *
		 * @param int $component_id
		 * @param int $menu_id
		 *
		 * @return array|null
		 */
		public function get_google_map($component_id, $menu_id)
		{
			if ($this->db->where('component_id', $component_id)->count_all_results('component_google_map') == 0)
			{
				$this->db->insert('component_google_map', array('component_id' => $component_id, 'menu_id' => $menu_id));
			}

			return $this->db->get_where('component_google_map', array('component_id' => $component_id))->row_array();
		}

		/**
		 * Збереження статті компоненту
		 *
		 * @param int $component_id
		 * @param string $title
		 * @param string $text
		 * @param int $wide
		 */
		public function update($component_id, $wide, $schedule, $information, $sale, $marker_lat, $marker_lng, $zoom, $center_lat, $center_lng)
		{
			$set = array('wide' => $wide);

            $_schedule = str_replace(array("\r", "\n", "\t"), '', $schedule);
            $set['schedule_ua'] = $this->db->escape_str($_schedule);

            $_sale = str_replace(array("\r", "\n", "\t"), '',$sale);
            $set['sale_ua'] = $this->db->escape_str($_sale);

            $_information= str_replace(array("\r", "\n", "\t"), '', $information);
            $set['information_ua'] = $this->db->escape_str($_information);

            $set['marker_lat'] = $marker_lat;
            $set['marker_lng'] = $marker_lng;
            $set['zoom'] = $zoom;
            $set['center_lat'] = $center_lat;
            $set['center_lng'] = $center_lng;
//            echo '<pre>';print_r($set);exit;

			$this->db->update('component_google_map', $set, array('component_id' => $component_id));
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
			$this->db->delete('component_google_map', array('component_id' => $component_id));
		}

	}