<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_benefit_services
	 *
	 * @property Admin_article_model $admin_article_model
	 */
	class Admin_loyalty_system extends MX_Controller {

		/**
		 * Редагування статті
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));
			$component_id = intval($this->input->get('component_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('loyalty_system_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_loyalty_system_model');
				$this->template_lib->set_title('Редагування компонента');
				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->template_lib->set_admin_menu_active('-');

				$template_data = array(
					'menu_id' => $menu_id,
					'component_id' => $component_id,
					'text' => $this->admin_loyalty_system_model->get_text(),
					'languages' => $this->config->item('languages'),
				);

				$this->template_lib->set_content($this->load->view('admin/edit_tpl', $template_data, TRUE));
			}
			else
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження статті
		 */
		public function update_article()
		{

			$response = array('error' => 1);

			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('loyalty_system_index', $menu_id))
			{
				$this->load->model('admin_loyalty_system_model');

				$this->admin_loyalty_system_model->set_data($this->input->post('text'));

				$this->init_model->set_menu_id($menu_id);
				$this->init_model->set_metatags();

				$response['error'] = 0;
			}

			return json_encode($response);
		}

		/**
		 * Видалення компоненту
		 */
		public function delete_component()
		{
			$response = array('error' => 1);
			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('loyalty_system_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_loyalty_system_model');

				$this->admin_loyalty_system_model->delete_component($component_id);

				$this->init_model->set_menu_id($menu_id);
				$this->init_model->set_metatags();

				$response['error'] = 0;
			}

			return json_encode($response);
		}
	}