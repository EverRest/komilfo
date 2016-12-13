<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Feedback
	 *
	 * @property Feedback_model $feedback_model
	 * @property Admin_feedback_model $admin_feedback_model
	 */

	class Feedback extends MX_Controller
	{

		private $per_page = 10;

		/**
		 * Вивід форми зворотного зв’язку / списку повідомлень
		 *
		 * @param int $menu_id
		 * @param int $component_id
		 * @param int $hidden
		 * @param string string $config
		 * @return string|void
		 */
		public function index($menu_id, $component_id, $hidden, $config = '')
		{
			$template_data = array(
				'menu_id' => $menu_id,
				'component_id' => $component_id,
				'h1' => $this->template_lib->get_h1()
			);

			$this->template_lib->set_h1();

			if ($this->init_model->is_admin())
			{
				$this->load->model('admin_feedback_model');
				$this->template_lib->set_js('admin/jquery.pagination.js');

				$template_data['hidden'] = $hidden;
				$template_data['total_rows'] = $this->admin_feedback_model->count_messages($component_id);
				$template_data['messages'] = $this->load->view('admin/list_tpl', array('messages' => $this->admin_feedback_model->get_messages($component_id, 0, $this->per_page)), TRUE);

				$this->admin_feedback_model->status($component_id);

				return $this->load->view('admin/feedback_tpl', $template_data, TRUE);
			}
			else
			{
				return $this->load->view('feedback_tpl', $template_data, TRUE);
			}
		}

		/**
		 * Відправка повідомлення
		 *
		 * @return string
		 */
		public function send()
		{
			$response = array('error' => 0);

			$this->load->library('captcha_lib');

			if (!$this->captcha_lib->validate($this->input->post('code', TRUE), 'feedback'))
			{
				$response['error'] = 1;
			}
			else
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('name', '', 'required');
				$this->form_validation->set_rules('email', '', 'required|valid_email');
				$this->form_validation->set_rules('message', '', 'required');

				if ($this->form_validation->run())
				{
					// Відправка повідомлення адміністратору

					$this->load->library('Email_lib');

					$domain_name = str_replace('www.', '', $this->input->server('HTTP_HOST'));
					$this->email_lib->SetFrom('info@' . $domain_name, $domain_name);
					$this->email_lib->AddAddress($this->config->item('site_email'));

					$subject = strip_tags($this->input->post('theme', TRUE));
					if ($subject == '') $subject = 'Зворотній зв`язок ' . $this->config->item('site_name_' . LANG);

					$tpl_data = array(
						'subject' => $subject,
						'name' => strip_tags($this->input->post('name', TRUE)),
						'email' => strip_tags($this->input->post('email', TRUE)),
						'phone' => strip_tags($this->input->post('phone', TRUE)),
						'theme' => strip_tags($this->input->post('theme', TRUE)),
						'message' => strip_tags(str_replace(array("\r", "\n", "\t"), '', $this->input->post('message', TRUE))),
						'time' => time()
					);

					$this->email_lib->Subject = $subject;
					$this->email_lib->Body = $this->load->view('admin/letter_tpl', $tpl_data, TRUE);

					$this->email_lib->Send();

					// Збереження повідомлення в базу даних

					$set = array(
						'component_id' => intval($this->input->post('component_id')),
						'menu_id' => intval($this->input->post('menu_id')),
						'name' => $this->db->escape_str($tpl_data['name']),
						'email' => $this->db->escape_str($tpl_data['email']),
						'phone' => $this->db->escape_str($tpl_data['phone']),
						'theme' => $this->db->escape_str($tpl_data['theme']),
						'message' => $this->db->escape_str($tpl_data['message']),
						'time' => $tpl_data['time'],
						'status' => 0
					);

					$this->load->model('feedback_model');
					$this->feedback_model->insert($set);
				}
				else
				{
					$response['error'] = 2;
				}
			}

			return json_encode($response);
		}

		/**
		 * Замовлення знижки
		 *
		 * @return string
		 */
		public function sale()
		{
			$response = array('error' => 1);

			$this->load->library('form_validation');

			$this->form_validation->set_rules('name', '', 'required');
			$this->form_validation->set_rules('phone', '', 'required');
			$this->form_validation->set_rules('email', '', 'required');

			if ($this->form_validation->run())
			{
				$name = strip_tags($this->input->post('name', TRUE));
				$phone = strip_tags($this->input->post('phone', TRUE));
				$email = strip_tags($this->input->post('email', TRUE));

				$template_data = array(
					'phone' => $phone,
					'name' => $name,
					'email' => $email,
				);

				$this->load->library('Email_lib');

				// Відправка листа адміну

				$this->email_lib->SetFrom('info@' . str_replace('www.', '', $_SERVER['HTTP_HOST']), str_replace('www.', '', $_SERVER['HTTP_HOST']));
				$this->email_lib->AddAddress($this->config->item('site_email'));

				$template_data['subject'] = 'Отримати знижку. ' . $this->config->item('site_name_' . LANG);

				$this->email_lib->Subject = $template_data['subject'];
				$this->email_lib->Body = $this->load->view('admin/sale_letter_tpl', $template_data, TRUE);

				$this->email_lib->Send();
				$this->email_lib->ClearAddresses();

				$response['error'] = 0;
			}

			return json_encode($response);
		}

		/**
		 * Формування коду перевірки
		 *
		 * @return mixed
		 */
		public function captcha()
		{
			$this->config->set_item('is_ajax_request', TRUE);

			$this->load->library('captcha_lib');
			$response = $this->captcha_lib->get_image('feedback');

			return $response;
		}
	}