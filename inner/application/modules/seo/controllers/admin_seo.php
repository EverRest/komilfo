<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property Admin_seo_model $admin_seo_model
	 */

	class Admin_seo extends MX_Controller {

		/**
		 * Форма редагування метатегів
		 */
		public function tags()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if (!$this->init_model->is_admin() OR !$this->init_model->check_access('seo_tags'))
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}

			$this->template_lib->set_title('Управління мета тегами');

			$this->template_lib->set_admin_menu_active('seo');
			$this->template_lib->set_admin_menu_active('tags', 'sub_level');

			$item_id = intval($this->input->get('item_id'));
			$module = $this->input->get('module');
			
			if ($module != '')
			{
				$modules = $this->config->item('frontend_modules');
				if (!in_array($module, $modules)) show_error('Unknown module', 500, 'URL error');
			}

			$this->init_model->set_menu_id($menu_id, TRUE);

			$this->load->model('admin_seo_model');

			$languages = $this->config->item('languages');

			$tpl_data = array(
				'menu_id' => $menu_id,
				'item_id' => $item_id,
				'module' => $module,
				'languages' => $languages,
				'tags' => $this->admin_seo_model->get_tags($menu_id, $item_id, $module, array_keys($languages))
			);
			$this->template_lib->set_content($this->load->view('tags_tpl', $tpl_data, TRUE));
		}

		/**
		 * Генерація ключових слів
		 */
		public function generate_keywords()
		{
			$response = array(
				'success' => FALSE,
				'keywords' => '',
			);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('seo_tags'))
			{
				$languages = $this->config->item('languages');
				$language = $this->input->post('language');
				$tags_id = intval($this->input->post('tags_id'));

				if ($tags_id > 0 AND isset($languages[$language]))
				{
					$this->load->library('seo_lib');
					$this->load->model('admin_seo_model');
					$this->load->helper('form');

					$response['keywords'] = $this->admin_seo_model->get_keywords($tags_id, $language);
					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Збереження метатегів
		 */
		public function update_tags()
		{
			$response = array(
				'success' => FALSE,
				'keywords' => '',
			);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('seo_tags'))
			{
				$tags_id = intval($this->input->post('tags_id'));

				if ($tags_id > 0)
				{
					$this->load->model('admin_seo_model');
					$this->load->helper('form');

					$type = $this->input->post('type');
					$title = $this->input->post('title');
					$description = $this->input->post('description');
					$keywords = $this->input->post('keywords');

					$set = array();

					foreach ($title as $key => $val)
					{
						$set['type_' . $key] = isset($type[$key]) ? 1 : 0;
						$set['title_' . $key] = form_prep($val);
						$set['description_' . $key] = form_prep($description[$key]);
						$set['keywords_' . $key] = form_prep($keywords[$key]);
						$set['cache_' . $key] = 0;
					}

					$where = array('tags_id' => intval($this->input->post('tags_id')));

					$this->admin_seo_model->save_tags($set, $where);

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Вивід форми для seo-link
		 */
		public function seo_link()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if (!$this->init_model->is_admin() OR !$this->init_model->check_access('seo_seo_link'))
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}

			$this->init_model->set_menu_id($menu_id, TRUE);

			$this->template_lib->set_title('Seo-link');
			$this->template_lib->set_admin_menu_active('seo');
			$this->template_lib->set_admin_menu_active('seo_link', 'sub_level');

			$this->load->model('admin_seo_model');

			$tpl_data = array(
				'menu_id' => $menu_id,
				'menus' => $this->admin_seo_model->get_menus(),
				'seo_link' => $this->admin_seo_model->get_seo_link($menu_id)
			);

			$this->template_lib->set_content($this->load->view('seo_link_tpl', $tpl_data, TRUE));
		}

		/**
		 * Збереження seo-link
		 */
		public function save_seo_link()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('seo_seo_link'))
			{
				$menu_id = intval($this->input->post('menu_id'));
				$hide_menu = intval($this->input->post('hide_menu'));
				$status = intval($this->input->post('status'));

				if ($menu_id > 0 AND $hide_menu > 0 AND in_array($status, array(0, 1)))
				{
					$this->load->model('admin_seo_model');

					if ($status == 1)
					{
						$this->admin_seo_model->hide_menu($menu_id, $hide_menu);
					}
					else
					{
						$this->admin_seo_model->show_menu($menu_id, $hide_menu);
					}

					$response['success'] = TRUE;
				}
			}

			return json_encode($response);
		}

		/**
		 * Xml карта сайту
		 */
		public function xml()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if (!$this->init_model->is_admin() OR !$this->init_model->check_access('seo_xml'))
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}

			$this->init_model->set_menu_id($menu_id, TRUE);

			$this->template_lib->set_title('Оновлення xml карти сайту');
			$this->template_lib->set_admin_menu_active('seo');
			$this->template_lib->set_admin_menu_active('xml', 'sub_level');

			$this->template_lib->set_content($this->load->view('xml_tpl', '', TRUE));
		}

		/**
		 * Оновлення xml карти сайту
		 *
		 * @return string
		 */
		public function update_xml()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('seo_seo_link'))
			{
				$this->load->model('admin_seo_model');
				$this->load->helper('file');

				$this->admin_seo_model->update_xml($this->config->item('languages'), $this->config->item('multi_languages'));

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}

		/**
		 * Редагування назви сайту
		 */
		public function site_name()
		{
			$menu_id = intval($this->input->get('menu_id'));

			if (!$this->init_model->is_admin() OR !$this->init_model->check_access('seo_site_name'))
			{
				redirect($this->init_model->get_link($menu_id, '{URL}'));
			}

			$this->init_model->set_menu_id($menu_id, TRUE);

			$this->template_lib->set_title('Назва сайту');
			$this->template_lib->set_admin_menu_active('config');
			$this->template_lib->set_admin_menu_active('site_name', 'sub_level');

			$this->load->model('admin_seo_model');
			$this->load->helper('form');

			$languages = $this->config->item('languages');

			$tpl_data = array(
				'languages' => $languages,
				'site_name' => $this->admin_seo_model->get_site_name(array_keys($languages))
			);
			$this->template_lib->set_content($this->load->view('site_name_tpl', $tpl_data, TRUE));
		}

		/**
		 * Збереження назви сайту
		 */
		public function save_site_name()
		{
			$response = array('success' => FALSE);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('seo_site_name'))
			{
				$this->load->model('admin_seo_model');
				$this->load->helper('form');

				$site_name = $this->input->post('site_name');

				foreach ($site_name as $key => $val)
				{
					$set = array('val' => $val);
					$where = array('key' => 'site_name_' . $key);

					$this->admin_seo_model->update_site_name($set, $where);
				}

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}
	}