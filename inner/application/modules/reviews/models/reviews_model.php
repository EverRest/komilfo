<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Reviews_model extends CI_Model
	{
		/**
		 * Отримання відгуків
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_reviews($component_id)
		{
			$this->db->select('review_id, component_id, title_' . LANG . ' as title, text_' . LANG . ' as text, image, logo');
			$this->db->where('component_id', $component_id);
			$this->db->where('hidden', 0);
			$this->db->order_by('position', 'asc');

			return $this->db->get('reviews')->result_array();
		}

		/**
		 * Отримання останніх відгуків
		 *
		 * @return array
		 */
		public function get_last()
		{
			$this->db->select('review_id, component_id, title_' . LANG . ' as title, text_' . LANG . ' as text, image, logo');
			$this->db->where('hidden', 0);
			$this->db->order_by('position', 'asc');
			$this->db->limit(3);

			return $this->db->get('reviews')->result_array();
		}

		/**
		 * Отримання посилання на сторінку з компонентом відгуків
		 *
		 * @param bool $counting
		 * @return string
		 */
		public function get_url($counting = TRUE)
		{
			if ($counting AND $this->db->where('hidden', 0)->count_all_results('reviews') == 0)
			{
				return '';
			}
			else
			{
				$this->db->select('menu.url_path_' . LANG . ' as url');
				$this->db->join('components', 'components.menu_id = menu.id');
				$this->db->where('components.module', 'reviews');
				$this->db->where('components.method', 'index');

				$url = $this->db->get('menu')->row('url');
				return is_string($url) ? $url : '';
			}
		}
	}