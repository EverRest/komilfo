<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_slider_model extends CI_Model
	{
		/**
		 * Отримання списку слайдів
		 *
		 * @return array
		 */
		public function get_slides($menu_id)
		{
			$this->db->select('slide_id, hidden, title_' . LANG . ' as title, file_name_'.LANG.' as file_name , description_' . LANG . ' as description ');
			$this->db->order_by('position');
			$this->db->where('menu_id=',$menu_id);
			return $this->db->get('slider')->result_array();
		}

		/**
		 * Додавання слайду
		 *
		 * @param int $add_top
		 * @return int
		 */
		public function add($add_top, $menu_id)
		{
			$db_data = array(
				'position' => ($add_top == 0) ? $this->max_position() : $this->min_position(),
				'menu_id' => $menu_id
			);

			$this->db->insert('slider', $db_data);
			return $this->db->insert_id();
		}

		/**
		 * Максимальна позиція слайду
		 *
		 * @return int
		 */
		private function max_position()
		{
			$position = $this->db->select_max('position', 'max')->get('slider')->row_array();
			return $position['max'] + 1;
		}

		/**
		 * Мінімальна позиція слайду
		 *
		 * @return int
		 */
		private function min_position()
		{
			$this->db->set('position', '`position` + 1', FALSE)->update('slider');

			$position = $this->db->select_min('position', 'min')->get('slider')->row_array();
			return $position['min'] - 1;
		}

		/**
		 * Отримання слайду
		 *
		 * @param int $slide_id
		 * @return array
		 */
		public function get($slide_id)
		{
			$this->db->select('slide_id, hidden, title_' . LANG . ' as title, file_name_'.LANG.' as file_name , description_' . LANG . ' as description, url_'.LANG);
			return $this->db->get_where('slider', array('slide_id' => $slide_id))->row_array();
		}

		/**
		 * Оновлення слайду
		 *
		 * @param array $set
		 * @param array $where
		 */
		public function update($set, $where)
		{
			$this->db->update('slider', $set, $where);
		}

		/**
		 * Отримання назви файлу зображення
		 *
		 * @param int $slide_id
		 * @param string $language
		 * @return null
		 */
		public function get_image($slide_id, $language)
		{
			$result = $this->db->select('file_name_'.$language . ' as file_name')->get_where('slider', array('slide_id' => $slide_id))->row_array();
			return isset($result['file_name']) ? $result['file_name'] : NULL;
		}

		/**
		 * Оновлення назви файлу зображення
		 *
		 * @param int $slide_id
		 * @param string $file_name
		 * @param string $language
		 */
		public function update_image($slide_id, $file_name)
		{
			$this->db->set('file_name_'.LANG, $file_name)->where('slide_id', $slide_id)->update('slider');
		}

		/**
		 * Видалення слайду
		 *
		 * @param int $slide_id
		 */
		public function delete($slide_id)
		{
			$this->db->delete('slider', array('slide_id' => $slide_id));
		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $slide_id
		 */
		public function delete_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
			$this->db->delete('slider', array('menu_id' => 1));
			$dir = ROOT_PATH . 'upload/slider/';
			delete_files($dir, TRUE, FALSE, 1);
		}

	}