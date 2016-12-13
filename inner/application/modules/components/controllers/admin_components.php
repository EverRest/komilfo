<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_components extends MX_Controller {

		/**
		 * Додавання нового компонента
		 */
		public function insert()
		{
			$response = array(
				'success' => false,
				'component' => ''
			);

			$modules = $this->config->item('backend_modules');

			$menu_id = (int)$this->input->post('menu_id');
			$module = strip_tags($this->input->post('module', true));
			$method = strip_tags($this->input->post('method', true));
			$config = strip_tags($this->input->post('config', true));

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access($module . '_' . $method, $menu_id)
				and in_array($module, $modules, true)
				and $method !== ''
			) {
				$this->load->model('admin_components_model');

				$db_config = array();

				if ((string)$config !== '') {
					$config = explode(';', $config);

					foreach ($config as $val) {
						$val = explode(':', $val);

						$val[0] = $this->db->escape_str(strip_tags($this->security->xss_clean($val[0])));
						$val[1] = $this->db->escape_str(strip_tags($this->security->xss_clean($val[1])));

						$db_config[$val[0]] = $val[1];
					}
				}

				$component_id = $this->admin_components_model->insert_component($menu_id, $module, $method, count($db_config) > 0 ? serialize($db_config) : '');

				$response['success'] = true;
				$response['component'] = Modules::run($module . '/' . $method, $menu_id, $component_id, 0, $db_config);
			}

			return json_encode($response);
		}

		/**
		 * Приховування/відображення компонента
		 */
		public function toggle_visibility()
		{
			$response = array('success' => FALSE);

			$component_id = (int)$this->input->post('component_id');
			$menu_id = (int)$this->input->post('menu_id');
			$status = (int)$this->input->post('status');

			$this->load->model('admin_components_model');
			$component = $this->admin_components_model->get_component($component_id);

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access($component['module'] . '_' . $component['method'], $menu_id)
				and $component_id > 0
				and in_array($status, array(0, 1), true)
			) {
				$this->admin_components_model->visibility($component_id, $status);
				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Зміна порядку сортування компонентів
		 */
		public function update_position()
		{
			$response = array('success' => false);

			$components = $this->input->post('components');
			$menu_id = (int)$this->input->post('menu_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access(null, $menu_id)
				and is_array($components)
				and count($components) > 0
			) {
				$this->load->model('admin_components_model');

				$this->admin_components_model->update_position($components);

				$response['success'] = true;
			}

			return json_encode($response);
		}
	}
