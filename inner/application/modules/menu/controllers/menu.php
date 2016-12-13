<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Menu
	 */
	class Menu extends MX_Controller {

		private $is_main;
		private $menu_id;
		private $menu_parents;

		/**
		 * Отримання всіх меню сайту
		 */
		public function set_menus()
		{
			$this->is_main = $this->init_model->is_main();
			$this->menu_id = $this->init_model->get_menu_id();
			$this->menu_parents = $this->init_model->get_menu_parents();

			$this->load->model('menu_model');
			$this->load->helper('menu');

			$this->_get_top_menu();
			// $this->_get_catalog_menu();
			$this->_get_footer_menu();
			$this->_get_side_menu();
		}

		/**
		 * Меню в хедері
		 */
		private function _get_top_menu()
		{
			$this->template_lib->set_template_var(
				'top_menu',
				$this->load->view(
					'top_menu_tpl',
					array(
						'parents' => $this->menu_parents,
						'menu' => $this->menu_model->get_menu_tree(1, 0),
						'is_main' => $this->is_main,
					),
					true
				)
			);
			$this->template_lib->set_template_var(
				'adaptive_main', 
				$this->load->view(
					'adaptive_main_menu_tpl', 
					array(
						'parents' => $this->menu_parents,
						'menu' => $this->menu_model->get_menu_tree(1, 0),
						'is_main' => $this->is_main,
					), 
					TRUE
				)
			);
		}

		/**
		 * Меню catalog
		 */
		public function get_catalog_menu()
		{
			$this->is_main = $this->init_model->is_main();
			$this->menu_id = $this->init_model->get_menu_id();
			$this->menu_parents = $this->init_model->get_menu_parents();
			$this->load->model('menu_model');
			$this->load->helper('menu');
			$parent_id = $this->menu_model->get_parent_id($this->menu_id);
			
			return $this->load->view(
				'catalog_menu_tpl',
				array(
					'parents' => $this->menu_parents,
					'menu' => $this->menu_model->get_menu_tree(1, $parent_id),
					'is_main' => $this->is_main,
				),
				true
			);
		}

		/**
		 * Меню в лівій колонці
		 */
		private function _get_side_menu()
		{
		
			$this->template_lib->set_template_var(
				'side_menu',
				$this->load->view(
					'side_menu_tpl',
					array(
						'parents' => $this->menu_parents,
						'menu' => $this->menu_model->get_menu_tree(1),
						'is_main' => $this->is_main,
						'menu_id' => $this->init_model->get_menu_id()
					),
					true
				)
			);
		
		}

		/**
		 * Меню футеру
		 */
		private function _get_footer_menu()
		{
			$this->template_lib->set_template_var(
				'footer_menu',
				$this->load->view(
					'footer_menu_tpl',
					array(
						'parents' => $this->menu_parents,
						'menu' => $this->menu_model->get_menu_tree(2, 0, array('id !=' => 572)),
						'is_main' => $this->is_main,
					),
					true
				)
			);
		}
	}