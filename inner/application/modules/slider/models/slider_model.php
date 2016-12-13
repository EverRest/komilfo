<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Slider_model extends CI_Model
	{
		public function get_slides($menu_id)
		{
			$this->db->select('slide_id, url_' . LANG . ' as url, file_name_' . LANG . ' as file_name,  title_' . LANG . ' as title, description_' . LANG . ' as description, hidden, hidden_'.LANG);
			$this->db->where('menu_id=',$menu_id );
			$this->db->order_by('position');
			return $this->db->get('slider')->result_array();
		}
	}