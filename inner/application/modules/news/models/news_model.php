<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	class News_model extends CI_Model
	{
		public function count_news($component_id)
		{
			$this->db->where('component_id', $component_id);
			$this->db->where('hidden', 0);
			return $this->db->count_all_results('news');
		}
		public function get_news($component_id, $page, $per_page)
		{
			$prefix = $this->db->dbprefix;
			$this->db->select('news.news_id, news.component_id, news.date, news.price_uah, news.title_' . LANG . ' as title, news.url_' . LANG . ' as url, news.anons_' . LANG . ' as anons, news.included, menu.main, menu.url_path_' . LANG . ' as menu_path_url, menu.url_'.LANG.' as menu_url');
			$this->db->select('(select `' . $prefix . 'news_images`.`file_name` from `' . $prefix . 'news_images` where `' . $prefix . 'news_images`.`news_id` = `' . $prefix . 'news`.`news_id` order by `' . $prefix . 'news_images`.`position` limit 1) as image', FALSE);
			$this->db->join('menu', 'news.menu_id = menu.id');
			$this->db->where('news.component_id', $component_id);
			$this->db->where('news.hidden', 0);
			$this->db->order_by('news.position', 'asc')->order_by('news.date', 'desc');
			$this->db->limit($per_page, (($page > 0) ? $page - 1 : 0) * $per_page);
			return $this->db->get('news')->result_array();
		}
		public function get_slider_news($type = '')
		{
			$prefix = $this->db->dbprefix;
			$this->db->select('news.news_id, news.component_id, news.date, news.price_uah, news.title_' . LANG . ' as title, news.url_' . LANG . ' as url, news.anons_' . LANG . ' as anons, menu.main, menu.url_path_' . LANG . ' as menu_url, news.menu_id, news.check_href');
			$this->db->select('(select `' . $prefix . 'news_images`.`file_name` from `' . $prefix . 'news_images` where `' . $prefix . 'news_images`.`news_id` = `' . $prefix . 'news`.`news_id` order by `' . $prefix . 'news_images`.`position` limit 1) as image', FALSE);
			$this->db->join('menu', 'news.menu_id = menu.id');
			$this->db->join('components', 'news.component_id = components.component_id');
			$this->db->where('news.hidden', 0);
			$this->db->where('menu.hidden', 0);
			$this->db->where('components.hidden', 0);
			if($type == 'main')
			{
				$this->db->where('news.on_main', 1);
				$this->db->limit(3);
			}
			
			$this->db->order_by('news.position', 'desc')->order_by('news.date', 'desc');
			return $this->db->get('news')->result_array();
		}
		public function get($news_id)
		{
			$prefix = $this->db->dbprefix;
			$this->db->select('news.news_id, news.component_id, news.date, news.title_' . LANG . ' as title, news.text_' . LANG . ' as text, news.url_' . LANG . ' as url, news.anons_' . LANG . ' as anons, news.included, news.price_uah, menu.main, news.menu_id, menu.url_path_' . LANG . ' as menu_url, news.social_comments');
			$this->db->select('(select `' . $prefix . 'news_images`.`file_name` from `' . $prefix . 'news_images` where `' . $prefix . 'news_images`.`news_id` = `' . $prefix . 'news`.`news_id` order by `' . $prefix . 'news_images`.`position` limit 1) as image', FALSE);
			$this->db->join('menu', 'news.menu_id = menu.id');
			$this->db->where('news.news_id', $news_id);
			$this->db->where('news.hidden', 0);
			return $this->db->get('news')->row_array();
		}
		public function get_images($news_id)
		{
			$this->db->where('news_id', $news_id);
			return $this->db->get('news_images')->result_array();
		}
		public function get_news_comments($news_id)
		{
			return $this->db->where('item_id', $news_id)->order_by('date', 'asc')->get('comments')->result_array();
		}
		/**
		 * Отримання відгуків
		 *
		 * @param int $news_id
		 * @return array
		 */
		public function get_comments($news_id)
		{
			$this->db->select('comments.comment_id, comments.reply_to, comments.comment, comments.date');
			$this->db->select('users.name, users.last_name, users.city, users.photo');
			$this->db->join('users', 'users.user_id = comments.user_id');
			$this->db->where('item_id', $news_id);
			$this->db->where('component', 'news');
			$this->db->order_by('comments.date', 'asc');
			$result = $this->db->get('comments')->result_array();
			$comments = array();
			foreach ($result as $v) $comments[$v['reply_to']][] = $v;
			return $comments;
		}
		public function get_last_comments()
		{
			$this->db->select('menu.url_path_' . LANG . ' as menu_url');
			$this->db->select('news.url_' . LANG . ' as url');
			$this->db->select('comments.comment, comments.date');
			$this->db->select('users.name, users.last_name, users.city, users.photo');
			$this->db->join('news', 'news.news_id=comments.item_id');
			$this->db->join('menu', 'menu.id=news.menu_id');
			$this->db->join('users', 'users.user_id = comments.user_id');
			//$this->db->where('comments.reply_to', 0);
			$this->db->where('comments.component', 'news');
			$this->db->order_by('comments.date', 'desc');
			$this->db->limit(5);
			return $this->db->get('comments')->result_array();
		}
		/**
		 * Отримання посилань на різних мовах
		 *
		 * @param int $news_id
		 * @param array $languages
		 *
		 * @return array
		 */
		public function get_language_links($news_id, $languages)
		{
			foreach ($languages as $language) $this->db->select('news.url_' . $language['code'] . ', menu.url_path_' . $language['code']);
			$this->db->join('menu', 'menu.id = news.menu_id');
			$this->db->where('news.news_id', $news_id);
			$result = $this->db->get('news')->row_array();
			$links = array();
			foreach ($languages as $language)
			{
				$links[$language['code']] = $this->uri->full_url($result['url_path_' . $language['code']] . '/' . $result['url_' . $language['code']], $language['code']);
			}
			return $links;
		}
		public function get_url($counting = TRUE)
		{
			if ($counting AND $this->db->where('hidden', 0)->count_all_results('news') == 0)
			{
				return '';
			}
			else
			{
				$this->db->select('menu.url_path_' . LANG . ' as url');
				$this->db->join('components', 'components.menu_id = menu.id');
				$this->db->where('components.module', 'news');
				$this->db->where('components.method', 'index');
				$url = $this->db->get('menu')->row('url');
				return is_string($url) ? $url : '';
			}
		}
		public function get_last()
		{
			$prefix = $this->db->dbprefix;
			$this->db->select('news.news_id, news.component_id, news.date, news.title_' . LANG . ' as title, news.text_' . LANG . ' as text, news.url_' . LANG . ' as url, news.anons_' . LANG . ' as anons, news.included, news.price_uah, news.social_comments, menu.main, menu.url_path_' . LANG . ' as menu_url');
			$this->db->select('(select `' . $prefix . 'news_images`.`file_name` from `' . $prefix . 'news_images` where `' . $prefix . 'news_images`.`news_id` = `' . $prefix . 'news`.`news_id` order by `' . $prefix . 'news_images`.`position` limit 1) as image', FALSE);
			$this->db->join('menu', 'news.menu_id = menu.id');
			$this->db->where('news.hidden', 0);
			$this->db->order_by('news.position', 'asc');
			$this->db->limit(3);
			return $this->db->get('news')->result_array();
		}
		public function get_component_news()
		{
			$this->db->select('component_id, menu_id');
			$this->db->where('module','news');
			$this->db->where('method','index');
			return $this->db->get('components')->row_array();
		}
	}