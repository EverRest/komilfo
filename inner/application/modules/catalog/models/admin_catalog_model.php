<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	class Admin_catalog_model extends CI_Model
	{

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
			return $this->db
				->where('parent_id', $menu_id)
				->order_by('position')
				->get('menu')
				->result_array();
		}

		public function get_menu()
		{
			$menu = array();

			$this->db->select('menu.id, menu.parent_id, menu.shops, menu.name_' . LANG . ' as name');
			$this->db->select('component_article.text_' . LANG . ' as text, component_article.title_' . LANG . ' as title, lat, lng, zoom');
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
			$this->db->select('component_article.component_id, component_article.menu_id, component_article.text_' . LANG . ' as text, component_article.shops, component_article.title_shop_' . LANG . ' as title, lat, lng');
			$this->db->join('menu', 'menu.id=component_article.menu_id');
			$this->db->where('menu_index', 2)->where('menu.hidden', 0);

			return $this->db->get('component_article')->result_array();
		}
		/**
		 * Отримання кількості аптек
		 *
		 * @param array $params
		 * @return int
		 */
		public function get_catalog_total(array $params)
		{
			return (int)$this->db
				->count_all_results('catalog');
		}

		/**
		 * Отримання списку аптек
		 *
		 * @param array $params
		 *
		 * @return array
		 */
		public function get_catalog_list(array $params)
		{
			$prefix = $this->db->dbprefix;
			if(isset($params['component_id'])) $this->db->where('catalog.component_id', $params['component_id']);
			return $this->db
				->select('catalog.catalog_id, catalog.hidden, catalog.title_' . LANG . ' as title')
				->select('(select '.$prefix.'catalog_images.photo from '.$prefix.'catalog_images where '.$prefix.'catalog_images.catalog_id = '.$prefix.'catalog.catalog_id order by position asc limit 1) as photo ')
				->order_by('catalog.position')
				// ->order_by('catalog.title_en')
				->get('catalog')
				->result_array();
		}

		public function get_catalog_by_id($catalog_id)
		{
			$prefix = $this->db->dbprefix;
			$this->db->where('catalog.catalog_id', $catalog_id);
			return $this->db
				->select('catalog.catalog_id, component_id, catalog.hidden, catalog.title_' . LANG . ' as title')
				->select('(select '.$prefix.'catalog_images.photo from '.$prefix.'catalog_images where '.$prefix.'catalog_images.catalog_id = '.$prefix.'catalog.catalog_id limit 1) as photo ')
				->order_by('catalog.position', 'desc')
				->get('catalog')
				->row('component_id');
		}

		/**
		 * Додавання нової аптеки
		 *
		 * @return int
		 */
		public function insert_catalog($data)
		{
			$this->db->insert(
				'catalog',
				array(
					'position' => (int)$this->db
						->select_max('position')
						->where('component_id', $data['component_id'])
						->get('catalog')
						->row('position') + 1,
					'component_id' => $data['component_id'],
					'menu_id' => $data['menu_id']
				)
			);
			unset($data['component_id']);
			$catalog_id = (int)$this->db->insert_id();

			$data['item_id'] = $catalog_id;
			$data['module'] = 'catalog';
			$data['method'] = 'details';

			$this->db->insert(
				'site_links',
				(array)$data
			);

			return $catalog_id;
		}

		/**
		 * Отримання інформації про аптеку
		 *
		 * @param int $catalog_id
		 *
		 * @return array|null
		 */
		public function get_catalog($catalog_id)
		{
			return $this->db
				->where('catalog_id', $catalog_id)
				->get('catalog')
				->row_array();
		}

		/**
		 * Отримання зображень
		 *
		 * @param int $catalog_id
		 * @return array
		 */
		public function get_catalog_images($catalog_id)
		{
			$result = $this->db
				->where('catalog_id', $catalog_id)
				->order_by('position')
				->get('catalog_images')
				->result_array();

			return $result;
		}

		/**
		 * Отримання позиції елемента
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_catalog_position($catalog_id)
		{
			$result = $this->db
			->select('MAX(position)')
				->where('catalog_id', $catalog_id)
				->get('catalog_images')
				->row('MAX(position)');

			return ($result+1);
		}

		/**
		 * Оновлення інформації про аптеку
		 *
		 * @param int $catalog_id
		 * @param array $set
		 */
		public function update_catalog($catalog_id, array $set, $option)
		{
			$this->db->update(
				'catalog',
				$set,
				array(
					'catalog_id' => $catalog_id,
				)
			);

			if((boolean)$option){
				$languages = array_keys($this->config->item('languages'));

				$this->db->select('menu.main');
				foreach ($languages as $language)
				{
					$this->db->select('menu.url_path_' . $language.', menu.url_'. $language);
				}
				$this->db->join('menu', 'menu.id = catalog.menu_id');
				$this->db->where('catalog.catalog_id', $catalog_id);

				$menu = $this->db->get('catalog')->row_array();
				
				$check = $this->db->select('*')
					->where('item_id', $catalog_id)
					->where('hash_en', md5($set['url_en']))
					->where('hash_ru', md5($set['url_ru']))
					->where('hash_ua', md5($set['url_ua']))
					->where('module', 'catalog')
					->where('method', 'details')
					->get('site_links')->result_array();
				if(empty($check)){
						// echo "<pre>";
						// print_r('asdasd');
						// echo "</pre>";exit();
						$this->db->insert('site_links', array('menu_id' => $set['menu_id'], 'hash_en' => md5($set['url_en']), 'hash_ru' => md5($set['url_ru']), 'hash_ua' => md5($set['url_ua']), 'item_id' => $catalog_id, 'module' => 'catalog', 'method' => 'details'));
				}else{
					foreach ($languages as $val)
					{
						$url = $set['url_' . $val];

						
							$this->db->set('hash_' . $val, md5($url));
							$this->db->where('item_id', $catalog_id);
							$this->db->where('module', 'catalog');
							$this->db->update('site_links');
						
					}
				}
			}
		}

		public function update($set, $where)
		{
			$this->db->update('catalog', $set, $where);
		}

		/**
		 * Збереження маркерів аптеки
		 *
		 * @param int $catalog_id
		 * @param array $images
		 */

		public function insert_images($set)
		{
			$this->db->insert('catalog_images', $set);
			return $this->db->insert_id();
		}

		public function update_image($image_id, $set)
		{
			$this->db->update('catalog_images', $set, array('image_id' => $image_id));
		}

		// public function update_images($catalog_id, array $images)
		// {
		// 	foreach ($images as $v) {
		// 		$c = (int)$this->db
		// 			->where('catalog_id', $catalog_id)
		// 			->where('image_id', $v)
		// 			->count_all_results('catalog_images');

		// 		if ($c === 0) {
		// 			$this->db->insert(
		// 				'catalog_images',
		// 				array(
		// 					'catalog_id' => $catalog_id,
		// 					'image_id' => $v,
		// 					'del_flag' => 1,
		// 				)
		// 			);
		// 		} else {
		// 			$this->db->update(
		// 				'catalog_images',
		// 				array(
		// 					'del_flag' => 1,
		// 				),
		// 				array(
		// 					'catalog_id' => $catalog_id,
		// 					'image_id' => $v,
		// 				)
		// 			);
		// 		}
		// 	}

		// 	$this->db->delete(
		// 		'catalog_images',
		// 		array(
		// 			'catalog_id' => $catalog_id,
		// 			'del_flag' => 0,
		// 		)
		// 	);

		// 	$this->db->update(
		// 		'catalog_images',
		// 		array(
		// 			'del_flag' => 0,
		// 		),
		// 		array(
		// 			'catalog_id' => $catalog_id,
		// 		)
		// 	);
		// }

		/**
		 * Видалення аптеки
		 *
		 * @param int $catalog_id
		 */
		public function delete_catalog($catalog_id, $menu_id = '')
		{
			$this->db->delete('catalog', array('catalog_id' => $catalog_id));
			$this->db->delete('catalog_images', array('catalog_id' => $catalog_id));
			$where = array('module' => 'catalog', 'method' => 'details','item_id' => $catalog_id);
			if($menu_id != ''){
				$where['menu_id'] = $menu_id;
			}
			$this->db->delete('site_links', $where);

			$dir = get_dir_path('upload/catalog/' . get_dir_code($catalog_id).'/'.$catalog_id, false);

			if (file_exists($dir)) {
				delete_files($dir, true, true, 1);
			}
		}

		public function delete_all($component_id)
		{
			$results = $this->db->where('component_id', $component_id)->get('catalog')->result_array();

			foreach ($results as $key => $resut) {
				$this->delete_catalog($resut['catalog_id'], $resut['menu_id']);
			}
		}

		public function delete_image($image_id,$photo)
		{
			$this->db->delete('catalog_images', array('image_id' => $image_id));

			$dir = get_dir_path('upload/catalog/' . get_dir_code($catalog_id).'/'.$catalog_id.'/'.$photo, false);

			if (file_exists($dir)) {
				unlink($dir);
			}
		}

		/**
		 * Отримання списку локацій
		 *
		 * @return array
		 */
		public function get_locations()
		{
			$result = $this->db
				->select('id, parent_id, name_' . LANG . ' as name')
				->where('menu_index', 2)
				->order_by('position', 'asc')
				->get('menu')
				->result_array();

			$regions = array();

			foreach ($result as $v) {
				$regions[$v['parent_id']][] = $v;
			}

			return $regions;
		}

		/**
		 * Отримання локації
		 *
		 * @param int $id
		 * @return array|null
		 */
		public function get_location($id)
		{
			return $this->db
				->select('parent_id, name_' . LANG . ' as name')
				->where('id', $id)
				->get('menu')
				->row_array();
		}

		/**
		 * Отримання списку маркерів
		 *
		 * @return array
		 */
		public function get_image($image_id)
		{
			return $this->db
				->where('image_id', $image_id)
				->get('catalog_images')
				->row_array();
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$this->db->where('component_id', $component_id)->get('components')->row();
			$this->delete_all($component_id, $menu_id);
			$this->db->delete(
				'components',
				array(
					'component_id' => $component_id,
				)
			);
		}
	}