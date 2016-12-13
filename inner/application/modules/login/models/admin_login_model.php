<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_login_model extends CI_Model
	{
		/**
		 * Отримання інформації про адміністратору
		 *
		 * @param string $login
		 * @return array|null
		 */
		public function get_admin($login)
		{
			$r = $this->db->where('login', $login)->where('status', 0)->where('edited', 1)->get('administrators');
			return ($r->num_rows() > 0) ? $r->row_array() : NULL;
		}

		/**
		 * Оновлення інформації про адміністратора
		 *
		 * @param $admin_id
		 */
		public function change_admin($admin_id)
		{
			$this->db->set('login_date', time());
			$this->db->where('admin_id', $admin_id);
			$this->db->update('administrators');
		}
	}
