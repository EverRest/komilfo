<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Menu_model extends CI_Model {

		/**
		 * Отримання однорівневого меню
		 *
		 * @param int $menu_index
		 * @param int $parent_id
		 * @param null|array $where
		 *
		 * @return array
		 */
		public function get_menu($menu_index = 0, $parent_id = 0, $where = null)
		{
			if (!$this->init_model->is_admin()) {
				$menu = $this->cache->get('menu/' . LANG . '_' . $menu_index . '_' . $parent_id);

				if ($menu) {
					return $menu;
				}
			}

			if (is_array($where)) {
				$this->db->where($where);
			}

			$menu = $this->db
				->select('id, main, target, name_' . LANG . ' as name, url_' . LANG . ' as url, static_url_' . LANG . ' as static_url, title_' . LANG . ' as title')
				->where('parent_id', $parent_id)
				->where('menu_index', $menu_index)
				->where('hidden', 0)
				->order_by('position')
				->get('menu')
				->result_array();

			$this->cache->save(LANG . '_' . $menu_index . '_' . $parent_id, $menu, 3600, 'menu/');

			return $menu;
		}

		/**
		 * Отримання батькывського id
		 *
		 * @param int $menu_id
		 *
		 * @return int
		 */
		public function get_parent_id($menu_id = 0)
		{

			$this->db->where('id', $menu_id);
			$this->db->where('hidden', 0);
			$this->db->order_by('position');

			$result = $this->db->get('menu')->row('parent_id');
			
			return $result;
		}


		/**
		 * Отримання багаторівневого меню
		 *
		 * @param int $menu_index
		 * @param int|array $parent_id
		 * @param null|array $where
		 *
		 * @return array
		 */
		public function get_menu_tree($menu_index = 0, $parent_id = 0, $where = null)
		{
			// if (!$this->init_model->is_admin()) {
			// 	$menu = $this->cache->get('menu/' . LANG . '_' . $menu_index . '_' . $parent_id);
			// 	if ($menu) {
			// 		return $menu;
			// 	}
			// }

			$menu = array();

			if ($parent_id > 0) {
				$this->db->like('url_path_id', '.' . $parent_id . '.');
			}

				
			if (is_array($where)) {
				$this->db->where($where);
			}

			$result = $this->db
				->select('id, parent_id, main, target, name_' . LANG . ' as name, title_' . LANG . ' as title, url_' . LANG . ' as url, static_url_' . LANG . ' as static_url, title_' . LANG . ' as title, image')
				->where('menu_index', $menu_index)
				->where('hidden', 0)
				->order_by('position')
				->get('menu')
				->result_array();

			if (count($result) > 0) {
				foreach ($result as $v) {
					$menu[$v['parent_id']][] = $v;
				}
			}

			$this->cache->save(LANG . '_' . $menu_index . '_' . $parent_id, $menu, 3600, 'menu/');

			return $menu;
		}
	}