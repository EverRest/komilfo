<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class News
	 *
	 * @property Admin_news_model $admin_news_model
	 */
	class Admin_news extends MX_Controller
	{
		/**
		 * Додавання новини
		 *
		 * @return int|string
		 */
		public function insert()
		{
			$response = array('success' => FALSE, 'news_id' => 0);
			$component_id = intval($this->input->post('component_id'));
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $component_id > 0)
			{
				$this->load->model('admin_news_model');

				$db_set = array(
					'component_id' => $component_id,
					'menu_id' => $menu_id,
					'date' => time(),
					'position' => $this->admin_news_model->get_position($component_id),
					'update' => time(),
				);

				$response['success'] = TRUE;
				$response['news_id'] = $this->admin_news_model->insert($db_set);

				$this->session->set_userdata('last_news', $response['news_id']);
			}

			return json_encode($response);
		}

		/**
		 * Відображення/приховування новини
		 *
		 * @return string
		 */
		public function visible()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$news_id = intval($this->input->post('news_id'));
			$status = intval($this->input->post('status'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $news_id > 0 AND in_array($status, array(0, 1)))
			{
				$this->load->model('admin_news_model');

				$set = array('hidden' => $status);
				$where = array('news_id' => $news_id);

                $this->db->update('news', $set, $where);

                $this->session->set_userdata('last_news', $news_id);
				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Редагування новини
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));
			$news_id = intval($this->input->get('news_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $news_id > 0)
			{
				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->session->set_userdata('last_news', $news_id);

				$this->template_lib->set_title('Редагування новини');

				$this->template_lib->set_admin_menu_active('components');
				$this->template_lib->set_admin_menu_active('', 'sub_level');

				$this->load->model('admin_news_model');

				$tpl_data = array(
					'news_id' => $news_id,
					'menu_id' => $menu_id,
					'news' => $this->admin_news_model->get($news_id),
					'news_images' => $this->admin_news_model->get_news_images($news_id),
					'languages' => $this->config->item('languages')
				);

				$content = $this->load->view('admin/edit_tpl', $tpl_data, TRUE);
				$this->template_lib->set_content($content);
			}
			else
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження новини
		 */
		public function save()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id))
			{
				$this->load->model('admin_news_model');
				$this->load->helpers(array('form', 'translit'));

				$news_id = intval($this->input->post('news_id'));

				$title = $this->input->post('title');
				$text = $this->input->post('text');
				$anons = $this->input->post('anons');

				$price_start_usd = $this->input->post('price_start_usd', true);
				$price_end_usd = $this->input->post('price_end_usd', true);

				$price_start_eur = $this->input->post('price_start_eur', true);
				$price_end_eur = $this->input->post('price_end_eur', true);

				$date = time();
				$set = array(
					'date' => $date,
					'price_start_usd' => $price_start_usd,
					'price_end_usd' => $price_end_usd,
					'price_start_eur' => $price_start_eur,
					'price_end_eur' => $price_end_eur
				);

				foreach ($title as $key => $val)
				{
					$set['title_' . $key] = form_prep($val);
					$set['url_' . $key] = translit($val);
					$set['anons_' . $key] = form_prep($anons[$key]);
					$_text = str_replace(array("\r", "\n", "\t"), '', $text[$key]);
					$set['text_' . $key] = $this->db->escape_str($_text);
				}

				$where = array('news_id' => $news_id);
				$this->admin_news_model->update($set, $where);
				$this->session->set_userdata('last_news', $news_id);
				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Видалення новини
		 *
		 * @return string
		 */
		public function delete()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$news_id = intval($this->input->post('news_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $news_id > 0)
			{
				$this->load->model('admin_news_model');
				$this->load->helper('file');

				$this->admin_news_model->delete($news_id);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Збереження порядку сортування новин
		 */
		public function save_position()
		{
			$response = array('success' => FALSE);
			$menu_id = $this->input->post('menu_id');
			$news_id = $this->input->post('news_id');
			$position = $this->input->post('position');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND is_array($position))
			{
				$this->load->model('admin_news_model');

				foreach ($position as $key => $val)
				{
					$set = array('position' => $key);
					$where = array('news_id' => $val);
					$this->admin_news_model->update($set, $where,1);
				}

				$response['success'] = TRUE;
				$this->session->set_userdata('last_news', $news_id);
			}

			return json_encode($response);
		}

		### ЗОБРАЖЕННЯ ###

		/**
		 * Завантаження зображення новини
		 */
		public function upload_image()
		{
			$response = array(
				'success' => FALSE,
				'file_name' => ''
			);
			$component_id = intval($this->input->post('component_id'));
			$news_id = intval($this->input->post('news_id'));
			$menu_id = intval($this->input->post('menu_id'));
			$on_prev=intval($this->input->post('on_prev'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $component_id > 0 AND $news_id > 0)
			{
				$dir = ROOT_PATH . 'upload/news/';
				if (!file_exists($dir)) mkdir($dir);

				$dir .= $this->init_model->dir_by_id($news_id) . '/';
				if (!file_exists($dir)) mkdir($dir);

				$dir .= $news_id . '/';
				if (!file_exists($dir)) mkdir($dir);

				$this->load->helper('translit');
				$file_name = translit_filename($_FILES['news_image']['name']);

				$upload_config = array(
					'upload_path' => $dir,
					'overwrite' => TRUE,
					'file_name' => $file_name,
					'allowed_types' => 'gif|jpg|png|jpeg'
				);

				$this->load->library('upload', $upload_config);

				if ($this->upload->do_upload('news_image'))
				{
					$this->load->library('Image_lib');

					$sizes = getimagesize($dir . $file_name);
					$height = (1000 * $sizes[1]) / $sizes[0];

					if ($sizes[0] > 1000)
					{
						$this->image_lib->resize($dir . $file_name, $dir . 's_' . $file_name, 1000, $height);

						$_w = 1000;
						$_h = $height;
					}
					else
					{
						copy($dir . $file_name, $dir . 's_' . $file_name);

						$_w = $sizes[0];
						$_h = $sizes[1];
					}

						$this->image_lib->resize_crop($dir . $file_name, $dir . $file_name, $_w, $_h);
						// $this->image_lib->resize_crop($dir .'s_'. $file_name, $dir .'t_'. $file_name, 75, 75);
						$this->image_lib->resize_crop($dir .'s_'. $file_name, $dir .'t_'. $file_name, 75, 75);

					$this->load->model('admin_news_model');

					$set = array(
						'news_id' => $news_id,
						'component_id' => $component_id,
						'menu_id' => $menu_id,
						'file_name' => $file_name,
						'position' => $this->admin_news_model->get_image_position($news_id),
					);

					$image_id = $this->admin_news_model->insert_image($set);
					$response['success'] = TRUE;
					$response['file_name'] = $file_name;
					$response['image_id'] = $image_id;
					$response['width'] = $_w;
					$response['height'] = $_h;
				}
			}

			$this->config->set_item('is_ajax_request', TRUE);
			return json_encode($response);
		}

		/**
		 * Обрізка зображення
		 */
		public function crop_image()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$image_id = intval($this->input->post('image_id'));
			$width = intval($this->input->post('width'));
			$coords = $this->input->post('coords');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id))
			{
				if ($coords['x'] < 0) $coords['x'] = 0;
				if ($coords['y'] < 0) $coords['y'] = 0;

				if ($coords['x'] >= 0 AND $coords['y'] >= 0 AND $coords['w'] > 0 AND $coords['h'] > 0 AND $image_id > 0)
				{
					$image = $this->db->select('news_id, component_id, file_name')->where('image_id', $image_id)->get('news_images')->row_array();

					if (count($image) > 0)
					{
						$dir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/upload/news/' . $this->init_model->dir_by_id($image['news_id']) . '/' . $image['news_id'] . '/';

						if (file_exists($dir . 's_' . $image['file_name']))
						{
							$sizes = getimagesize($dir . 's_' . $image['file_name']);
							$w_index = $sizes[0] / $width;
							$h_index = $sizes[1] / round(($width * $sizes[1]) / $sizes[0]);
							$x = $coords['x'] * $w_index;
							$y = $coords['y'] * $h_index;
							$x2 = $coords['x2'] * $w_index;
							$y2 = $coords['y2'] * $h_index;
							$this->load->library('Image_lib');

								$this->image_lib->crop($dir . 's_' . $image['file_name'], $dir .'t_'. $image['file_name'], $x2 - $x, $y2 - $y, $x, $y, 75, 75);
								$response['image'] = '/upload/news/' . $this->init_model->dir_by_id($image['news_id']) . '/' . $image['news_id'] . '/t_' . $image['file_name'] . '?t' . time();

							$response['success'] = TRUE;
						}
					}
				}
			}

			return json_encode($response);
		}

		/**
		 * Накладання водяного знаку
		 *
		 * @return string
		 */
		public function watermark_image()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$image_id = intval($this->input->post('image_id'));
			$position = intval($this->input->post('position'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $position > 0 AND $image_id > 0 AND $this->config->item('watermark') != '')
			{
				$this->load->model('admin_news_model');

				$result = $this->admin_news_model->get_image($image_id);

				if (count($result) > 0)
				{
					$dir_code = $this->init_model->dir_by_id($result['news_id']);
					$dir = ROOT_PATH . 'upload/news/' . $dir_code . '/' . $result['news_id'] . '/';

					if (file_exists($dir . 's_' . $result['file_name']))
					{
						$this->load->library('Image_lib');
						$this->image_lib->watermark($dir . 's_' . $result['file_name'], $dir . $result['file_name'], ROOT_PATH . 'upload/watermarks/' . $this->config->item('watermark'), $position, $this->config->item('watermark_padding'), $this->config->item('watermark_opacity'));

						$this->db->set('watermark_position', $position);
						$this->db->where('image_id', $image_id);
						$this->db->update('news_images');
					}

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Порядок сортування зображень
		 */
		public function images_position()
		{
			$response = array('success' => FALSE);
			$menu_id = $this->input->post('menu_id');
			$items = $this->input->post('items');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND is_array($items) AND count($items) > 0)
			{
				$this->load->model('admin_news_model');
				foreach ($items as $key => $val) $this->admin_news_model->update_image(intval($val), array('position' => intval($key)));

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Видалення зображення
		 */
		public function delete_image()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$image_id = intval($this->input->post('image_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $image_id > 0)
			{
				$this->load->model('admin_news_model');
				$this->admin_news_model->delete_image($image_id);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Видалення компоненту
		 *
		 * @return string
		 */
		public function delete_component()
		{
			$response = array('error' => 1);
			$menu_id = intval($this->input->post('menu_id'));
			$component_id = intval($this->input->post('component_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $component_id > 0)
			{
				$this->load->model('admin_news_model');
				$this->load->helper('file');

				$this->admin_news_model->delete_component($component_id);

				$response['error'] = 0;
			}

			return json_encode($response);
		}

		/**
		 * Видалення компоненту
		 *
		 * @return string
		 */
		public function delete_last_component()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$component_id = intval($this->input->post('component_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id) AND $component_id > 0)
			{
				$this->load->model('admin_news_model');

				$this->admin_news_model->delete_last_component($component_id);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}
	}