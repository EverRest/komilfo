<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_google_map_model extends CI_Model {

		/**
		 * Отримання карти компоненту
		 *
		 * @param int $component_id
		 * @param int $menu_id
		 *
		 * @return array|null
		 */
		public function get_map($component_id, $menu_id)
		{
			if ($this->db->where('component_id', $component_id)->count_all_results('google_map') == 0)
			{
				$this->db->insert('google_map', array('component_id' => $component_id, 'menu_id' => $menu_id));
			}

			return $this->db->get_where('google_map', array('component_id' => $component_id))->row_array();
		}

		/**
		 * Збереження карти компоненту
		 *
		 * @param int $component_id
		 * @param array $set
		 */
		public function update($component_id, $set)
		{
			$this->db->update('google_map', $set, array('component_id' => $component_id));
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
			$this->db->delete('google_map', array('component_id' => $component_id));
		}

	}