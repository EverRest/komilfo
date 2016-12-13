<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Where_model extends CI_Model
	{
		/**
		 * Отримання меню
		 *
		 * @return array
		 */
		public function get_menu($country = array(), $city = array(), $shops = array())
		{
			$menu = array();

			$this->db->select('menu.id, menu.parent_id, menu.name_' . LANG . ' as name');
			$this->db->select('component_article.text_' . LANG . ' as text, component_article.title_' . LANG . ' as title, component_article.title_shop_' . LANG . ' as title_shop, component_article.address_' . LANG . ' as address, component_article.address_2_' . LANG . ' as address_2, component_article.facebook, component_article.phone, lat, lng, zoom');
			$this->db->join('component_article', 'component_article.menu_id=menu.id', 'left');
			// $this->db->join('catalog', 'component_article.menu_id=menu.id', 'left');
			if(!empty($shops)) {$this->db->where_in('component_article.component_id', $shops);}
			if(!empty($city)) {$this->db->where_in('menu.id', $city);}
			$this->db->where('menu.menu_index', 2)->where('menu.hidden', 0);
			$this->db->order_by('menu.position', 'asc');


			$result = $this->db->get('menu')->result_array();

			if(!empty($country)) $menu[] = $this->db->select('menu.id, menu.parent_id, menu.name_' . LANG . ' as name')->where_in('id', $country)->get('menu')->result_array();
			
			foreach ($result as $row) {
				$menu[$row['parent_id']][] = $row;
			}

			return $menu;
		}

		/**
		 * Отримання усіх маркерів
		 *
		 * @return array
		 */
		public function get_all_markers($country = array(), $city = array(), $shops = array())
		{
			$this->db->select('component_article.text_' . LANG . ' as text, component_article.title_' . LANG . ' as title, component_article.title_shop_' . LANG . ' as title_shop, component_article.address_' . LANG . ' as address, component_article.address_2_' . LANG . ' as address_2, component_article.facebook, component_article.phone, lat, lng');
			$this->db->join('menu', 'menu.id=component_article.menu_id');
			if(!empty($shops)) $this->db->where_in('component_article.component_id', $shops);
			if(!empty($city)) $this->db->where_in('menu.id', $city);
			$this->db->where('menu_index', 2)->where('menu.hidden', 0);

			$result = $this->db->get('component_article')->result_array();

			return $result;
		}

		/**
		 * Отримання контенту
		 *
		 * @param $id
		 * @return string
		 */
		public function get($id)
		{
			$component_id = $this->db->select('component_id')->where('menu_id', $id)->where('module', 'article')->get('components')->row('component_id');

			if (is_numeric($component_id))
			{
				$result = $this->db->select('text_' . LANG . ' as text, lat, lng, zoom')->where('component_id', $component_id)->get('component_article')->row_array();

				if (isset($result['text']))
				{
					$result['text'] = stripslashes($result['text']);
					return json_encode($result);
				}
			}

			return json_encode(array());
		}

		/**
		 * Отримання сукны по id
		 *
		 * @param $catalog_id
		 * @return array
		 */

		public function get_dress($catalog_id)
		{
			return $this->db
				->where('catalog_id', $catalog_id)
				->get('catalog')
				->row_array();
		}
	}