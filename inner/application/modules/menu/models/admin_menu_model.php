<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_menu_model extends CI_Model
	{

		private $paths_cache = array();

		/**
		 * Повний ID шлях до пункту меню
		 * @var array
		 */
		private $url_path_id = array();

		/**
		 * Повний url до пункту меню
		 * @var array
		 */
		private $url_path = array();

		/**
		 * Отримання списку пунктів меню
		 *
		 * @param int $menu_index
		 * @param null $language
		 *
		 * @return array
		 */
		public function get_menu($menu_index, $language = NULL)
		{
			if ($language === null) {
				$language = LANG;
			}

			$menu = array(
				'root' => array(),
				'children' => array(),
			);

			$result = $this->db
				->select('id, parent_id, position, hidden, main, target, name_' . $this->config->item('language') . ' as def_name, name_' . $language . ' as name, url_' . $language . ' as url, static_url_' . $language . ' as static_url, image')
				//->where('id !=', 572)
				->where('menu_index', $menu_index)
				->order_by('parent_id, position')
				->get('menu')
				->result_array();

			if (count($result) > 0) {
				foreach ($result as $row) {
					if ((int)$row['parent_id'] === 0) {
						$menu['root'][] = $row;
					} else {
						$menu['children'][$row['parent_id']][] = $row;
					}
				}
			}

			return $menu;
		}

		public function get_item($item_id)
		{
			return $this->db
				->where('id', $item_id)
				->get('menu')
				->row_array();
		}

		/**
		 * Додавання нового пункту меню
		 *
		 * @param int $menu_index
		 * @param int $parent_id
		 *
		 * @return int|null
		 */
		public function menu_add($menu_index = 0, $parent_id = 0)
		{
			$set = array(
				'parent_id' => $parent_id,
				'menu_index' => $menu_index,
				'update' => time()
			);
			$this->db->insert('menu', $set);

			$menu_id = $this->db->insert_id();

			$set = array(
				'item_id' => $menu_id,
				'component_id' => 0,
				'menu_id' => $menu_id,
				'module' => 'components',
				'method' => 'get_components'
			);
			$this->db->insert('site_links', $set);

			return $menu_id;
		}

		/**
		 * Оновлення інформації про пункт меню
		 *
		 * @param int $id
		 * @param array $set
		 * @param bool $update_uri
		 * @param null|string $language
		 * @return object
		 */
		public function menu_update($id, array $set, $update_uri = false, $language = null)
		{
			$this->db->update('menu', $set, array('id' => $id));
			$item_not_found = false;
			if ($update_uri) {
				if ($language === null) {
					$language = LANG;
				}

				$menu = $this->db
					->select('main, url_' . $language . ' as url, static_url_' . $language . ' as static_url')
					->where('id', $id)
					->get('menu')
					->row_array();

				if ((int)$menu['main'] === 1) {
					$uri = '';
				} else {
					$uri = $menu['url'];
					
					if(in_array($uri, $this->config->item('frontend_modules'))) {
						$uri .= '-'.$id;
						$this->db->update(
							'menu',
							array('url_' . $language => $uri),
							array('id' => $id)
						);
					}

					$link = $this->db
						->select('item_id, menu_id, module')
						->where('item_id !=', $id)
						->where('menu_id !=', $id)
						->where('hash_' . $language, md5($uri))
						->get('site_links')
						->row_array();

					if (isset($link['item_id'])) {
						$uri .= '-' . $id;
						$this->db->update(
							'menu',
							array('url_' . $language => $uri),
							array('id' => $id)
						);
					}
					
					$search = $this->db
						->select('*')
						->where('item_id', $id)
						->where('menu_id', $id)
						->where('hash_' . $language, md5($uri))
						->get('site_links')
						->row_array();

					if(!empty($search))
						$item_not_found = false;
					else{
						$item_not_found = true;
					}
				}

				if($item_not_found){
					$this->db->insert(
						'site_links',
						array(
							'hash_'.$language => md5($uri),
							'item_id' => $id,
							'menu_id' => $id,
							'module' => 'components',
							'method' => 'get_components',
						)
					);
				}else{
					$this->db
						->set('hash_' . $language, md5($uri))
						->where('item_id', $id)
						->where('menu_id', $id)
						->where('module', 'components')
						->update('site_links');
				}
			}
		}

		/**
		 * Збереження порядку сортування пунктів меню
		 *
		 * @param $items
		 */
		public function update_position($items)
		{
			if (is_array($items)) {
				$set = array();

				foreach ($items as $val) {
					if (isset($val['id']) AND $val['id'] > 0) {
						$set[] = array(
							'id' => $val['id'],
							'parent_id' => (int)$val['parent_id'],
							'level' => (int)$val['level'],
							'position' => (int)$val['position'],
						);

						//$this->menu_update($val['id'], $set);
					}

					if (count($set) === 50) {
						$this->db->update_batch('menu', $set, 'id');
						$set = array();
					}
				}

				if (count($set) > 0) {
					$this->db->update_batch('menu', $set, 'id');
				}
			}
		}

		/**
		 * Встановлення пункту меню головним
		 *
		 * @param $id
		 */
		public function set_main($id)
		{
			$menu_id = (int)$this->db
				->select('id')
				->where('main', 1)
				->get('menu')
				->row('id');

			$this->menu_update($menu_id, array('main' => 0), true);
			$this->menu_update($id, array('main' => 1), true);
		}

		/**
		 * Видалення пункту меню
		 *
		 * @param $id
		 */
		public function menu_delete($id)
		{
			// Видалення компонентів пункту меню

			$this->db->delete('site_links', array('menu_id' => $id));
			$this->db->delete('components', array('menu_id' => $id));
			$this->db->delete('component_article', array('menu_id' => $id));
			//$this->db->delete('google_map', array('menu_id' => $id));
			$this->db->delete('feedback', array('menu_id' => $id));
			$this->db->delete('seo_tags', array('menu_id' => $id));

			// Видалення блоку "Новини"
			$result = $this->db
				->select('component_id')
				->where('menu_id', $id)
				->get('news')
				->result_array();

			if (count($result) > 0) {
				foreach ($result as $row) {
					$dir = get_dir_path('upload/news/' . get_dir_code($row['news_id']).'/'.$row['news_id'], false);
					delete_files($dir, true, true, 1);
				}
			}

			$this->db->delete('news', array('menu_id' => $id));

			// Видалення пункту меню
			$this->db->delete('menu', array('id' => $id));

			$dir = get_dir_path('upload/menu/' . $id);
			delete_files($dir, true, false, 1);

			// Видалення дочірніх пунктів меню
			$result = $this->db
				->select('id')
				->where('parent_id', $id)
				->get('menu')
				->result_array();

			if (count($result) > 0) {
				foreach ($result as $row) {
					$this->menu_delete($row['id']);
				}
			}
		}

		/**
		 * Оновлення шляхів пунктів меню
		 *
		 * @param int $menu_id
		 */
		public function update_paths($menu_id)
		{
			$this->url_path_id = array();
			$this->set_paths($menu_id);

			$this->db->update(
				'menu',
				array('url_path_id' => count($this->url_path_id) > 0 ? '.' . implode('.', $this->url_path_id) . '.' : ''),
				array('id' => $menu_id)
			);

			$menu = $this->db
				->select('id')
				->where('parent_id', $menu_id)
				->get('menu')
				->result_array();

			foreach ($menu as $v) {
				$this->update_paths($v['id']);
			}
		}

		/**
		 * Рекурсивне отримання посилань
		 *
		 * @param int $id
		 */
		private function set_paths($id)
		{
			$r = $this->db
				->select('parent_id')
				->where('id', $id)
				->get('menu')
				->row_array();

			if (count($r) > 0 and $r['parent_id'] > 0) {
				array_unshift($this->url_path_id, $r['parent_id']);
				$this->set_paths($r['parent_id']);
			}
		}

		/**
		 * Отримання сформованого шляху до пункту меню
		 *
		 * @param $id
		 * @param $urls
		 * @param $languages
		 * @return string
		 */
		private function get_paths($id, $urls, $languages)
		{
			if (!isset($this->paths_cache[$id]))
			{
				$this->set_paths($id, $languages);

				$this->paths_cache[$id]['id'] = $this->url_path_id;
				foreach ($languages as $v)
				{
					$this->paths_cache[$id][$v] = isset($this->url_path[$v]) ? $this->url_path[$v] : '';
					$this->url_path[$v][] = $urls[$v];
				}
			}
			else
			{
				$this->url_path_id = $this->paths_cache[$id]['id'];
				foreach ($languages as $v)
				{
					$this->url_path[$v] = $this->paths_cache[$id][$v];
					$this->url_path[$v][] = $urls[$v];
				}
			}

			$paths = array('id' => count($this->url_path_id) > 0 ? ('.' . implode('.', $this->url_path_id) . '.') : '');
			foreach ($languages as $v)
			{
				$paths[$v] = implode('/', $this->url_path[$v]);
			}

			return $paths;
		}
	}