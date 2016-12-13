<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Article
	 *
	 * @property Benefit_services_model $article_model
	 */
	class Loyalty_system extends MX_Controller {

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
			$this->load->model('loyalty_system_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
			);

			if ($this->init_model->is_admin())
			{
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/loyalty_system_tpl', $template_data, TRUE);
			}
			else
			{
				return $this->load->view('loyalty_system_tpl', $template_data, TRUE);
			}
		}

		public function popup_desc()
		{
			$response = array('success' => false);

			$news_id = intval($this->input->post('id', true));

			if($news_id > 0)
			{
				$this->load->model('loyalty_system_model');
				$result = $this->loyalty_system_model->get_text();
				
				$response['text'] = stripslashes($result['text']);
				$response['success'] = true;
			}

			return json_encode($response);
		}


	}