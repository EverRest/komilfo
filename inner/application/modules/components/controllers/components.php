<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Components extends MX_Controller {

		/**
		 * Отримання компонентів сторінки
		 *
		 * @param int $menu_id
		 */
		public function get_components($menu_id = 0)
		{
			$menu_id = (int)$menu_id;

			if ($menu_id === 0) {
				show_404();
			}

			$this->load->model('components_model');

			$page = (int)$this->input->get('page');
			$url_filters = $this->uri->get_url_filters();

			$components = $this->components_model->get_components($menu_id, $page, $url_filters);

			if (count($components) > 0) {
				foreach ($components as $component) {
					if ($component['config'] !== '') {
						$component['config'] = json_decode($component['config'], true);
					} else {
						$component['config'] = array();
					}

					$result = Modules::run(
						$component['module'] . '/' . $component['method'],
						$menu_id,
						$component['component_id'],
						$component['hidden'],
						$component['config']
					);

					if ($result !== null and $result !== '') {
						$this->template_lib->set_content($result, 'append');
					}
				}
			}
		}
	}
