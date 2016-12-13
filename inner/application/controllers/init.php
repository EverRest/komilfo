<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Init
	 *
	 */
	class Init extends MX_Controller
	{
		/**
		 *
		 */
		public function __construct()
		{
			parent::__construct();

			$this->output->set_header('HTTP/1.0 200 OK');
			$this->output->set_header('Content-Type: text/html; charset=UTF-8');
			$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT');
			$this->output->set_header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT');
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
			$this->output->set_header('Cache-Control: post-check=0, pre-check=0');
			$this->output->set_header('Pragma: no-cache');

			if (!$this->input->is_ajax_request() and $this->input->server('REMOTE_ADDR') === '194.44.233.232') {
				$this->output->enable_profiler();
			}

			$this->load->database();
			$this->load->driver('cache', array('adapter' => 'file'));
			$this->load->driver('session');
			$this->load->model('init_model');

			$this->init_model->set_config();
			$this->uri->set_language();

			$this->load->language('global', LANG);

			$this->load->library('template_lib');
			$this->load->helper('language');
			$this->load->helper('url');
			$this->load->helper('directory');
			$this->load->helper('functions');

			$is_open = $this->input->get('is_open');
			if ($is_open !== null) {
				$this->session->set_userdata('open_site', true);
			}
		}

		/**
		 *
		 */
		public function index()
		{
			$result = null;

			$frontend_modules = $this->config->item('frontend_modules');
			$backend_modules = $this->config->item('backend_modules');

			$module_index = 1;
			$method_index = 2;

			if ($this->config->item('multi_languages') and LANG !== DEF_LANG) {
				$module_index++;
				$method_index++;
			}

			$module = str_replace(array('.', '..'), '', $this->uri->segment($module_index, ''));
			$method = str_replace(array('.', '..'), '', $this->uri->segment($method_index, ''));

			if ($module === 'admin') {
				$module_index++;
				$method_index++;

				$module = str_replace(array('.', '..'), '', $this->uri->segment($module_index, ''));
				$method = str_replace(array('.', '..'), '', $this->uri->segment($method_index, ''));

				if (in_array($module, $backend_modules, true)) {
					$result = Modules::run($module . '/admin_' . $module . '/' . $method);
				}
			} else {
				if (in_array($module, $frontend_modules, true) and $method !== 'index') {
					$result = Modules::run($module . '/' . ($method !== '' ? $method : 'index'));
				} else {
					$route = $this->init_model->get_routing();

					if (is_array($route)) {
						$result = Modules::run($route[0] . '/' . $route[1], $route[2]);
					}
				}
			}

			if ($result === null) {
				show_404();
			} else {
				if (
					(int)$this->config->item('is_gag') === 1
					and !$this->init_model->is_admin()
					and !$this->session->userdata('open_site')
				) {
					$result = $this->init_model->get_gag();
					$this->config->set_item('is_ajax_request', true);
				}
			}

			if ($this->input->is_ajax_request() or $this->config->item('is_ajax_request')) {
				$this->output->set_output($result);
			} else {
				// Meta tags
				if (!$this->template_lib->is_metatags()) {
					$this->init_model->set_metatags();
				}
				//slider
				// Modules::run('slider/set_slider');
				// Menu
				Modules::run('menu/set_menus');
				//sliders
				Modules::run('sliders/set_sliders');

				$this->template_lib->set_template_var('static', $this->init_model->get_static_information());

				// Bread crumbs navigation
				$this->init_model->set_bread_crumbs();
				$this->template_lib->get_bread_crumbs();
				$this->template_lib->get_template_var('bread_crumbs');
				// $article = $this->db->select('*')->where('component_id !=', 2)->where('component_id !=', 3)->get('component_article')->result_array();
			
				// foreach ($article as $key => $value) {
				// 	$this->db->insert('components', array('component_id' => $value['component_id'], 'menu_id' => $value['menu_id'], 'module' => 'article', 'method' => 'index'));
				// }

				if (
					$this->init_model->is_admin()
					and !$this->init_model->is_panel_hidden()
					and $this->init_model->check_access(null, $this->init_model->get_menu_id())
				) {
					$this->template_lib->set_content(
						$this->load->view(
							'admin/admin_menu_tpl',
							array(
								'menu_id' => $this->init_model->get_menu_id(),
								'active' => $this->template_lib->get_admin_menu_active(),
								'admin_menu' => include APPPATH . 'modules/menu/config/admin_menu.php',
								'admin_menu_rules' => $this->session->userdata('admin_menu_rules'),
								'page_content' => $this->template_lib->get_content()
							),
							true
						)
					);
				}

				$this->output->set_output(
					$this->load->view(
						$this->template_lib->get_template(),
						$this->template_lib->get_template_vars(),
						true
					)
				);
			}
		}
	}