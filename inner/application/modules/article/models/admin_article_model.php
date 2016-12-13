<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_article_model extends CI_Model
	{
		public function menu_index($menu_id)
		{
			return $this->db->select('menu_index')->where('id', $menu_id)->get('menu')->row('menu_index');
		}

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
		public function update($component_id, $title, $text, $wide, $lat, $lng, $zoom, $title_shop, $address, $address_2, $phone, $facebook)
		{
			$set = array(
				'lat' => $lat,
				'lng' => $lng,
				'zoom' => $zoom,
				'wide' => $wide,
				'phone' => $phone,
				'facebook' => $facebook
			);

			foreach ($title as $language => $val)
			{
				$set['title_' . $language] = form_prep(strip_tags($val));

				$_text = str_replace(array("\r", "\n", "\t"), '', $text[$language]);
				$set['text_' . $language] = $this->db->escape_str($_text);

				$_sign = nl2br($sign[$language]);
				$_sign = str_replace(array("\r", "\n", "\t"), '', $_sign);
				// $set['sign_' . $language] = $this->db->escape_str($_sign);
			}

			foreach ($title_shop as $language => $val)
			{
				$set['title_shop_' . $language] = form_prep(strip_tags($val));

				$set['address_' . $language] = form_prep(strip_tags($address[$language]));
				$set['address_2_' . $language] = form_prep(strip_tags($address_2[$language]));

			}

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