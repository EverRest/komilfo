<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property Admin_config_model $admin_config_model
	 */

	class Admin_config extends MX_Controller
	{
		/**
		 * Загальні налаштування
		 */
		public function common()
		{
			$menu_id = (int)$this->input->get('menu_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('config_common', $menu_id)
			) {
				$this->init_model->set_menu_id($menu_id, true);

				$this->template_lib
					->set_title('Загальні налаштування сайту')
					->set_admin_menu_active('config')
					->set_admin_menu_active('common', 'sub_level');

				$this->load->model('admin_config_model');
				$this->load->helper('form');
				$languages = $this->config->item('languages');

				$config_array = array(
					'site_email' => '',
					'print_icon' => 0,
					'delete_alert' => 1,
					'facebook_uri' => '',
					'vk_uri' => '',
				);
				
				foreach ($languages as $key => $val){
					$config_array['site_name_' . $key] = '';	
				}

				$this->template_lib->set_content(
					$this->load->view(
						'common_tpl',
						array(
							'languages' => $languages,
							'config' => $this->admin_config_model->get_config(
								$config_array
							)
						),
						true
					)
				);
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження загальних налаштувань
		 *
		 * @return string
		 */
		public function save_common()
		{
			$response = array('success' => false);

			$config = $this->input->post('config');
			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('config_common')
				and is_array($config)
			) {
				$this->load->model('admin_config_model');

				foreach ($config as $key => $val) {
					$this->admin_config_model->save_config(
						$key,
						is_array($val) ? serialize($val) : $val,
						!($key === 'address_' . LANG)
					);
				}

				$this->cache->delete('config/db');
				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Статична інформація
		 */

		public function static_information()
		{
			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_header'))
			{
				$menu_id = intval($this->input->get('menu_id'));

				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->template_lib->set_title('Налаштування шапки сайту');
				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('static_information', 'sub_level');
				$this->load->model('admin_config_model');

				$template_data = array(
					'languages' => $this->config->item('languages'),
					'data' => $this->admin_config_model->get_static_information('component_static_information')
				);

				$this->template_lib->set_content($this->load->view('static_information_tpl', $template_data, TRUE));
			}
		}

		/**
		 * Збереження налаштувань статичної інформації
		 */
		public function save_static_information()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_header'))
			{
				//map data
				
				
				$this->load->model('admin_config_model');
				$this->load->helper('form');

				$set = array(
					'center_lat' => $this->input->post('center_lat', TRUE),
					'center_lng' => $this->input->post('center_lng', TRUE),
					'zoom' => $this->input->post('zoom'),
					'marker_lat' => $this->input->post('marker_lat', TRUE),
					'marker_lng' => $this->input->post('marker_lng', TRUE),
				);

				$title = $this->input->post('title', TRUE);
				$description = $this->input->post('description', TRUE);
				// $languages = $this->config->item('languages');

				foreach ($title as $language => $val)
				{
					$set['title_' . $language] = form_prep($val);
					$set['description_' . $language] = form_prep($description[$language]);
				}

				//static data


				$floor = $this->input->post('floor', true);
				$address = $this->input->post('address',true);
				$slogan = $this->input->post('slogan',true);



				$set['vk'] = form_prep($this->input->post('vk',true));
				$set['fb'] = form_prep($this->input->post('fb',true));
				$set['tw'] = form_prep($this->input->post('tw',true));
				$set['yt'] = form_prep($this->input->post('yt',true));
				$set['gp'] = form_prep($this->input->post('gp',true));
				$set['ig'] = form_prep($this->input->post('ig',true));
				$set['code'] = form_prep($this->input->post('code', true));
				$set['phone_1'] = form_prep($this->input->post('phone1',true));
				$set['phone_2'] = form_prep($this->input->post('phone2',true));
				$set['phone_3'] = form_prep($this->input->post('phone3',true));
				$set['email'] = form_prep($this->input->post('email',true));
				$set['form_view'] = form_prep($this->input->post('form_view',true));
	
				foreach ($address as $key => $value) {
					$set['address_'.$key] = form_prep($value);
					$set['floor_'.$key] = form_prep($floor[$key]);
					$set['slogan_'.$key] = form_prep($slogan[$key]);
				}

				
				$this->admin_config_model->save_static_information($set);

				$response['success'] = TRUE;
			}
			return json_encode($response);
		}

		/**
		 * Мовні налаштування
		 */
		public function languages()
		{
			$menu_id = (int)$this->input->get('menu_id');

			if (
				$this->init_model->is_admin()
				and $this->init_model->check_access('config_languages', $menu_id)
			) {
				$this->init_model->set_menu_id($menu_id, true);

				$this->template_lib
					->set_title('Налаштування мов сайту')
					->set_admin_menu_active('config')
					->set_admin_menu_active('languages', 'sub_level');

				$this->load->model('admin_config_model');

				$this->template_lib->set_content(
					$this->load->view(
						'languages_tpl',
						array(
							'config' => $this->admin_config_model->get_config(
								array(
									'def_lang' => LANG,
									'languages' => '',
								)
							),
							'languages' => $this->config->item('languages'),
						),
						true
					)
				);
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження мовних налаштувань
		 *
		 * @return string
		 */
		public function save_languages()
		{
			$response = array('success' => false);

			$config = $this->input->post('config');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('config_languages')
				and
				is_array($config)
			) {
				$this->load->model('admin_config_model');

				foreach ($config as $key => $val) {
					$this->admin_config_model->save_config($key, is_array($val) ? serialize($val) : $val);
				}

				$this->cache->delete('config/db');
				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Водяний знак
		 */
		public function watermark()
		{
			$menu_id = (int)$this->input->get('menu_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('config_watermark', $menu_id)
			) {
				$this->init_model->set_menu_id($menu_id, true);

				$this->template_lib
					->set_title('Водяний знак')
					->set_admin_menu_active('config')
					->set_admin_menu_active('watermark', 'sub_level');

				$this->load->model('admin_config_model');

				$this->template_lib->set_content(
					$this->load->view(
						'watermark_tpl',
						array(
							'config' => $this->admin_config_model->get_config(
								array(
									'watermark' => '',
									'watermark_padding' => 0,
									'watermark_opacity' => 0
								)
							)
						),
						true
					)
				);
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження водяного знаку
		 *
		 * @return string
		 */
		public function save_watermark()
		{
			$response = array('success' => false);

			$config = $this->input->post('config');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('config_watermark')
				and
				is_array($config)
			) {
				$this->load->model('admin_config_model');

				foreach ($config as $key => $val) {
					$this->admin_config_model->save_config($key, $val);
				}

				$this->cache->delete('config/db');
				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Завантаження водяного знаку
		 *
		 * @return string
		 */
		public function upload_watermark()
		{
			$response = array('success' => false);

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('config_watermark')
			) {
				$this->load->helper('directory');
				$dir = get_dir_path('upload/watermarks');

				$this->load->helper('translit');
				$file_name = translit_filename($_FILES['watermark_image']['name']);

				$upload_config = array(
					'upload_path' => $dir,
					'overwrite' => false,
					'file_name' => $file_name,
					'allowed_types' => 'gif|jpg|jpeg|png',
				);

				$this->load->library('upload', $upload_config);

				if ($this->upload->do_upload('watermark_image')) {
					$file_name = $this->upload->data('file_name');

					$this->load->model('admin_config_model');
					$config = $this->admin_config_model->get_config(array('watermark' => ''));

					if (
						$config['watermark'] !== ''
						and
						$config['watermark'] !== $file_name
						and
						file_exists($dir . $config['watermark'])
					) {
						unlink($dir . $config['watermark']);
					}

					$this->admin_config_model->save_config('watermark', $file_name);

					$response['success'] = true;
					$response['file_name'] = $file_name . '?t=' . time() . mt_rand(100000, 1000000);

					$this->cache->delete('config/db');
				}
			}

			$this->config->set_item('is_ajax_request', TRUE);
			return json_encode($response);
		}

		/**
		 * Видалення водяного знаку
		 *
		 * @return string
		 */
		public function delete_watermark()
		{
			$response = array('success' => false);

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('config_watermark')
			) {
				$this->load->model('admin_config_model');

				$config = $this->admin_config_model->get_config(array('watermark' => ''));

				if ($config['watermark'] !== '') {
					$this->load->helper('directory');
					$dir = get_dir_path('upload/watermarks', false);

					if (file_exists($dir. $config['watermark'])) {
						unlink($dir. $config['watermark']);
					}

					$this->admin_config_model->save_config('watermark', '');
				}

				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Заглушка
		 */
		public function gag()
		{
			$menu_id = (int)$this->input->get('menu_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('config_gag', $menu_id)
			) {
				$this->init_model->set_menu_id($menu_id, true);

				$this->template_lib
					->set_title('Заглушка')
					->set_admin_menu_active('config')
					->set_admin_menu_active('gag', 'sub_level');

				$this->load->model('admin_config_model');

				$this->template_lib->set_content(
					$this->load->view(
						'gag_tpl',
						array(
							'config' => $this->admin_config_model->get_gag(),
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
		 * Збереження заглушки
		 *
		 * @return string
		 */
		public function save_gag()
		{
			$response = array('success' => false);

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('config_gag')
			) {
				$this->load->model('admin_config_model');

				$this->admin_config_model->save_gag(
					(int)$this->input->post('is_gag'),
					array(
						'ua' => str_replace(array("\r", "\n", "\t"), '', $this->input->post('ua')),
						'ru' => str_replace(array("\r", "\n", "\t"), '', $this->input->post('ru')),
						'en' => str_replace(array("\r", "\n", "\t"), '', $this->input->post('en')),
					)
				);

				$this->cache->delete('config/db');
				$response['success'] = true;
			}

			return json_encode($response);
		}
	}