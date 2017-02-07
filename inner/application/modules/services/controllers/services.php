<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class services
	 *
	 * @property Admin_services_model $admin_services_model
	 * @property services_model $services_model
	 */
	class Services extends MX_Controller
	{
		/**
		 * Вивід списка новин
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param array $config
		 */
		public function index($menu_id, $component_id, $hidden, $config = array())
		{
			$page = intval($this->input->get('page'));

			$template_data = array(
				'component_id' => $component_id,
				'menu_id' => $menu_id,
				'hidden' => $hidden,
			);

			$base_url = $this->init_model->get_link($menu_id, '{URL}');

			if ($this->init_model->is_admin() AND $this->init_model->check_access('services_index', $menu_id))
			{
				$this->load->model('admin_services_model');

				$pagination_config = array(
					'cur_page' => $page,
					'padding' => 1,
					'first_url' => $base_url,
					'base_url' => $base_url . '?page=',
					'per_page' => 30,
					'total_rows' => $this->admin_services_model->count_services($component_id),
					'full_tag_open' => '<nav class="fm admin_paginator"><table align="center"><tr><td align="center"><div class="fm paginator">',
					'full_tag_close' => '</div></td></tr></table></nav>',
					'first_link' => FALSE,
					'last_link' => FALSE,
					'prev_link' => '&lt;&lt;',
					'next_link' => '&gt;&gt;'
				);
				$this->load->library('pagination', $pagination_config);
				$pagination = $this->pagination->create_links();

				$template_data['services'] = $this->admin_services_model->get_services($component_id, $page);

				$template_data['last'] = $this->session->userdata('last_services');
				$template_data['pagination'] = $pagination;

				$this->load->view('admin/services_tpl', $template_data);
			}
			else
			{
				define('services', TRUE);
				$this->load->model('services_model');
				$this->load->helper('functions');
				$pagination_config = array(
					'cur_page' => $page,
					'padding' => 1,
					'first_url' => $base_url,
					'base_url' => $base_url . '?page=',
					'per_page' => 10,
					'total_rows' => $this->services_model->count_services($component_id),
					'full_tag_open' => '<nav class="fm paginator"><table align="center"><tr><td align="center"><div class="fm paginator">',
					'full_tag_close' => '</div></td></tr></table></nav>',
					'first_link' => FALSE,
					'last_link' => FALSE,
					'prev_link' => '',
					'next_link' => ''
				);

				$this->load->library('pagination', $pagination_config);
				$pagination = $this->pagination->create_links();

				$template_data['services'] = $this->services_model->get_services($component_id, $page);
				$template_data['pagination'] = $pagination;

				$this->load->view('services_tpl', $template_data);
			}
		}

		public function popup_desc()
		{
			$response = array('success' => false);

			$services_id = intval($this->input->post('id', true));

			if($services_id > 0)
			{
				$this->load->model('services_model');
				$result = $this->services_model->get($services_id);
				
				$response['text'] = stripslashes($result['text']);
				$response['success'] = true;
			}

			return json_encode($response);
		}


		/**
		 * Вивід детального новини
		 *
		 * @param int|null $services_id
		 */
		public function details($services_id = NULL)
		{
			define('DETAILS', TRUE);

			if (!is_numeric($services_id)) show_404();

			$this->load->model('services_model');

			$services = $this->services_model->get($services_id);

			$images = $this->services_model->get_images($services_id);

			if (count($services) == 0) show_404();

			$this->init_model->set_menu_id($services['menu_id']);

			$this->template_lib->set_title($services['title']);
			$this->template_lib->set_bread_crumbs('', $services['title']);

			$this->load->library('seo_lib');

			$description = $this->seo_lib->generate_description($services['text']);
			$this->template_lib->set_description($description);

			$keywords = $this->seo_lib->generate_keywords($services['title'] . ' ' . $services['text']);
			$this->template_lib->set_keywords($keywords);

			$language_links = $this->services_model->get_language_links($services_id, $this->config->item('database_languages'));
			$this->template_lib->set_template_var('language_links', $language_links);

			$tpl_data = array(
				'services_id' => $services_id,
				'services' => $services,
				'images'=> $images,
				'header_data' => $this->init_model->get_header()
			);
			$content = $this->load->view('details_tpl', $tpl_data, TRUE);
			$this->template_lib->set_content($content);
		}

		/**
		 * Popup детального товару
		 *
		 */
		public function get_popup()
		{
			$response = array('success' => false);

			$services_id = intval($this->input->post('services_id', true));
			$lang = $this->input->post('LANG', true);

			if($services_id > 0)
			{
				$this->load->model('services_model');
				$result = $this->services_model->get_services_popup($services_id, $lang);

				$_tpl_product ='<div class="fm title_detail">' . $result['title'] . '</div><div class="fm photo">' . ($result['image']!='' ? '<div ><img src="/upload/services/' . $this->init_model->dir_by_id($result['services_id']) . '/' . $result['services_id'] . '/t_' . $result['image'].'" alt="' . $result['title'] . '"></div>' : '') . '</div><div class="fm text_detail">' . $result['anons'] . '</div>';
				$_tpl_table = stripcslashes($result['text']);

				$response['tpl_product'] = $_tpl_product;
				$response['product_name'] = $result['title'];
				$response['tpl_table'] =  $_tpl_table;

				$response['success'] = true;
			}
			return json_encode($response);
		}

	}