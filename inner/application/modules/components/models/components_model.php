<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Components_model extends CI_Model {

		/**
		 * Отримання компонентів сторінки
		 *
		 * @param int $menu_id
		 * @return null
		 */
		public function get_components($menu_id = 0)
		{
			$this->db->select('component_id, hidden, module, method, config');
			$this->db->where('menu_id', $menu_id);
			$this->db->order_by('position');

			$result = $this->db->get('components')->result_array();

			foreach ($result as $k => $v)
			{
				if ($v['hidden'] == 1 AND !$this->init_model->check_access($v['module'] . '_' . $v['method'], $menu_id))
				{
					unset($result[$k]);
				}
			}

			return count($result) > 0 ? $result : NULL;
		}
	}