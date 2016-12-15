<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_benefits_model extends CI_Model
	{

		/**
		 * Отримання статті компоненту
		 *
		 * @param int $component_id
		 * @param int $menu_id
		 *
		 * @return array|null
		 */
		public function get_benefits($component_id, $menu_id)
		{
			if ($this->db->where('component_id', $component_id)->count_all_results('component_benefits') == 0)
			{
				$this->db->insert('component_benefits', array('component_id' => $component_id, 'menu_id' => $menu_id));
			}

			return $this->db->get_where('component_benefits', array('component_id' => $component_id))->row_array();
		}

		/**
		 * Збереження статті компоненту
		 *
		 * @param int $component_id
		 * @param string $title
		 * @param string $text
		 * @param int $wide
		 */
		public function update($component_id, $title, $text, $wide,  $author, $quote, $btn_active)
		{
			$set = array('wide' => $wide);

            $set['title_ua'] = form_prep(strip_tags($title));

            $_text = str_replace(array("\r", "\n", "\t"), '', $text);
            $set['text_ua'] = $this->db->escape_str($_text);

            $_quote = str_replace(array("\r", "\n", "\t"), '', $quote);
            $set['quote_ua'] = $this->db->escape_str($_quote);

            $set['author_ua'] = form_prep(strip_tags($author));
			$set['btn_active'] = $btn_active;


			$this->db->update('component_benefits', $set, array('component_id' => $component_id));
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
			$this->db->delete('component_benefits', array('component_id' => $component_id));
		}

	}