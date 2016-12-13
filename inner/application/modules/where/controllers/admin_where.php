<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_where
	 *
	 * @property Admin_where_model $admin_where_model
	 */
	class Admin_where extends MX_Controller {
		/**
		 * Видалення компоненту
		 */
		public function delete_component()
		{
			$response = array(
				'success' => false,
			);

			$component_id = (int)$this->input->post('component_id');

			if ($this->init_model->is_admin() and $this->init_model->check_access('where_index') and $component_id > 0)
			{
				$this->load->model('admin_where_model');
				$this->admin_where_model->delete_component($component_id);

				$response['success'] = true;
			}

			return json_encode($response);
		}

	}