<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Call
	 */

	class Call extends MX_Controller
	{
		/**
		 * Відправка замовлення дзвінка
		 *
		 * @return string
		 */
		public function send()
		{
			$response = array('success' => FALSE);
			
			$this->load->library('form_validation');

			$name = strip_tags($this->input->post('name', TRUE));
			$phone = $this->input->post('phone', TRUE);
			$message = stripslashes($this->input->post('message', true));
			$email = strip_tags($this->input->post('email', TRUE));

			$this->form_validation->set_rules('phone', '', 'required');
			if($name != '') $this->form_validation->set_rules('name', '', 'required');
			if($email != '') $this->form_validation->set_rules('email', '', 'required');
				
			if ($this->form_validation->run() AND $this->config->item('site_email') != '')
			{
				$this->load->library('Email_lib');

				$tpl_data = array ();
				
				if($name != '') $tpl_data['name'] = $name;
				if($phone != '') $tpl_data['phone'] = $phone;
				if($message != '') $tpl_data['message'] = $message;
				if($email != '') $tpl_data['email'] = $email;
								
				if($this->input->post('subject')){
					$subject = $this->input->post('subject');
				}else $subject = 'Замовлення курсу';

				$tpl_data['time'] = time();
				$tpl_data['subject'] = $subject;

				$domain_name = str_replace('www.', '', $this->input->server('HTTP_HOST'));
				$this->email_lib->SetFrom('info@' . $domain_name, $domain_name);
				$this->email_lib->AddAddress($this->config->item('site_email'));
				$this->email_lib->Subject = $subject;
				$this->email_lib->Body = $this->load->view('letter_tpl', $tpl_data, TRUE);
				$this->email_lib->Send();
				$response['success'] = TRUE;
				
			}
			return json_encode($response);
		}

	}