<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_seo_model
	 */
	class Admin_seo_model extends CI_Model
	{
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
		public function get_tags($menu_id, $item_id, $module, array $languages)
		{
			$where = array(
				'menu_id' => $menu_id,
			);

			if ($item_id > 0) {
				$where = array(
					'item_id' => $item_id,
					'module' => $module,
				);
			}

			if ((int)$this->db->where($where)->count_all_results('seo_tags') === 0) {
				$this->db->insert(
					'seo_tags',
					array(
						'menu_id' => $menu_id,
						'item_id' => $item_id,
						'module' => $module
					)
				);
			}

			foreach ($languages as $language) {
				$this->db->select('tags_id, type_' . $language . ', title_' . $language . ', description_' . $language . ', keywords_' . $language . '');
			}

			return $this->db->get_where('seo_tags', $where)->row_array();
		}

		/**
		 * Збереження мета тегів
		 *
		 * @param int $tags_id
		 * @param array $set
		 */
		public function save_tags($tags_id, array $set)
		{
			$this->db->update(
				'seo_tags',
				$set,
				array('tags_id' => $tags_id)
			);
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

			$tags_info = $this->db
				->select('menu_id, item_id, module')
				->where('tags_id', $tags_id)
				->get('seo_tags')
				->row_array();

			if (isset($tags_info['item_id']) and (int)$tags_info['item_id'] === 0) {
				$result = $this->db
					->select('component_article.title_' . $language . ' as title, component_article.text_' . $language . ' as text')
					->join('components', 'components.component_id = component_article.component_id')
					->where('component_article.menu_id', $tags_info['menu_id'])
					->where('components.hidden', 0)
					->get('component_article')
					->result_array();

				if (count($result) > 0) {
					$text = '';

					foreach ($result as $row) {
						$text .= $row['title'] . ' ' . $row['text'] . ' ';
					}

					return form_prep($this->seo_lib->generate_keywords($text));
				}
			}

			return $keywords;
		}

		/**
		 * Ононвлення xml карти сайту
		 *
		 * @param array $languages
		 * @param bool $multi_languages
		 */
		public function update_xml(array $languages, $multi_languages)
		{
			$cache = array();

			$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
			$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

			if (!$multi_languages) {
				$languages = array(
					array(
						'code' => LANG,
						'url' => '',
					),
				);
			}

			foreach ($languages as $val) {
				$result = $this->db
					->select('id, main, url_' . $val['code'] . ' as url, update')
					->where('id !=', 196)
					->where('hidden', 0)
					->where('url_' . $val['code'] . ' != ', '')
					->order_by('main', 'desc')
					->get('menu')
					->result_array();

				if (count($result) > 0) {
					foreach ($result as $row) {
						$link = ((int)$row['main'] === 1)
							? $this->uri->full_url('', $val['url'])
							: $this->uri->full_url($row['url'], $val['url']);

						if (!in_array($link, $cache, true)) {
							if ((int)$row['main'] === 1) {
								$priority = '1.0';
								$changefreq = 'daily';
							} else {
								$priority = '0.9';
								$changefreq = 'weekly';
							}

							$lastmod = date('c', $row['update'] > 0 ? $row['update'] : time());

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
