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

				$this->template_lib->set_title('Редагування контактів');
				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->template_lib->set_admin_menu_active('-');

				$template_data = array(
					'menu_id' => $menu_id,
					'component_id' => $component_id,
					'google_map' => $this->admin_google_map_model->get_google_map($component_id, $menu_id),
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
		public function update_map()
		{
			$response = array('error' => 1);
			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('google_map_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$wide = intval($this->input->post('wide'));
                $information = $this->input->post('information');
                $schedule = $this->input->post('schedule');
                $sale = $this->input->post('sale');

                $marker_lat = $this->input->post('marker_lat');
                $marker_lng = $this->input->post('marker_lng');
                $zoom = $this->input->post('zoom');
                $center_lat = $this->input->post('center_lat');
                $center_lng = $this->input->post('center_lng');

				$this->load->model('admin_google_map_model');
				$this->load->helper('form');
				$this->admin_google_map_model->update($component_id, $wide, $information, $schedule, $sale, $marker_lat, $marker_lng, $zoom, $center_lat, $center_lng);

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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('google_map_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_google_map_model');

				$this->admin_google_map_model->delete_component($component_id);

				$this->init_model->set_menu_id($menu_id);
				$this->init_model->set_metatags();

				$response['error'] = 0;
			}

			return json_encode($response);
		}
	}