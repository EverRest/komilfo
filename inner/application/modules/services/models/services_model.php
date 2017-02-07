<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class services_model extends CI_Model
	{
		public function count_services($component_id)
		{
			$this->db->where('component_id', $component_id);
			$this->db->where('hidden', 0);
			return $this->db->count_all_results('services');
		}

		public function get_services($component_id, $page)
		{
			$prefix = $this->db->dbprefix;

			$this->db->select('services.text_'.LANG.', services.services_id, services.component_id, services.date, services.title as title, services.url_' . LANG . ' as url, services.anons_' . LANG . ', menu.main, menu.url_path_' . LANG . ' as menu_url, services.price, services.text');
			$this->db->select('services.text_'.LANG.', services.services_id, services.component_id, services.date, services.title as title, services.url_' . LANG . ' as url, services.anons_' . LANG . ', menu.main, menu.url_path_' . LANG . ' as menu_url, services.price, services.text');
			$this->db->join('menu', 'services.menu_id = menu.id');
			$this->db->where('services.component_id', $component_id);
			$this->db->where('services.hidden', 0);
			$this->db->order_by('services.position', 'asc')->order_by('services.date', 'desc');
			$this->db->limit(10, (($page > 0) ? $page - 1 : 0) * 10);

			return $this->db->get('services')->result_array();
		}

		public function get_last_services()
		{
			$prefix = $this->db->dbprefix;

			$this->db->select('services.services_id, services.component_id, services.date, services.title_' . LANG . ' as title, services.url_' . LANG . ' as url, services.anons_' . LANG . ' as anons, menu.main, menu.url_path_' . LANG . ' as menu_url');
			$this->db->select('(select `' . $prefix . 'services_images`.`file_name` from `' . $prefix . 'services_images` where `' . $prefix . 'services_images`.`services_id` = `' . $prefix . 'services`.`services_id` order by `' . $prefix . 'services_images`.`position` limit 1) as image', FALSE);
			$this->db->join('menu', 'services.menu_id = menu.id');
			$this->db->join('components', 'services.component_id = components.component_id');
			$this->db->where('services.hidden', 0);
			$this->db->where('menu.hidden', 0);
			$this->db->where('components.hidden', 0);
			$this->db->limit(4);
			$this->db->order_by('services.position', 'asc')->order_by('services.date', 'desc');

			return $this->db->get('services')->result_array();
		}

		public function get($services_id)
		{
			$prefix = $this->db->dbprefix;

			$this->db->select('services.services_id, services.menu_id, services.component_id, services.date, services.hidden, services.title_' . LANG . ' as title, services.url_' . LANG . ' as url, services.text_' . LANG . ' as text, services.anons_' . LANG . ' as anons, menu.url_path_' . LANG . ' as menu_url');
			$this->db->select('(select `' . $prefix . 'services_images`.`file_name` from `' . $prefix . 'services_images` where `' . $prefix . 'services_images`.`services_id` = `' . $prefix . 'services`.`services_id` order by `' . $prefix . 'services_images`.`position` limit 1) as image', FALSE);
			$this->db->join('menu', 'services.menu_id = menu.id');
			$this->db->join('components', 'components.component_id = services.component_id');
			$this->db->where('services.services_id', $services_id);
			$this->db->where('services.hidden', 0);
			$this->db->where('menu.hidden', 0);
			$this->db->where('components.hidden', 0);
			return $this->db->get('services')->row_array();
		}

		public function get_services_popup($services_id,$lang)
		{
			$prefix = $this->db->dbprefix;

			$this->db->select('services.services_id, services.menu_id, services.component_id, services.date, services.hidden, services.title_' . $lang . ' as title, services.url_' . $lang . ' as url, services.text_' . $lang . ' as text, services.anons_' . $lang . ' as anons, menu.url_path_' . $lang . ' as menu_url');
			$this->db->select('(select `' . $prefix . 'services_images`.`file_name` from `' . $prefix . 'services_images` where `' . $prefix . 'services_images`.`services_id` = `' . $prefix . 'services`.`services_id` order by `' . $prefix . 'services_images`.`position` limit 1) as image', FALSE);
			$this->db->join('menu', 'services.menu_id = menu.id');
			$this->db->join('components', 'components.component_id = services.component_id');
			$this->db->where('services.services_id', $services_id);
			$this->db->where('services.hidden', 0);
			$this->db->where('menu.hidden', 0);
			$this->db->where('components.hidden', 0);
			return $this->db->get('services')->row_array();
		}


		public function get_images($services_id)
		{

			$this->db->where('services_id', $services_id);
			return $this->db->get('services_images')->result_array();
		}

		/**
		 * Отримання відгуків
		 *
		 * @param int $services_id
		 * @return array
		 */
		public function get_comments($services_id)
		{
			$this->db->select('comments.comment_id, comments.reply_to, comments.comment, comments.date');
			$this->db->select('users.name, users.last_name, users.city, users.photo');
			$this->db->join('users', 'users.user_id = comments.user_id');
			$this->db->where('item_id', $services_id);
			$this->db->where('component', 'services');
			$this->db->order_by('comments.date', 'asc');

			$result = $this->db->get('comments')->result_array();

			$comments = array();
			foreach ($result as $v) $comments[$v['reply_to']][] = $v;

			return $comments;
		}

		public function get_last_comments()
		{
			$this->db->select('menu.url_path_' . LANG . ' as menu_url');
			$this->db->select('services.url_' . LANG . ' as url');
			$this->db->select('comments.comment, comments.date');
			$this->db->select('users.name, users.last_name, users.city, users.photo');
			$this->db->join('services', 'services.services_id=comments.item_id');
			$this->db->join('menu', 'menu.id=services.menu_id');
			$this->db->join('users', 'users.user_id = comments.user_id');
			//$this->db->where('comments.reply_to', 0);
			$this->db->where('comments.component', 'services');
			$this->db->order_by('comments.date', 'desc');
			$this->db->limit(5);

			return $this->db->get('comments')->result_array();
		}

		/**
		 * Отримання посилань на різних мовах
		 *
		 * @param int $services_id
		 * @param array $languages
		 *
		 * @return array
		 */
		public function get_language_links($services_id, $languages)
		{
			foreach ($languages as $language) $this->db->select('services.url_' . $language['code'] . ', menu.url_path_' . $language['code']);
			$this->db->join('menu', 'menu.id = services.menu_id');
			$this->db->where('services.services_id', $services_id);

			$result = $this->db->get('services')->row_array();
			$links = array();

			foreach ($languages as $language)
			{
				$links[$language['code']] = $this->uri->full_url($result['url_path_' . $language['code']] . '/' . $result['url_' . $language['code']], $language['code']);
			}

			return $links;
		}
	}