<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_where_model extends CI_Model
	{
		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
		}

	}