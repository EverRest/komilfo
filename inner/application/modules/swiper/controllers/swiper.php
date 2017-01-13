<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property swiper_model $swiper_model
	 */

	class Swiper extends MX_Controller
	{
		/**
		 * Отримання списку слайдів
		 */
		public function index($menu_id, $component_id, $hidden, $config = '')
		{
			$this->load->model('swiper_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
//				'swipes' => $this->swiper_model->get_slides($menu_id),
			);

			if ($this->init_model->is_admin())
			{
				$template_data['hidden'] = $hidden;
                $template_data['swipes'] = $this->swiper_model->get_slides($menu_id);
				return $this->load->view('admin/swiper_tpl', $template_data, TRUE);
			}
			else
			{
                $template_data['swipes'] = $this->swiper_model->get_slides($menu_id);
				return $this->load->view('swiper_tpl', $template_data, TRUE);
			}
		}
	}