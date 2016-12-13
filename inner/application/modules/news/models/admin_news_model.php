<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_news_model extends CI_Model
	{
		/**
		 * Загальна кількість всіх новин в компоненті
		 *
		 * @param int $component_id
		 * @return string
		 */
		public function count_news($component_id)
		{
			return $this->db->where('component_id', $component_id)->count_all_results('news');
		}

		/**
		 * Отримання всіх новин компоненту
		 *
		 * @param int $component_id
		 * @param int $page
		 * @return null
		 */
		public function get_news($component_id, $page)
		{
			$prefix = $this->db->dbprefix;

			$this->db->select('news.news_id, news.component_id, news.hidden, news.date, news.title_' . LANG . ' as title, news.anons_' . LANG . ' as anons, menu.main, menu.url_path_' . LANG . ' as menu_url');
			$this->db->select('(select `' . $prefix . 'news_images`.`file_name` from `' . $prefix . 'news_images` where `' . $prefix . 'news_images`.`news_id` = `' . $prefix . 'news`.`news_id` order by `' . $prefix . 'news_images`.`position` limit 1) as image', FALSE);
			$this->db->join('menu', 'news.menu_id = menu.id', 'inner');
			$this->db->where('news.component_id', $component_id);
			$this->db->limit(30, ($page > 0 ? $page - 1 : $page) * 30);
			$this->db->order_by('news.position', 'asc')->order_by('news.date', 'desc');

			return $this->db->get('news')->result_array();
		}

		/**
		 * Додавання новини
		 *
		 * @param array $set
		 * @return mixed
		 */
		public function insert($set)
		{
			$this->db->insert('news', $set);

			$news_id = $this->db->insert_id();

			$set = array(
				'item_id' => $news_id,
				'component_id' => $set['component_id'],
				'menu_id' => $set['menu_id'],
				'module' => 'news',
				'method' => 'details'
			);
			$this->db->insert('site_links', $set);

			$set = array(
				'item_id' => $news_id,
				'component_id' => $set['component_id'],
				'menu_id' => $set['menu_id'],
				'module' => 'news'
			);
			$this->db->insert('seo_tags', $set);

			return $news_id;
		}

		/**
		 * Отримання новини
		 *
		 * @param $news_id
		 */
		public function get($news_id)
		{
			return $this->db->get_where('news', array('news_id' => $news_id))->row_array();
		}

		/**
		 * Оновлення новини
		 *
		 * @param array $set
		 * @param array $where
		 */
		public function update($set, $where, $position=0)
		{
			$this->db->update('news', $set, $where);

			if($position==0){
			$languages = array_keys($this->config->item('languages'));

			$this->db->select('menu.main');

			foreach ($languages as $language)
			{
				$this->db->select('menu.url_path_' . $language);
			}

			$this->db->join('menu', 'menu.id = news.menu_id');
			$this->db->where('news.news_id', $where['news_id']);

			$menu = $this->db->get('news')->row_array();

			foreach ($languages as $val)
			{
				$url = ($menu['main'] == 0) ? $menu['url_path_' . $val] . '/' . $set['url_' . $val] : $set['url_' . $val];

				$this->db->set('hash_' . $val, md5($url));
				$this->db->where('item_id', $where['news_id']);
				$this->db->where('module', 'news');
				$this->db->update('site_links');
			}
			}
		}

		/**
		 * Отримання останньої позиції в компоненті
		 *
		 * @param int $component_id
		 * @return int
		 */
		public function get_position($component_id)
		{
			$this->db->set('`position`', '`position` + 1', FALSE);
			$this->db->where('component_id', $component_id);
			$this->db->update('news');

			return 0;
		}

		### Зображення ###

		public function get_image($image_id)
		{
			return $this->db->where('image_id', $image_id)->get('news_images')->row_array();
		}

		public function get_news_images($news_id)
		{
			return $this->db->where('news_id', $news_id)->order_by('position')->get('news_images')->result_array();
		}

		public function get_image_position($news_id)
		{
			$this->db->select_max('position', 'max');
			$this->db->where('news_id', $news_id);
			$r = $this->db->get('news_images');
			return $r->row()->max + 1;
		}

		public function insert_image($set)
		{
			$this->db->insert('news_images', $set);
			return $this->db->insert_id();
		}

		public function update_image($image_id, $set)
		{
			$this->db->update('news_images', $set, array('image_id' => $image_id));
		}

		/**
		 * Видалення зображення
		 *
		 * @param int $image_id
		 */
		public function delete_image($image_id)
		{
			$im = $this->db->select('news_id, component_id, file_name')->where('image_id', $image_id)->get('news_images')->row_array();

			if (count($im) > 0)
			{
				$dir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/upload/news/' . $this->init_model->dir_by_id($im['news_id']) . '/' . $im['news_id'] . '/';
				$file_name = $im['file_name'];

				if (file_exists($dir . $file_name)) unlink($dir . $file_name);
				if (file_exists($dir . 's_' . $file_name)) unlink($dir . 's_' . $file_name);
				if (file_exists($dir . 't_' . $file_name)) unlink($dir . 't_' . $file_name);


				$this->db->delete('news_images', array('image_id' => $image_id));
			}
		}

		/**
		 * Видалення новини
		 *
		 * @param int $news_id
		 */
		public function delete($news_id)
		{
			$result = $this->db->select('component_id')->get_where('news', array('news_id' => $news_id))->row_array();

			if (count($result) > 0)
			{
				$dir = ROOT_PATH . 'upload/news/' . $this->init_model->dir_by_id($news_id) . '/' . $news_id . '/';
				delete_files($dir, TRUE, FALSE, 1);

				$this->db->delete('news', array('news_id' => $news_id));
				$this->db->delete('news_images', array('news_id' => $news_id));
				$this->db->delete('site_links', array('item_id' => $news_id, 'module' => 'news'));
				$this->db->delete('seo_tags', array('item_id' => $news_id, 'module' => 'news'));
			}
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$news = $this->db->select('news_id')->where('component_id', $component_id)->get('news')->result_array();

			foreach ($news as $v)
			{
				$this->db->delete('site_links', array('item_id' => $v['news_id'], 'module' => 'news'));
				$this->db->delete('seo_tags', array('item_id' => $v['news_id'], 'module' => 'news'));
			}

			$dir = ROOT_PATH . 'upload/news/' . $component_id . '/';
			delete_files($dir, TRUE, FALSE, 1);

			$this->db->delete('news', array('component_id' => $component_id));
			$this->db->delete('news_images', array('component_id' => $component_id));
			$this->db->delete('components', array('component_id' => $component_id));
		}

		/**
		 * Видалення компоненту останніх новин
		 *
		 * @param int $component_id
		 */
		public function delete_last_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
		}
	}

