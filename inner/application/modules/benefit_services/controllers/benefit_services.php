<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Benefit_services
	 *
	 * @property Benefit_services_model $benefit_services
	 */
	class Benefit_services extends MX_Controller {

		/*
		 * Вивід  компоненту
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param string $config
		 */

		public function index($menu_id, $component_id, $hidden, $config = '')
		{
			$this->load->model('benefit_services_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
				'article' => $this->benefit_services_model->get_article(),
			);

			if ($this->init_model->is_admin())
			{
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/benefit_services_tpl', $template_data, TRUE);
			}
			else
			{
				return $this->load->view('benefit_services_tpl', $template_data, TRUE);
			}
		}

		/**
		 * Дані компонента (компоненту лендінга)
		 */

		public function get_data()
		{
			$response = array('success' => FALSE);
			$this->load->model('benefit_services_model');
			$index = $this->input->post("index");
			$type = $this->input->post("type");

			if($index > 0)
			{
				$result = $this->benefit_services_model->get_data();

				$response['success'] = TRUE;

				if($type==1)
				{
					$response['result'] = stripslashes($result['m_'.$index.'_'.$index]);
				}else
				{
					$response['result'] = stripslashes($result['m'.$index]);
				}
			}

			return json_encode($response);
		}

	}