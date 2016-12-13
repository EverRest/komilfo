<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Printing
	 *
	 * @property Article_model $article_model
	 * @property News_model $news_model
	 */
	class Printing extends MX_Controller {

		/**
		 * Вивід даних для друку
		 *
		 * @retur string
		 */
		public function index()
		{
			$response = '';

			$module = $this->input->get('module');
			$id = intval($this->input->get('id'));

			if ($id < 1) show_404();

			// Друк текстового компоненту

			if ($module == 'article')
			{
				$this->load->model('article/article_model');

				$article = $this->article_model->get_article($id);
				if (count($article) == 0) show_404();

				$response = $this->load->view('print_article_tpl', array('article' => $article), TRUE);
			}

			if ($response == '') show_404();

			$this->config->set_item('is_ajax_request', TRUE);
			return $response;
		}
	}