<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Admin_feedback_model
	 */
	class Admin_feedback_model extends CI_Model {

		/**
		 * Кількість повідомлень
		 *
		 * @param $component_id
		 * @return string
		 */
		public function count_messages($component_id)
		{
			//$this->db->where('component_id', $component_id);
			return $this->db->count_all_results('feedback');
		}

		/**
		 * Отримання повідомлень
		 *
		 * @param int $component_id
		 * @param int $page
		 * @param int $per_page
		 *
		 * @return array
		 */
		public function get_messages($component_id, $page, $per_page)
		{
			//$this->db->where('component_id', $component_id);
			$this->db->order_by('time', 'desc');
			$this->db->order_by('status', 'asc');
			$this->db->limit($per_page, $page * $per_page);
			return $this->db->get('feedback')->result_array();
		}

		/**
		 * Ононвлення статусу повідомлень
		 *
		 * @param int $component_id
		 */
		public function status($component_id)
		{
			$this->db->set('status', 1);
			$this->db->where('component_id', $component_id);
			$this->db->update('feedback');
		}
		/**
		 * Видалення повідомлення
		 *
		 * @param int $message_id
		 */
		public function delete($message_id)
		{
			$this->db->delete('feedback', array('id' => $message_id));
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
			$this->db->delete('feedback', array('component_id' => $component_id));
		}

	}