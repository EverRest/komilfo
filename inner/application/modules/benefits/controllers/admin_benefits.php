<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_benefits
	 *
	 * @property Admin_benefits_model $admin_benefits_model
	 */
	class Admin_benefits extends MX_Controller {

		/**
		 * Редагування статті
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));
			$component_id = intval($this->input->get('component_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('benefits_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_benefits_model');

				$this->template_lib->set_title('Редагування компоненти');
				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->template_lib->set_admin_menu_active('-');

				$template_data = array(
					'menu_id' => $menu_id,
					'component_id' => $component_id,
					'benefits' => $this->admin_benefits_model->get_benefits($component_id, $menu_id),
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
		public function update_benefits()
		{
			$response = array('error' => 1);
			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('benefits_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$title = $this->input->post('title_ua');
				$text = $this->input->post('text_ua');
                $author = $this->input->post('author_ua');
                $quote = $this->input->post('quote_ua');
				$wide = intval($this->input->post('wide'));

//				$background_fone = $this->input->post('background_fone');

				$btn_active = $this->input->post('btn_active');

				$this->load->model('admin_benefits_model');
				$this->load->helper('form');

				$this->admin_benefits_model->update($component_id, $title, $text, $wide, $author, $quote, $btn_active);

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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('benefits_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_benefits_model');

				$this->admin_benefits_model->delete_component($component_id);

				$this->init_model->set_menu_id($menu_id);
				$this->init_model->set_metatags();

				$response['error'] = 0;
			}

			return json_encode($response);
		}
	}