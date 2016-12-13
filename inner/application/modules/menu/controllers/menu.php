<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Menu extends MX_Controller {

		private $seo_link;
		//private $menu_cache = array();
		private $menu_id = 0;
		private $menu_parents = array();

		/**
		 * Отримання всіх меню сайту
		 */
		public function set_menus()
		{
			$this->menu_id = $this->init_model->get_menu_id();
			$this->menu_parents = $this->init_model->get_menu_parents();

			$this->load->model('menu_model');
			$this->load->helper('menu');

			$this->seo_link = $this->menu_model->get_seo_link($this->menu_id);

			$this->_get_bottom_menu();
		}

		/**
		 * Меню сайту
		 */
		private function _get_bottom_menu()
		{
			$this->menu_id = $this->init_model->get_menu_id();
			$this->menu_parents = $this->init_model->get_menu_parents();

			$this->load->model('menu_model');
			$this->load->helper('menu');

			$this->seo_link = $this->menu_model->get_seo_link($this->menu_id);

			$template_vars = array(
				'parents' => $this->menu_parents,
				'menu' => $this->menu_model->get_menu_tree(1),
				'seo_link' => $this->seo_link,
				'is_main' => $this->init_model->is_main()
			);
			$this->template_lib->set_template_var('bottom_menu', $this->load->view('bottom_menu_tpl', $template_vars, TRUE));
		}
	}