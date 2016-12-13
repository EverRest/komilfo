<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Init
	 *
	 * @property Cart_model $cart_model
	 */
	class Init extends MX_Controller {

		function __construct()
		{
			parent::__construct();

			//if (!$this->input->is_ajax_request()) $this->output->enable_profiler();

			$this->load->database();
			$this->load->driver('cache', array('adapter' => 'file'));
			$this->load->driver('session');
			$this->load->model('init_model');

			$this->init_model->set_config();
			$this->uri->set_language();

			$this->load->library('template_lib');
			$this->load->helper('url');

			$this->output->set_header('HTTP/1.0 200 OK');
			$this->output->set_header('Content-Type: text/html; charset=UTF-8');
			$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT');
			$this->output->set_header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT');
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
			$this->output->set_header('Cache-Control: post-check=0, pre-check=0');
			$this->output->set_header('Pragma: no-cache');
		}

		public function index()
		{

			$result = NULL;

			$frontend_modules = $this->config->item('frontend_modules');
			$backend_modules = $this->config->item('backend_modules');

			$module_index = 1;
			$method_index = 2;

			if ($this->config->item('multi_languages') AND defined('LANG_SEGMENT'))
			{
				$module_index++;
				$method_index++;
			}

			$module = $this->uri->segment($module_index);
			$method = $this->uri->segment($method_index);

			if ($module == 'admin')
			{
				$module_index++;
				$method_index++;

				$module = $this->uri->segment($module_index);
				$method = $this->uri->segment($method_index);

				if (in_array($module, $backend_modules)) $result = Modules::run($module . '/admin_' . $module . '/' . $method);
			}
			elseif (in_array($module, $frontend_modules) AND $method !== NULL)
			{
				$result = Modules::run($module . '/' . $method);

				if ($result === NULL)
				{
					$route = $this->init_model->get_routing();
					$result = Modules::run($route[0] . '/' . $route[1], $route[2]);
				}
			}
			else
			{
				$route = $this->init_model->get_routing();
				$result = Modules::run($route[0] . '/' . $route[1], $route[2]);
			}

			if ($result === NULL) show_404();

			if ($this->input->is_ajax_request() OR $this->config->item('is_ajax_request'))
			{
				$this->output->set_output($result);
			}
			else
			{
				// Meta tags
				if (!$this->template_lib->is_metatags()) $this->init_model->set_metatags();

				/*
				 Menu
				Modules::run('menu/set_menus');

				 Bread crumbs navigation
				$this->init_model->set_bread_crumbs();
				$this->template_lib->get_bread_crumbs();
				*/

				/*Отримуємо основні компонентів сайту*/

				$this->template_lib->set_template_var('header_data',$this->init_model->get_header());
				$this->template_lib->set_template_var('footer_data',$this->init_model->get_footer());
				$data_menu = $this->init_model->get_componentsMenu();
				$this->template_lib->set_template_var('menu', $data_menu['module']);
				$this->template_lib->set_template_var('menu_hidden', $data_menu['hidden']);


				if ($this->init_model->is_admin())
				{
					$template_vars = array(
						'menu_id' => $this->init_model->get_menu_id(),
						'active' => $this->template_lib->get_admin_menu_active(),
						'admin_menu' => include APPPATH . 'modules/menu/config/admin_menu.php',
						'admin_menu_rules' => $this->session->userdata('admin_menu_rules'),
						'page_content' => $this->template_lib->get_content()
					);
                                        
					$page_content = $this->load->view('admin_menu_tpl', $template_vars, TRUE);
					$this->template_lib->set_content($page_content);

					$this->load->view($this->template_lib->get_template(), $this->template_lib->get_template_vars());
				}
				else
				{
					$template_vars = $this->template_lib->get_template_vars();
					$this->load->view($this->template_lib->get_template(), $template_vars);

				}
			}
		}
	}