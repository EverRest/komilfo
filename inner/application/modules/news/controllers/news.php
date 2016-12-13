<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class News
	 *
	 * @property Admin_news_model $admin_news_model
	 * @property News_model $news_model
	 */
	class News extends MX_Controller
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

			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_index', $menu_id))
			{
				$this->load->model('admin_news_model');

				$pagination_config = array(
					'cur_page' => $page,
					'padding' => 1,
					'first_url' => $base_url,
					'base_url' => $base_url . '?page=',
					'per_page' => 30,
					'total_rows' => $this->admin_news_model->count_news($component_id),
					'full_tag_open' => '<nav class="fm admin_paginator"><table align="center"><tr><td align="center"><div class="fm paginator">',
					'full_tag_close' => '</div></td></tr></table></nav>',
					'first_link' => FALSE,
					'last_link' => FALSE,
					'prev_link' => '&lt;&lt;',
					'next_link' => '&gt;&gt;'
				);
				$this->load->library('pagination', $pagination_config);
				$pagination = $this->pagination->create_links();

				$template_data['news'] = $this->admin_news_model->get_news($component_id, $page);

				$template_data['last'] = $this->session->userdata('last_news');
				$template_data['pagination'] = $pagination;

				$this->load->view('admin/news_tpl', $template_data);
			}
			else
			{
				define('NEWS', TRUE);
				$this->load->model('news_model');
				$this->load->helper('functions');
				$pagination_config = array(
					'cur_page' => $page,
					'padding' => 1,
					'first_url' => $base_url,
					'base_url' => $base_url . '?page=',
					'per_page' => 10,
					'total_rows' => $this->news_model->count_news($component_id),
					'full_tag_open' => '<nav class="fm paginator"><table align="center"><tr><td align="center"><div class="fm paginator">',
					'full_tag_close' => '</div></td></tr></table></nav>',
					'first_link' => FALSE,
					'last_link' => FALSE,
					'prev_link' => '',
					'next_link' => ''
				);

				$this->load->library('pagination', $pagination_config);
				$pagination = $this->pagination->create_links();

				$template_data['news'] = $this->news_model->get_news($component_id, $page);
				$template_data['pagination'] = $pagination;

				$this->load->view('news_tpl', $template_data);
			}
		}

		public function popup_desc()
		{
			$response = array('success' => false);

			$news_id = intval($this->input->post('id', true));

			if($news_id > 0)
			{
				$this->load->model('news_model');
				$result = $this->news_model->get($news_id);
				
				$response['text'] = stripslashes($result['text']);
				$response['success'] = true;
			}

			return json_encode($response);
		}


		/**
		 * Вивід детального новини
		 *
		 * @param int|null $news_id
		 */
		public function details($news_id = NULL)
		{
			define('DETAILS', TRUE);

			if (!is_numeric($news_id)) show_404();

			$this->load->model('news_model');

			$news = $this->news_model->get($news_id);

			$images = $this->news_model->get_images($news_id);

			if (count($news) == 0) show_404();

			$this->init_model->set_menu_id($news['menu_id']);

			$this->template_lib->set_title($news['title']);
			$this->template_lib->set_bread_crumbs('', $news['title']);

			$this->load->library('seo_lib');

			$description = $this->seo_lib->generate_description($news['text']);
			$this->template_lib->set_description($description);

			$keywords = $this->seo_lib->generate_keywords($news['title'] . ' ' . $news['text']);
			$this->template_lib->set_keywords($keywords);

			$language_links = $this->news_model->get_language_links($news_id, $this->config->item('database_languages'));
			$this->template_lib->set_template_var('language_links', $language_links);

			$tpl_data = array(
				'news_id' => $news_id,
				'news' => $news,
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

			$news_id = intval($this->input->post('news_id', true));
			$lang = $this->input->post('LANG', true);

			if($news_id > 0)
			{
				$this->load->model('news_model');
				$result = $this->news_model->get_news_popup($news_id, $lang);

				$_tpl_product ='<div class="fm title_detail">' . $result['title'] . '</div><div class="fm photo">' . ($result['image']!='' ? '<div ><img src="/upload/news/' . $this->init_model->dir_by_id($result['news_id']) . '/' . $result['news_id'] . '/t_' . $result['image'].'" alt="' . $result['title'] . '"></div>' : '') . '</div><div class="fm text_detail">' . $result['anons'] . '</div>';
				$_tpl_table = stripcslashes($result['text']);

				$response['tpl_product'] = $_tpl_product;
				$response['product_name'] = $result['title'];
				$response['tpl_table'] =  $_tpl_table;

				$response['success'] = true;
			}
			return json_encode($response);
		}

	}