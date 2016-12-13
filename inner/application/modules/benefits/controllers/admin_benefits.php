<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_benefit_services
	 *
	 * @property Admin_article_model $admin_article_model
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
				$this->template_lib->set_title('Редагування компонента');
				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->template_lib->set_admin_menu_active('-');

				$template_data = array(
					'menu_id' => $menu_id,
					'component_id' => $component_id,
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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('benefits_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$data = $this->input->post('text');
				$mode = $this->input->post('mode');

				if ($mode == 1)
				{
					$file_deff = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/inner/application/modules/benefits/views/benefits_deff_tpl.php');
					$file_new = $_SERVER['DOCUMENT_ROOT'].'/inner/application/modules/benefits/views/benefits_tpl.php';
					file_put_contents($file_new, $file_deff);
				}
				else
				{
					$text = $data[LANG];

					$file = $_SERVER['DOCUMENT_ROOT'].'/inner/application/modules/benefits/views/benefits_tpl.php';

					if (!empty($text))
					{
						$file_def = $_SERVER['DOCUMENT_ROOT'].'/inner/application/modules/benefits/views/benefits_deff_tpl.php';
						file_put_contents($file, $text);
					}
				}

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