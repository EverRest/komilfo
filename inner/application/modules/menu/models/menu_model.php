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
		 * @return mixed
		 */
		public function get_menu($menu_index = 0, $parent_id = 0, $where = NULL)
		{
			if (!$this->init_model->is_admin())
			{
				$menu = $this->cache->get('menu_' . LANG . '_' . $menu_index . '_' . $parent_id);
				if ($menu) return $menu;
			}

			$this->db->select('id, main, target, name_' . LANG . ' as name, url_path_' . LANG . ' as url, static_url_' . LANG . ' as static_url, image, icon');
			$this->db->where('parent_id', $parent_id);
			$this->db->where('menu_index', $menu_index);
			$this->db->where('hidden', 0);
			if (is_array($where)) $this->db->where($where);
			$this->db->order_by('position');

			$menu = $this->db->get('menu')->result_array();

			$this->cache->save('menu_' . LANG . '_' . $menu_index . '_' . $parent_id, $menu, 3600);

			return $menu;
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
		public function get_menu_tree($menu_index = 0, $parent_id = 0, $where = NULL)
		{
			if (!$this->init_model->is_admin())
			{
				$menu = $this->cache->get('menu_' . LANG . '_' . $menu_index . '_' . $parent_id);
				if ($menu) return $menu;
			}

			$menu = array();

			$this->db->select('id, parent_id, main, target, name_' . LANG . ' as name, title_' . LANG . ' as title, url_path_' . LANG . ' as url, static_url_' . LANG . ' as static_url, image, icon');
			$this->db->where('menu_index', $menu_index);
			$this->db->where('hidden', 0);
			if ($parent_id > 0) $this->db->like('url_path_id', '.' . $parent_id . '.');
			if (is_array($where)) $this->db->where($where);
			$this->db->order_by('position');

			$result = $this->db->get('menu');

			if ($result->num_rows() > 0)
			{
				$result_array = $result->result_array();

				foreach ($result_array as $row)
				{
					$menu[$row['parent_id']][] = $row;
				}
			}

			$this->cache->save('menu_' . LANG . '_' . $menu_index . '_' . $parent_id, $menu, 3600);

			return $menu;
		}

		/**
		 * Отримання прихованих пунктів меню
		 *
		 * @param int $menu_id
		 *
		 * @return array
		 */
		public function get_seo_link($menu_id)
		{
			$seo_link = array();

			$r = $this->db->select('hide_items')->where('menu_id', $menu_id)->get('seo_link');
			if ($r->num_rows() > 0)
			{
				$row = $r->row_array();
				if ($row['hide_items'] != '') $seo_link = unserialize($row['hide_items']);
			}

			return $seo_link;
		}
	}