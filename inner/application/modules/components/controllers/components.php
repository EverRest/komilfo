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
			$menu_id = intval($menu_id);
			if ($menu_id == 0) show_404();

			$this->load->model('components_model');

			$components = $this->components_model->get_components($menu_id);

			if ($components !== NULL)
			{
				$mode = intval($this->input->get('mode'));

				foreach ($components as $component)
				{
					if ($component['module'] != 'catalog' OR ($component['module'] == 'catalog' AND $mode == 0))
					{
						if ($component['config'] != '') $component['config'] = unserialize($component['config']);
						$result = Modules::run($component['module'] . '/' . $component['method'], $menu_id, $component['component_id'], $component['hidden'], $component['config']);
                                                $this->template_lib->set_content($result, 'append');            
					}
				}
			}
		}

	}
