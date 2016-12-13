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
		private $big = array(620, 440);
		private $image_thumb = array(240, 240);
		private $per_page = 10;
		private $admin_per_page = 30;
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
					'per_page' => $this->admin_per_page,
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
				$template_data['news'] = $this->admin_news_model->get_news($component_id, $page, $this->admin_per_page);
				$template_data['image_thumb'] = $this->image_thumb;
				$template_data['last'] = $this->session->userdata('last_news');
				$template_data['pagination'] = $pagination;
				$this->load->view('admin/news_tpl', $template_data);
			}
			else
			{
				// if(!NEWS) define('NEWS', TRUE);
				$this->load->model('news_model');
				$this->load->helper('functions');
				$pagination_config = array(
					'cur_page' => $page,
					'padding' => 1,
					'first_url' => $base_url,
					'base_url' => $base_url . '?page=',
					'per_page' => $this->per_page,
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
				$template_data['news'] = $this->news_model->get_news($component_id, $page, $this->per_page);
				foreach($template_data['news'] as $key => $val)
				{
					if($val['included']!='') $template_data['news'][$key]['included'] = unserialize($val['included']);
				}
				$template_data['pagination'] = $pagination;
				$this->load->view('news_tpl', $template_data);
			}
		}
		/**
		 * Останні відгуки
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param array $config
		 */
		public function last($menu_id, $component_id, $hidden, $config = array())
		{
			$this->load->model('news_model');
			$template_data = array(
				'component_id' => $component_id,
				'menu_id' => $menu_id,
				'hidden' => $hidden,
			);
			if ($this->init_model->is_admin() AND $this->init_model->check_access('news_last', $menu_id))
			{
				$template_data['news_url'] = $this->news_model->get_url(FALSE);
				$this->load->view('admin/last_tpl', $template_data);
			}
			else
			{
				$template_data['news'] = $this->news_model->get_last();
				$template_data['news_url'] = $this->news_model->get_url();
				$template_data['component_data'] = $this->news_model->get_component_news();
				$this->load->view('on_main_news_tpl', $template_data);
			}
			// $this->template_lib->set_template_var('on_main_news',$this->load->view('on_main_news_tpl', $template_data,true));
		}
		public function slider_news()
		{
			$this->load->model('news_model');
			$this->load->helper('functions');
			$template_data['last_news'] = $this->news_model->get_slider_news('slider');
			$this->template_lib->set_template_var('travel_slider',$this->load->view('slider_news_tpl', $template_data,true));
		}
		public function on_main()
		{
			$this->load->model('news_model');
			$this->load->helper('functions');
			$template_data['news'] = $this->news_model->get_slider_news('main');
			$template_data['news_url'] = $this->news_model->get_url();
			$this->template_lib->set_template_var('on_main_news',$this->load->view('on_main_news_tpl', $template_data,true));
		}
		/**
		 * Вивід детального новини
		 *
		 * @param int|null $news_id
		 */
		public function details($news_id = NULL)
		{
			if (!is_numeric($news_id)) show_404();
			$this->load->model('news_model');
			$news = $this->news_model->get($news_id);
			if($news['social_comments']!='')
			{
				$social_comments = unserialize($news['social_comments']);
				define('DETAILS_NEWS',TRUE);
				if($social_comments['vk']==1)
				{
					define('vk',TRUE);
				}
				if($social_comments['fb']==1)
				{
					define('fb',TRUE);
				}
			}
			// 'h1' => $this->template_lib->get_h1()
			$this->template_lib->set_h1();
			$news['included'] = unserialize($news['included']);
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
				'h1' => $this->template_lib->get_h1(),
				'menu_id'=> $news['menu_id']
			);
			$content = $this->load->view('details_tpl', $tpl_data, TRUE);
			$this->template_lib->set_content($content);
		}
		/**
		 * Останні відгуки
		 *
		 * @return array
		 */
		public function last_comments()
		{
			$this->load->model('news_model');
			$this->load->helper('functions');
			$template_data = array('comments' => $this->news_model->get_last_comments());
			$this->template_lib->set_template_var('last_comments', $this->load->view('last_comments_tpl', $template_data, TRUE));
		}
		
		private function _convertBBcodes($text)
		{
			$find = array(
				'~\[b\](.*?)\[/b\]~s',
				'~\[i\](.*?)\[/i\]~s',
				'~\[u\](.*?)\[/u\]~s',
				'~\[quote\](.*?)\[/quote\]~s',
				'~\[link\]((?:ftp|https|http?)://.*?)\[/link\]~s',
				'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
			);
			$replace = array(
				'<b>$1</b>',
				'<i>$1</i>',
				'<span style="text-decoration:underline;">$1</span>',
				'<blockquote>$1</blockquote>',
				'<!--noindex--><a href="$1" target="_blank" rel="nofollow">$1</a><!--/noindex-->',
				'<img src="$1" alt="" />'
			);
			return preg_replace($find, $replace, $text);
		}
	}