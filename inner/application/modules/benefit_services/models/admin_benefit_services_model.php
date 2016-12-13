<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_benefit_services_model extends CI_Model
	{

		/**
		 * Отримання компоненту
		 * @return array|null
		 */
		public function get_article()
		{
			if ($this->db->where('id', 1)->count_all_results('component_services') == 0)
			{
				$this->db->insert('component_services', array('id' => 1));
			}

			return $this->db->get_where('component_services', array('id' => 1))->row_array();
		}

		/**
		 * Збереження компоненту
		 *
		 */
		public function update($data)
		{

				$data['m1'] = str_replace(array("\r", "\n", "\t"), '', $data['m1']);
				$data['m1'] = $this->db->escape_str($data['m1']);

				$data['m2'] = str_replace(array("\r", "\n", "\t"), '', $data['m2']);
				$data['m2'] = $this->db->escape_str($data['m2']);

				$data['m3'] = str_replace(array("\r", "\n", "\t"), '', $data['m3']);
				$data['m3'] = $this->db->escape_str($data['m3']);

				$data['m4'] = str_replace(array("\r", "\n", "\t"), '', $data['m4']);
				$data['m4'] = $this->db->escape_str($data['m4']);



				$data['m_1'] = str_replace(array("\r", "\n", "\t"), '', $data['m_1']);
				$data['m_1'] = $this->db->escape_str($data['m_1']);

				$data['m_2'] = str_replace(array("\r", "\n", "\t"), '', $data['m_2']);
				$data['m_2'] = $this->db->escape_str($data['m_2']);

				$data['m_3'] = str_replace(array("\r", "\n", "\t"), '', $data['m_3']);
				$data['m_3'] = $this->db->escape_str($data['m_3']);

				$data['m_4'] = str_replace(array("\r", "\n", "\t"), '', $data['m_4']);
				$data['m_4'] = $this->db->escape_str($data['m_4']);

				$data['m_5'] = str_replace(array("\r", "\n", "\t"), '', $data['m_5']);
				$data['m_5'] = $this->db->escape_str($data['m_5']);

				$data['m_1_1'] = str_replace(array("\r", "\n", "\t"), '', $data['m_1_1']);
				$data['m_1_1'] = $this->db->escape_str($data['m_1_1']);

				$data['m_2_2'] = str_replace(array("\r", "\n", "\t"), '', $data['m_2_2']);
				$data['m_2_2'] = $this->db->escape_str($data['m_2_2']);

				$data['m_3_3'] = str_replace(array("\r", "\n", "\t"), '', $data['m_3_3']);
				$data['m_3_3'] = $this->db->escape_str($data['m_3_3']);

				$data['m_4_4'] = str_replace(array("\r", "\n", "\t"), '', $data['m_4_4']);
				$data['m_4_4'] = $this->db->escape_str($data['m_4_4']);

				$data['m_5_5'] = str_replace(array("\r", "\n", "\t"), '', $data['m_5_5']);
				$data['m_5_5'] = $this->db->escape_str($data['m_5_5']);





			$this->db->update('component_services', $data, array('id' => 1));
		}

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

	}