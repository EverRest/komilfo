<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Admin_benefit_services_model extends CI_Model
	{

		/**
		 * Отримання компоненту
		 * @return array|null
		 */
		public function get_article()
		{
			if ($this->db->where('id', 1)->count_all_results('component_services') == 0)
			{
				$this->db->insert('component_services', array('id' => 1));
			}

			return $this->db->get_where('component_services', array('id' => 1))->row_array();
		}

        public function get_service($id)
        {
            return $this->db->get_where('component_services', array('component_id' => $id))->result_array();
		}

		/**
		 * Збереження компоненту
		 *
		 */
		public function update($data)
		{
            $res = array();
            $res['component_id'] = $data['component_id'];
            $res['menu_id'] = $data['menu_id'];
            $res['header'] = $data['ca_header'];

            unset($data['component_id']);
            unset($data['menu_id']);
            unset($data['ca_header']);

            $arr_num = array();

            foreach ($data as $key => $value) {
                array_push($arr_num, $value);
            }

            for($x = 0; $x < count($arr_num) - 1; $x++) {
                $res['description'] = $arr_num[$x];
                $x++;
                $res['price'] = $arr_num[$x];
                $this->db->insert('component_services', $res);
            }

		}

		/**
		 * Видалення компоненту
		 *
		 * @param int $component_id
		 */
		public function delete_component($component_id)
		{
			$this->db->delete('components', array('component_id' => $component_id));
			$this->db->delete('component_services', array('component_id' => $component_id));
		}

	}