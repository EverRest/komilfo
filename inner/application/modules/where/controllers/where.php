<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Where
	 *
	 * @property Where_model $where_model
	 */
	class Where extends MX_Controller {

		/**
		 * @param $menu_id
		 * @param $component_id
		 * @param $hidden
		 * @param array $config
		 * @return string|void
		 */
		public function index($menu_id, $component_id, $hidden, array $config)
		{
			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
			);

			if ($this->init_model->is_admin() and $this->init_model->check_access('where_index', $menu_id))
			{
				$template_data['hidden'] = $hidden;
				return $this->load->view('admin/where_tpl', $template_data, TRUE);
			}
			else
			{
				$this->load->model('where_model');

				$uri = $this->input->server('REQUEST_URI');
				
				$uri = substr($uri, 1, -1);
				$segments = explode('/', $uri);

				if(preg_match("/^[0-9]*$/", $segments[count($segments)-1]) == 1){
					$dress = $this->where_model->get_dress($segments[count($segments)-1]);					
				}
				if(!empty($dress)){
					$shops = ($this->is_serialized($dress['shops'])? unserialize($dress['shops']):$dress['shops']);
					$country = ($this->is_serialized($dress['country'])? unserialize($dress['country']):$dress['country']);
					$city = ($this->is_serialized($dress['city'])? unserialize($dress['city']):$dress['city']);

					$template_data['menu'] = $this->where_model->get_menu($country, $city, $shops);
					$template_data['markers'] = $this->where_model->get_all_markers($country, $city, $shops);
				}else{
					$template_data['menu'] = $this->where_model->get_menu();
					$template_data['markers'] = $this->where_model->get_all_markers();
				}
				return $this->load->view('where_tpl', $template_data, TRUE);
			}
		}

		/**
		 * @return string
		 */
		public function get()
		{
			$response = '';
			$id = (int)$this->input->post('id');

			if ($id > 0)
			{
				$this->load->model('where_model');
				$response = $this->where_model->get($id);
			}

			return $response;
		}

		function is_serialized($value, &$result = null)
		{
			// Bit of a give away this one
			if (!is_string($value))
			{
				return false;
			}
			// Serialized false, return true. unserialize() returns false on an
			// invalid string or it could return false if the string is serialized
			// false, eliminate that possibility.
			if ($value === 'b:0;')
			{
				$result = false;
				return true;
			}
			$length	= strlen($value);
			$end	= '';
			switch ($value[0])
			{
				case 's':
					if ($value[$length - 2] !== '"')
					{
						return false;
					}
				case 'b':
				case 'i':
				case 'd':
					// This looks odd but it is quicker than isset()ing
					$end .= ';';
				case 'a':
				case 'O':
					$end .= '}';
					if ($value[1] !== ':')
					{
						return false;
					}
					switch ($value[2])
					{
						case 0:
						case 1:
						case 2:
						case 3:
						case 4:
						case 5:
						case 6:
						case 7:
						case 8:
						case 9:
						break;
						default:
							return false;
					}
				case 'N':
					$end .= ';';
					if ($value[$length - 1] !== $end[0])
					{
						return false;
					}
				break;
				default:
					return false;
			}
			if (($result = @unserialize($value)) === false)
			{
				$result = null;
				return false;
			}
			return true;
		}
	}