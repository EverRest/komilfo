<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Reviews
	 *
	 * @property Admin_reviews_model $admin_reviews_model
	 * @property Reviews_model $reviews_model
	 */
	class Reviews extends MX_Controller
	{
		/**
		 * Вивід списка відгуків
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param array $config
		 */
		public function index($menu_id, $component_id, $hidden, $config = array())
		{
			$template_data = array(
				'component_id' => $component_id,
				'menu_id' => $menu_id,
				'hidden' => $hidden,
			);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_index', $menu_id))
			{
				$this->load->model('admin_reviews_model');

				$template_data['reviews'] = $this->admin_reviews_model->get_reviews($component_id);
				$template_data['last_review'] = $this->session->userdata('last_reviews');

				$this->load->view('admin/reviews_tpl', $template_data);
			}
			else
			{
				$this->load->model('reviews_model');
				$this->load->view('reviews_tpl', array('reviews' => $this->reviews_model->get_reviews($component_id)));
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
			$this->load->model('reviews_model');

			$template_data = array(
				'component_id' => $component_id,
				'menu_id' => $menu_id,
				'hidden' => $hidden,
			);

			if ($this->init_model->is_admin() AND $this->init_model->check_access('reviews_last', $menu_id))
			{
				$template_data['reviews_url'] = $this->reviews_model->get_url(FALSE);
				$this->load->view('admin/last_tpl', $template_data);
			}
			else
			{
				$template_data['reviews'] = $this->reviews_model->get_last();
				$template_data['reviews_url'] = $this->reviews_model->get_url();

				$this->load->view('last_tpl', $template_data);
			}
		}
	}