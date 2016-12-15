<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property Admin_slider_model $admin_slider_model
	 */

	class Admin_slider extends MX_Controller
	{
		protected $thumb_sizes=array(300, 300);

		/**
		 * Вивід списку слайдів
		 */
		public function index()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id))
			{
				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->template_lib->set_title('Управління слайдером');

				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('slider', 'sub_level');

				$this->load->model('admin_slider_model');

				$tpl_data = array(
					'menu_id' => $menu_id,
					'slides' => $this->admin_slider_model->get_slides($menu_id),
					'last_slide' => $this->session->userdata('last_slide'),
					'thumb' => $this->thumb_sizes,
				);
				$this->template_lib->set_content($this->load->view('admin/slider_tpl', $tpl_data, TRUE));
			}
			else
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Додавання слайду
		 *
		 * @return string
		 */
		public function add()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id))
			{
				$this->load->model('admin_slider_model');

				$response['slide_id'] = $this->admin_slider_model->add(intval($this->input->post('add_top')), $menu_id);
				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Редагування слайду
		 */
		public function edit()
		{
			$menu_id = intval($this->input->get('menu_id'));
			$slide_id = intval($this->input->get('slide_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id) AND $slide_id > 0)
			{
				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->template_lib->set_title('Управління слайдером - редагування слайду');

				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('slider', 'sub_level');

				$this->session->set_userdata('last_slide', $slide_id);
				$this->load->model('admin_slider_model');

				$tpl_data = array(
					'menu_id' => $menu_id,
					'slide_id' => $slide_id,
					'languages' => $this->config->item('languages'),
					'slide' => $this->admin_slider_model->get($slide_id),
					'thumb' => $this->thumb_sizes,
					'big' => $this->thumb_sizes,
				);

				$this->template_lib->set_content($this->load->view('admin/edit_tpl', $tpl_data, TRUE));
			}
			else
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження інформації про слайд
		 *
		 * @return string
		 */
		public function save()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->get('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id))
			{
				$this->load->model('admin_slider_model');
				$this->load->helper('form');
				// $url = $this->input->post('url');

				$title = $this->input->post('title');
                $description = $this->input->post('description');
				$hidden = $this->input->post('hidden');
				$set = array();
					
				foreach ($title as $key => $val)
				{
				    $set['title_' . $key] = $title[$key];
					$set['hidden_' . $key] = isset($hidden[$key]) ? 1 : 0;
				}
                foreach ($description as $key => $val)
                {
                    $set['description_' . $key] = form_prep($val);
                }
				

				// foreach ($url as $key => $val)
				// {
				// 	$set['url_' . $key] = $url[$key];
				// }
                $where = array('slide_id' => intval($this->input->post('slide_id')));
				$this->admin_slider_model->update($set, $where);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Завантаження слайду
		 *
		 * @return string
		 */
		public function upload()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$slide_id = intval($this->input->post('slide_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id) AND $slide_id > 0)
			{
				$dir = ROOT_PATH . 'upload/slider/';
				if (!file_exists($dir)) mkdir($dir);

				$dir .= $menu_id . '/';
				if (!file_exists($dir)) mkdir($dir);

				$dir .= $slide_id . '/';
				if (!file_exists($dir)) mkdir($dir);

				$this->load->helper('translit');

				$upload_config = array(
					'upload_path' => $dir,
					'overwrite' => FALSE,
					'file_name' => translit_filename($_FILES['slide_image']['name']),
					'allowed_types' => 'gif|jpg|jpeg|png'
				);

				$this->load->library('upload', $upload_config);
                $this->load->library('Image_lib');

				if ($this->upload->do_upload('slide_image'))
				{
				    $file_name = $this->upload->data('file_name');

					$size = getimagesize($dir . $file_name);
					$height = (1000 * $size[1]) / $size[0];

					if ($size[0] > 1000)
					{
						$this->image_lib->resize($dir . $file_name, $dir . 's_' . $file_name, 1000, $height);
						$this->image_lib->resize($dir . $file_name, $dir . $file_name, 1000, $height);
					}
					else
					{
						copy($dir . $file_name, $dir . 's_' . $file_name);
					}

					$this->load->library('Image_lib');

					$this->image_lib->resize_crop($dir . $file_name, $dir .'t_'. $file_name, $this->thumb_sizes[0], $this->thumb_sizes[1]);

					$this->load->model('admin_slider_model');

					$old_file_name = $this->admin_slider_model->get_image($slide_id, LANG);

					if ($old_file_name != '' AND $old_file_name != $file_name)
					{
						if (file_exists($dir . $old_file_name)) unlink($dir . $old_file_name);
						if (file_exists($dir . 's_' . $old_file_name)) unlink($dir . 's_' . $old_file_name);
					}

					$this->admin_slider_model->update_image($slide_id, $file_name);

					$response['success'] = TRUE;
					$response['slide_id'] = $slide_id;
					$response['width'] = $size[0];
					$response['height'] = $size[1];
					$response['file_name'] = $file_name . '?t=' . time() . rand(10000, 1000000);
				}
			}

			$this->config->set_item('is_ajax_request', TRUE);
			return json_encode($response);
		}

		/**
		 * Обрізка слайду
		 *
		 * @return string
		 */
		public function crop()
		{
			$this->init_model->is_admin('json');
			$response = array('success' => FALSE);
			 $image_id = intval($this->input->post('slide_id'));
			 $width = intval($this->input->post('width'));
			 $menu_id = intval($this->input->post('menu_id'));
			 $coords = $this->input->post('coords');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('slider_index', $menu_id))
			{
				if ($coords['x'] < 0) $coords['x'] = 0;
				if ($coords['y'] < 0) $coords['y'] = 0;
					if ($coords['x'] >= 0 AND $coords['y'] >= 0 AND $coords['w'] > 0 AND $coords['h'] > 0 AND $image_id > 0)
					{
						$image = $this->db->select('file_name_'.LANG.' as file_name, slide_id')->where('slide_id', $image_id)->get('slider')->row_array();

						if (count($image) > 0)
						{
							$dir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/upload/slider/' . $menu_id . '/' . $image['slide_id'] . '/';

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
								$this->image_lib->crop($dir . 's_' . $image['file_name'], $dir . $image['file_name'], $x2 - $x, $y2 - $y, $x, $y, 300, 300);

								$response['success'] = TRUE;
								$response['image'] = '/upload/slider/' . $menu_id . '/' . $image['slide_id'] .'/'. $image['file_name'] . '?t' . time();
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
		public function delete_photo()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$slide_id = intval($this->input->post('slide_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id) AND $slide_id > 0)
			{
				$this->load->model('admin_slider_model');
				$file_name = $this->admin_slider_model->get_image($slide_id, LANG);

				if ($file_name !== NULL AND $file_name != '')
				{
					$dir = ROOT_PATH . 'upload/slider/' . $menu_id . '/' . $slide_id . '/';

					if ($file_name != '')
					{
						if (file_exists($dir . $file_name)) unlink($dir . $file_name);
						if (file_exists($dir . 's_' . $file_name)) unlink($dir . 's_' . $file_name);

						$this->admin_slider_model->update_image($slide_id, '', LANG);
					}
				}

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Приховування слайду
		 *
		 * @return string
		 */
		public function hidden()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$slide_id = intval($this->input->post('slide_id'));
			$hidden = intval($this->input->post('hidden'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id) AND $slide_id > 0 AND is_numeric($hidden))
			{
				$this->load->model('admin_slider_model');

				$set = array('hidden' => $hidden);
				$where = array('slide_id' => $slide_id);
				$this->admin_slider_model->update($set, $where);

				$this->session->set_userdata('last_slide', $slide_id);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Збереження порядку сортування
		 */
		public function slides_position()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$slides = $this->input->post('slides');
			$slide_id = intval($this->input->post('slide_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id) AND is_array($slides))
			{
				$this->load->model('admin_slider_model');

				foreach ($slides as $key => $val)
				{
					if (is_numeric($key) AND is_numeric($val))
					{
						$set = array('position' => intval($key));
						$where = array('slide_id' => intval($val));
						$this->admin_slider_model->update($set, $where);
					}
				}

				$this->session->set_userdata('last_slide', $slide_id);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Видалення слайду
		 *
		 * @return string
		 */
		public function delete()
		{
			$response = array('success' => FALSE);
			$menu_id = intval($this->input->post('menu_id'));
			$slide_id = intval($this->input->post('slide_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_slider', $menu_id) AND $slide_id > 0)
			{
				$this->load->helper('file');

				$dir = ROOT_PATH . 'upload/slider/' . $menu_id . '/' . $slide_id . '/';
				if (file_exists($dir)) delete_files($dir, TRUE, FALSE, 1);

				$this->load->model('admin_slider_model');
				$this->admin_slider_model->delete($slide_id);

				$response['success'] = TRUE;
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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_index', $menu_id) AND $menu_id > 0 AND $component_id > 0)
			{
				$this->load->model('admin_slider_model');

				$this->admin_slider_model->delete_component($component_id);

				$this->init_model->set_menu_id($menu_id);
				$this->init_model->set_metatags();

				$response['error'] = 0;
			}

			return json_encode($response);
		}

	}