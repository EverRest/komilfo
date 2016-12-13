<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Article
	 *
	 * @property Benefit_services_model $article_model
	 */
	class Questions extends MX_Controller {

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
			$this->load->model('questions_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
				'header_data' => $this->init_model->get_header()
			);

			if ($this->init_model->is_admin())
			{
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/questions_tpl', $template_data, TRUE);
			}
			else
			{
				return $this->load->view('questions_tpl', $template_data, TRUE);
			}
		}

	}