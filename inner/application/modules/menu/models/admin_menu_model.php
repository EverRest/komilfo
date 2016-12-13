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
		 * @return array|null
		 */
		public function get_menu($menu_index, $language = NULL)
		{
			if (is_null($language)) $language = LANG;

			$this->db->select('id, parent_id, position, hidden, main, target, name_' . $this->config->item('language') . ' as def_name, name_' . $language . ' as name, url_path_' . $language . ' as url, static_url_' . $language . ' as static_url, image');
			$this->db->where('id !=', 196);
			$this->db->where('menu_index', $menu_index);
			$this->db->order_by('parent_id, position');
			$result = $this->db->get('menu')->result_array();

			if (count($result) > 0)
			{
				$menu = array(
					'root' => array(),
					'children' => array()
				);

				foreach ($result as $row)
				{
					if ($row['parent_id'] == 0)
					{
						$menu['root'][] = $row;
					}
					else
					{
						$menu['children'][$row['parent_id']][] = $row;
					}
				}

				return $menu;
			}

			return NULL;
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
		 * @return object
		 */
		public function menu_update($id, $set = array())
		{
			$this->db->update('menu', $set, array('id' => $id));
		}

		/**
		 * Збереження порядку сортування пунктів меню
		 *
		 * @param $items
		 */
		public function update_position($items)
		{
			if (is_array($items))
			{
				$set = array();

				foreach ($items as $val)
				{
					if (isset($val['id']) AND $val['id'] > 0)
					{
						$set[] = array(
							'id' => $val['id'],
							'parent_id' => intval($val['parent_id']),
							'level' => intval($val['level']),
							'position' => intval($val['position'])
						);

						//$this->menu_update($val['id'], $set);
					}

					if (count($set) == 50) {
						$this->db->update_batch('menu', $set, 'id');
						$set = array();
					}
				}

				if (count($set) > 0) $this->db->update_batch('menu', $set, 'id');
			}
		}

		/**
		 * Встановлення пункту меню головним
		 *
		 * @param $id
		 */
		public function set_main($id)
		{
			$this->db->update('menu', array('main' => 0), array('main' => 1));
			$this->db->update('menu', array('main' => 1), array('id' => $id));
		}

		/**
		 * Видалення пункту меню
		 *
		 * @param $id
		 */
		public function menu_delete($id)
		{
			// Видалення компонентів пункту меню

			$this->db->delete('site_links', array('item_id' => $id, 'component_id' => 0));
			$this->db->delete('components', array('menu_id' => $id));
			$this->db->delete('component_article', array('menu_id' => $id));
			$this->db->delete('google_map', array('menu_id' => $id));
			$this->db->delete('feedback', array('menu_id' => $id));
			$this->db->delete('seo_tags', array('component_id' => 0, 'menu_id' => $id));
			$this->db->delete('seo_link', array('menu_id' => $id));

			// Видалення новин
			$result = $this->db->select('news_id')->where('menu_id', $id)->get('news');

			if ($result->num_rows() > 0)
			{
				$result_array = $result->result_array();
				foreach ($result_array as $row)
				{
					$dir = ROOT_PATH . 'upload/news/' . $this->init_model->dir_by_id($row['news_id']) . '/';
					if (file_exists($dir)) delete_files($dir, TRUE, FALSE, 1);

					$this->db->delete('site_links', array('item_id' => $row['news_id'], 'module' => 'news'));
					$this->db->delete('seo_tags', array('item_id' => $row['news_id'], 'module' => 'news'));
					$this->db->delete('comments', array('item_id' => $row['news_id'], 'component' => 'news'));
				}
			}

			$this->db->delete('news', array('menu_id' => $id));
			$this->db->delete('news_images', array('menu_id' => $id));

			// Видалення галерей
			$result = $this->db->select('gallery_id')->where('menu_id', $id)->get('galleries');

			if ($result->num_rows() > 0)
			{
				$result_array = $result->result_array();

				foreach ($result_array as $row)
				{
					$dir = ROOT_PATH . 'upload/gallery/' . $this->init_model->dir_by_id($row['gallery_id']) . '/';
					if (file_exists($dir)) delete_files($dir, TRUE, FALSE, 1);
				}
			}

			$this->db->delete('galleries', array('menu_id' => $id));
			$this->db->delete('gallery_images', array('menu_id' => $id));

			// Видалення пункту меню
			$this->db->delete('menu', array('id' => $id));

			$dir = ROOT_PATH . 'upload/menu/' . $id . '/';
			if (file_exists($dir)) delete_files($dir, TRUE, FALSE, 1);

			// Видалення дочірніх пунктів меню
			$result = $this->db->select('id')->where('parent_id', $id)->get('menu');
			if ($result->num_rows() > 0)
			{
				$result_array = $result->result_array();
				foreach ($result_array as $row)
				{
					$this->menu_delete($row['id']);
				}
			}
		}

		/**
		 * Оновлення посилань пунктів меню
		 *
		 * @param int $menu_id
		 * @param array $languages
		 * @param bool $current
		 */
		public function update_paths($menu_id = NULL, $languages = array(), $current = TRUE)
		{
			$_languages = array_keys($languages);

			$this->db->select('id, parent_id, main');
			foreach ($_languages as $language)
			{
				$this->db->select('url_' . $language);
			}

			if ($current)
			{
				$this->db->where('id', $menu_id);
				$this->db->or_where('parent_id', $menu_id);
			}
			else
			{
				$this->db->where('parent_id', $menu_id);
			}

			$r = $this->db->get('menu');

			$menu_set = array();

			foreach ($r->result_array() as $key => $row)
			{
				if ($row['main'] != 1)
				{
					$this->url_path = array();
					$this->url_path_id = array();
					$urls = array();
					foreach ($_languages as $language)
					{
						$urls[$language] = $row['url_' . $language];
					}
					$paths = $this->get_paths($row['parent_id'], $urls, $_languages);

					$links_set = array();
					$menu_set[$key] = array(
						'id' => $row['id'],
						'url_path_id' => $paths['id']
					);
					foreach ($_languages as $language)
					{
						$md_5 = md5($paths[$language]);

						$menu_set[$key]['url_path_' . $language] = $paths[$language];
						$menu_set[$key]['url_hash_' . $language] = $md_5;

						$links_set['hash_' . $language] = $md_5;
					}

					$where = array('item_id' => $row['id'], 'component_id' => 0);
					$this->db->update('site_links', $links_set, $where);

					// Оновлення посилань новин
					$this->db->select('news_id');
					foreach ($_languages as $language) $this->db->select('url_' . $language);
					$this->db->where('menu_id', $row['id']);

					$result = $this->db->get('news');

					if ($result->num_rows() > 0)
					{
						$result_array = $result->result_array();

						foreach ($result_array as $_row)
						{
							$set = array();
							foreach ($_languages as $language) $set['hash_' . $language] = md5($paths[$language] . '/' . $_row['url_' . $language]);
							$where = array('item_id' => $_row['news_id'], 'module' => 'news');
							$this->db->update('site_links', $set, $where);
						}
					}
				}

				$this->update_paths($row['id'], $languages, FALSE);
			}

			if (count($menu_set) > 0) $this->db->update_batch('menu', $menu_set, 'id');
		}

		/**
		 * Рекурсивне отримання посилань
		 *
		 * @param int $id
		 * @param array $languages
		 */
		private function set_paths($id, $languages)
		{
			$this->db->select('parent_id, main');
			foreach ($languages as $language)
			{
				$this->db->select('url_' . $language);
			}
			$this->db->where('id', $id);

			$r = $this->db->get('menu');

			if ($r->num_rows() > 0)
			{
				$row = $r->row_array();

				array_unshift($this->url_path_id, $id);

				if ($row['main'] != 1)
				{
					foreach ($languages as $language)
					{
						if (!isset($this->url_path[$language])) $this->url_path[$language] = array();
						array_unshift($this->url_path[$language], $row['url_' . $language]);
					}
				}

				if ($row['parent_id'] > 0) $this->set_paths($row['parent_id'], $languages);
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