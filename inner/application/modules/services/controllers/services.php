<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class services
	 *
	 * @property services_model $services_model
	 */
	class Services extends MX_Controller {

		/**
		 * Вивід текстового компоненту
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param string $config
		 * @return string
		 */
		public function index($menu_id, $component_id, $hidden, $config = '')
		{
			$this->load->model('services_model');
            $this->load->model('menu/menu_model');

			$template_data = array(
				'menu_id' => $menu_id,
                'menu' => $this->menu_model->get_menu_tree(2),
				'component_id' => $component_id,
				'services' => $this->services_model->get_services($component_id),
				'h1' => $this->template_lib->get_h1()
			);

			$this->template_lib->set_h1();

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('services_index', $menu_id)
			) {
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/services_tpl', $template_data, TRUE);
			} else {
				return $this->load->view('services_tpl', $template_data, TRUE);
			}
		}
	}