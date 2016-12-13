<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property Slider_model $slider_model
	 */

	class Slider extends MX_Controller
	{
		/**
		 * Отримання списку слайдів
		 */
		public function index($menu_id, $component_id, $hidden, $config = '')
		{
			$this->load->model('slider_model');

			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
				'slides' => $this->slider_model->get_slides($menu_id),
			);

			if ($this->init_model->is_admin())
			{
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/slider_tpl', $template_data, TRUE);
			}
			else
			{
				return $this->load->view('slider_tpl', $template_data, TRUE);
			}
		}
	}