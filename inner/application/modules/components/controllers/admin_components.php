<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_components extends MX_Controller {

		/**
		 * Додавання нового компонента
		 */
		public function insert()
		{
			$this->init_model->is_admin('json');

			$response = array(
				'error' => 1,
				'component' => ''
			);

			$modules = $this->config->item('backend_modules');

			$menu_id = intval($this->input->post('menu_id'));
			$module = strip_tags($this->input->post('module', TRUE));
			$method = strip_tags($this->input->post('method', TRUE));
			$config = strip_tags($this->input->post('config', TRUE));

			if ($this->init_model->is_admin() AND $this->init_model->check_access($module . '_' . $method, $menu_id) AND in_array($module, $modules) AND $method != '')
			{
				$this->load->model('admin_components_model');

				$db_config = '';
				$config = strval($config);

				if ($config != '')
				{
					$db_config = array();
					$config = explode(';', $config);

					foreach ($config as $val)
					{
						$val = explode(':', $val);

						$val[0] = $this->db->escape_str(strip_tags($this->security->xss_clean($val[0])));
						$val[1] = $this->db->escape_str(strip_tags($this->security->xss_clean($val[1])));

						$db_config[$val[0]] = $val[1];
					}

					$db_config = serialize($db_config);
				}

				$component_id = $this->admin_components_model->insert_component($menu_id, $module, $method, $db_config);

				$response['error'] = 0;
				$response['component'] = Modules::run($module . '/' . $method, $menu_id, $component_id, 0, $db_config);
			}

			return json_encode($response);
		}

		/**
		 * Приховування/відображення компонента
		 */
		public function toggle_visibility()
		{
			$this->init_model->is_admin('json');

			$response = array('success' => FALSE);

			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));
			$status = intval($this->input->post('status'));

			$this->load->model('admin_components_model');
			$component = $this->admin_components_model->get_component($component_id);

			if ($this->init_model->is_admin() AND $this->init_model->check_access($component['module'] . '_' . $component['method'], $menu_id) AND $component_id > 0 AND in_array($status, array(0, 1)))
			{
				$this->admin_components_model->visibility($component_id, $status);
				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Зміна порядку сортування компонентів
		 */
		public function update_position()
		{
			$this->init_model->is_admin('json');

			$response = array('error' => 1);
			$components = $this->input->post('components');
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access(NULL, $menu_id) AND is_array($components) AND count($components) > 0)
			{
				$this->load->model('admin_components_model');
				$this->admin_components_model->update_position($components);

				$response['error'] = 0;
			}

			return json_encode($response);
		}
	}
