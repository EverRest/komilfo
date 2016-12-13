<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property Search_model $search_model
	 */

	class Search extends MX_Controller
	{
		public function index()
		{
			if (LANG == 'ua') $w = 'Пошук';
			if (LANG == 'ru') $w = 'Поиск';
			if (LANG == 'en') $w = 'Search';

			$this->template_lib->set_title($w);
			$this->template_lib->set_bread_crumbs('', $w);

			$this->load->model('search_model');
			$this->load->helper('functions');

			$query = $this->input->get('query', TRUE);
			$query = strip_tags($query);

			$tpl_data = array(
				'query' => $query,
				'count_results' => 0,
			);

			if ($this->search_model->set_query($query))
			{
				$tpl_data['count_results'] = $this->search_model->count_results();

				// if ($tpl_data['count_results']['articles'] > 0) $tpl_data['articles'] = $this->search_model->articles_preview();
				if ($tpl_data['count_results']['catalog'] > 0) $tpl_data['catalog'] = $this->search_model->catalog_preview(); else $tpl_data['catalog'] = array();
				// if ($tpl_data['count_results']['news'] > 0) $tpl_data['news'] = $this->search_model->news_preview();
			}
			
			$content = $this->load->view('search_tpl', $tpl_data, TRUE);
			$this->template_lib->set_content($content);
		}

		public function news($page = 1)
		{
			define('HIDE_BANNER', TRUE);

			if (LANG == 'ua') $w = 'Пошук в новинах';
			if (LANG == 'ru') $w = 'Поиск в новостях';
			if (LANG == 'en') $w = 'Search in news';

			$this->template_lib->add_crumb('', $w);
			$this->seo_lib->set_title($w);

			$this->load->model('search_model');

			$query = $this->input->cookie('search_query', TRUE);
			$query = strip_tags($query);

			if (!is_numeric($page)) $page = 1;

			$tpl_data = array(
				'query' => $query,
				'count' => 0
			);

			if ($this->search_model->set_query($query))
			{
				$tpl_data['count'] = $this->search_model->news_count();

				if ($tpl_data['count'] > 0)
				{
					$tpl_data['news'] = $this->search_model->news($page);

					$pagination_config = array(
						'cur_page' => $page,
						'padding' => 1,
						'first_url' => 'search/news',
						'base_url' => 'search/news/page/',
						'per_page' => 10,
						'total_rows' => $tpl_data['count'],

						'full_tag_open' => '<table align="center"><tr><td align="center"><div class="fm paginator">',
						'full_tag_close' => '</div></td></tr></table>',
						'first_link' => FALSE,
						'last_link' => FALSE,
						'prev_link' => '&lt;&lt;',
						'next_link' => '&gt;&gt;'
					);

					$this->load->library('pagination', $pagination_config);
					$tpl_data['pagination'] = $this->pagination->create_links();

					$this->load->helper('translit');
				}
			}

			$content = $this->load->view('news_tpl', $tpl_data, TRUE);
			$this->template_lib->set_content($content);

			$this->template_lib->set_template('site_main_tpl');
			$this->template_lib->display();
		}
	}