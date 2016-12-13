<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_administrators_model extends CI_Model
	{
		/**
		 * Отримання адміністраторів сайту
		 *
		 * @return array
		 */
		public function get_administrators()
		{
			$this->db->select('admin_id, name, create_date, login_date, root, status');
			$this->db->order_by('create_date', 'asc');
			return $this->db->get('administrators')->result_array();
		}

		/**
		 * Додавання адміністратора
		 *
		 * @param array $set
		 * @return int
		 */
		public function insert($set)
		{
			$this->db->insert('administrators', $set);
			return $this->db->insert_id();
		}

		/**
		 * Отримання адміністратора
		 *
		 * @param $admin_id
		 */
		public function get($admin_id)
		{
			return $this->db->get_where('administrators', array('admin_id' => $admin_id))->row_array();
		}

		/**
		 * Перевірка правильності паролю
		 *
		 * @param int $admin_id
		 * @param string $password
		 * @return bool
		 */
		public function check_password($admin_id, $password)
		{
			$return = FALSE;

			$this->db->select('salt, password, edited');
			$this->db->where('admin_id', $admin_id);
			$result = $this->db->get('administrators')->row_array();

			if (isset($result['salt']))
			{
				if ($result['edited'] == 0 OR $result['password'] === md5($password . $result['salt'])) $return = TRUE;
			}

			return $return;
		}

		/**
		 * Оновлення адміністратора
		 *
		 * @param int $admin_id
		 * @param array $set
		 */
		public function update($admin_id, $set)
		{
			$this->db->update('administrators', $set, array('admin_id' => $admin_id));
		}

		/**
		 * Видалення адміністратора
		 *
		 * @param int $admin_id
		 */
		public function delete($admin_id)
		{
			$this->db->delete('administrators', array('admin_id' => $admin_id));
		}

		/**
		 * Отримання меню сайту
		 *
		 * @return array
		 */
		public function get_menus()
		{
			$this->db->select('id, parent_id, menu_index, name_' . LANG . ' as name');
			$this->db->order_by('menu_index');
			$this->db->order_by('level');
			$this->db->order_by('position');

			$result = $this->db->get('menu')->result_array();

			$menu = array();
			foreach ($result as $v) $menu[$v['menu_index']][$v['parent_id']][] = $v;

			return $menu;
		}

		/**
		 * Фомування випадкового паролю
		 *
		 * @param int $chars_min
		 * @param int $chars_max
		 * @param bool $use_upper_case
		 * @param bool $include_numbers
		 * @param bool $include_special_chars
		 *
		 * @return string
		 */
		public function get_random_password($chars_min = 6, $chars_max = 8, $use_upper_case = FALSE, $include_numbers = TRUE, $include_special_chars = FALSE)
		{
			$length = rand($chars_min, $chars_max);

			$selection = 'aeuoyibcdfghjklmnpqrstvwxz';
			if ($include_numbers) $selection .= "1234567890";
			if ($include_special_chars) $selection .= "!@\"#$%&[]{}?|";

			$password = "";
			for ($i = 0; $i < $length; $i++)
			{
				$current_letter = $use_upper_case ? (rand(0, 1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];
				$password .= $current_letter;
			}

			return $password;
		}
	}

