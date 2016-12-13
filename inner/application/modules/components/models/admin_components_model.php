<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_components_model extends CI_Model {

		/**
		 * Додавання нового компоненту
		 *
		 * @param int $menu_id
		 * @param string $module
		 * @param string $method
		 * @param string $config
		 *
		 * @return mixed
		 */
		public function insert_component($menu_id, $module, $method, $config = '')
		{
			$this->shift_components($menu_id);

			$set = array(
				'menu_id' => $menu_id,
				'position' => 0,
				'module' => $module,
				'method' => $method,
				'config' => $config
			);
			$this->db->insert('components', $set);
			return $this->db->insert_id();
		}

		/**
		 * Отримання інформації про компонент
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_component($component_id)
		{
			$component = array(
				'module' => '',
				'method' => ''
			);

			$result = $this->db->select('module', 'method')->where('component_id', $component_id)->get('components')->row_array();
			if (isset($result['module'])) $component = $result;

			return $component;
		}

		/**
		 * Приховування/відображення компоненту
		 *
		 * @param int $component_id
		 * @param int $status
		 */
		public function visibility($component_id, $status)
		{
			$this->db->set('hidden', $status);
			$this->db->where('component_id', $component_id);
			$this->db->update('components');
		}

		/**
		 * Збереження порядку сортування компонентів
		 *
		 * @param array $components
		 */
		public function update_position($components)
		{
			foreach ($components as $position => $component_id)
			{
				$this->db->set('position', intval($position));
				$this->db->where('component_id', intval($component_id));
				$this->db->update('components');
			}
		}

		/**
		 * Зміщення порядку сортування компонентів
		 *
		 * @param int $menu_id
		 */
		private function shift_components($menu_id)
		{
			$this->db->set('`position`', '`position` + 1', FALSE);
			$this->db->where('menu_id', $menu_id);
			$this->db->update('components');
		}
	}