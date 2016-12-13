<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_seo_model extends CI_Model
	{

		private $menu_id;

		/**
		 * Отримання мета тегів
		 *
		 * @param int $menu_id
		 * @param int $item_id
		 * @param string $module
		 * @param array $languages
		 *
		 * @return array
		 */
		public function get_tags($menu_id, $item_id, $module, $languages)
		{
			if ($item_id > 0)
			{
				$where = array(
					'item_id' => $item_id,
					'module' => $module
				);
			}
			else
			{
				$where = array('menu_id' => $menu_id);
			}

			if ($this->db->where($where)->count_all_results('seo_tags') == 0)
			{
				$set = array(
					'menu_id' => $menu_id,
					'item_id' => $item_id,
					'module' => $module
				);
				$this->db->insert('seo_tags', $set);
			}

			foreach ($languages as $language)
			{
				$this->db->select('tags_id, type_' . $language . ', title_' . $language . ', description_' . $language . ', keywords_' . $language . '');
			}

			return $this->db->get_where('seo_tags', $where)->row_array();
		}

		/**
		 * Збереження мета тегів
		 *
		 * @param array $set
		 * @param array $where
		 */
		public function save_tags($set, $where)
		{
			$this->db->update('seo_tags', $set, $where);
		}

		/**
		 * Генерація ключових слів
		 *
		 * @param int $tags_id
		 * @param string $language
		 *
		 * @return string
		 */
		public function get_keywords($tags_id, $language)
		{
			$keywords = '';

			$tags_info = $this->db->select('menu_id, item_id, module')->get_where('seo_tags', array('tags_id' => $tags_id))->row_array();

			if (count($tags_info) > 0)
			{
				if ($tags_info['item_id'] == 0)
				{
					$this->db->select('component_article.title_' . $language . ' as title, component_article.text_' . $language . ' as text');
					$this->db->join('components', 'components.component_id = component_article.component_id');
					$this->db->where('component_article.menu_id', $tags_info['menu_id']);
					$this->db->where('components.hidden', 0);

					$result = $this->db->get('component_article');

					if ($result->num_rows() > 0)
					{
						$text = '';
						$result_array = $result->result_array();

						foreach ($result_array as $row)
						{
							$text .= $row['title'] . ' ' . $row['text'] . ' ';
						}

						return form_prep($this->seo_lib->generate_keywords($text));
					}
				}
				else
				{

				}
			}

			return $keywords;
		}

		/**
		 * Отримання всіх меню сайту
		 *
		 * @return array|null
		 */
		public function get_menus()
		{
			$this->db->select('id, parent_id, name_' . LANG . ' as name');
			$this->db->order_by('menu_index, position');

			$r = $this->db->get('menu');

			if ($r->num_rows() > 0)
			{
				$menu = array(
					'root' => array(),
					'children' => array()
				);

				foreach ($r->result_array() as $row)
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
			else
			{
				return NULL;
			}
		}

		/**
		 * Отримання закритих пунктів меню для активної сторінки
		 *
		 * @param int $menu_id
		 *
		 * @return array|mixed
		 */
		public function get_seo_link($menu_id)
		{
			$this->db->select('hide_items');
			$this->db->where('menu_id', $menu_id);
			$r = $this->db->get('seo_link');
			if ($r->num_rows() > 0) return unserialize($r->row()->hide_items);

			return array();
		}

		/**
		 * Приховування пункту меню
		 *
		 * @param int $menu_id
		 * @param int $hide_menu
		 */
		public function hide_menu($menu_id, $hide_menu)
		{
			$this->db->select('hide_items');
			$this->db->where('menu_id', $menu_id);
			$r = $this->db->get('seo_link');
			if ($r->num_rows() > 0)
			{
				$seo_link = unserialize($r->row()->hide_items);
				$seo_link[] = $hide_menu;
				$seo_link = serialize($seo_link);

				$this->db->set('hide_items', $seo_link);
				$this->db->where('menu_id', $menu_id);
				$this->db->update('seo_link');
			}
			else
			{
				$data = array(
					'menu_id' => $menu_id,
					'hide_items' => serialize(array($hide_menu))
				);
				$this->db->insert('seo_link', $data);
			}
		}

		/**
		 * Відобаження пункту меню
		 *
		 * @param int $menu_id
		 * @param int $hide_menu
		 */
		public function show_menu($menu_id, $hide_menu)
		{
			$this->db->select('hide_items');
			$this->db->where('menu_id', $menu_id);
			$r = $this->db->get('seo_link');
			if ($r->num_rows() > 0)
			{
				$seo_link = unserialize($r->row()->hide_items);
				$seo_link = array_flip($seo_link);

				if (isset($seo_link[$hide_menu]))
				{
					unset($seo_link[$hide_menu]);
				}

				if (count($seo_link) > 0)
				{
					$seo_link = serialize(array_flip($seo_link));

					$this->db->set('hide_items', $seo_link);
					$this->db->where('menu_id', $menu_id);
					$this->db->update('seo_link');
				}
				else
				{
					$this->db->where('menu_id', $menu_id);
					$this->db->delete('seo_link');
				}
			}
		}

		/**
		 * Ононвлення xml карти сайту
		 *
		 * @param array $languages
		 * @param bool $multi_languages
		 */
		public function update_xml($languages, $multi_languages)
		{

			if (is_array($languages))
			{
				$cache = array();

				$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
				$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

				$languages = $multi_languages ? array_keys($languages) : array(LANG);

				foreach ($languages as $val)
				{
					$_val = $multi_languages ? $val : '';

					$this->db->select('id, main, url_path_' . $val . ' as url, update');
					$this->db->where('id !=', 196);
					$this->db->where('hidden', 0);
					$this->db->order_by('main', 'desc');
					$r = $this->db->get('menu');

					if ($r->num_rows() > 0)
					{
						foreach ($r->result_array() as $row)
						{
							$link = ($row['main'] == 1) ? $this->uri->full_url('', $_val) : $this->uri->full_url($row['url'], $_val);

							if (!in_array($link, $cache))
							{
								if ($row['main'] == 1)
								{
									$priority = '1.0';
									$changefreq = 'daily';
								}
								else
								{
									$priority = '0.9';
									$changefreq = 'weekly';
								}

								$lastmod = date('c', $row['update']);

								$xml .= "\t" . '<url>' . "\n";
								$xml .= "\t\t" . '<loc>' . $link . '</loc>' . "\n";
								$xml .= "\t\t" . '<lastmod>' . $lastmod . '</lastmod>' . "\n";
								$xml .= "\t\t" . '<changefreq>' . $changefreq . '</changefreq>' . "\n";
								$xml .= "\t\t" . '<priority>' . $priority . '</priority>' . "\n";

								$xml .= "\t" . '</url>' . "\n";

								$cache[] = $link;
							}
						}
					}

				}

				$xml .= '</urlset>';

				write_file(ROOT_PATH . 'sitemap.xml', $xml);
			}
		}

		/**
		 * Отримання назви сайту
		 *
		 * @param array $languages
		 *
		 * @return array
		 */
		public function get_site_name($languages)
		{
			$site_name = array();

			foreach ($languages as $language)
			{
				$r = $this->db->select('val')->where('key', 'site_name_' . $language)->get('config');
				if ($r->num_rows() > 0) $site_name[$language] = $r->row('val');
			}

			return $site_name;
		}

		/**
		 * Оновлення назви сайту
		 *
		 * @param $set
		 * @param $where
		 */
		public function update_site_name($set, $where)
		{
			$this->db->update('config', $set, $where);
		}
	}
