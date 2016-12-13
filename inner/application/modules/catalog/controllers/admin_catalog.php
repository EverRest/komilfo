<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	/**
	 * Class Admin_catalog
	 *
	 * @property Admin_catalog_model $admin_catalog_model
	 */

	class Admin_catalog extends MX_Controller
	{

		/**
		 * Додавання аптеки
		 */
		public function insert_catalog()
		{
			$response = array('success' => false);

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index')
			) {
				$this->load->model('admin_catalog_model');
				$menu_id = (int)$this->input->post('menu_id');
				$component_id = (int)$this->input->post('component_id');
				$data = array('menu_id' => $menu_id, 'component_id' => $component_id);
				$catalog_id = $this->admin_catalog_model->insert_catalog($data);

				$response['catalog_id'] = $catalog_id;
				$response['success'] = true;

				$this->session->set_userdata('last_catalog', $catalog_id);
			}

			return json_encode($response);
		}

		/**
		 * Приховування/відображення аптеки
		 */
		public function catalog_visibility()
		{
			$response = array('success' => false);

			$catalog_id = (int)$this->input->post('catalog_id');
			$status = (int)$this->input->post('status');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index')
				and $catalog_id > 0
				and in_array($status, array(0, 1), true)
			) {
				$this->load->model('admin_catalog_model');

				$this->admin_catalog_model->update_catalog(
					$catalog_id,
					array(
						'hidden' => $status,
					)
				);

				$this->session->set_userdata('last_catalog', $catalog_id);

				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Видалення апткеки
		 */
		public function delete_catalog()
		{
			$response = array('success' => false);

			$catalog_id = (int)$this->input->post('catalog_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index')
				and $catalog_id > 0
			) {
				$this->load->model('admin_catalog_model');
				$this->load->helper('directory');
				$this->load->helper('file');

				$this->admin_catalog_model->delete_catalog($catalog_id);

				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Редагування аптеки
		 */
		public function edit()
		{
			$menu_id = (int)$this->input->get('menu_id');
			$catalog_id = (int)$this->input->get('catalog_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index', $menu_id)
				and $catalog_id > 0
			) {
				$this->init_model->set_menu_id($menu_id, true);
				$this->session->set_userdata('last_catalog', $catalog_id);

				$this->template_lib
					->set_title('Редагування аптеки')
					->set_admin_menu_active('catalog')
					->set_admin_menu_active('catalog', 'sub_level');

				$this->load->model('admin_catalog_model');

				$component_id = $this->admin_catalog_model->get_catalog_by_id($catalog_id);
				$this->load->config('catalog');

				$this->template_lib->set_content(
					$this->load->view(
						'catalog/admin/edit_tpl',
						array(
							'menu_id' => $menu_id,
							'component_id' =>$component_id,
							'catalog_id' => $catalog_id,
							'catalog' => $this->admin_catalog_model->get_catalog($catalog_id),
							'catalog_images' => $this->admin_catalog_model->get_catalog_images($catalog_id),
							'menu' => $this->admin_catalog_model->get_menu(),
							'markers' => $this->admin_catalog_model->get_all_markers(),
							'languages' => (array)$this->config->item('languages'),
							'thumb' => (array)$this->config->item('catalog_thumb_sizes'),
						),
						true
					)
				);
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження порядку сортування новин
		 */
		public function save_position()
		{
			$response = array('success' => FALSE);
			$menu_id = $this->input->post('menu_id');
			$catalog_id = $this->input->post('catalog_id');
			$position = $this->input->post('position');
			
			if ($this->init_model->is_admin() AND $this->init_model->check_access('catalog_index', $menu_id) AND is_array($position))
			{
				$this->load->model('admin_catalog_model');
				foreach ($position as $key => $val)
				{
					$set = array('position' => $key);
					$where = array('catalog_id' => $val);
					$this->admin_catalog_model->update($set, $where);
				}
				$response['success'] = TRUE;
				$this->session->set_userdata('last_catalog', $catalog_id);
			}
			return json_encode($response);
		}

		/**
		 * Збереження даних аптеки
		 *
		 * @return string
		 */
		public function save()
		{
			$response = array('success' => false);
			$menu_id = (int)$this->input->post('menu_id');
			$catalog_id = (int)$this->input->post('catalog_id');
			$count = $this->input->post('count');
			$country = $this->input->post('country');
			$city = $this->input->post('city');
			$text = $this->input->post('text');

			for ($i=0; $i <= $count ; $i++) { 
				$on_main[] = $this->input->post('on_main_'.$i, true);
			}
			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index')
				and $catalog_id > 0
			) {
				$this->load->model('admin_catalog_model');
				$this->load->helpers(array('form', 'translit'));
				$region_id = 0;

				$set = array();
				$title = (array)$this->input->post('title');
				$set['shops'] = serialize($on_main);
				foreach ($title as $lang => $_title) {
					$set['title_' . $lang] = form_prep($_title);
					$set['text_' . $lang] = form_prep($text[$lang]);
					$set['url_' . $lang] = ($_title != ''? translit($_title, $lang) : $catalog_id);
				}
				// $set['position'] = $this->admin_catalog_model->get_catalog_position($catalog_id);
				$set['menu_id'] = $menu_id;
				$set['country'] = $country;
				$set['city'] = $city;
				//true для запису хешів в таблицю site_links

				$this->admin_catalog_model->update_catalog($catalog_id, $set, true);

				// $images = array_map('intval', (array)$this->input->post('marker'));
				// $this->admin_catalog_model->update_images($catalog_id, $images);

				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Завантаження зображення
		 *
		 * @return string
		 */
		public function upload_photo()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$catalog_id = (int)$this->input->post('catalog_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index', $menu_id)
				and $catalog_id > 0
			) {
				$this->load->helper('translit');
				$this->load->helper('directory');

				$dir = get_dir_path('upload/catalog/' . get_dir_code($catalog_id) . '/' . $catalog_id);

				$this->load->library(
					'upload',
					array(
						'upload_path' => $dir,
						'overwrite' => false,
						'file_name' => translit_filename($_FILES['image']['name']),
						'allowed_types' => 'gif|jpg|jpeg|png',
					)
				);

				if ($this->upload->do_upload('image')) {
					$this->load->library('image_lib');
					$this->load->model('admin_catalog_model');
					$this->load->config('catalog');

					$file_name = $this->upload->data('file_name');

					$real_sizes = getimagesize($dir . $file_name);
					$image_source = (array)$this->config->item('catalog_image_source');
					$thumb_sizes = (array)$this->config->item('catalog_thumb_sizes');

					$prop = $image_source[0]/$image_source[1];

					$width = ($real_sizes[0] < $image_source[0]) ? $real_sizes[0] : $image_source[0];
					$height = ($real_sizes[0] < $image_source[0]) ? round($real_sizes[1] / $prop) : $image_source[1];

					$this->image_lib->resize($dir . $file_name, $dir . 's_' . $file_name, $width, $height);
					$this->image_lib->resize_crop($dir . $file_name, $dir . $file_name, $image_source[0], $image_source[1], false);

					$image_id = $this->admin_catalog_model->insert_images(
						array(
							'catalog_id' => $catalog_id,
							'photo' => $file_name,
						)
					);

					$response['success'] = true;
					$response['width'] = $image_source[0];
					$response['height'] = $image_source[1];
					$response['image_id'] = $image_id;
					$response['photo'] = $file_name . '?t=' . time() . mt_rand(10000, 1000000);
				}
			}

			$this->config->set_item('is_ajax_request', true);
			return json_encode($response);
		}

		/**
		 * Обрізка зображення
		 *
		 * @return string
		 */
		public function crop_photo()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$image_id = (int)$this->input->post('image_id');
			$catalog_id = (int)$this->input->post('catalog_id');

			$coords = $this->input->post('coords');
			$coords = array_map('floatval', $coords);
			$width = (int)$this->input->post('width');
			
			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index', $menu_id)
				and $image_id > 0
				and is_numeric($coords['x'])
				and is_numeric($coords['y'])
				and $coords['w'] >= 0
				and $coords['h'] >= 0
				and $width > 0
			) {
				$this->load->model('admin_catalog_model');

				$image = $this->admin_catalog_model->get_image($image_id);
				
				if ($image !== null) {
					$this->load->library('Image_lib');
					$this->load->helper('directory');
					$this->load->config('catalog');

					$dir = get_dir_path('upload/catalog/' . get_dir_code($catalog_id) . '/' . $catalog_id);
					// echo $dir . 's_' . $image['photo'];exit();
					if (file_exists($dir . 's_' . $image['photo'])) {
						$real_sizes = getimagesize($dir . 's_' . $image['photo']);
						$thumb_sizes = (array)$this->config->item('catalog_thumb_sizes');

						$w_index = $real_sizes[0] / $width;
						$h_index = $real_sizes[1] / round(($width * $real_sizes[1]) / $real_sizes[0]);

						$x = round($coords['x'] * $w_index);
						$y = round($coords['y'] * $h_index);
						$x2 = round($coords['x2'] * $w_index);
						$y2 = round($coords['y2'] * $h_index);

						$this->image_lib->crop(
							$dir . 's_' . $image['photo'],
							$dir . $image['photo'],
							$x2 - $x,
							$y2 - $y,
							$x,
							$y,
							$thumb_sizes[0],
							$thumb_sizes[1]
						);

						$this->admin_catalog_model->update_image(
							$image_id,
							array(
								'is_crop' => 1,
								'crop_x' => $x,
								'crop_y' => $y,
								'crop_x2' => $x2,
								'crop_y2' => $y2,
							)
						);

						$response['src'] = '/upload/catalog/' . get_dir_code($catalog_id) . '/' . $catalog_id . '/' . $image['photo'] . '?t' . time() . mt_rand(10000, 1000000);
						$response['success'] = true;
					}
				}
			}

			return json_encode($response);
		}

		/**
		 * Накладання водяного знаку на зображення
		 *
		 * @return string
		 */
		public function watermark_photo()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$image_id = (int)$this->input->post('image_id');
			$catalog_id = (int)$this->input->post('catalog_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index', $menu_id)
				and $catalog_id > 0
				and (string)$this->config->item('watermark') !== ''
			) {
				$this->load->model('admin_catalog_model');

				$image = $this->admin_catalog_model->get_image($image_id);

				if ($image !== null) {
					$this->load->library('Image_lib');
					$this->load->helper('directory');
					$this->load->config('catalog');

					$dir = get_dir_path('upload/catalog/' . get_dir_code($catalog_id) . '/' . $catalog_id);

					if (file_exists($dir . 's_' . $image['photo'])) {
						$image_source = (array)$this->config->item('catalog_image_source');
						if ((int)$image['is_crop'] === 1) {
							$this->image_lib->crop(
								$dir . 's_' . $image['photo'],
								$dir . $image['photo'],
								$image['crop_x2'] - $image['crop_x'],
								$image['crop_y2'] - $image['crop_y'],
								$image['crop_x'],
								$image['crop_y'],
								$image_source[0],
								$image_source[1]
							);
						} else {
							$this->image_lib->resize_crop(
								$dir . 's_' . $image['photo'],
								$dir . $image['photo'],
								$image_source[0],
								$image_source[1]
							);
						}

						$position = (int)$this->input->post('position');

						if ($position > 0) {
							$this->image_lib->watermark(
								$dir . $image['photo'],
								$dir . $image['photo'],
								get_dir_path('upload/watermarks') . $this->config->item('watermark'),
								$position,
								$this->config->item('watermark_padding'),
								$this->config->item('watermark_opacity')
							);
						}

						$this->admin_catalog_model->update_image(
							$image_id,
							array(
								'watermark_position' => $position,
							)
						);

						$response['success'] = true;
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
		public function remove_photo()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$image_id = (int)$this->input->post('image_id');
			$catalog_id = (int)$this->input->post('catalog_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index', $menu_id)
				and $catalog_id > 0
			) {
				$this->load->model('admin_catalog_model');
				$this->load->helper('directory');
				$this->load->helper('file');

				$image = $this->admin_catalog_model->get_image($image_id);

				if ($image !== null and $image['photo'] !== '') {
					$dir = get_dir_path('upload/catalog/' . get_dir_code($catalog_id) . '/' . $catalog_id, false);

					if (file_exists($dir . $image['photo'])) {
						unlink($dir . $image['photo']);
					}

					if (file_exists($dir . 's_' . $image['photo'])) {
						unlink($dir . 's_' . $image['photo']);
					}

					if (file_exists($dir . 't_' . $image['photo'])) {
						unlink($dir . 't_' . $image['photo']);
					}

					$this->admin_catalog_model->delete_image(
						$image_id
					);

					$response['success'] = true;
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
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$component_id = (int)$this->input->post('component_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('photos_index', $menu_id)
				and $component_id > 0
			) {
				$this->load->model('admin_catalog_model');

				$this->admin_catalog_model->delete_component($component_id);

				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Зображення
		 */
		public function images()
		{
			$menu_id = (int)$this->input->get('menu_id');
			$component_id = (int)$this->input->get('component_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_open', $menu_id)
				and $component_id > 0
			) {
				$this->init_model->set_menu_id($menu_id, true);

				$this->template_lib
					->set_title('Редагування зображень в блоці "Скоро відкриття/ми відкрились"')
					->set_admin_menu_active('components')
					->set_admin_menu_active('', 'sub_level');

				$this->load->model('admin_catalog_model');
				$this->load->config('catalog');

				$this->template_lib->set_content(
					$this->load->view(
						'catalog/admin/edit_images_tpl',
						array(
							'menu_id' => $menu_id,
							'component_id' => $component_id,
							'config' => $this->init_model->get_component_config($component_id),

							'thumb' => (array)$this->config->item('open_thumb_sizes'),
							'big' => (array)$this->config->item('open_big_sizes'),
						),
						true
					)
				);
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження порядку виводу зображень
		 *
		 * @return string
		 */
		public function position_photos()
		{
			$response = array('success' => false);
			$menu_id = (int)$this->input->post('menu_id');
			$photos = (array)$this->input->post('photos');
			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_index', $menu_id)
				and count($photos) > 0
			) {
				$this->load->model('admin_catalog_model');
				foreach ($photos as $position => $photo_id) {
					$this->admin_catalog_model->update_image(
						(int)$photo_id,
						array(
							'position' => (int)$position
						)
					);
				}
				$response['success'] = true;
			}
			return json_encode($response);
		}

		/**
		 * Завантаження зображення
		 *
		 * @return string
		 */
		public function upload_open_photo()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$component_id = (int)$this->input->post('component_id');
			$type = (string)$this->input->post('type');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_open', $menu_id)
				and $component_id > 0
				and in_array($type, array('open', 'opened'), true)
			) {
				$this->load->helper('translit');
				$this->load->helper('directory');

				$dir = get_dir_path('upload/open/' . $component_id);

				$this->load->library(
					'upload',
					array(
						'upload_path' => $dir,
						'overwrite' => false,
						'file_name' => translit_filename($_FILES['image']['name']),
						'allowed_types' => 'gif|jpg|jpeg|png',
					)
				);

				if ($this->upload->do_upload('image')) {
					$this->load->library('image_lib');
					$this->load->config('catalog');

					$file_name = $this->upload->data('file_name');

					$real_sizes = getimagesize($dir . $file_name);
					// $image_source = (array)$this->config->item('open_image_source');
					// $big_sizes = (array)$this->config->item('open_big_sizes');
					// $thumb_sizes = (array)$this->config->item('open_thumb_sizes');

					// $width = ($real_sizes[0] < $image_source[0]) ? $real_sizes[0] : $image_source[0];
					// $height = round(($width * $real_sizes[1]) / $real_sizes[0]);

					// $this->image_lib->resize($dir . $file_name, $dir . 's_' . $file_name, $width, $height);
					// $this->image_lib->resize_crop($dir . $file_name, $dir . $file_name, $big_sizes[0], $big_sizes[1], false);
					// $this->image_lib->resize($dir . $file_name, $dir . 't_' . $file_name, $thumb_sizes[0], $thumb_sizes[1], false);

					$config = $this->init_model->get_component_config($component_id);
					$config[$type]['file_name'] = $file_name;
					$this->init_model->set_component_config($component_id, $config);

					$response['success'] = true;
					$response['width'] = $real_sizes[0];
					$response['height'] = $real_sizes[1];
					$response['file_name'] = $file_name . '?t=' . time() . mt_rand(10000, 1000000);
				}
			}

			$this->config->set_item('is_ajax_request', true);
			return json_encode($response);
		}

		/**
		 * Обрізка зображення
		 *
		 * @return string
		 */
		public function crop_open_photo()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$component_id = (int)$this->input->post('component_id');
			$type = (string)$this->input->post('type');

			$coords = $this->input->post('coords');
			$coords = array_map('floatval', $coords);

			$width = (int)$this->input->post('width');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_open', $menu_id)
				and $component_id > 0
				and in_array($type, array('open', 'opened'), true)
				and is_numeric($coords['x'])
				and is_numeric($coords['y'])
				and $coords['w'] >= 0
				and $coords['h'] >= 0
				and $width > 0
			) {
				$config = $this->init_model->get_component_config($component_id);

				if (array_key_exists($type, $config)) {
					$this->load->library('Image_lib');
					$this->load->helper('directory');
					$this->load->config('catalog');

					$dir = get_dir_path('upload/open/' . $component_id);

					if (file_exists($dir . 's_' . $config[$type]['file_name'])) {
						$real_sizes = getimagesize($dir . 's_' . $config[$type]['file_name']);
						$thumb_sizes = (array)$this->config->item('open_thumb_sizes');
						$big_sizes = (array)$this->config->item('open_big_sizes');

						$w_index = $real_sizes[0] / $width;
						$h_index = $real_sizes[1] / round(($width * $real_sizes[1]) / $real_sizes[0]);

						$x = round($coords['x'] * $w_index);
						$y = round($coords['y'] * $h_index);
						$x2 = round($coords['x2'] * $w_index);
						$y2 = round($coords['y2'] * $h_index);

						$this->image_lib->crop(
							$dir . 's_' . $config[$type]['file_name'],
							$dir . $config[$type]['file_name'],
							$x2 - $x,
							$y2 - $y,
							$x,
							$y,
							$big_sizes[0],
							$big_sizes[1]
						);

						$this->image_lib->resize(
							$dir . $config[$type]['file_name'],
							$dir . 't_' . $config[$type]['file_name'],
							$thumb_sizes[0],
							$thumb_sizes[1]
						);

						$config[$type]['is_crop'] = 1;
						$config[$type]['crop_x'] = $x;
						$config[$type]['crop_y'] = $y;
						$config[$type]['crop_x2'] = $x2;
						$config[$type]['crop_y2'] = $y2;

						$this->init_model->set_component_config($component_id, $config);

						$response['src'] = '/upload/open/' .$component_id . '/t_' . $config[$type]['file_name'] . '?t' . time() . mt_rand(10000, 1000000);
						$response['success'] = true;
					}
				}
			}

			return json_encode($response);
		}

		/**
		 * Накладання водяного знаку на зображення
		 *
		 * @return string
		 */
		public function watermark_open_photo()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$component_id = (int)$this->input->post('component_id');
			$type = (string)$this->input->post('type');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_open', $menu_id)
				and $component_id > 0
				and in_array($type, array('open', 'opened'), true)
				and (string)$this->config->item('watermark') !== ''
			) {
				$config = $this->init_model->get_component_config($component_id);

				if (array_key_exists($type, $config)) {
					$this->load->library('Image_lib');
					$this->load->helper('directory');
					$this->load->config('catalog');

					$dir = get_dir_path('upload/open/' . $component_id, false);

					if (file_exists($dir . 's_' . $config[$type]['file_name'])) {
						$big_sizes = (array)$this->config->item('open_big_sizes');

						if (array_key_exists('is_crop', $config[$type]) and (int)$config[$type]['is_crop'] === 1) {
							$this->image_lib->crop(
								$dir . 's_' . $config[$type]['file_name'],
								$dir . $config[$type]['file_name'],
								$config[$type]['crop_x2'] - $config[$type]['crop_x'],
								$config[$type]['crop_y2'] - $config[$type]['crop_y'],
								$config[$type]['crop_x'],
								$config[$type]['crop_y'],
								$big_sizes[0],
								$big_sizes[1]
							);
						} else {
							$this->image_lib->resize_crop(
								$dir . 's_' . $config[$type]['file_name'],
								$dir . $config[$type]['file_name'],
								$big_sizes[0],
								$big_sizes[1]
							);
						}

						$position = (int)$this->input->post('position');

						if ($position > 0) {
							$this->image_lib->watermark(
								$dir . $config[$type]['file_name'],
								$dir . $config[$type]['file_name'],
								get_dir_path('upload/watermarks') . $this->config->item('watermark'),
								$position,
								$this->config->item('watermark_padding'),
								$this->config->item('watermark_opacity')
							);
						}

						$config[$type]['watermark_position'] = $position;
						$this->init_model->set_component_config($component_id, $config);

						$response['success'] = true;
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
		public function remove_open_photo()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$component_id = (int)$this->input->post('component_id');
			$type = (string)$this->input->post('type');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('catalog_open', $menu_id)
				and $component_id > 0
				and in_array($type, array('open', 'opened'), true)
			) {
				$this->load->helper('directory');
				$this->load->helper('file');

				$config = $this->init_model->get_component_config($component_id);

				if (array_key_exists($type, $config)) {
					$dir = get_dir_path('upload/open/' . $component_id, false);

					if (file_exists($dir . $config[$type]['file_name'])) {
						unlink($dir . $config[$type]['file_name']);
					}

					if (file_exists($dir . 's_' . $config[$type]['file_name'])) {
						unlink($dir . 's_' . $config[$type]['file_name']);
					}

					if (file_exists($dir . 't_' . $config[$type]['file_name'])) {
						unlink($dir . 't_' . $config[$type]['file_name']);
					}

					unset($config[$type]);
					$this->init_model->set_component_config($component_id, $config);

					$response['success'] = true;
				}
			}

			return json_encode($response);
		}

		/**
		 * Видалення компоненту
		 *
		 * @return string
		 */
		public function delete_open_component()
		{
			$response = array('success' => false);

			$menu_id = (int)$this->input->post('menu_id');
			$component_id = (int)$this->input->post('component_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('photos_open', $menu_id)
				and $component_id > 0
			) {
				$this->load->model('admin_catalog_model');

				$this->load->helper('directory');
				$this->load->helper('file');

				$dir = get_dir_path('upload/open/' . $component_id, false);

				if (file_exists($dir)) {
					delete_files($dir, true, true, 1);
				}

				$this->admin_catalog_model->delete_component($component_id);

				$response['success'] = true;
			}

			return json_encode($response);
		}
	}