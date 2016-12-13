<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property Admin_seo_model $admin_seo_model
	 * @property Experts_model $experts_model
	 */
	class Cron extends MX_Controller
	{

		public function sitemap()
		{
			$this->load->helper('file');
			$this->load->model('seo/admin_seo_model');
			$this->admin_seo_model->update_xml($this->config->item('languages'), $this->config->item('multi_languages'));
			exit;
		}

		public function experts_online()
		{
			$this->load->model('experts/experts_model');
			$this->experts_model->check_online();
			exit;
		}

	}
