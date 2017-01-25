<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_services
	 *
	 * @property Admin_services_model $admin_services_model
	 */
	class Admin_services extends MX_Controller {

		/**
		 * Редагування статті
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));
			$component_id = intval($this->input->get('component_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('services_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_services_model');

				$this->template_lib->set_title('Редагування статті');
				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->template_lib->set_admin_menu_active('-');

				$template_data = array(
					'menu_id' => $menu_id,
					'component_id' => $component_id,
					'services' => $this->admin_services_model->get_services($component_id, $menu_id),
					'languages' => $this->config->item('languages'),
					'menu_index' => $this->admin_services_model->menu_index($menu_id)
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
		public function update_services()
		{
			$response = array('success' => FALSE);
			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('services_index', $menu_id) AND $component_id > 0)
			{
				$title = $this->input->post('title');
				$text = $this->input->post('text');
				$lat = strval($this->input->post('lat'));
				$lng = strval($this->input->post('lng'));
				$zoom = intval($this->input->post('zoom'));
				$wide = intval($this->input->post('wide'));

				$title_shop = $this->input->post('title_shop');
				$address = $this->input->post('address');
				$address_2 = $this->input->post('address_2');
				$phone = $this->input->post('phone');
				$facebook = $this->input->post('facebook');

				$this->load->model('admin_services_model');
				$this->load->helper('form');

				$this->admin_services_model->update($component_id, $title, $text, $wide, $lat, $lng, $zoom, $title_shop, $address, $address_2, $phone, $facebook);

				$this->init_model->set_menu_id($menu_id);
				$this->init_model->set_metatags();

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
			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('services_index', $menu_id) AND $component_id > 0)
			{
				$this->load->model('admin_services_model');

				$this->admin_services_model->delete_component($component_id);

				$this->init_model->set_menu_id($menu_id);
				$this->init_model->set_metatags();

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}
	}