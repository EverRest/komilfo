<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_frequent_model extends CI_Model
	{

		/**
		 * Отримання статті компоненту
		 *
		 * @param int $component_id
		 * @param int $menu_id
		 *
		 * @return array|null
		 */
		public function get_article()
		{

			return $this->db->get('component_frequent')->result_array();
		}

		/**
		 * Збереження статті компоненту
		 *
		 * @param int $component_id
		 * @param string $title
		 * @param string $text
		 * @param int $wide
		 */

		public function update($data)
		{

			for($i=1; $i <= 11; $i++)
			{

				$this->db->where('id=',$i);
				$this->db->update('component_frequent', array('answer'=>str_replace(array("\r", "\n", "\t"), '', $data['ques'.$i]), 'questions'=>str_replace(array("\r", "\n", "\t"), '', $data['answ'.$i])));
			}
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