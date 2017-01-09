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
			$menu_id = intval($this->input->get('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_common'))
			{
				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->template_lib->set_title('Загальні налаштування сайту');
				$this->template_lib->set_js('admin/checkboxes.js');

				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('common', 'sub_level');

				$this->load->model('admin_config_model');

				$vars = array(
					'site_email' => '',
					'print_icon' => '',
					'delete_alert' => '',
					'percent' => '',
				);

				$template_data = array(
					'menu_id' => $menu_id,
					'config' => $this->admin_config_model->config_get($vars),
				);

				$this->template_lib->set_content($this->load->view('common_tpl', $template_data, TRUE));
			}
			else
			{
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
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_common'))
			{
				$this->load->model('admin_config_model');

				$vars = array(
					'site_email' => $this->input->post('site_email'),
					'print_icon' => $this->input->post('print_icon'),
					'delete_alert' => $this->input->post('delete_alert')
				);
				$this->admin_config_model->config_set($vars);

				$this->cache->delete('db_config');
				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Хедер сайту
		 */

		public function header()
		{
			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_header'))
			{
				$menu_id = intval($this->input->get('menu_id'));

				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->template_lib->set_title('Налаштування шапки сайту');
				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('header', 'sub_level');
				$this->load->model('admin_config_model');

				$template_data = array(
					'languages' => $this->config->item('languages'),
					'data' => $this->admin_config_model->get_header()
				);

				$this->template_lib->set_content($this->load->view('header_tpl', $template_data, TRUE));
			}
		}

        /**
         * Swiper сайту
         */

        public function swiper()
        {
            if ($this->init_model->is_admin() AND $this->init_model->check_access('config_header'))
            {
                $menu_id = intval($this->input->get('menu_id'));

                $this->init_model->set_menu_id($menu_id, TRUE);

                $this->template_lib->set_title('Налаштування свайпера сайту');
                $this->template_lib->set_admin_menu_active('config');
                $this->template_lib->set_admin_menu_active('swiper', 'sub_level');
                $this->load->model('admin_config_model');

                $template_data = array(
                    'languages' => $this->config->item('languages'),
                    'swiper' => $this->admin_config_model->get_swiper()
                );

                $this->template_lib->set_content($this->load->view('swiper_tpl', $template_data, TRUE));
            }
        }

        /**
         * Збереження налаштувань свайпера
         */

        public function save_swiper()
        {
            $response = array('success' => FALSE);

            if ($this->init_model->is_admin() AND $this->init_model->check_access('config_header'))
            {
                $this->load->model('admin_config_model');
                $this->load->helper('form');
                echo '<pre>';print_r($this->input->post());
                $slogan = $this->input->post('slogan');
                $phone1 = $this->input->post('kyivstar');
                $phone2 = $this->input->post('life');
                $phone3 = $this->input->post('mts');

                $off_timer = $this->input->post('off_timer') == 'on' ? 1 : 0;

//				foreach ($slogan as $key => $val)
//				{
//					$data['slogan_' . $key] = form_prep($val);
                $data['kyivstar_' . 'ua'] = form_prep($phone1["ua"]);
                $data['life_' . 'ua'] = form_prep($phone2["ua"]);
//					$data['mts_' . $key] = form_prep($phone3[$key]);

//					if ($date[$key] != '')
//					{
//						$date[$key] = ($time[$key] != '') ? strtotime($date[$key] . ' ' . $time[$key]) : strtotime($date[$key]);
//					}
//					else
//					{
//						$date[$key] = time();
//					}
//				}



                $this->admin_config_model->save_header($data);

                $response['success'] = TRUE;
            }

            return json_encode($response);
        }
		/**
		 * Збереження налаштувань хедера
		 */
		public function save_header()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_header'))
			{
				$this->load->model('admin_config_model');
				$this->load->helper('form');

				$slogan = $this->input->post('slogan');
				$phone1 = $this->input->post('kyivstar');
				$phone2 = $this->input->post('life');
				$phone3 = $this->input->post('mts');

				$off_timer = $this->input->post('off_timer') == 'on' ? 1 : 0;

//				foreach ($slogan as $key => $val)
//				{
//					$data['slogan_' . $key] = form_prep($val);
					$data['kyivstar_' . ua] = form_prep($phone1["ua"]);
					$data['life_' . ua] = form_prep($phone2["ua"]);
//					$data['mts_' . $key] = form_prep($phone3[$key]);

//					if ($date[$key] != '')
//					{
//						$date[$key] = ($time[$key] != '') ? strtotime($date[$key] . ' ' . $time[$key]) : strtotime($date[$key]);
//					}
//					else
//					{
//						$date[$key] = time();
//					}
//				}

				

				$this->admin_config_model->save_header($data);

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Підвал сайту (мапа)
		 */

		public function main_footer()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_languages'))
			{

				$this->init_model->set_menu_id($menu_id, TRUE);
				$this->template_lib->set_title('Налаштування футера сайту');
				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('footer', 'sub_level');
				$this->load->model('admin_config_model');

				$template_data = array(
					'menu_id' => $menu_id,
					'data' => $this->admin_config_model->get_map(),
					'languages' => $this->config->item('languages'), 
				);
				$this->template_lib->set_content($this->load->view('footer_tpl', $template_data, TRUE));

				$this->init_model->set_menu_id($menu_id, TRUE);

			}
		}

		/**
		 * Збереження інформації футера (мапа)
		 */
		public function update_footer()
		{
			$response = array('error' => 1);

			$this->load->model('admin_config_model');
			$this->load->helper('form');

			$set = array(
				'vk' => $this->input->post('vk', TRUE),
				'fb' => $this->input->post('fb', TRUE),
				'ing' => $this->input->post('gplus'),
			);

			$this->admin_config_model->update($set);

			$response['error'] = 0;

			return json_encode($response);
		}

		/**
		 * Мовні налаштування
		 */
		public function languages()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_languages'))
			{
				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->template_lib->set_title('Налаштування мов сайту');
				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('languages', 'sub_level');

				$this->load->model('admin_config_model');

				$vars = array(
					'def_lang' => '',
					'languages' => '',
				);

				$template_data = array(
					'menu_id' => $menu_id,
					'config' => $this->admin_config_model->config_get($vars),
					'languages' => $this->config->item('languages'),
				);

				$this->template_lib->set_content($this->load->view('languages_tpl', $template_data, TRUE));
			}
			else
			{
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
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_languages'))
			{
				$this->load->model('admin_config_model');

				$vars = array(
					'def_lang' => $this->input->post('def_lang'),
					'languages' => serialize($this->input->post('languages')),
				);
				$this->admin_config_model->config_set($vars);
                                $response['success']=TRUE;
				$this->cache->delete('db_config');
			}

			return json_encode($response);
		}

		/**
		 * Водяний знак
		 */
		public function watermark()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_watermark'))
			{
				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->template_lib->set_title('Водяний знак');

				$this->template_lib->set_admin_menu_active('config');
				$this->template_lib->set_admin_menu_active('watermark', 'sub_level');

				$menu_id = intval($this->input->get('menu_id'));
				$this->init_model->set_menu_id($menu_id, TRUE);

				$this->load->model('admin_config_model');

				$vars = array(
					'watermark' => '',
					'watermark_padding' => '',
					'watermark_opacity' => '',
				);

				$template_data = array(
					'menu_id' => $menu_id,
					'config' => $this->admin_config_model->config_get($vars),
				);

				$this->template_lib->set_content($this->load->view('watermark_tpl', $template_data, TRUE));
			}
			else
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Збереження параметрів водяного знаку
		 *
		 * @return string
		 */
		public function save_watermark()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_exchange'))
			{
				$this->load->model('admin_config_model');

				$vars = array(
					'watermark_padding' => $this->input->post('watermark_padding'),
					'watermark_opacity' => $this->input->post('watermark_opacity')
				);
				$this->admin_config_model->config_set($vars);

				$this->cache->delete('db_config');
				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Завантаження зображення водяного знаку
		 *
		 * @return string
		 */
		public function upload_watermark()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_exchange'))
			{
				$dir = ROOT_PATH . 'upload/watermarks/';
				if (!file_exists($dir)) mkdir($dir);

				$this->load->helper('translit');
				$file_name = translit_filename($_FILES['watermark_image']['name']);

				$upload_config = array(
					'upload_path' => $dir,
					'overwrite' => FALSE,
					'file_name' => $file_name,
					'allowed_types' => 'gif|jpg|jpeg|png'
				);

				$this->load->library('upload', $upload_config);

				if ($this->upload->do_upload('watermark_image'))
				{
					$file_name = $this->upload->data('file_name');

					$this->load->model('admin_config_model');
					$config = $this->admin_config_model->config_get(array('watermark' => ''));

					if ($config['watermark'] != '' AND $config['watermark'] != $file_name AND file_exists($dir . $config['watermark']))
					{
						unlink($dir . $config['watermark']);
					}

					$set = array('watermark' => $file_name);
					$this->admin_config_model->config_set($set);

					$response['success'] = TRUE;
					$response['file_name'] = $file_name . '?t=' . time() . rand(100000, 1000000);
				}

				$this->config->set_item('is_ajax_request', TRUE);
				$this->cache->delete('db_config');
			}

			return json_encode($response);
		}

		/**
		 * Видалення зображення водяного знаку
		 *
		 * @return string
		 */
		public function delete_watermark()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('config_exchange'))
			{
				$this->load->model('admin_config_model');

				$config = $this->admin_config_model->config_get(array('watermark' => ''));

				if ($config['watermark'] != '')
				{
					$file = ROOT_PATH . 'upload/watermarks/' . $config['watermark'];
					if (file_exists($file)) unlink($file);

					$this->admin_config_model->config_set(array('watermark' => ''));
				}

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}
	}
