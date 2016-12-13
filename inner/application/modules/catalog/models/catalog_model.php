<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	class Catalog_model extends CI_Model
	{

		/**
		 * Перевірка на наявність дочірніх пунктів меню з каталогом.
		 * Дочірнім рахується пункт меню з доданим компонентом каталогу.
		 *
		 * @param int|null $menu_id
		 * @return bool
		 */
		public function is_parent($menu_id = NULL)
		{
			if (!$this->init_model->is_admin()) $this->db->where('menu.hidden', 0);
			$this->db->join('components', 'components.menu_id = menu.id');
			$this->db->where('menu.parent_id', intval($menu_id));
			$this->db->where('components.module', 'catalog');
			$this->db->where('components.hidden', 0);
			return (bool)$this->db->count_all_results('menu');
		}

		public function get_categories($menu_id)
		{
			$result = $this->db
				->where('parent_id', $menu_id)
				->where('hidden', 0)
				->order_by('position')
				->get('menu')
				->result_array();

				foreach ($result as $key => $value) {
					$res = $this->db
						->where('menu_id', $value['id'])
						->where('module', 'catalog')
						->get('components')
						->row_array();
					if($res['hidden'] == 1) unset($result[$key]);
					if(empty($res)) unset($result[$key]);
				}
			return $result;
		}

		public function get_menu()
		{
			$menu = array();

			$this->db->select('menu.id, menu.parent_id, menu.shops, menu.name_' . LANG . ' as name');
			$this->db->select('component_article.text_' . LANG . ' as text, component_article.title_' . LANG . ' as title, component_article.component_id as shop_id, lat, lng, zoom');
			$this->db->join('component_article', 'component_article.menu_id=menu.id', 'left');
			$this->db->where('menu.menu_index', 2)->where('menu.hidden', 0);
			$this->db->order_by('menu.position', 'asc');
			// $this->db->group_by('menu.id');
			$result = $this->db->get('menu')->result_array();
			foreach ($result as $row) $menu[$row['parent_id']][] = $row;

			return $menu;
		}

		public function get_all_markers()
		{
			$this->db->select('component_article.component_id, component_article.menu_id, component_article.text_' . LANG . ' as text, component_article.shops, component_article.title_' . LANG . ' as title, lat, lng');
			$this->db->join('menu', 'menu.id=component_article.menu_id');
			$this->db->where('menu_index', 2)->where('menu.hidden', 0);

			return $this->db->get('component_article')->result_array();
		}
		
		/**
		 * Отримання кількості аптек
		 *
		 * @param array $params
		 * @return array
		 */
		public function get_total(array $params = array())
		{
			return (int)$this->db
				->where('hidden', 0)
				->count_all_results('catalog');
		}

		/**
		 * Отримання аптеки
		 *
		 * @param int $catalog_id
		 * @return array|null
		 */
		public function get_items($component_id)
		{
			$catalog = $this->db
				->select('catalog.*, catalog.url_'.LANG.' as url, menu.main, menu.url_'.LANG.' as menu_url, menu.url_path_'.LANG.' as menu_path_url')
				->where('catalog.component_id', $component_id)
				->join('menu', 'menu.id = catalog.menu_id')
				->order_by('position')
				->get('catalog')
				->result_array();

				
			foreach ($catalog as $key => $value) {
				
				if($key == 0){
					$catalog['menu_name'] = $this->db
						->where('id', $value['menu_id'])
						->get('menu')
						->row('name_'.LANG);
				}

				$catalog[$key]['images'] = $this->db
					->select('photo')
					->where('catalog_id', $value['catalog_id'])
					->order_by('position', 'asc')
					->limit(1)
					->get('catalog_images')
					->result_array();
			}
			// echo "<pre>";
			// print_r($catalog);
			// echo "</pre>";exit();
			return $catalog;
		}

		public function get_item($catalog_id)
		{
			$catalog = $this->db
				->select('*')
				->where('catalog_id', $catalog_id)
				->order_by('position')
				->get('catalog')
				->row_array();

			$catalog["left_link"] = $this->db
				->select('catalog.catalog_id, catalog.url_'.LANG.' as url, menu.main, menu.url_'.LANG.' as menu_url, menu.url_path_'.LANG.' as menu_path_url')
				->join('menu', 'menu.id = catalog.menu_id')
				->where('catalog.position', $catalog['position']-1)
				->where('catalog.component_id', $catalog['component_id'])
				->limit(1)
				->order_by('catalog.position', 'asc')
				->get('catalog')
				->row_array();

			
			$catalog['right_link'] = $this->db
				->select('catalog.catalog_id, catalog.url_'.LANG.' as url, menu.main, menu.url_'.LANG.' as menu_url, menu.url_path_'.LANG.' as menu_path_url')
				->join('menu', 'menu.id = catalog.menu_id')
				->where('catalog.position', $catalog['position']+1)
				->where('catalog.component_id', $catalog['component_id'])
				->limit(1)
				->order_by('catalog.position', 'desc')
				->get('catalog')
				->row_array();

			$catalog['images'] = $this->db
				->select('catalog_id, photo')
				->where('catalog_id', $catalog['catalog_id'])
				->order_by('position', 'asc')
				->get('catalog_images')
				->result_array();

			// echo "<pre>";
			// print_r($catalog);
			// echo "</pre>";exit();
			return $catalog;
		}

		/**
		 * Отримання меню маркерів
		 *
		 * @return array
		 */
		public function get_markers()
		{
			return $this->db
				->select('id, name_' . LANG . ' as name, image, icon')
				->where('hidden', 0)
				->where('menu_index', 3)
				->order_by('position', 'asc')
				->get('menu')
				->result_array();
		}

		/**
		 * Отримання посилань на різних мовах
		 *
		 * @param int $catalog_id
		 * @param array $languages
		 *
		 * @return array
		 */
		public function get_language_links($catalog_id, $languages)
		{
			foreach ($languages as $language) $this->db->select('catalog.url_' . $language['code'] . ', menu.url_path_' . $language['code']);
			$this->db->join('menu', 'menu.id = catalog.menu_id');
			$this->db->where('catalog.catalog_id', $catalog_id);

			$result = $this->db->get('catalog')->row_array();
			$links = array();

			foreach ($languages as $language)
			{
				$links[$language['code']] = $this->uri->full_url($result['url_path_' . $language['code']] . '/' . $result['url_' . $language['code']], $language['code']);
			}

			return $links;
		}

		/**
		 * Отримання даних для компоненту "Ми відкрились"
		 *
		 * @return array
		 */
		public function get_open()
		{
			$items = array();

			$result = $this->db
			->select('catalog.catalog_id, catalog.open_time, catalog.title_' . LANG . ' as title, catalog.open_sign_' . LANG . ' as open_sign')

			->select('r.name_' . LANG . ' as region')
			->join('menu as r', 'r.id = catalog.region_id', 'left')

			->select('c.name_' . LANG . ' as city')
			->join('menu as c', 'c.id = catalog.city_id', 'left')

			->where('catalog.open', 1)
			->where('catalog.open_time > ', time())

			->order_by('catalog.open_time', 'asc')
			->limit(7)
			->get('catalog')
			->result_array();

			if (count($result) > 0) {
				$items['open'] = $result;
			}

			$result = $this->db
				->select('catalog.catalog_id, catalog.title_' . LANG . ' as title, catalog.phone_1, catalog.phone_2, w_days_' . LANG . ' as w_days, w_hours')

				->select('r.name_' . LANG . ' as region')
				->join('menu as r', 'r.id = catalog.region_id', 'left')

				->select('c.name_' . LANG . ' as city')
				->join('menu as c', 'c.id = catalog.city_id', 'left')

				->where('catalog.open', 1)
				->where('catalog.open_time < ', time())

				->order_by('catalog.open_time', 'desc')
				->limit(7)
				->get('catalog')
				->result_array();

			if (count($result) > 0) {
				$items['opened'] = $result;
			}

			return $items;
		}
	}