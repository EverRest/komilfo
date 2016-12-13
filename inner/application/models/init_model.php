<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	class init_model extends CI_Model
	{
		/**
		 * ID активного пункту меню
		 *
		 * @var null|int
		 */
		private $menu_id = 0;

		/**
		 * ID активних пунктів меню
		 *
		 * @var array
		 */
		private $menu_parents = array(0);

		/**
		 * Головна сторінка
		 *
		 * @var bool
		 */
		private $is_main_page = false;

		/**
		 * Інформація про активний пункт меню
		 *
		 * @var string
		 */
		private $current_menu = array();

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

		################################################################################################################

		/**
		 * Отримання налаштувань з бази даних/кешу
		 */
		public function set_config()
		{
			$config = $this->cache->get('config/db');

			if ($config and !$this->is_admin()) {
				$this->_set_config($config);
			} else {
				$result = $this->db
					->get('config')
					->result_array();

				if (count($result) > 0) {
					$this->cache->save('db', $result, 3600, 'config/');
					$this->_set_config($result);
				} else {
					show_error('Settings not found');
				}
			}
		}

		/**
		 * Отримання налаштувань футтера та хедера
		 */
		public function get_static_information()
		{
			$result = $this->db->select("*, description_".LANG." as description, title_".LANG." as title, email, phone_1, phone_2, phone_3, yt, tw, fb, vk, ig, gp, code, floor_".LANG." as floor, slogan_".LANG." as slogan, address_".LANG." as address")->get_where('component_static_information' , array('id' =>1 ))->row_array();
			return $result;
		}

		/**
		 * Встановлення налаштувань
		 *
		 * @param array $config
		 */
		private function _set_config($config)
		{
			foreach ($config as $row) {
				if ($row['key'] !== 'languages') {
					$this->config->set_item($row['key'], $row['val']);
				} else {
					$this->config->set_item(
						'database_' . $row['key'],
						$row['val'] !== '' ? unserialize(stripslashes($row['val'])) : array()
					);
				}
			}
		}

		################################################################################################################

		/**
		 * Перевірка на зміну url при зверненні до певного компоненту
		 * $this->config->item('allowed_components') компоненти які дозволені
		 *
		 * @return array|null
		 */

		public function get_segments($uri, $component = false, $index = null, $get_last = true)
		{
			$return = array();
			$segments = explode('/', $uri);
			if(count($segments) > 1){
				if($index !== null){
					$return['segment'] = $segments[$index];
				}
				if($get_last){
					if(preg_match("/^[0-9]*$/", $segments[count($segments)-1]) == 1) $return['last_segment'] = $segments[count($segments)-1];	
				}

				if($component){
					$components_array = $this->_get_component_name($segments[0]);
					if(!empty($components_array) && is_array($components_array)){
						foreach ($components_array as $key => $component) {
							if(in_array($component['module'], $this->config->item('allowed_components'))){
								$return['component'] = $component['module'];
							}
						}
					}
				}
			}

			return $return;
		}

		/**
		 * Отримання маршрутизації за посиланням
		 *
		 * @return array|null
		 */
		public function get_routing()
		{
			$routing = null;

			$uri = $this->uri->uri_string();
			$clean_uri = $this->uri->clean_url(array(LANG => 0));


			$segment = $this->get_segments($clean_uri, true, 0, true);
			if(isset($segment['last_segment'])){
				if(isset($segment['component'])){
					$clean_uri = $segment['segment'];
				}
			}

			if ($clean_uri === '') {
				$routing = $this->_get_main_routing();
			} else {
				/*
				$_uri = explode('/', $uri);

				if (array_key_exists(0, $_uri)) {
					$_uri = explode('-', $_uri[0]);

					if (
						array_key_exists(0, $_uri)
						and array_key_exists(1, $_uri)
						and is_numeric($_uri[1])
					) {
						if ((string)$_uri[0] === 'u') {
							$routing = array(
								'profile',
								'view_profile',
								(int)$_uri[1]
							);
						}

						if ((string)$_uri[0] === 'p') {
							$routing = array(
								'catalog',
								'product',
								(int)$_uri[1]
							);
						}

						if ((string)$_uri[0] === 't') {
							$routing = array(
								'club',
								'theme',
								(int)$_uri[1]
							);
						}
					}
				}
				*/

				if ($routing === null) {
					$routing = $this->_get_inner_routing($uri, $clean_uri);
				}
			}


			return $routing;
		}

		/**
		 * Встановлення маршрутизації головної сторінки
		 *
		 * @return array|null
		 */
		private function _get_main_routing()
		{
			$routing = null;

			$result = $this->db
				->select('id, parent_id, level, menu_index, position, main, url_path_id, name_' . LANG . ' as name, url_' . LANG . ' as url, image, icon')
				->where('main', 1)
				->limit(1)
				->get('menu')
				->row_array();

			if ($result !== null) {
				$this->set_main();
				$this->set_menu_id((int)$result['id']);
				$this->current_menu[$result['id']] = $result;

				$routing = array(
					'components',
					'get_components',
					(int)$result['id'],
				);
			}

			return $routing;
		}
		/**
		 * Встановлення маршрутизації головної сторінки
		 *
		 * @return array|null
		 */
		private function _get_component_name($url)
		{
			$routing = null;

			$result = $this->db
				->select('id, parent_id, level, menu_index, position, main, url_path_id, name_' . LANG . ' as name, url_' . LANG . ' as url, image, icon')
				->where('url_'.LANG, $url)
				->limit(1)
				->get('menu')
				->row_array();

			if ($result !== null) {
				$routing = $this->db
					->select('module')
					->where('menu_id', $result['id'])
					->get('components')->result_array();
			}

			return $routing;
		}

		/**
		 * Встановлення маршрутизації для внутрішніх сторінок
		 *
		 * @param $uri
		 * @param $clean_uri
		 * @return array|null
		 */
		private function _get_inner_routing($uri, $clean_uri)
		{
			$routing = null;

			$this->load->helper('form');

			$result = $this->db
				->select('item_id, menu_id, module, method')
				->where_in('hash_' . LANG, array(md5($uri), md5($clean_uri)))
				->limit(1)
				->get('site_links')
				->row_array();

			if ($result !== null) {
				$this->set_menu_id((int)$result['menu_id'], true);

				if ((int)$this->get_current_menu_info('menu_index') === 2 and (int)$this->get_current_menu_info('parent_id') > 0) {
					define('MAP_CITY', $this->get_menu_id());
				}

				if ((int)$this->get_current_menu_info('menu_index') === 3) {
					define('MAP_MARKER', $this->get_menu_id());
				}

				$routing = array(
					$result['module'],
					$result['method'],
					(int)$result['item_id'],
				);
			}

			return $routing;
		}

		################################################################################################################

		/**
		 * Встановлення активного пункту меню
		 *
		 * @param int $menu_id
		 * @param bool $get_info
		 * @return bool
		 */
		public function set_menu_id($menu_id = 0, $get_info = false, $show_404 = true)
		{
			$this->menu_id = (int)$menu_id;

			if ($get_info) {
				$result = $this->db
					->select('parent_id, level, menu_index, position, main, url_path_id, name_' . LANG . ' as name, url_' . LANG . ' as url, image, icon')
					->where('id', $this->menu_id)
					->get('menu')
					->row_array();

				if ($result === null) {
					if ($show_404) {
						show_404();
					}
				} else {
					$this->set_main((int)$result['main'] === 1);
					$path_id = explode('.', trim($result['url_path_id'], '.'));

					$this->set_menu_parents($path_id);
					$this->set_menu_parents(array($this->menu_id));

					$this->current_menu[$menu_id] = $result;

					return true;
				}
			}

			return false;
		}

		/**
		 * Отримання активного пункту меню
		 *
		 * @return int
		 */
		public function get_menu_id()
		{
			if ($this->menu_id === 0) {
				$this->menu_id = (int)$this->input->get('menu_id');
			}

			return $this->menu_id;
		}

		/**
		 * Встановлення активних пунктів меню
		 *
		 * @param array $menu_parents
		 */
		public function set_menu_parents(array $menu_parents)
		{
			foreach ($menu_parents as $key => $parent)
			{
				if (!is_numeric($parent)) {
					unset($menu_parents[$key]);
				}
			}

			$this->menu_parents = array_merge($this->menu_parents, array_map('intval', $menu_parents));
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
		 * Встановлення ознаки головної сторінки
		 * @param bool $flag
		 */
		public function set_main($flag = true)
		{
			$this->is_main_page = $flag;
		}

		/**
		 * Перевірка на головну сторінку
		 *
		 * @return bool
		 */
		public function is_main()
		{
			return $this->is_main_page;
		}

		/**
		 * Отримання id головної сторінки
		 *
		 * @return int
		 **/
		public function get_main_id()
		{
			return (int)$this->db
				->where('main', 1)
				->get('menu')
				->row('id');
		}

		/**
		 * Отримання інформації про активний пункт меню
		 *
		 * @param string $key
		 * @return mixed|null
		 */
		public function get_current_menu_info($key) {
			return
				(
					array_key_exists($this->menu_id, $this->current_menu)
					and array_key_exists($key, $this->current_menu[$this->menu_id])
				)
					? $this->current_menu[$this->menu_id][$key]
					: null;

		}

		/**
		 * Отримання інформації про пункт меню
		 *
		 * @param int $menu_id
		 * @return array|null
		 */
		public function get_menu_info($menu_id)
		{
			return $this->db
				->select('parent_id, level, menu_index, position, main, url_path_id, name_' . LANG . ' as name, url_' . LANG . ' as url, image, icon')
				->where('id', $menu_id)
				->get('menu')
				->row_array();
		}

		#######################################################################################################

		/**
		 * Перевірка наявності компонента на сторінці
		 *
		 * @param int $menu_id
		 * @param string $module
		 * @param null|string $method
		 *
		 * @return bool
		 */
		public function check_component($menu_id, $module, $method = null)
		{
			if ($menu_id > 0) {
				$this->db->where('menu_id', $menu_id);
			}

			$this->db->where('module', $module);

			if ($method !== null) {
				$this->db->where('method', $method);
			}

			return (int)$this->db->count_all_results('components') > 0;
		}

		/**
		 * Отримання посилання на сторінку з потрібним компонентом
		 *
		 * @param string $module
		 * @param null $menu_id
		 * @return string
		 */
		public function get_component_link($module, $menu_id = null, $only_result = false)
		{
			$link = '#';

			$this->db->select('menu.main, menu.url_' . LANG . ' as url, components.*');

			$this->db->where('components.hidden', 0);
			$this->db->where('components.module', $module);

			if ($menu_id !== null) {
				$this->db->where('components.menu_id', $menu_id);
			}

			$this->db->join('menu', 'menu.id = components.menu_id');
			$this->db->where('menu.hidden', 0);

			$result = $this->db->get('components')->row_array();

			if ($result !== null) {
				if($only_result) $link = (int)$result['main'] === 1 ? '' : $result['url']; else $link = (int)$result['main'] === 1 ? $this->uri->full_url() : $this->uri->full_url($result['url']);
			}

			return $link;
		}

		/**
		 * Отримання id компоненту
		 *
		 * @param string $module
		 * @param null|int $menu_id
		 * @return int
		 */
		public function get_component_id($module, $menu_id = null)
		{
			$this->db->select('component_id');

			$this->db->where('hidden', 0);
			$this->db->where('module', $module);

			if ($menu_id !== null) {
				$this->db->where('menu_id', $menu_id);
			}

			$result = $this->db->get('components')->row_array();

			return $result !== null ? (int)$result['component_id'] : 0;
		}

		/**
		 * Отримання налаштувань компоненту
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_component_config($component_id)
		{
			$config = array();

			$component = $this->db
				->select('config')
				->where('component_id', $component_id)
				->get('components')
				->row_array();

			if ($component !== null and $component['config'] !== '') {
				$config = json_decode($component['config'], true);
			}

			return $config;
		}

		/**
		 * Збереження налаштувань компоненту
		 *
		 * @param int $component_id
		 * @param array $set
		 */
		public function set_component_config($component_id, array $set)
		{
			$this->db->update(
				'components',
				array(
					'config' => json_encode($set),
				),
				array(
					'component_id' => $component_id,
				)
			);
		}

		/**
		 * Отримання інфомації про компоненту
		 *
		 * @param int $component_id
		 * @return array|null
		 */
		public function get_component_info($component_id)
		{
			return $this->db
				->where('component_id', $component_id)
				->get('components')
				->row_array();
		}

		#######################################################################################################

		/**
		 * Отримання сегментів візуальної навігації
		 */
		public function set_bread_crumbs()
		{
			if (count($this->menu_parents) > 1) {
				$bread_crumbs = $this->menu_parents;

				unset($bread_crumbs[0]);
				reset($bread_crumbs);

				$this->db->select('main, name_' . LANG . ' as name, url_' . LANG . ' as url, static_url_' . LANG . ' as static_url');

				if (count($bread_crumbs) > 0) {
					if (count($bread_crumbs) > 1) {
						$this->db->where_in('id', $bread_crumbs);
					} else {
						$this->db->where('id', $bread_crumbs[1]);
					}
				}

				$this->db->or_where('main', 1);
				$this->db->order_by('main', 'asc');
				$this->db->order_by('level', 'desc');

				$result = $this->db->get('menu')->result_array();

				if (count($result) > 0) {
					foreach ($result as $val) {
						if ((int)$val['main'] === 1) {
							$this->template_lib->set_bread_crumbs($this->uri->full_url(), $val['name'], 'prepend');
						} else {
							if ($val['static_url'] !== '') {
								if (preg_match('!^\w+://! i', $val['static_url']) === 1) {
									$var['url'] = $val['static_url'];
								} else {
									if (preg_match('!\.\w+$! i', $val['static_url']) === 1) {
										$val['url'] = $this->config->base_url($val['static_url']);
									} else {
										$val['url'] = $this->uri->full_url($val['static_url']);
									}
								}
							} else {
								$val['url'] = $this->uri->full_url($val['url']);
							}

							$this->template_lib->set_bread_crumbs($val['url'], $val['name'], 'prepend');
						}
					}
				}
			} else {
				$result = $this->db
					->select('main, name_' . LANG . ' as name, url_' . LANG . ' as url')
					->where('main', 1)
					->get('menu')
					->row_array();

				if ($result !== null) {
					$this->template_lib->set_bread_crumbs($this->uri->full_url(), $result['name'], 'prepend');
				}
			}
		}

		#######################################################################################################

		/**
		 * Отримання посилання через ID пункту меню
		 *
		 * @param int $menu_id
		 * @param string $template
		 * @param bool $br
		 * @param bool $hidden
		 * @param null|string $language
		 *
		 * @return string
		 */
		public function get_link($menu_id, $template, $br = false, $hidden = false, $language = null)
		{
			if ($language === '' or ($language === null and LANG === DEF_LANG)) {
				$language = (string)$this->config->item('def_lang');
				$_language = '';
			} else if ($language === null AND LANG !== DEF_LANG) {
				$language = LANG_SEGMENT;
				$_language = LANG_SEGMENT;
			} else {
				$_language = $language;
			}

			if ($hidden === false) {
				$this->db->where('hidden', 0);
			}
			

			$result = $this->db
				->select('main, target, name_' . $language . ' as name, url_' . $language . ' as url, static_url_' . $language . ' as static_url')
				->where('id', $menu_id)
				->get('menu')
				->row_array();

			if ($result !== null) {
				if ($br === true) {
					$result['name'] = str_replace(' ', '<br>', $result['name']);
				}

				if ((int)$result['main'] === 1) {
					$url = $this->uri->full_url('', $_language);
				} else {
					if ($result['static_url'] !== '') {
						if (preg_match('!^\w+://! i', $result['static_url']) === 1) {
							$url = $result['static_url'];
						} else {
							if (preg_match('!\.\w+$! i', $result['static_url']) === 1) {
								$url = $this->config->base_url($result['static_url']);
							} else {
								$url = $this->uri->full_url($result['static_url']);
							}
						}
					} else {
						$url = $this->uri->full_url($result['url'], $_language);
					}
				}

				$template = str_replace(array('{URL}', '{NAME}'), array($url, $result['name']), $template);
			}

			return $template;
		}

		#######################################################################################################

		/**
		 * Встановлення метатегів сторінки
		 */
		public function set_metatags()
		{
			$is_set = false;

			$c = $this->db
				->where('menu_id', $this->menu_id)
				->count_all_results('seo_tags');

			if ((int)$c === 0) {
				$this->db->insert(
					'seo_tags',
					array(
						'item_id' => 0,
						'component_id' => 0,
						'menu_id' => $this->menu_id,
					)
				);
			}

			$result = $this->db
				->select('type_' . LANG . ' as type, title_' . LANG . ' as title, description_' . LANG . ' as description, keywords_' . LANG . ' as keywords')
				->select('cache_title_' . LANG . ' as cache_title, cache_description_' . LANG . ' as cache_description, cache_keywords_' . LANG . ' as cache_keywords, cache_' . LANG . ' as cache')
				->where('menu_id', $this->menu_id)
				->where('item_id', 0)
				->get('seo_tags')
				->row_array();

			if ($result !== null) {
				if ((int)$result['type'] === 1) {
					if ($result['title'] !== '') {
						$this->template_lib
							->set_title($result['title'])
							->set_description($result['description'])
							->set_keywords($result['keywords']);

						$is_set = true;
					}
				} else {
					if ((int)$result['cache'] === 1 and $result['cache_title'] !== '' and !$this->is_admin()) {
						$this->template_lib
							->set_title($result['cache_title'])
							->set_description($result['cache_description'])
							->set_keywords($result['cache_keywords']);

						$is_set = true;
					} else {
						$result = $this->db
							->select('component_article.title_' . LANG . ' as title, component_article.text_' . LANG . ' as text')

							->join('components', 'components.component_id = component_article.component_id')
							->where('component_article.menu_id', $this->menu_id)
							->where('components.hidden', 0)

							->order_by('components.position')
							->get('component_article')
							->result_array();

						if (count($result) > 0) {
							$this->load->library('seo_lib');

							$title = '';
							$text = '';

							foreach ($result as $key => $row)
							{
								if ($key === 0) {
									$title = $row['title'];
								}

								$text .= $row['title'] . ' ' . $row['text'] . ' ';
							}

							if (trim($title) !== '' or trim($text) !== '') {
								$description = $this->seo_lib->generate_description($text);
								$keywords = $this->seo_lib->generate_keywords($text);

								$this->template_lib
									->set_title($title)
									->set_description($description)
									->set_keywords($keywords);

								$this->db->update(
									'seo_tags',
									array(
										'cache_title_' . LANG => $title,
										'cache_description_' . LANG => $description,
										'cache_keywords_' . LANG => $keywords,
										'cache_' . LANG => 1,
									),
									array(
										'menu_id' => $this->menu_id,
										'item_id' => 0,
									)
								);

								$is_set = true;
							}
						}
					}
				}
			}

			if ($is_set === false) {
				if (
					array_key_exists($this->menu_id, $this->current_menu)
					and array_key_exists('name', $this->current_menu[$this->menu_id])
				) {
					$title = $this->current_menu[$this->menu_id]['name'];
				} else {
					$title = $this->config->item('site_name_' . LANG);
				}

				$this->template_lib->set_title($title);

				$this->db->update(
					'seo_tags',
					array(
						'cache_title_' . LANG => $title,
						'cache_description_' . LANG => '',
						'cache_keywords_' . LANG => '',
						'cache_' . LANG => 1,
					),
					array(
						'menu_id' => $this->menu_id,
						'item_id' => 0,
					)
				);
			}
		}

		################################################################################################################

		/**
		 * Перевірка залогованості та прав доступу адміністратора
		 *
		 * @param string $return_type
		 * @return bool
		 */
		public function is_admin($return_type = '')
		{
			if (!defined('IS_ADMIN'))
			{
				$check_result = false;
				$admin_id = (int)$this->session->userdata('admin_id');
				$admin_key = $this->session->userdata('admin_key');

				if ($admin_id > 0 and mb_strlen($admin_key) === 32) {
					$admin = $this->db
						->select('password, site_menu, admin_menu')
						->where('admin_id', $admin_id)
						->limit(1)
						->get('administrators')
						->row_array();

					if (is_array($admin) and array_key_exists('password', $admin)) {
						$key = md5($admin_id . $admin['password'] . $this->config->item('encryption_key') . $this->input->user_agent());

						if ($admin_key === $key) {
							$check_result = true;

							$this->site_menu_rules = explode(',', $admin['site_menu']);
							$this->session->set_userdata('site_menu_rules', $this->site_menu_rules);

							$this->admin_menu_rules = explode(',', $admin['admin_menu']);
							$this->session->set_userdata('admin_menu_rules', $this->admin_menu_rules);

							$this->input->set_cookie(
								array(
									'name' => 'manager_bridge',
									'value' => $admin_key,
									'expire' => 0,
									'path' => '/',
									'httponly' => true,
								)
							);
						}
					}
				}

				define('IS_ADMIN', $check_result);
			}

			if (!IS_ADMIN and $return_type === 'redirect') {
				redirect($this->uri->full_url('admin/login/index'));
			}

			return IS_ADMIN;
		}

		/**
		 * Перевірка на прихованість панелі адміна
		 *
		 * @return bool
		 */
		public function is_panel_hidden()
		{
			return (int)$this->session->userdata('hide_adm_panel') === 1;
		}

		/**
		 * Перевірка прав доступа
		 *
		 * @param null $admin_rules
		 * @param null $site_rules
		 * @param bool $root_only
		 * @return bool
		 */
		public function check_access($admin_rules = null, $site_rules = null, $root_only = false)
		{
			$return = true;

			if ((int)$this->session->userdata('admin_root') === 1) {
				return $return;
			}

			if ($admin_rules !== null) {
				$_admin_rules = explode('|', $admin_rules);

				foreach ($_admin_rules as $v) {
					if (!in_array($v, $this->admin_menu_rules, true)) {
						$return = false;
					}
				}
			}

			if ($site_rules !== null) {
				$_site_rules = explode('|', $site_rules);

				foreach ($_site_rules as $v) {
					if (!in_array($v, $this->site_menu_rules, true)) {
						$return = false;
					}
				}
			}

			if ($root_only and $return and (int)$this->session->userdata('admin_root') !== 1) {
				$return = false;
			}

			return $return;
		}

		#######################################################################################################

		/**
		 * Отримання заглушки
		 *
		 * @return string
		 */
		public function get_gag()
		{
			$text = (string)$this->db
				->select('text')
				->where('lang', LANG)
				->get('gag')
				->row('text');

			return $text !== '' ? stripslashes($text) : $text;
		}
	}