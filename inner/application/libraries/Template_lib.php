<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Template_lib {

		protected $ci;

		/**
		 * Головний шаблон сайту
		 *
		 * @var string
		 */
		private $template = 'site_main_tpl';

		/**
		 * Активні пункти меню адмністратора
		 *
		 * @var array
		 */
		private $admin_menu_active = array('top_level' => 'components', 'sub_level' => 'components');

		/**
		 * Змінні для передачі в шаблон
		 *
		 * @var array
		 */
		private $template_vars = array();

		/**
		 * Наявність тегу H1
		 *
		 * @var bool
		 */
		private $is_h1 = FALSE;

		/**
		 * Наявність метатегів
		 *
		 * @var bool
		 */
		private $is_metatags = FALSE;

		/**
		 * Конструктор класу
		 */
		public function __construct()
		{
			$this->ci = & get_instance();
			$this->ci->load->helper('form');

			$this->template_vars = array(
				'page_title' => '',
				'page_description' => '',
				'page_keywords' => '',
				'page_js' => array(),
				'page_css' => array(),
				'bread_crumbs' => array(),
				'page_content' => array()
			);
		}

		/**
		 * Підключення css файлу
		 *
		 * @param string $location
		 * @param bool $css_dir
		 */
		public function set_css($location = '', $css_dir = FALSE)
		{
			if ($location !== '')
			{
				if (!$css_dir) $location = base_url('css/' . $location);
				$this->template_vars['page_css'][md5($location)] = sprintf('<link href="%s" rel="stylesheet" type="text/css">', $location);
			}
		}

		/**
		 * Підключення javascript файлу
		 *
		 * @param string $location
		 * @param bool $js_dir
		 */
		public function set_js($location = '', $js_dir = TRUE)
		{
			if ($location !== '')
			{
				if ($js_dir) $location = base_url('js/' . $location);
				$this->template_vars['page_javascript'][md5($location)] = sprintf('<script type="text/javascript" src="%s"></script>', $location);
			}
		}

		/**
		 * Встановлення назви сторінки
		 *
		 * @param string $title
		 */
		public function set_title($title = '')
		{
			$this->template_vars['page_title'] = form_prep($title);
			$this->is_metatags = TRUE;
		}

		/**
		 * Встановлення опису сторінки
		 *
		 * @param string $description
		 */
		public function set_description($description = '')
		{
			$this->template_vars['page_description'] = form_prep($description);
			$this->is_metatags = TRUE;
		}

		/**
		 * Встановлення ключових слів сторінки
		 *
		 * @param string $keywords
		 */
		public function set_keywords($keywords = '')
		{
			$this->template_vars['page_keywords'] = form_prep($keywords);
			$this->is_metatags = TRUE;
		}

		/**
		 * Перевірка наявності метатегів
		 *
		 * @return mixed
		 */
		public function is_metatags()
		{
			return $this->is_metatags;
		}

		/**
		 * Додавання сегменту до візуальної навігації
		 *
		 * @param string $url
		 * @param string $name
		 * @param string $mode
		 */
		public function set_bread_crumbs($url = '', $name = '', $mode = 'append')
		{
			if ($mode == 'prepend')
			{
				array_unshift($this->template_vars['bread_crumbs'], array($url, $name));
			}
			else
			{
				$this->template_vars['bread_crumbs'][] = array($url, $name);
			}
		}

		/**
		 * Отримання навыгації
		 *
		 * @return array
		 */
		public function get_bread_crumbs()
		{
			$bread_crumbs = $this->template_vars['bread_crumbs'];
			$last = array_pop($bread_crumbs);

			if (count($bread_crumbs) > 0)
			{
				foreach ($bread_crumbs as $key => $segment)
				{
					$bread_crumbs[$key] = '<a href="' . $segment[0] . '">' . $segment[1] . '</a>';
				}

				//$bread_crumbs[] = '';

				$this->template_vars['bread_crumbs'] = array(
					'navigation' => $bread_crumbs,
					'current_name' => $last[1],
				);
			}
			else
			{
				$this->template_vars['bread_crumbs'] = array();
			}
		}

		/**
		 * Встановлення наявності тегу H1
		 */
		public function set_h1()
		{
			$this->is_h1 = TRUE;
		}

		/**
		 * Отримання наявності тегу H1
		 *
		 * @return bool
		 */
		public function get_h1()
		{
			return $this->is_h1;
		}

		/**
		 * Встановлення/додавання основоного контенту сторінки
		 *
		 * @param string $content
		 * @param string $mode
		 */
		public function set_content($content = '', $mode = '')
		{
			if ($mode == 'prepend')
			{
				array_unshift($this->template_vars['page_content'], $content);
			}
			elseif ($mode == 'append')
			{
				$this->template_vars['page_content'][] = $content;
			}
			else
			{
				$this->template_vars['page_content'] = array($content);
			}
		}

		/**
		 * Отримання поточного основного контенту сторінки
		 *
		 * @return string
		 */
		public function get_content()
		{
			return (isset($this->template_vars['page_content']) AND is_array($this->template_vars['page_content'])) ? implode($this->template_vars['page_content'], "\n") : '';
		}

		/**
		 * Встановлення довільної змінної шаблону
		 *
		 * @param string $key
		 * @param string $val
		 */
		public function set_template_var($key = '', $val = '')
		{
			$this->template_vars[$key] = $val;
		}

		/**
		 * Отримання довільної змінної шаблону
		 *
		 * @param string $key
		 * @return null
		 */
		public function get_template_var($key = '')
		{
			return isset($this->template_vars[$key]) ? $this->template_vars[$key] : NULL;
		}

		/**
		 * Отримання змінних шаблону
		 *
		 * @return array
		 */
		public function get_template_vars()
		{
			$this->template_vars['page_css'] = isset($this->template_vars['page_css']) ? implode("\n\t\t", $this->template_vars['page_css']) . "\n" : '';
			$this->template_vars['page_javascript'] = isset($this->template_vars['page_javascript']) ? implode("\n\t\t", $this->template_vars['page_javascript']) . "\n" : '';
			$this->template_vars['page_content'] = $this->get_content();

			return $this->template_vars;
		}

		/**
		 * Встановлення головного шаблону сайту
		 *
		 * @param string $template
		 */
		public function set_template($template = '')
		{
			$this->template = $template;
		}

		/**
		 * Отримання поточного головного шаблону сайту
		 *
		 * @return string
		 */
		public function get_template()
		{
			return $this->template;
		}

		###################################################################################################################################

		/**
		 * Встановлення активних пунктів меню адміністратора
		 *
		 * @param string $menu_item
		 * @param string $menu_level
		 */
		public function set_admin_menu_active($menu_item = '', $menu_level = 'top_level')
		{
			$this->admin_menu_active[$menu_level] = $menu_item;
		}

		/**
		 * Отримання активних пунктів меню адміністратора
		 *
		 * @return array
		 */
		public function get_admin_menu_active()
		{
			return $this->admin_menu_active;
		}
	}