<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_config_model extends CI_Model
	{
		/**
		 * Отримання налаштувань
		 *
		 * @param array $config
		 * @return array
		 */
		public function get_config(array $config)
		{
			$result = $this->db
				->select('key, val')
				->where_in('key', array_keys($config))
				->get('config')
				->result_array();

			foreach ($result as $v) {
				$config[$v['key']] = $v['val'];
			}

			return $config;
		}

		/**
		 * Збереження налаштування
		 *
		 * @param string $key
		 * @param string $val
		 * @param bool $escape_key
		 * @param bool $escape_val
		 */
		public function save_config($key, $val, $escape_key = true, $escape_val = true)
		{
			$c = (int)$this->db
				->where('key', $escape_key ? $this->db->escape_str($key) : $key)
				->count_all_results('config');

			if ($c === 0) {
				$this->db->insert(
					'config',
					array(
						'key' => $escape_key ? $this->db->escape_str($key) : $key,
						'val' => $escape_val ? $this->db->escape_str($val) : $val,
					)
				);
			} else {
				$this->db->update(
					'config',
					array('val' => $escape_val ? $this->db->escape_str($val) : $val),
					array('key' => $escape_key ? $this->db->escape_str($key) : $key)
				);
			}
		}

		/**
		 * Отримання налаштувань заглушки
		 *
		 * @return array
		 */
		public function get_gag()
		{
			$config = $this->get_config(array('is_gag' => 0));

			$result = $this->db
				->select('lang, text')
				->get('gag')
				->result_array();

			foreach ($result as $v) {
				$config[$v['lang']] = $v['text'];
			}

			return $config;
		}

		/**
		 * Збереження налаштувань заглушки
		 *
		 * @param $is_gag
		 * @param $gag
		 */
		public function save_gag($is_gag, $gag)
		{
			$this->save_config('is_gag', $is_gag);

			foreach ($gag as $k => $v) {
				$this->db->set('text', $this->db->escape_str($v));
				$this->db->where('lang', $k);
				$this->db->update('gag');
			}
		}

		/**
		 * Отримання статичної інформації
		 *
		 * @param $type
		 * @return array
		 */

		public function get_static_information($type)
		{
			if ($this->db->where('id', 1)->count_all_results($type) == 0)
			{
				$this->db->insert($type, array('id' => 1));
			}
			return $this->db->select(" *, floor_".LANG." as floor, slogan_".LANG." as slogan, address_".LANG." as address")->get_where($type, array('id' => 1))->row_array();
		}

		/**
		 *
		 * Збереження статичної інформації
		 * @param $set
		 */

		public function save_static_information($set)
		{
			$this->db->update('component_static_information', $set, array('id'=>1));
		}
	}