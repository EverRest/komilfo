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
			$this->cache->clean();
		}

		/**
		 * Вивід меню для редагування
		 */
		public function index()
		{

			$menu_id = intval($this->input->get('menu_id'));

			if (!$this->init_model->is_admin() OR !$this->init_model->check_access('menu'))
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}

			$this->init_model->set_menu_id($menu_id, TRUE);

			$menu_index = intval($this->input->get('menu_index'));

			$this->template_lib->set_title('Управління меню сайту');
			$this->template_lib->set_admin_menu_active('menu', 'top_level');
			$this->template_lib->set_admin_menu_active($menu_index, 'sub_level');

			$this->load->model('admin_menu_model');

			$template_vars = array(
				'menu' => $this->admin_menu_model->get_menu($menu_index),
				'menu_id' => $menu_id,
				'menu_index' => $menu_index,
				'last_menu' => intval($this->session->userdata('last_menu')),
			);
			$menu = $this->load->view('admin/menu_list_tpl', $template_vars, TRUE);

			$template_vars = array(
				'menu' => $menu,
				'menu_id' => $menu_id,
				'menu_index' => $menu_index,
				'languages' => $this->config->item('languages')
			);
			$this->template_lib->set_content($this->load->view('admin/menu_tpl', $template_vars, TRUE));
		}

		/**
		 * Завантаження меню через ajax запит
		 */
		public function load()
		{
			$response = array(
				'error' => 1,
				'menu' => '',
			);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$menu_index = intval($this->input->post('menu_index'));
				$menu_id = intval($this->input->post('menu_id'));
				$language = $this->input->post('language');
				$languages = $this->config->item('languages');

				if ($menu_index > 0 AND isset($languages[$language]))
				{
					$this->load->model('admin_menu_model');

					$response['error'] = 0;
					$response['menu'] = $this->load->view('admin/menu_list_tpl', array('menu' => $this->admin_menu_model->get_menu($menu_index, $language), 'menu_index' => $menu_index, 'menu_id' => $menu_id, 'last_menu' => intval($this->session->userdata('last_menu'))), TRUE);
				}
			}

			return json_encode($response);
		}

		/**
		 * Додавання пункту меню
		 *
		 * @return string
		 */
		public function insert()
		{
			$response = array(
				'error' => 1,
				'menu_id' => 0,
			);
                        
		 if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
		 {		
				
			$menu_index = intval($this->input->post('menu_index'));
			$parent_id = intval($this->input->post('parent_id'));
  			if ($menu_index > 0 AND $parent_id >= 0)
			{
				$this->load->model('admin_menu_model');

				$response['error'] = 0;
				$id = $this->admin_menu_model->menu_add($menu_index, $parent_id);
				$this->session->set_userdata('last_menu', $id);
				$response['menu_id'] = $id;
				$this->_clean_cache();
			}


		 }
			return json_encode($response);
		}

		/**
		 * Збереження пункту меню
		 */
		public function update()
		{
			$response = array('error' => 1);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$id = intval($this->input->post('id'));
				$name = $this->db->escape_str(strip_tags($this->input->post('name', TRUE)));
				$menu_index = intval($this->input->post('menu_index'));
				$language = $this->input->post('language');
				$languages = $this->config->item('languages');

				if ($id > 0 AND $menu_index > 0 AND isset($languages[$language]))
				{
					$this->load->model('admin_menu_model');
					$this->load->helpers(array('translit', 'form'));

					$set = array(
						'name_' . $language => form_prep($name),
						'url_' . $language => translit($name)
					);
					$this->admin_menu_model->menu_update($id, $set);
					$this->admin_menu_model->update_paths($id, array($language => ''));

					$response['error'] = 0;
					$response['link'] = $this->init_model->get_link($id, '{URL}');
				}

				$this->_clean_cache();
			}

			return json_encode($response);
		}

		/**
		 * Збереження статичного посилання
		 *
		 * @return string
		 */
		public function update_link()
		{
			$response = array('error' => 1);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$id = intval($this->input->post('id'));
				$target = intval($this->input->post('target'));
				$url = $this->db->escape_str(strip_tags($this->input->post('url', TRUE)));
				$language = $this->input->post('language');
				$languages = $this->config->item('languages');

				if ($id > 0 AND isset($languages[$language]))
				{
					$this->load->model('admin_menu_model');
					$this->load->helpers(array('translit', 'form'));

					$set = array(
						'target' => $target,
						'static_url_' . $language => $url,
					);
					$this->admin_menu_model->menu_update($id, $set);
					$this->admin_menu_model->update_paths($id, array($language => ''));

					$response['error'] = 0;
					$response['link'] = $url != '' ? $url : $this->init_model->get_link($id, '{URL}');

					$this->_clean_cache();
				}
			}

			return json_encode($response);
		}

		/**
		 * Збереження порядку сортування меню
		 */
		public function update_position()
		{
			$response = array('error' => 1);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$items = $this->input->post('items');
				$menu_id = intval($this->input->post('menu_id'));

				if (is_array($items) AND $menu_id > 0)
				{
					$this->load->model('admin_menu_model');
					$this->admin_menu_model->update_position($items);

					$this->session->set_userdata('last_menu', $menu_id);

					$this->_clean_cache();
					$response['error'] = 0;
				}
			}

			return json_encode($response);
		}

		/**
		 * Встановлення пункту меню головним
		 */
		public function set_main()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$id = intval($this->input->post('id'));
				$menu_index = intval($this->input->post('menu_index'));

				if ($id > 0 AND $menu_index > 0)
				{
					$this->session->set_userdata('last_menu', $id);

					$this->load->model('admin_menu_model');
					$this->admin_menu_model->set_main($id);
					$this->admin_menu_model->update_paths($menu_index, $this->config->item('languages'));

					$response['success'] = TRUE;
				}

				$this->_clean_cache();
			}

			return json_encode($response);
		}

		/**
		 * Приховування/відображення пункту меню
		 */
		public function hidden()
		{
			$response = array('error' => 1);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$id = intval($this->input->post('id'));
				$status = intval($this->input->post('status'));

				if ($id > 0 AND in_array($status, array(0, 1)))
				{
					$this->session->set_userdata('last_menu', $id);

					$this->load->model('admin_menu_model');
					$this->admin_menu_model->menu_update($id, array('hidden' => $status));

					$response['error'] = 0;
				}

				$this->_clean_cache();
			}

			return json_encode($response);
		}

		/**
		 * Видалення пункту меню
		 */
		public function delete()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$id = intval($this->input->post('id'));

				if ($id > 0)
				{
					$this->load->model('admin_menu_model');
					$this->admin_menu_model->menu_delete($id);

					$this->_clean_cache();
					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Форма завантаженя зображення
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if (!$this->init_model->is_admin() OR !$this->init_model->check_access('menu'))
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}

			$menu_index = intval($this->input->get('menu_index'));
			$item_id = intval($this->input->get('item_id'));
			$catalog = intval($this->input->get('catalog'));

			$this->template_lib->set_title('Редагування пункту меню');
			$this->template_lib->set_admin_menu_active('menu', 'top_level');
			$this->template_lib->set_admin_menu_active($menu_index, 'sub_level');

			$this->init_model->set_menu_id($menu_id, TRUE);
			$this->session->set_userdata('last_menu', $item_id);

			$this->load->model('admin_menu_model');
			$result = $this->db->where('id', $item_id)->get('menu')->row_array();

			if (count($result) > 0)
			{
				$template_data = array(
					'menu_index' => $menu_index,
					'menu_id' => $menu_id,
					'item_id' => $item_id,
					'menu' => $result,
					'catalog' => $catalog,
					'languages' => $this->config->item('languages'),
				);
				$this->template_lib->set_content($this->load->view('admin/edit_tpl', $template_data, TRUE));
			}
		}

		/**
		 * Збереження опису пункту меню
		 */
		public function update_info()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$id = intval($this->input->post('id'));

				if ($id > 0)
				{
					$this->load->model('admin_menu_model');
					$this->load->helper('form');

					$set = array();

					$title = $this->input->post('title');
					foreach ($title as $k => $v) $set['title_' . $k] = form_prep($v);

					$this->admin_menu_model->menu_update($id, $set);

					$this->_clean_cache();
					$response['success'] = TRUE;
				}
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
			$response = array('success' => FALSE, 'width' => '', 'height' => '');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$menu_id = intval($this->input->post('menu_id'));
				$menu_index = intval($this->input->post('menu_index'));

				if ($menu_id > 0 AND $menu_index > 0)
				{
					$dir = ROOT_PATH . 'upload/menu/';
					if (!file_exists($dir)) mkdir($dir);

					$dir .= $menu_id . '/';
					if (!file_exists($dir)) mkdir($dir);

					$this->load->helper('translit');
					$file_name = translit_filename($_FILES['image']['name']);

					$upload_config = array(
						'upload_path' => $dir,
						'overwrite' => FALSE,
						'file_name' => $file_name,
						'allowed_types' => 'gif|jpg|jpeg|png',
					);

					$this->load->library('upload', $upload_config);

					if ($this->upload->do_upload('image'))
					{
						$file_name = $this->upload->data('file_name');

						$sizes = getimagesize($dir . $file_name);

						$width = ($sizes[0] < 1000) ? $sizes[0] : 1000;
						$height = ($width * $sizes[1]) / $sizes[0];

						$this->load->library('Image_lib');

						$this->image_lib->resize($dir . $file_name, $dir . 's_' . $file_name, $width, $height);
						if ($menu_index == 1) $this->image_lib->resize_crop($dir . $file_name, $dir . $file_name, 180, 180);
						if ($menu_index == 4) $this->image_lib->resize($dir . $file_name, $dir . $file_name, 133, 31);

						$result = $this->db->select('image')->where('id', $menu_id)->get('menu')->row_array();
						if ($result['image'] != '' AND $result['image'] != $file_name)
						{
							if (file_exists($dir . $result['image'])) unlink($dir . $result['image']);
							if (file_exists($dir . 's_' . $result['image'])) unlink($dir . 's_' . $result['image']);
						}

						$set = array('image' => $file_name);
						$where = array('id' => $menu_id);
						$this->db->update('menu', $set, $where);

						$response['success'] = TRUE;
						$response['width'] = $width;
						$response['height'] = $height;
						$response['file_name'] = $file_name . '?t=' . time() . rand(100000, 1000000);

						$this->config->set_item('is_ajax_request', TRUE);
						$this->_clean_cache();
					}
				}
			}

			return json_encode($response);
		}

		/**
		 * Обрізка зображення
		 *
		 * @return string
		 */
		public function crop_image()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu'))
			{
				$menu_id = intval($this->input->post('menu_id'));
				$menu_index = intval($this->input->post('menu_index'));
				$width = floatval($this->input->post('width'));
				$coords = $this->input->post('coords');
				$coords = array_map('floatval', $coords);

				if (is_numeric($coords['x']) AND is_numeric($coords['y']) AND $coords['w'] >= 0 AND $coords['h'] >= 0 AND $menu_id >= 0 AND $menu_index > 0 AND $width > 0)
				{
					$result = $this->db->select('image')->where('id', $menu_id)->get('menu')->row_array();

					if (count($result) > 0)
					{
						$dir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/upload/menu/' . $menu_id . '/';

						if (file_exists($dir . 's_' . $result['image']))
						{
							$sizes = getimagesize($dir . 's_' . $result['image']);

							$w_index = $sizes[0] / $width;
							$h_index = $sizes[1] / round(($width * $sizes[1]) / $sizes[0]);

							$x = $coords['x'] * $w_index;
							$y = $coords['y'] * $h_index;
							$x2 = $coords['x2'] * $w_index;
							$y2 = $coords['y2'] * $h_index;

							$this->load->library('Image_lib');
							if ($menu_index == 1) $this->image_lib->crop($dir . 's_' . $result['image'], $dir . $result['image'], $x2 - $x, $y2 - $y, $x, $y, 180, 180);
							if ($menu_index == 4) $this->image_lib->crop($dir . 's_' . $result['image'], $dir . $result['image'], $x2 - $x, $y2 - $y, $x, $y, 133, 31);

							$response['image'] = '/upload/menu/' . $menu_id . '/' . $result['image'] . '?t=' . time() . rand(10000, 1000000);
							$response['success'] = TRUE;
						}
					}
				}
			}

			return json_encode($response);
		}

		/**
		 * Видалення зображення
		 *
		 * @return string
		 */
		public function delete_image()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('menu') AND $menu_id > 0)
			{
				$result = $this->db->select('image')->where('id', $menu_id)->get('menu')->row_array();

				if (count($result) > 0)
				{
					$dir = ROOT_PATH . 'upload/menu/' . $menu_id . '/';

					if ($result['image'] != '')
					{
						if (file_exists($dir . $result['image'])) unlink($dir . $result['image']);
						if (file_exists($dir . 's_' . $result['image'])) unlink($dir . 's_' . $result['image']);
						if (file_exists($dir . 'g_' . $result['image'])) unlink($dir . 'g_' . $result['image']);

						$set = array('image' => '');
						$where = array('id' => $menu_id);
						$this->db->update('menu', $set, $where);
					}
				}

				$this->_clean_cache();
				$response['success'] = TRUE;
			}

			return json_encode($response);
		}
	}
