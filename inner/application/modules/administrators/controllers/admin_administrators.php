<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Administrators
	 *
	 * @property Admin_administrators_model $admin_administrators_model
	 */
	class Admin_administrators extends MX_Controller
	{
		/**
		 * Вивід списка адміністраторів
		 */
		public function index()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if (!$this->init_model->is_admin() OR !$this->init_model->check_access('config_administrators', NULL, TRUE))
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}

			$this->init_model->set_menu_id($menu_id, TRUE);

			$this->template_lib->set_title('Адміністратори сайту');

			$this->template_lib->set_admin_menu_active('config');
			$this->template_lib->set_admin_menu_active('administrators', 'sub_level');

			$this->load->model('admin_administrators_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'administrators' => $this->admin_administrators_model->get_administrators()
			);
			$c = $this->load->view('admin/administrators_tpl', $template_data, TRUE);
			$this->template_lib->set_content($c);
		}

		/**
		 * Додавання адміністратора
		 *
		 * @return string
		 */
		public function insert()
		{
			$response = array(
				'success' => FALSE,
				'admin_id' => 0,
			);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_administrators', NULL, TRUE))
			{
				$this->load->model('admin_administrators_model');

				$set = array('create_date' => time());
				$response['admin_id'] = $this->admin_administrators_model->insert($set);
				$response['date'] = date('d.m.Y H:i', $set['create_date']);
				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Блокування/розблокування адміністратора
		 *
		 * @return string
		 */
		public function status()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_administrators', NULL, TRUE))
			{
				$admin_id = intval($this->input->post('admin_id'));
				$status = intval($this->input->post('status'));

				if ($admin_id > 0 AND in_array($status, array(0, 1)))
				{
					$this->load->model('admin_administrators_model');

					$set = array('status' => $status);
					$this->admin_administrators_model->update($admin_id, $set);

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Редагування адміністратора
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if (!$this->init_model->is_admin() OR !$this->init_model->check_access('config_administrators', NULL, TRUE))
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}

			$this->init_model->set_menu_id($menu_id, TRUE);

			$admin_id = intval($this->input->get('admin_id'));

			if ($menu_id > 0 AND $admin_id > 0)
			{
				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->load->model('admin_administrators_model');
				$this->load->helper('admin_administrators');

				$tpl_data = array(
					'admin' => $this->admin_administrators_model->get($admin_id),
					'admin_id' => $admin_id,
					'menu_id' => $menu_id,

					'admin_menu' => include APPPATH . 'modules/menu/config/admin_menu.php',
					'site_menu' => $this->admin_administrators_model->get_menus(),
				);

				$content = $this->load->view('admin/edit_tpl', $tpl_data, TRUE);
				$this->template_lib->set_content($content);

				$this->template_lib->set_title('Редагування адміністратора');

				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('administrators', 'sub_level');
			}
		}

		/**
		 * Збереження адміністратора
		 */
		public function save()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_administrators', NULL, TRUE))
			{
				$this->load->model('admin_administrators_model');
				$this->load->helper('form');

				$admin_id = intval($this->input->post('admin_id'));

				$set = array(
					'name' => form_prep($this->input->post('name')),
					'login' => form_prep($this->input->post('login')),
				);

				if ($this->input->post('password') != '')
				{
					if ($this->admin_administrators_model->check_password($admin_id, $this->input->post('old_password')))
					{
						$set['salt'] = md5($this->admin_administrators_model->get_random_password());
						$set['password'] = md5($this->input->post('password') . $set['salt']);
						$set['edited'] = 1;
					}
				}

				$site_menu = $this->input->post('site_menu');
				$set['site_menu'] = is_array($site_menu) ? implode(',', array_map('intval', $site_menu)) : '';

				$admin_menu = $this->input->post('admin_menu');
				$set['admin_menu'] = is_array($admin_menu) ? implode(',', array_map('form_prep', $admin_menu)) : '';

				$this->admin_administrators_model->update($admin_id, $set);

				$response['success'] = TRUE;
			}

			return json_encode(array('success' => TRUE));
		}

		/**
		 * Видалення адміністратора
		 *
		 * @return string
		 */
		public function delete()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_administrators', NULL, TRUE))
			{
				$admin_id = intval($this->input->post('admin_id'));

				if ($admin_id > 0)
				{
					$this->load->model('admin_administrators_model');
					$this->admin_administrators_model->delete($admin_id);

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}
	}