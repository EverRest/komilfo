<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_config_model extends CI_Model
	{
		public function config_get($keys = array())
		{
			$result = $this->db->select('key, val')->where_in('key', array_keys($keys))->get('config')->result_array();
			foreach ($result as $v) $keys[$v['key']] = $v['val'];

			return $keys;
		}

		public function config_set($vars)
		{
			foreach ($vars as $k => $v)
			{
				if ($this->db->where('key', $k)->count_all_results('config') > 0)
				{
					$this->db->set('val', $v)->where('key', $k)->update('config');
				}
				else
				{
					$set = array(
						'key' => $k,
						'val' => $v,
					);
					$this->db->insert('config', $set);
				}
			}
		}

		public function get_admin()
		{
			return $this->db->select('login')->where('id', 1)->get('admin')->row_array();
		}

		public function save_admin($set, $where)
		{
			$this->db->update('admin', $set, $where);
		}

		public function check_password($where)
		{
			$r = $this->db->where($where)->count_all_results('admin');
			return ($r > 0) ? TRUE : FALSE;
		}

		public function save_password($set, $where)
		{
			$this->db->update('admin', $set, $where);
		}

		public function change_prices($usd, $eur, $rur)
		{
			if ($usd > 0 OR $eur > 0 OR $rur > 0)
			{
				$result = $this->db->select('product_id, price_usd, price_eur, price_rur')->where('price_usd >', 0)->or_where('price_eur >', 0)->or_where('price_rur >', 0)->get('catalog')->result_array();

				foreach ($result as $row)
				{
					$price = 0;

					if ($row['price_usd'] > 0)
					{
						$price = round($row['price_usd'] * $usd);
					}
					else
					{
						if ($row['price_eur'] > 0)
						{
							$price = round($row['price_eur'] * $eur);
						}
						else
						{
							if ($row['price_rur'] > 0)
							{
								$price = round($row['price_rur'] * $eur);
							}
						}
					}

					if ($price > 0)
					{
						$this->db->update('catalog', array('price' => $price), array('product_id' => $row['product_id']));
					}
				}
			}
		}

		public function get_header()
		{
			$result = $this->db->get('component_header')->row_array();
			return $result;
		}

		public function save_header($set)
		{
			$where = array('id'=>1);
			$this->db->update('component_header', $set, $where);
		}


		public function get_map()
		{
			if ($this->db->count_all_results('component_footer') == 0)
			{
				$this->db->insert('component_footer', array('id'=>1));
			}

			return $this->db->get('component_footer')->row_array();
		}


		public function update( $set)
		{
			$this->db->update('component_footer', $set, array('id'=>1));
		}
	}