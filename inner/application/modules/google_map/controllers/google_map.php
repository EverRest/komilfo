<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Google_map
	 *
	 * @property Google_map_model $google_map_model
	 */

	class Google_map extends MX_Controller {

		public function index($menu_id, $component_id, $hidden, $config = '')
		{
			$this->load->model('google_map_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
				'map' => $this->google_map_model->get_map($component_id),
				'h1' => $this->template_lib->get_h1()
			);

			if (
				$this->init_model->is_admin()
				and !$this->init_model->is_panel_hidden()
				and $this->init_model->check_access('google_map_index', $menu_id)
			) {
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/google_map_tpl', $template_data, TRUE);
			} else {
				return $this->load->view('google_map_tpl', $template_data, TRUE);
			}
		}
	}