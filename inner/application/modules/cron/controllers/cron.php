<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property Admin_seo_model $admin_seo_model
	 * @property Catalog_model $catalog_model
	 * @property Admin_profiles_model $admin_profiles_model
	 */
	class Cron extends MX_Controller
	{
		/**
		 * Офовлення карти сайту
		 */
		public function sitemap()
		{
			$this->load->helper('file');
			$this->load->model('seo/admin_seo_model');
			$this->admin_seo_model->update_xml($this->config->item('languages'), $this->config->item('multi_languages'));

			exit;
		}
	}