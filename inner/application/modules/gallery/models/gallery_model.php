<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Gallery_model extends CI_Model
	{
		public function get_slides($menu_id)
		{
			$this->db->select('slide_id, url_' . LANG . ' as url, file_name_' . LANG . ' as file_name,  title_' . LANG . ' as title, hidden, hidden_'.LANG);
			$this->db->where('menu_id=',$menu_id );
			$this->db->order_by('position');
			return $this->db->get('gallery')->result_array();
		}
	}