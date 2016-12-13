<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_feedback
	 *
	 * @property Admin_feedback_model $admin_feedback_model
	 */

	class Admin_feedback extends MX_Controller {

		private $per_page = 10;

		/**
		 * Отримання повідомлень
		 *
		 * @return string
		 */
		public function get()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('feedback_index', $menu_id) AND $menu_id > 0)
			{
				$page = intval($this->input->post('page'));

				if ($page >= 0)
				{
					$this->load->model('admin_feedback_model');
					$response['messages'] = $this->load->view('admin/list_tpl', array('messages' => $this->admin_feedback_model->get_messages(0, $page, $this->per_page)), TRUE);

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Видалення повідомлення
		 */
		public function delete()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$message_id = $this->input->post('message_id');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('feedback_index', $menu_id) AND $menu_id > 0 AND $message_id > 0)
			{
				$this->load->model('admin_feedback_model');
				$this->admin_feedback_model->delete($message_id);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Видалення компоненту
		 */
		public function delete_component()
		{
			$response = array('error' => 1);
			$menu_id = intval($this->input->post('menu_id'));
			$component_id = $this->input->post('component_id');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('feedback_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_feedback_model');
				$this->admin_feedback_model->delete_component($component_id);

				$response['error'] = 0;
				$response['success'] = true;
			}

			return json_encode($response);
		}

	}