<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_menu extends MX_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->_clean_cache();
		}

		/**
		 * Очистка кешу
		 */
		public function _clean_cache()
		{
			$this->cache->clean('menu/');
		}

		/**
		 * Вивід меню для редагування
		 */
		public function index()
		{
			$menu_id = (int)$this->input->get('menu_id');
			$menu_index = (int)$this->input->get('menu_index');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_' . $menu_index, $menu_id)
			) {
				$this->init_model->set_menu_id($menu_id, true);

				$this->template_lib
					->set_title('Управління меню сайту')
					->set_admin_menu_active('menu', 'top_level')
					->set_admin_menu_active($menu_index, 'sub_level');

				$this->load->model('admin_menu_model');

				$this->template_lib->set_content(
					$this->load->view(
						'admin/menu_tpl',
						array(
							'menu' =>
								$this->load->view(
									'admin/menu_list_tpl',
									array(
										'menu' => $this->admin_menu_model->get_menu($menu_index),
										'menu_id' => $menu_id,
										'menu_index' => $menu_index,
										'last_menu' => (int)$this->session->userdata('last_menu'),
									),
									true
								),
							'menu_id' => $menu_id,
							'menu_index' => $menu_index,
							'languages' => $this->config->item('languages')
						),
						true
					)
				);
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Завантаження меню через ajax запит
		 */
		public function menu_load()
		{
			$response = array(
				'success' => false,
				'menu' => '',
			);

			$menu_index = (int)$this->input->post('menu_index');
			$menu_id = (int)$this->input->post('menu_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_' . $menu_index)
			) {
				$this->load->model('admin_menu_model');

				$response['menu'] = $this->load->view(
					'admin/menu_list_tpl',
					array(
						'menu' => $this->admin_menu_model->get_menu($menu_index),
						'menu_index' => $menu_index,
						'menu_id' => $menu_id,
						'last_menu' => (int)$this->session->userdata('last_menu'),
					),
					true
				);
				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Збереження стану відкритого меню
		 */
		public function menu_open()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_index')
				and
				$menu_id > 0
			) {
				$open_menus = $this->session->userdata('open_menus');

				if (!is_array($open_menus)) {
					$open_menus = array();
				}

				if (!in_array($menu_id, $open_menus, true)) {
					$open_menus[] = $menu_id;
				}

				$this->session->set_userdata('open_menus', $open_menus);

				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Збереження стану закритого меню
		 */
		public function menu_close()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_index')
				and
				$menu_id > 0
			) {
				$open_menus = $this->session->userdata('open_menus');

				if (!is_array($open_menus)) {
					$open_menus = array();
				}

				$key = array_search($menu_id, $open_menus, true);

				if ($key !== false) {
					unset($open_menus[$key]);
					$open_menus = array_values($open_menus);

					$this->session->set_userdata('open_menus', $open_menus);
				}

				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Додавання пункту меню
		 *
		 * @return string
		 */
		public function insert_item()
		{
			$response = array(
				'success' => false,
				'menu_id' => 0,
			);

			$menu_index = (int)$this->input->post('menu_index');
			$parent_id = (int)$this->input->post('parent_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_' . $menu_index)
				and
				$parent_id >= 0
			) {
				$this->load->model('admin_menu_model');

				$id = $this->admin_menu_model->menu_add($menu_index, $parent_id);

				$this->session->set_userdata('last_menu', $id);
				$response['menu_id'] = $id;

				$response['success'] = true;
				$this->_clean_cache();
			}

			return json_encode($response);
		}

		/**
		 * Збереження пункту меню
		 */
		public function update_item()
		{
			$response = array('success' => false);

			$id = (int)$this->input->post('id');
			$name = $this->db->escape_str(strip_tags($this->input->post('name', true)));
			$menu_index = (int)$this->input->post('menu_index');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_' . $menu_index)
				and
				$id >= 0
			) {
				$this->load->model('admin_menu_model');
				$this->load->helpers(array('translit', 'form'));

				$this->admin_menu_model->menu_update(
					$id,
					array(
						'name_' . LANG => form_prep($name),
						'url_' . LANG => translit($name),
					),
					true
				);

				$response['link'] = $this->init_model->get_link($id, '{URL}');
				$response['success'] = true;

				$this->_clean_cache();
			}

			return json_encode($response);
		}

		/**
		 * Збереження статичного посилання
		 *
		 * @return string
		 */
		public function update_item_link()
		{
			$response = array('success' => false);

			$id = (int)$this->input->post('id');
			$target = (int)$this->input->post('target');
			$url = $this->db->escape_str(strip_tags($this->input->post('url', true)));

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu')
				and
				$id >= 0
			) {
				$this->load->model('admin_menu_model');
				$this->load->helpers(array('translit', 'form'));

				$set = array(
					'target' => $target,
					'static_url_' . LANG => $url,
				);
				$this->admin_menu_model->menu_update($id, $set, true);

				$response['link'] = $this->init_model->get_link($id, '{URL}');
				$response['success'] = true;

				$this->_clean_cache();
			}

			return json_encode($response);
		}

		/**
		 * Збереження порядку сортування меню
		 */
		public function update_items_position()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$items = $this->input->post('items');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu')
				and
				$menu_id >= 0
				and
				is_array($items)
			) {
				$this->load->model('admin_menu_model');

				$this->admin_menu_model->update_position($items);
				$this->admin_menu_model->update_paths($menu_id);

				$this->session->set_userdata('last_menu', $menu_id);
				$response['success'] = true;

				$this->_clean_cache();
			}

			return json_encode($response);
		}

		/**
		 * Встановлення пункту меню головним
		 */
		public function set_main()
		{
			$response = array('success' => false);

			$id = (int)$this->input->post('id');
			$menu_index = (int)$this->input->post('menu_index');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_' . $menu_index)
				and
				$id >= 0
			) {
				$this->load->model('admin_menu_model');

				$this->admin_menu_model->set_main($id);

				$this->session->set_userdata('last_menu', $id);
				$response['success'] = true;

				$this->_clean_cache();
			}

			return json_encode($response);
		}

		/**
		 * Приховування/відображення пункту меню
		 */
		public function item_visibility()
		{
			$response = array('success' => false);

			$id = (int)$this->input->post('id');
			$menu_index = (int)$this->input->post('menu_index');
			$status = (int)$this->input->post('status');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_' . $menu_index)
				and
				$id > 0
				and
				in_array($status, array(0, 1), true)
			) {
				$this->session->set_userdata('last_menu', $id);

				$this->load->model('admin_menu_model');
				$this->admin_menu_model->menu_update($id, array('hidden' => $status));

				$this->_clean_cache();
				$response['success'] = true;
			}

			return json_encode($response);
		}

		# Зображення до понктів меню

		/**
		 * Видалення пункту меню
		 */
		public function delete_item()
		{
			$response = array('success' => false);

			$id = (int)$this->input->post('id');
			$menu_index = (int)$this->input->post('menu_index');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('menu_' . $menu_index)
				and
				$id > 0
			) {
				$this->load->model('admin_menu_model');
				$this->load->helper('directory');
				$this->load->helper('file');

				$this->admin_menu_model->menu_delete($id);

				$this->_clean_cache();
				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Форма завантаженя зображення
		 */
		public function edit()
		{
			$menu_index = (int)$this->input->get('menu_index');
			$menu_id = (int)$this->input->get('menu_id');
			$item_id = (int)$this->input->get('item_id');
			$catalog = (int)$this->input->get('catalog');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('menu_' . $menu_index, $menu_id)
				and $item_id > 0
			) {
				$this->init_model->set_menu_id($menu_id, true);
				$this->session->set_userdata('last_menu', $item_id);

				$this->template_lib
					->set_title('Редагування пункту меню')
					->set_admin_menu_active('menu', 'top_level')
					->set_admin_menu_active($menu_index, 'sub_level');

				$this->load->model('admin_menu_model');

				$item = $this->admin_menu_model->get_item($item_id);

				if (count($item) > 0) {
					$this->template_lib->set_content(
						$this->load->view(
							'admin/edit_tpl',
							array(
								'menu_index' => $menu_index,
								'menu_id' => $menu_id,
								'item_id' => $item_id,
								'item' => $item,
								'catalog' => $catalog,
								'thumb' => array(718, 510),
								'languages' => $this->config->item('languages'),
							),
							true
						)
					);
				}
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження опису пункту меню
		 */
		public function update_info()
		{
			$response = array('success' => false);

			$id = (int)$this->input->post('id');
			$menu_index = (int)$this->input->post('menu_index');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('menu_' . $menu_index)
				and $id > 0
			) {
				$this->load->model('admin_menu_model');
				$this->load->helper('form');

				$this->admin_menu_model->menu_update(
					$id,
					array(
						'email' => form_prep($this->input->post('email', true))
					),
					false,
					null
				);

				$this->_clean_cache();
				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Завантаження зображення
		 *
		 * @return string
		 */
		public function upload_image()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$menu_index = (int)$this->input->post('menu_index');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('menu_' . $menu_index)
				and $menu_id > 0
			) {
				$this->load->helper('directory');
				$this->load->helper('translit');

				$dir = get_dir_path('upload/menu/' . $menu_id);
				$file_name = translit_filename($_FILES['image']['name']);

				$upload_config = array(
					'upload_path' => $dir,
					'overwrite' => false,
					'file_name' => $file_name,
					'allowed_types' => 'gif|jpg|jpeg|png',
				);

				$this->load->library('upload', $upload_config);

				$this->load->library('Image_lib');

				if ($this->upload->do_upload('image')) {
					$file_name = $this->upload->data('file_name');

					$this->image_lib->resize_crop($dir . $file_name, $dir . "s_" . $file_name, 718, 510, FALSE);
					$result = $this->db->select('image')->where('id', $menu_id)->get('menu')->row_array();

					if ($result['image'] !== '' and $result['image'] !== $file_name) {
						if (file_exists($dir . $result['image'])) {
							unlink($dir . $result['image']);
						}

						if (file_exists($dir . 's_' . $result['image'])) {
							unlink($dir . 's_' . $result['image']);
						}
					}

					$set = array('image' => $file_name);
					$where = array('id' => $menu_id);
					$this->db->update('menu', $set, $where);

					$response['success'] = true;
					$response['file_name'] = $file_name . '?t=' . time() . mt_rand(100000, 1000000);
				}
			}

			$this->config->set_item('is_ajax_request', true);
			$this->_clean_cache();

			return json_encode($response);
		}

		/**
		 * Видалення зображення
		 *
		 * @return string
		 */
		public function remove_image()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$menu_index = (int)$this->input->post('menu_index');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('menu_' . $menu_index)
				and $menu_id > 0
			) {
				$result = $this->db->select('image')->where('id', $menu_id)->get('menu')->row_array();

				if (count($result) > 0) {
					$this->load->helper('directory');

					$dir = get_dir_path('/upload/menu/' . $menu_id);

					if ($result['image'] !== '') {
						if (file_exists($dir . $result['image'])) {
							unlink($dir . $result['image']);
						}

						if (file_exists($dir . 's_' . $result['image'])) {
							unlink($dir . 's_' . $result['image']);
						}

						$set = array('image' => '');
						$where = array('id' => $menu_id);
						$this->db->update('menu', $set, $where);
					}
				}

				$response['success'] = true;
			}

			$this->_clean_cache();

			return json_encode($response);
		}

		/**
		 * Завантаження іконки
		 *
		 * @return string
		 */
		public function upload_icon()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$menu_index = (int)$this->input->post('menu_index');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('menu_' . $menu_index)
				and $menu_id > 0
			) {
				$this->load->helper('directory');
				$this->load->helper('translit');

				$dir = get_dir_path('upload/menu/' . $menu_id);
				$file_name = translit_filename($_FILES['icon']['name']);

				$upload_config = array(
					'upload_path' => $dir,
					'overwrite' => false,
					'file_name' => $file_name,
					'allowed_types' => 'gif|jpg|jpeg|png',
				);

				$this->load->library('upload', $upload_config);

				if ($this->upload->do_upload('icon')) {
					$file_name = $this->upload->data('file_name');

					$result = $this->db->select('icon')->where('id', $menu_id)->get('menu')->row_array();

					if (
						$result['icon'] !== ''
						and $result['icon'] !== $file_name
						and file_exists($dir . $result['icon'])
					) {
						unlink($dir . $result['icon']);
					}

					$set = array('icon' => $file_name);
					$where = array('id' => $menu_id);
					$this->db->update('menu', $set, $where);

					$response['success'] = true;
					$response['file_name'] = $file_name . '?t=' . time() . mt_rand(100000, 1000000);
				}
			}

			$this->config->set_item('is_ajax_request', true);
			$this->_clean_cache();

			return json_encode($response);
		}

		/**
		 * Видалення іконки
		 *
		 * @return string
		 */
		public function remove_icon()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$menu_index = (int)$this->input->post('menu_index');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('menu_' . $menu_index)
				and $menu_id > 0
			) {
				$result = $this->db->select('icon')->where('id', $menu_id)->get('menu')->row_array();

				if (count($result) > 0) {
					$this->load->helper('directory');

					$dir = get_dir_path('/upload/menu/' . $menu_id);

					if ($result['icon'] !== '') {
						if (file_exists($dir . $result['icon'])) {
							unlink($dir . $result['icon']);
						}

						$set = array('icon' => '');
						$where = array('id' => $menu_id);
						$this->db->update('menu', $set, $where);
					}
				}

				$response['success'] = true;
			}

			$this->_clean_cache();

			return json_encode($response);
		}
	}
