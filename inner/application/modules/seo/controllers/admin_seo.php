<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_seo
	 *
	 * @property Admin_seo_model $admin_seo_model
	 */
	class Admin_seo extends MX_Controller
	{
		/**
		 * Форма редагування метатегів
		 */
		public function tags()
		{
			$menu_id = (int)$this->input->get('menu_id');
			$item_id = (int)$this->input->get('item_id');
			$module = (string)$this->input->get('module');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('seo_tags', $menu_id)
				and
				$item_id >= 0
			) {
				if ($module !== '') {
					$modules = $this->config->item('frontend_modules');

					if (!in_array($module, $modules, true)) {
						show_error('Unknown module', 500, 'URL error');
					}
				}

				$this->init_model->set_menu_id($menu_id, true);

				$this->template_lib
					->set_title('Управління мета тегами')
					->set_admin_menu_active('seo')
					->set_admin_menu_active('tags', 'sub_level');

				$this->load->model('admin_seo_model');
				$languages = $this->config->item('languages');

				$this->template_lib->set_content(
					$this->load->view(
						'tags_tpl',
						array(
							'menu_id' => $menu_id,
							'item_id' => $item_id,
							'module' => $module,
							'languages' => $languages,
							'tags' => $this->admin_seo_model->get_tags($menu_id, $item_id, $module, array_keys($languages))
						),
						true
					)
				);
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Генерація ключових слів
		 */
		public function generate_keywords()
		{
			$response = array('success' => false);

			$languages = $this->config->item('languages');
			$language = $this->input->post('language');
			$tags_id = (int)$this->input->post('tags_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('seo_tags')
				and
				$tags_id > 0
				and
				isset($languages[$language])
			) {
				$this->load->library('seo_lib');
				$this->load->model('admin_seo_model');
				$this->load->helper('form');

				$response['keywords'] = $this->admin_seo_model->get_keywords($tags_id, $language);
				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Збереження метатегів
		 */
		public function update_tags()
		{
			$response = array('success' => false);

			$tags_id = (int)$this->input->post('tags_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('seo_tags')
				and
				$tags_id > 0
			) {
				$this->load->model('admin_seo_model');
				$this->load->helper('form');

				$set = array();
				$type = $this->input->post('type');
				$title = $this->input->post('title');
				$description = $this->input->post('description');
				$keywords = $this->input->post('keywords');

				foreach ($title as $key => $val) {
					$set['type_' . $key] = isset($type[$key]) ? 1 : 0;
					$set['title_' . $key] = form_prep($val);
					$set['description_' . $key] = form_prep($description[$key]);
					$set['keywords_' . $key] = form_prep($keywords[$key]);
					$set['cache_' . $key] = 0;
				}

				$this->admin_seo_model->save_tags($tags_id, $set);

				$response['success'] = true;
			}

			return json_encode($response);
		}

		/**
		 * Форма оновлення карти сайту
		 */
		public function xml()
		{
			$menu_id = (int)$this->input->get('menu_id');

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('seo_xml', $menu_id)
			) {
				$this->init_model->set_menu_id($menu_id, true);

				$this->template_lib
					->set_title('Оновлення xml карти сайту')
					->set_admin_menu_active('seo')
					->set_admin_menu_active('xml', 'sub_level');

				$this->template_lib->set_content($this->load->view('xml_tpl', '', true));
			} else {
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}
		}

		/**
		 * Оновлення xml карти сайту
		 *
		 * @return string
		 */
		public function update_xml()
		{
			$response = array('success' => false);

			if (
				$this->init_model->is_admin()
				and
				$this->init_model->check_access('seo_xml')
			) {
				$this->load->model('admin_seo_model');
				$this->load->helper('file');

				$this->admin_seo_model->update_xml(
					$this->config->item('database_languages'),
					$this->config->item('multi_languages')
				);

				$response['success'] = true;
			}

			return json_encode($response);
		}
	}