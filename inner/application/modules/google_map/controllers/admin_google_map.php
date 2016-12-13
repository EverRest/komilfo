<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_google_map
	 *
	 * @property Admin_google_map_model $admin_google_map_model
	 */

	class Admin_google_map extends MX_Controller {

		/**
		 * Редагування статті
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));
			$component_id = intval($this->input->get('component_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('google_map_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_google_map_model');

				$template_data = array(
					'menu_id' => $menu_id,
					'component_id' => $component_id,
					'map' => $this->admin_google_map_model->get_map($component_id, $menu_id),
					'languages' => $this->config->item('languages')
				);
				$this->template_lib->set_content($this->load->view('admin/edit_tpl', $template_data, TRUE));

				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->template_lib->set_admin_menu_active('-');
			}
			else
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження карти
		 */
		public function update_map()
		{
			$response = array('success' => FALSE);
			$menu_id = $this->input->post('menu_id');
			$component_id = $this->input->post('component_id');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('google_map_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_google_map_model');
				$this->load->helper('form');

				$set = array(
					'center_lat' => $this->input->post('center_lat', TRUE),
					'center_lng' => $this->input->post('center_lng', TRUE),
					'zoom' => $this->input->post('zoom'),
					'marker_lat' => $this->input->post('marker_lat', TRUE),
					'marker_lng' => $this->input->post('marker_lng', TRUE),
				);

				$title = $this->input->post('title', TRUE);
				$description = $this->input->post('description', TRUE);

				foreach ($title as $language => $val)
				{
					$set['title_' . $language] = form_prep($val);
					$set['description_' . $language] = form_prep($description[$language]);
				}

				$this->admin_google_map_model->update($component_id, $set);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Видалення компоненту
		 */
		public function delete_component()
		{
			$response = array('success' => FALSE);
			$menu_id = $this->input->post('menu_id');
			$component_id = $this->input->post('component_id');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('google_map_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_google_map_model');
				$this->admin_google_map_model->delete_component($component_id);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}
	}