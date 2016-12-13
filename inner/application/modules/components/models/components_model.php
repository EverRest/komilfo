<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Components_model extends CI_Model {

		/**
		 * Отримання компонентів сторінки
		 *
		 * @param int $menu_id
		 * @param int $page
		 * @param array $filters
		 * @return array
		 */
		public function get_components($menu_id = 0, $page = 0, array $filters)
		{
			if (!$this->init_model->is_admin()) {
				$this->db->where('hidden', 0);
			}

			$this->db->select('component_id, hidden, module, method, config');
			$this->db->where('menu_id', $menu_id);

			if ($page > 0 or count($filters) > 0) {
				$this->db->where('module !=', 'article');
			}

			$this->db->order_by('position');

			return $this->db->get('components')->result_array();
		}
	}