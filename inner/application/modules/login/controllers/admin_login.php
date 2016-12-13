<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @property Admin_login_model $admin_login_model
	 */

	class Admin_login extends MX_Controller
	{
		public function index()
		{
			$this->template_lib->set_title('Вхід');
			$this->template_lib->set_css('admin.css');

			$this->template_lib->set_content($this->load->view('form_tpl', '', TRUE));
		}

		public function login()
		{
			$response = array(
				'error' => 1,
				'message' => array(1, 2)
			);

			$this->load->library('form_validation');

			$this->form_validation->set_rules('login', 1, 'required');
			$this->form_validation->set_rules('password', 1, 'required');

			if ($this->form_validation->run())
			{
				$this->load->model('admin_login_model');

				$login = $this->input->post('login', TRUE);
				$password = $this->input->post('password');

				$admin_data = $this->admin_login_model->get_admin($login);
                                
                                if (isset($admin_data['admin_id']))
				{
					if (md5($password . $admin_data['salt']) === $admin_data['password'])
					{
						$admin_key = md5($admin_data['admin_id'] . $admin_data['password'] . $this->config->item('encryption_key') . $this->input->user_agent());
						$this->session->set_userdata('admin_key', $admin_key);
						$this->session->set_userdata('admin_id', $admin_data['admin_id']);
						$this->session->set_userdata('admin_name', $admin_data['name']);
						$this->session->set_userdata('admin_root', $admin_data['root']);
						$this->session->set_userdata('key_admin', $admin_key);
						$this->session->set_userdata('is_admin', $admin_data['admin_id']);

						$this->admin_login_model->change_admin($admin_data['admin_id']);

						$response['error'] = 0;
						$response['message'] = '';
					}
					else
					{
						$response['message'] = array(2);
					}
				}
				else
				{
					$response['message'] = array(1);
				}
			}
			else
			{
				$response['message'] = $this->form_validation->error_array();
			}

			return json_encode($response);
		}

		public function logout()
		{
			$this->session->unset_userdata('admin_key');
			$this->session->unset_userdata('admin_id');

			redirect($this->uri->full_url());
		}
	}