<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_reviews_model extends CI_Model
	{
		/**
		 * Загальна кількість всіх відгуків в компоненті
		 *
		 * @param int $component_id
		 * @return string
		 */
		public function count_reviews($component_id)
		{
			return $this->db->where('component_id', $component_id)->count_all_results('reviews');
		}

		/**
		 * Отримання всіх відгуків компоненту
		 *
		 * @param int $component_id
		 * @return array
		 */
		public function get_reviews($component_id)
		{
			$this->db->select('reviews.review_id, reviews.component_id, reviews.hidden, reviews.title_' . LANG . ' as title, reviews.image');
			$this->db->where('reviews.component_id', $component_id);
			$this->db->order_by('reviews.position', 'asc');

			return $this->db->get('reviews')->result_array();
		}

		/**
		 * Додавання відгуку
		 *
		 * @param array $set
		 * @return int
		 */
		public function insert($set)
		{
			$this->db->insert('reviews', $set);
			return $this->db->insert_id();
		}

		/**
		 * Отримання відгуку
		 *
		 * @param $review_id
		 */
		public function get($review_id)
		{
			return $this->db->get_where('reviews', array('review_id' => $review_id))->row_array();
		}

		/**
		 * Оновлення відгуку
		 *
		 * @param array $set
		 * @param array $where
		 */
		public function update($set, $where)
		{
			$this->db->update('reviews', $set, $where);
		}

		/**
		 * Отримання останньої позиції в компоненті
		 *
		 * @param int $component_id
		 * @return int
		 */
		public function get_position($component_id)
		{
			$this->db->set('`position`', '`position` + 1', FALSE);
			$this->db->where('component_id', $component_id);
			$this->db->update('reviews');

			return 0;
		}

		/**
		 * Видалення відгуку
		 *
		 * @param int $review_id
		 */
		public function delete($review_id)
		{
			$dir = ROOT_PATH . 'upload/reviews/' . $this->init_model->dir_by_id($review_id) . '/' . $review_id . '/';
			if (file_exists($dir)) delete_files($dir, TRUE, FALSE, 1);

			$this->db->delete('reviews', array('review_id' => $review_id));
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$result = $this->db->select('review_id')->where('component_id', $component_id)->get('reviews')->result_array();

			foreach ($result as $v)
			{
				$dir = ROOT_PATH . 'upload/reviews/' . $this->init_model->dir_by_id($v['review_id']) . '/' . $v['review_id'] . '/';
				delete_files($dir, TRUE, FALSE, 1);
			}

			$this->db->delete('reviews', array('component_id' => $component_id));
			$this->db->delete('components', array('component_id' => $component_id));
		}
		
		/**
		 * Видалення компоненту "Останні відгуки"
		 *
		 * @param int $component_id
		 */
		public function delete_last_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
		}

		### Зображення ###

		/**
		 * Отримання зображеня до відгуку
		 *
		 * @param int $review_id
		 * @return array
		 */
		public function get_review_image($review_id)
		{
			return $this->db->select('image')->where('review_id', $review_id)->get('reviews')->row('image');
		}

		/**
		 * Видалення зображення
		 *
		 * @param int $review_id
		 * @return int
		 */
		public function delete_image($review_id)
		{
			$im = $this->get_review_image($review_id);

			if (is_string($im) AND $im != '')
			{
				$dir = ROOT_PATH . 'upload/reviews/' . $this->init_model->dir_by_id($review_id) . '/' . $review_id . '/';
				$file_name = $im;

				if (file_exists($dir . $file_name)) unlink($dir . $file_name);
				if (file_exists($dir . 's_' . $file_name)) unlink($dir . 's_' . $file_name);

				$this->update(array('image' => ''), array('review_id' => $review_id));
			}
		}

		### Логотип ###

		/**
		 * Отримання логотипу до відгуку
		 *
		 * @param int $review_id
		 * @return array
		 */
		public function get_review_logo($review_id)
		{
			return $this->db->select('logo')->where('review_id', $review_id)->get('reviews')->row('logo');
		}

		/**
		 * Видалення логотипу
		 *
		 * @param int $review_id
		 * @return int
		 */
		public function delete_logo($review_id)
		{
			$im = $this->get_review_logo($review_id);

			if (is_string($im) AND $im != '')
			{
				$dir = ROOT_PATH . 'upload/reviews/' . $this->init_model->dir_by_id($review_id) . '/' . $review_id . '/';
				$file_name = $im;

				if (file_exists($dir . $file_name)) unlink($dir . $file_name);
				if (file_exists($dir . 's_' . $file_name)) unlink($dir . 's_' . $file_name);

				$this->update(array('logo' => ''), array('review_id' => $review_id));
			}
		}
	}