<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Article
	 *
	 * @property Article_model $article_model
	 */
	class Article extends MX_Controller {

		/**
		 * Вивід текстового компоненту
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param string $config
		 * @return string
		 */
		public function index($menu_id, $component_id, $hidden, $config = '')
		{
			$this->load->model('article_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
				'article' => $this->article_model->get_article($component_id),
				'h1' => $this->template_lib->get_h1()
			);

			$this->template_lib->set_h1();

			if (
				$this->init_model->is_admin()
				and !$this->init_model->is_panel_hidden()
				and $this->init_model->check_access('article_index', $menu_id)
			) {
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/article_tpl', $template_data, TRUE);
			} else {
				return $this->load->view('article_tpl', $template_data, TRUE);
			}
		}
	}