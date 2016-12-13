<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Init_model extends CI_Model
	{
		/**
		 * ID активного пункту меню
		 *
		 * @var int
		 */
		private $menu_id = 0;

		/**
		 * ID активних пунктів меню
		 *
		 * @var array
		 */
		private $menu_parents = array(0);

		/**
		 * Права доступа до панелі адміністратора
		 *
		 * @var array
		 */
		protected $admin_menu_rules = array();
		/**
		 * Права доступа до пунктів меню
		 *
		 * @var array
		 */
		protected $site_menu_rules = array();

		/**
		 * Головна сторінка
		 *
		 * @var boolean
		 */
		private $is_main = FALSE;

		/**
		 * Назва активного пункту меню
		 *
		 * @var string
		 */
		private $menu_name = '';

		#######################################################################################################

		/**
		 * Отримання та встановлення налаштувань
		 */
		public function set_config()
		{
			$config = $this->_get_config(!$this->is_admin());
			$this->_set_config($config);
		}

		/**
		 * Отримання налаштувань
		 *
		 * @param boolean $cache
		 * @return array
		 */
		private function _get_config($cache = TRUE)
		{
			$config = FALSE;
			if ($cache) $config = $this->cache->get('db_config');

			if (!$config)
			{
				$config = $this->db->get('config')->result_array();
				$this->cache->save('db_config', $config, 3600);
			}

			return $config;
		}

		/**
		 * Встановлення налаштувань
		 *
		 * @param array $config
		 */
		private function _set_config($config)
		{
			foreach ($config as $v)
			{
				if ($v['key'] != 'languages')
				{
					$this->config->set_item($v['key'], $v['val']);
				}
				else
				{
					$this->config->set_item('database_' . $v['key'], $v['val'] != '' ? unserialize($v['val']) : array());
				}
			}
		}

		#######################################################################################################

		/**
		 * Перевірка залогованості та прав доступу адміністратора
		 *
		 * @param string $return_type
		 * @return bool
		 */
		public function is_admin($return_type = '')
		{
			if (!defined('ADMIN_VALIDATE'))
			{
				$check_result = FALSE;
				$admin_id = intval($this->session->userdata('admin_id'));
				$admin_key = $this->session->userdata('admin_key');

				if ($admin_id > 0 AND mb_strlen($admin_key) == 32)
				{
					$admin = $this->db->select('password, site_menu, admin_menu')->where('admin_id', $admin_id)->get('administrators')->row_array();

					if (isset($admin['password']))
					{
						$key = md5($admin_id . $admin['password'] . $this->config->item('encryption_key') . $this->input->user_agent());
						if ($admin_key === $key)
						{
							$check_result = TRUE;

							$this->site_menu_rules = explode(',', $admin['site_menu']);
							$this->session->set_userdata('site_menu_rules', $this->site_menu_rules);

							$this->admin_menu_rules = explode(',', $admin['admin_menu']);
							$this->session->set_userdata('admin_menu_rules', $this->admin_menu_rules);

							$cookie = array(
								'name' => 'manager_bridge',
								'value' => 'The Value',
								'expire' => 0,
								'path' => '/',
								'httponly' => TRUE,
							);

							$this->input->set_cookie($cookie);
						}
					}
				}

				define('ADMIN_VALIDATE', $check_result);
			}
			else
			{
				$check_result = ADMIN_VALIDATE;
			}

			if (!$check_result AND $return_type == 'redirect')
			{
				redirect($this->uri->full_url('admin/login/index'));
			}

			return $check_result;
		}

		/**
		 * Перевірка прав доступа
		 *
		 * @param null $admin_rules
		 * @param null $site_rules
		 * @param bool $root_only
		 * @return bool
		 */
		public function check_access($admin_rules = NULL, $site_rules = NULL, $root_only = FALSE)
		{
			$return = TRUE;

			if ($this->session->userdata('admin_root') == 1)
			{
				return $return;
			}

			if ($admin_rules !== NULL)
			{
				$admin_rules = explode('|', $admin_rules);
				foreach ($admin_rules as $v) if (!in_array($v, $this->admin_menu_rules)) $return = FALSE;
			}

			if ($site_rules !== NULL)
			{
				$site_rules = explode('|', $site_rules);
				foreach ($site_rules as $v) if (!in_array($v, $this->site_menu_rules)) $return = FALSE;
			}

			if ($root_only)
			{
				if ($return AND $this->session->userdata('admin_root') != 1)
				{
					$return = FALSE;
				}
			}

			return $return;
		}

		#######################################################################################################

		/**
		 * Визначення внутрішньої адресації за адресою сторінки
		 *
		 * @return array
		 */
		public function get_routing()
		{
			$url = $this->uri->clean_url(array(LANG => 0));

			if ($url == '')
			{
				$route = $this->_get_main_routing();
			}
			else
			{
				$route = $this->_get_inner_routing($url);
			}

			if (!$route)
			{
				show_404();
			}
			else
			{
				return $route;
			}
		}

		/**
		 * Отримання даних для головної сторінки
		 *
		 * @return array|boolean
		 */
		private function _get_main_routing()
		{
			$return = FALSE;

			$this->db->select('id, name_' . LANG . ' as name');
			$this->db->where('main', 1);
			$result = $this->db->get('menu')->row_array();

			if (count($result) > 0)
			{
				$this->set_menu_id($result['id']);

				$this->set_main();
				$this->menu_name = $result['name'];

				$return = array('components', 'get_components', $result['id']);
			}

			return $return;
		}

		/**
		 * Отримання даних для внутрішньої сторінки
		 *
		 * @param string $url
		 * @return array|boolean
		 */
		private function _get_inner_routing($url)
		{
			$return = FALSE;

			$this->db->select('item_id, menu_id, module, method');
			$this->db->where('hash_' . LANG, md5($url));

			$result = $this->db->get('site_links')->row_array();

			if (count($result) > 0)
			{
				$this->set_menu_id($result['menu_id'], TRUE);

				$return = array(
					$result['module'],
					$result['method'],
					$result['item_id'],
				);
			}

			return $return;
		}

		#######################################################################################################

		/**
		 * Встановлення активного пункту меню
		 *
		 * @param int $menu_id
		 * @param boolean $get_info
		 */
		public function set_menu_id($menu_id = 0, $get_info = FALSE)
		{
			$this->menu_id = intval($menu_id);

			if ($get_info)
			{
				$this->db->select('level, menu_index, position, main, url_path_id, name_' . LANG . ' as name');
				$this->db->where('id', $this->menu_id);

				$result = $this->db->get('menu')->row_array();

				$this->is_main = ($result['main'] == 0) ? FALSE : TRUE;
				$path_id = explode('.', trim($result['url_path_id'], '.'));

				$this->set_menu_parents($path_id);
				$this->set_menu_parents(array($this->menu_id));

				$this->menu_name = $result['name'];
			}
		}

		/**
		 * Встановлення активних пунктів меню
		 *
		 * @param array $menu_parents
		 */
		public function set_menu_parents($menu_parents = array())
		{
			foreach ($menu_parents as $key => $parent)
			{
				if (!is_numeric($parent)) unset($menu_parents[$key]);
			}

			$this->menu_parents = array_merge($this->menu_parents, array_map('intval', $menu_parents));
		}

		/**
		 * Встановлення ознаки головної сторінки
		 */
		public function set_main()
		{
			$this->is_main = TRUE;
		}

		/**
		 * Отримання активного пункту меню
		 *
		 * @return int
		 */
		public function get_menu_id()
		{
			if ($this->menu_id == 0)
			{
				$this->menu_id = intval($this->input->get('menu_id'));
			}

			return $this->menu_id;
		}

		/**
		 * Отримання активних пунктів меню
		 *
		 * @return array
		 */
		public function get_menu_parents()
		{
			return $this->menu_parents;
		}

		/**
		 * Перевірка на головну сторінку
		 *
		 * @return boolean
		 */
		public function is_main()
		{
			return $this->is_main;
		}

		/**
		 * Перевірка наявності компонента на сторінці
		 *
		 * @param int $menu_id
		 * @param string $module
		 * @param string $method
		 *
		 * @return boolean
		 */
		public function check_component($menu_id, $module, $method = '')
		{
			if ($menu_id > 0)
			{
				$this->db->where('menu_id', $menu_id);
			}

			$this->db->where('module', $module);

			if ($method == '')
			{
				$this->db->where('method', $method);
			}

			return (bool)$this->db->count_all_results('components');
		}

		#######################################################################################################

		/**
		 * Отримання посилання на сторінку з потрібним компонентом
		 *
		 * @param string $module
		 * @return string
		 */
		public function get_component_link($module)
		{
			$link = '#';

			$this->db->select('menu.main, menu.url_path_' . LANG . ' as url');
			$this->db->join('menu', 'menu.id = components.menu_id');
			$this->db->where('components.hidden', 0);
			$this->db->where('components.module', $module);
			$this->db->where('menu.hidden', 0);

			$result = $this->db->get('components')->row_array();

			if (count($result) > 0)
			{
				$link = ($result['main'] == 1) ? $this->uri->full_url() : $this->uri->full_url($result['url']);
			}

			return $link;
		}

		#######################################################################################################

		/**
		 * Отримання сегментів візуальної навігації
		 */
		public function set_bread_crumbs()
		{
			if (count($this->menu_parents) > 1)
			{
				$bread_crumbs = $this->menu_parents;

				unset($bread_crumbs[0]);
				reset($bread_crumbs);

				$segments = $this->_get_bread_crumbs_segments($bread_crumbs);

				if (count($segments) > 0)
				{
					$this->_set_bread_crumbs_segments($segments);
				}
			}
			else
			{
				$this->_get_bread_crumbs_main_segment();
			}
		}

		/**
		 * Отримання сегментів навігації
		 *
		 * @param array $bread_crumbs
		 * @return array
		 */
		private function _get_bread_crumbs_segments($bread_crumbs)
		{
			$this->db->select('main, name_' . LANG . ' as name, url_path_' . LANG . ' as url, static_url_' . LANG . ' as static_url');

			if (count($bread_crumbs) > 0)
			{
				if (count($bread_crumbs) > 1)
				{
					$this->db->where_in('id', $bread_crumbs);
				}
				else
				{
					$this->db->where('id', $bread_crumbs[1]);
				}
			}

			$this->db->or_where('main', 1);
			$this->db->order_by('main', 'asc')->order_by('level', 'desc');

			return $this->db->get('menu')->result_array();
		}

		/**
		 * Передача сегментів навігації в шаблон
		 *
		 * @param array $segments
		 */
		private function _set_bread_crumbs_segments($segments)
		{
			foreach ($segments as $v)
			{
				if ($v['main'] == 1)
				{
					$this->template_lib->set_bread_crumbs($this->uri->full_url(), $v['name'], 'prepend');
				}
				else
				{
					$this->template_lib->set_bread_crumbs($this->uri->full_url($v['static_url'] != '' ? $v['static_url'] : $v['url']), $v['name'], 'prepend');
				}
			}
		}

		/**
		 * Встановлення сегменту навігації головної сторінки
		 */
		private function _get_bread_crumbs_main_segment()
		{
			$this->db->select('main, name_' . LANG . ' as name, url_path_' . LANG . ' as url');
			$this->db->where('main', 1);

			$result = $this->db->get('menu')->row_array();
			$this->template_lib->set_bread_crumbs($this->uri->full_url(), $result['name'], 'prepend');
		}

		#######################################################################################################

		/**
		 * Отримання посилання через ID пункту меню
		 *
		 * @param int $menu_id
		 * @param string $template
		 * @param boolean $word_wrap
		 * @param boolean $hidden
		 * @param null|string $language
		 *
		 * @return string
		 */
		function get_link($menu_id, $template = '', $word_wrap = FALSE, $hidden = FALSE, $language = NULL)
		{
			list($language, $_language) = $this->_set_link_languages($language);

			$link = $this->_get_link_data($menu_id, $language, $hidden);
			return $link ? $this->_render_link($link, $_language, $word_wrap, $template) : '';
		}

		/**
		 * Встановлення мови для формування посилання
		 *
		 * @param string $language
		 * @return array
		 */
		private function _set_link_languages($language)
		{
			if ($language === '' OR ($language === NULL AND !defined('LANG_SEGMENT')))
			{
				$language = $this->config->item('def_lang');
				$_language = '';
			}
			else if ($language === NULL AND defined('LANG_SEGMENT'))
			{
				$language = LANG_SEGMENT;
				$_language = LANG_SEGMENT;
			}
			else
			{
				$_language = $language;
			}

			return array(
				$language,
				$_language,
			);
		}

		/**
		 * Отримання даних для побулови посилання
		 *
		 * @param int $menu_id
		 * @param string $language
		 * @param boolean $hidden
		 * @return array|boolean
		 */
		private function _get_link_data($menu_id, $language, $hidden)
		{
			$this->db->select('main, target, name_' . $language . ' as name, url_path_' . $language . ' as url, static_url_' . $language . ' as static_url');
			$this->db->where('id', $menu_id);

			if ($hidden === FALSE)
			{
				$this->db->where('hidden', 0);
			}

			$link = $this->db->get('menu')->row_array();
			return isset($link['name']) ? $link : FALSE;
		}

		/**
		 * Формування посилання
		 *
		 * @param array $link
		 * @param string $language
		 * @param boolean $word_wrap
		 * @param string $template
		 * @return string
		 */
		private function _render_link($link, $language, $word_wrap, $template)
		{
			if ($word_wrap === TRUE)
			{
				$link['name'] = str_replace(" ", '<br>', $link['name']);
			}

			if ($link['main'] == 1)
			{
				$url = $this->uri->full_url('', $language);
			}
			else
			{
				if ($link['static_url'] != '')
				{
					$url = ($link['target'] == 0) ? $this->uri->full_url($link['static_url']) : $link['static_url'];
				}
				else
				{
					$url = $this->uri->full_url($link['url'], $language);
				}
			}

			return str_replace(array('{URL}', '{NAME}'), array($url, $link['name']), $template);
		}

		#######################################################################################################

		/**
		 * Встановлення метатегів сторінки
		 */
		public function set_metatags()
		{
			$menu_id = $this->get_menu_id();

			$this->_check_metatags($menu_id);

			$tags = $this->_get_metatags($menu_id);

			if ($tags['type'] == 1)
			{
				$this->_set_metatags($tags['title'], $tags['description'], $tags['keywords']);
				$set = TRUE;
			}
			else
			{
				if ($tags['cache'] == 1 AND $tags['cache_title'] != '' AND !$this->is_admin())
				{
					$this->_set_metatags($tags['cache_title'], $tags['cache_description'], $tags['cache_keywords']);
					$set = TRUE;
				}
				else
				{
					$set = $this->_set_content_metatags($menu_id);
				}
			}

			if (!$set) $this->template_lib->set_title($this->menu_name != '' ? $this->menu_name : $this->config->item('site_name_' . LANG));
		}

		private function _check_metatags($menu_id)
		{
			if ($this->db->where('menu_id', $this->menu_id)->count_all_results('seo_tags') == 0)
			{
				$set = array(
					'item_id' => 0,
					'component_id' => 0,
					'menu_id' => $menu_id,
				);
				$this->db->insert('seo_tags', $set);
			}
		}

		/**
		 * Отримання мета тегів
		 *
		 * @param int $menu_id
		 * @return array
		 */
		private function _get_metatags($menu_id)
		{
			$this->db->select('type_' . LANG . ' as type, title_' . LANG . ' as title, description_' . LANG . ' as description, keywords_' . LANG . ' as keywords');
			$this->db->select('cache_title_' . LANG . ' as cache_title, cache_description_' . LANG . ' as cache_description, cache_keywords_' . LANG . ' as cache_keywords, cache_' . LANG . ' as cache');
			$this->db->where('menu_id', $menu_id);
			$this->db->where('item_id', 0);

			return $this->db->get('seo_tags')->row_array();
		}

		/**
		 * Формування мета тегів з контенту сторінки
		 *
		 * @param int $menu_id
		 * @return boolean
		 */
		private function _set_content_metatags($menu_id)
		{
			$return = FALSE;

			$this->db->select('component_article.title_' . LANG . ' as title, component_article.text_' . LANG . ' as text');
			$this->db->join('components', 'components.component_id = component_article.component_id');
			$this->db->where('component_article.menu_id', $menu_id);
			$this->db->where('components.hidden', 0);
			$this->db->order_by('components.position');

			$result = $this->db->get('component_article')->result_array();

			if (count($result) > 0)
			{
				$this->load->library('seo_lib');

				$title = '';
				$text = '';

				foreach ($result as $k => $v)
				{
					if ($k == 0) $title = $v['title'];
					$text .= $v['title'] . ' ' . $v['text'] . ' ';
				}

				$description = $this->seo_lib->generate_description($text);
				$keywords = $this->seo_lib->generate_keywords($text);

				$this->_set_metatags($title, $description, $keywords);
				$this->_set_cache_metatags($menu_id, $title, $description, $keywords);

				$return = TRUE;
			}

			return $return;
		}

		/**
		 * Кешування мета тегів
		 *
		 * @param int $menu_id
		 * @param string $title
		 * @param string $description
		 * @param string $keywords
		 */
		private function _set_cache_metatags($menu_id, $title, $description, $keywords)
		{
			$set = array(
				'cache_title_' . LANG => $title,
				'cache_description_' . LANG => $description,
				'cache_keywords_' . LANG => $keywords,
				'cache_' . LANG => 1
			);
			$this->db->update('seo_tags', $set, array('menu_id' => $menu_id, 'item_id' => 0));
		}

		/**
		 * Передача мета тегів в шаблон
		 *
		 * @param string $title
		 * @param string $description
		 * @param string $keywords
		 */
		private function _set_metatags($title, $description, $keywords)
		{
			$this->template_lib->set_title($title);
			$this->template_lib->set_description($description);
			$this->template_lib->set_keywords($keywords);
		}

		#######################################################################################################

		/**
		 * @param int $id
		 * @return int
		 */
		public function dir_by_id($id)
		{
			return ceil($id / 100) * 100;
		}

		#######################################################################################################

		/**
		 * Перевірка залогованості користувача
		 *
		 * @return bool
		 */
		public function is_user()
		{
			$return = FALSE;

			if (!defined('USER_VALIDATE'))
			{
				$user_id = intval($this->session->userdata('user_id'));
				if ($user_id > 0) $return = TRUE;
			}
			else
			{
				$return = USER_VALIDATE;
			}

			return $return;
		}

		/**
		 * Отримання інформації хедера
		 * @return array
		 */
		public function get_header()
		{
			$result = $this->db->get('component_header')->row_array();
			return $result;
		}


		/**
		 * Отримання інформації про футер
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_footer()
		{
			$this->db->select('*');
			$this->db->where('id', 1);
			return $this->db->get('component_footer')->row_array();
		}

		public function get_componentsMenu(){
			$this->db->select('module, hidden');
			$result = $this->db->get('components')->result_array();
			foreach ($result as $key => $value) {	
				$return[$value['module']] = $value['module'];
				$res[$value['module']] = $value['hidden'];
			}
			
			return array("module" => $return, "hidden" => $res);
		}

	}