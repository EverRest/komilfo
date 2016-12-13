<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_loyalty_system_model extends CI_Model
	{
		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
			$this->db->delete('component_article', array('component_id' => $component_id));
		}

		public function get_text()
		{
			return $this->db->get("loyalty", array('id' => 1))->row_array();
		}

		public function set_data($text)
		{
			$this->db->update('loyalty', array("text" => $text), array('id' => 1));
		}

	}