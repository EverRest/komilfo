<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Benefits
	 *
	 * @property benefits_model $benefits_model
	 */
	class Benefits extends MX_Controller {

		/**
		 * Вивід текстового компоненту
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param string $config
		 */
		public function index($menu_id, $component_id, $hidden, $config = '')
		{
			$this->load->model('benefits_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
				'benefits' => $this->benefits_model->get_benefits($component_id),
				'h1' => $this->template_lib->get_h1()
			);

			$this->template_lib->set_h1();

			if ($this->init_model->is_admin())
			{
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/benefits_tpl', $template_data, TRUE);
			}
			else
			{
				return $this->load->view('benefits_tpl', $template_data, TRUE);
			}
		}
	}