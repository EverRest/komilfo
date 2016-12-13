<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_article_model extends CI_Model
	{

		/**
		 * Отримання статті компоненту
		 *
		 * @param int $component_id
		 * @param int $menu_id
		 *
		 * @return array|null
		 */
		public function get_article($component_id, $menu_id)
		{
			if ($this->db->where('component_id', $component_id)->count_all_results('component_article') == 0)
			{
				$this->db->insert('component_article', array('component_id' => $component_id, 'menu_id' => $menu_id));
			}

			return $this->db->get_where('component_article', array('component_id' => $component_id))->row_array();
		}

		/**
		 * Збереження статті компоненту
		 *
		 * @param int $component_id
		 * @param string $title
		 * @param string $text
		 * @param int $wide
		 */
		public function update($component_id, $title, $text, $wide, $background_fone, $btn_active)
		{
			$set = array('wide' => $wide);

			foreach ($title as $language => $val)
			{
				$set['title_' . $language] = form_prep(strip_tags($val));

				$_text = str_replace(array("\r", "\n", "\t"), '', $text[$language]);
				$set['text_' . $language] = $this->db->escape_str($_text);
			}

			$set['background_fone'] = $background_fone;
			$set['btn_active'] = $btn_active;


			$this->db->update('component_article', $set, array('component_id' => $component_id));
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