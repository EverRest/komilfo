<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_benefit_services
	 *
	 * @property Admin_benefit_services_model $admin_benefit_services
	 */
	class Admin_benefit_services extends MX_Controller {

		/**
		 * Редагування компоненту
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));
			$component_id = intval($this->input->get('component_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('benefit_services_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_benefit_services_model');

				$this->template_lib->set_title('Редагування компонента');
				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->template_lib->set_admin_menu_active('-');

				$template_data = array(
					'menu_id' => $menu_id,
					'component_id' => $component_id,
					'article' => $this->admin_benefit_services_model->get_article(),
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
		 * Збереження компоненту
		 */
		public function update_article()
		{

			$response = array('error' => 1);
			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('benefit_services_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$data =array(
				't1' => $this->input->post('t1'),
				't2' => $this->input->post('t2'),
				't3' => $this->input->post('t3'),
				't4' => $this->input->post('t4'),
				'm1' => $this->input->post('m1'),
				'm2' => $this->input->post('m2'),
				'm3' => $this->input->post('m3'),
				'm4' => $this->input->post('m4'),
					't_1' => $this->input->post('t_1'),
					't_2' => $this->input->post('t_2'),
					't_3' => $this->input->post('t_3'),
					't_4' => $this->input->post('t_4'),
					't_5' => $this->input->post('t_5'),
					'm_1' => $this->input->post('m_1'),
					'm_2' => $this->input->post('m_2'),
					'm_3' => $this->input->post('m_3'),
					'm_4' => $this->input->post('m_4'),
					'm_5' => $this->input->post('m_5'),
						'm_1_1' => $this->input->post('m_1_1'),
						'm_2_2' => $this->input->post('m_2_2'),
						'm_3_3' => $this->input->post('m_3_3'),
						'm_4_4' => $this->input->post('m_4_4'),
						'm_5_5' => $this->input->post('m_5_5'),

				);

				$this->load->model('admin_benefit_services_model');
				$this->load->helper('form');

				$this->admin_benefit_services_model->update($data);

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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('benefit_services_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_benefit_services_model');

				$this->admin_benefit_services_model->delete_component($component_id);

				$this->init_model->set_menu_id($menu_id);
				$this->init_model->set_metatags();

				$response['error'] = 0;
			}

			return json_encode($response);
		}
	}