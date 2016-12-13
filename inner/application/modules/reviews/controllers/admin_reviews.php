<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_reviews
	 *
	 * @property Admin_reviews_model $admin_reviews_model
	 */
	class Admin_reviews extends MX_Controller
	{
		/**
		 * Додавання відгуку
		 *
		 * @return int|string
		 */
		public function insert()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$component_id = intval($this->input->post('component_id'));

				if ($component_id > 0 )
				{
					$this->load->model('admin_reviews_model');

					$set = array(
						'component_id' => $component_id,
						'menu_id' => $menu_id,
						'position' => $this->admin_reviews_model->get_position($component_id),
					);
					$review_id = $this->admin_reviews_model->insert($set);

					$this->session->set_userdata('last_review', $review_id);

					$response['success'] = TRUE;
					$response['review_id'] = $review_id;
				}
			}

			return json_encode($response);
		}

		/**
		 * Відображення/приховування відгуку
		 *
		 * @return string
		 */
		public function visible()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));
				$status = intval($this->input->post('status'));

				if ($review_id > 0 AND in_array($status, array(0, 1)))
				{
					$this->load->model('admin_reviews_model');

					$set = array('hidden' => $status);
					$where = array('review_id' => $review_id);
					$this->admin_reviews_model->update($set, $where);

					$this->session->set_userdata('last_review', $review_id);

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Редагування відгуку
		 */
		public function edit()
		{
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$this->template_lib->set_title('Редагування відгуку');

				$this->template_lib->set_admin_menu_active('components');
				$this->template_lib->set_admin_menu_active('', 'sub_level');

				$review_id = intval($this->input->get('review_id'));

				if ($review_id > 0)
				{
					$this->init_model->set_menu_id($review_id, TRUE);
					$this->session->set_userdata('last_review', $review_id);

					$this->load->model('admin_reviews_model');
					$this->load->helper('form');

					$tpl_data = array(
						'review' => $this->admin_reviews_model->get($review_id),
						'review_id' => $review_id,
						'menu_id' => $menu_id,
						'languages' => $this->config->item('languages'),
					);

					$content = $this->load->view('admin/edit_tpl', $tpl_data, TRUE);
					$this->template_lib->set_content($content);
				}
			}
			else
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження відгуку
		 */
		public function save()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));

				if ($review_id > 0)
				{
					$this->load->model('admin_reviews_model');
					$this->load->helper('translit');

					$title = $this->input->post('title');
					$text = $this->input->post('text');

					$set = array();
					foreach ($title as $key => $val)
					{
						$set['title_' . $key] = $val;
						$set['text_' . $key] = $text[$key];
					}

					$where = array('review_id' => $review_id);
					$this->admin_reviews_model->update($set, $where);

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Збереження порядку сортування відгуків
		 */
		public function save_position()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$position = $this->input->post('position');

				if (is_array($position))
				{
					$this->load->model('admin_reviews_model');

					foreach ($position as $key => $val)
					{
						$set = array('position' => intval($key));
						$where = array('review_id' => intval($val));
						$this->admin_reviews_model->update($set, $where);
					}

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Видалення відгуку
		 *
		 * @return string
		 */
		public function delete()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));

				if ($review_id > 0)
				{
					$this->load->model('admin_reviews_model');
					$this->load->helper('file');

					$this->admin_reviews_model->delete($review_id);

					$response['success'] = TRUE;
				}
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
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$component_id = intval($this->input->post('component_id'));

				if ($component_id > 0)
				{
					$this->load->model('admin_reviews_model');
					$this->load->helper('file');

					$this->admin_reviews_model->delete_component($component_id);

					$response['success'] = TRUE;
				}
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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_last', $menu_id))
			{
				$component_id = intval($this->input->post('component_id'));

				if ($component_id > 0)
				{
					$this->load->model('admin_reviews_model');
					$this->admin_reviews_model->delete_last_component($component_id);

					$response['error'] = 0;
				}
			}

			return json_encode($response);
		}

		### ЗОБРАЖЕННЯ ###

		/**
		 * Завантаження зображення статті
		 */
		public function upload_image()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));

				if ($review_id > 0)
				{
					$dir = ROOT_PATH . 'upload/reviews/';
					if (!file_exists($dir)) mkdir($dir);

					$dir .= $this->init_model->dir_by_id($review_id) . '/';
					if (!file_exists($dir)) mkdir($dir);

					$dir .= $review_id . '/';
					if (!file_exists($dir)) mkdir($dir);

					$this->load->helper('translit');
					$file_name = translit_filename($_FILES['review_image']['name']);

					$upload_config = array(
						'upload_path' => $dir,
						'overwrite' => FALSE,
						'file_name' => 's_' . $file_name,
						'allowed_types' => 'gif|jpg|png|jpeg'
					);

					$this->load->library('upload', $upload_config);

					if ($this->upload->do_upload('review_image'))
					{
						$upload_file_name = $this->upload->data('file_name');

						$sizes = getimagesize($dir . $upload_file_name);

						$width = $sizes[0] > 1000 ? 1000 : $sizes[0];
						$height = 1000 * $sizes[1] / $sizes[0];

						$this->load->library('Image_lib');
						$this->image_lib->resize_crop($dir . $upload_file_name, $dir . $upload_file_name, $width, $height);
						$this->image_lib->resize_crop($dir . $upload_file_name, $dir . $file_name, 132, 132);

						$this->load->model('admin_reviews_model');

						$set = array('image' => $file_name);
						$this->admin_reviews_model->update($set, array('review_id' => $review_id));

						$response['success'] = TRUE;
						$response['width'] = $width;
						$response['height'] = $height;
						$response['file_name'] = $file_name;
					}
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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));
				$width = intval($this->input->post('width'));

				$coords = $this->input->post('coords');
				$coords = array_map('floatval', $coords);

				if ($coords['x'] >= 0 AND $coords['y'] >= 0 AND $coords['w'] > 0 AND $coords['h'] > 0 AND $review_id > 0 AND $width > 0)
				{
					$this->load->model('admin_reviews_model');
					$image = $this->admin_reviews_model->get_review_image($review_id);
					//$image = $this->db->select('image')->where('review_id', $review_id)->get('reviews')->row('image');

					if (is_string($image) AND $image != '')
					{
						$dir = ROOT_PATH . 'upload/reviews/' . $this->init_model->dir_by_id($review_id) . '/' . $review_id . '/';

						if (file_exists($dir . 's_' . $image))
						{
							$sizes = getimagesize($dir . 's_' . $image);

							$w_index = $sizes[0] / $width;
							$h_index = $sizes[1] / round(($width * $sizes[1]) / $sizes[0]);

							$x = $coords['x'] * $w_index;
							$y = $coords['y'] * $h_index;
							$x2 = $coords['x2'] * $w_index;
							$y2 = $coords['y2'] * $h_index;

							$this->load->library('Image_lib');
							$this->image_lib->crop($dir . 's_' . $image, $dir . $image, $x2 - $x, $y2 - $y, $x, $y, 132, 132);

							$response['success'] = TRUE;
							$response['image'] = '/upload/reviews/' . $this->init_model->dir_by_id($review_id) . '/' . $review_id . '/' . $image . '?t=' . time() . rand(10000, 1000000);
						}
					}
				}
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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));

				if ($review_id > 0)
				{
					$this->load->model('admin_reviews_model');
					$this->admin_reviews_model->delete_image($review_id);

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		### ЛОГОТИП ###

		/**
		 * Завантаження логоипу
		 */
		public function upload_logo()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));

				if ($review_id > 0)
				{
					$dir = ROOT_PATH . 'upload/reviews/';
					if (!file_exists($dir)) mkdir($dir);

					$dir .= $this->init_model->dir_by_id($review_id) . '/';
					if (!file_exists($dir)) mkdir($dir);

					$dir .= $review_id . '/';
					if (!file_exists($dir)) mkdir($dir);

					$this->load->helper('translit');
					$file_name = translit_filename($_FILES['review_logo']['name']);

					$upload_config = array(
						'upload_path' => $dir,
						'overwrite' => FALSE,
						'file_name' => 's_' . $file_name,
						'allowed_types' => 'gif|jpg|png|jpeg'
					);

					$this->load->library('upload', $upload_config);

					if ($this->upload->do_upload('review_logo'))
					{
						$data = $this->upload->data();
						$upload_file_name = $data['file_name'];

						$this->load->library('Image_lib');

						if ($data['image_width'] > 1000) {
							$width = 1000;
							$height = $width * $data['image_height'] / $data['image_width'];

							$this->image_lib->resize($dir . $upload_file_name, $dir . $upload_file_name, $width, $height);
						}
						else
						{
							$width = $data['image_width'];
							$height = $data['image_height'];
						}

						$this->image_lib->resize($dir . $upload_file_name, $dir . $file_name, 262, 60);

						$this->load->model('admin_reviews_model');

						$set = array('logo' => $file_name);
						$this->admin_reviews_model->update($set, array('review_id' => $review_id));

						$response['success'] = TRUE;
						$response['width'] = $width;
						$response['height'] = $height;
						$response['file_name'] = $file_name;
					}
				}
			}

			$this->config->set_item('is_ajax_request', TRUE);
			return json_encode($response);
		}

		/**
		 * Обрізка логотипу
		 */
		public function crop_logo()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));
				$width = intval($this->input->post('width'));

				$coords = $this->input->post('coords');
				$coords = array_map('floatval', $coords);

				if ($coords['x'] >= 0 AND $coords['y'] >= 0 AND $coords['w'] > 0 AND $coords['h'] > 0 AND $review_id > 0 AND $width > 0)
				{
					$this->load->model('admin_reviews_model');
					$image = $this->admin_reviews_model->get_review_logo($review_id);

					if (is_string($image) AND $image != '')
					{
						$dir = ROOT_PATH . 'upload/reviews/' . $this->init_model->dir_by_id($review_id) . '/' . $review_id . '/';

						if (file_exists($dir . 's_' . $image))
						{
							$sizes = getimagesize($dir . 's_' . $image);

							$w_index = $sizes[0] / $width;
							$h_index = $sizes[1] / round(($width * $sizes[1]) / $sizes[0]);

							$x = $coords['x'] * $w_index;
							$y = $coords['y'] * $h_index;
							$x2 = $coords['x2'] * $w_index;
							$y2 = $coords['y2'] * $h_index;

							$this->load->library('Image_lib');
							$this->image_lib->crop($dir . 's_' . $image, $dir . $image, $x2 - $x, $y2 - $y, $x, $y, 108, 108);

							$response['success'] = TRUE;
							$response['image'] = '/upload/reviews/' . $this->init_model->dir_by_id($review_id) . '/' . $review_id . '/' . $image . '?t=' . time() . rand(10000, 1000000);
						}
					}
				}
			}

			return json_encode($response);
		}


		/**
		 * Видалення логотипу
		 */
		public function delete_logo()
		{
			$response = array('success' => FALSE);

			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$review_id = intval($this->input->post('review_id'));

				if ($review_id > 0)
				{
					$this->load->model('admin_reviews_model');
					$this->admin_reviews_model->delete_logo($review_id);

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}
	}