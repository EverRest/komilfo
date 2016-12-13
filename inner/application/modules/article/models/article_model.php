<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Article_model extends CI_Model {

		/**
		 * Отримання статті компоненту
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_article($component_id)
		{
			return $this->db->select('title_' . LANG . ' as title, btn_active, text_' . LANG . ' as text, wide, background_fone')->get_where('component_article', array('component_id' => $component_id))->row_array();
		}

	}