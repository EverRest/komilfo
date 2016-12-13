<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Call
	 */

	class Call extends MX_Controller
	{
		/**
		 * Відправка замовлення ремонту/дзвінка
		 *
		 * @return string
		 */
		public function send()
		{
			$response = array('success' => FALSE);

			$this->load->library('form_validation');

			$email = $this->input->post('email');
			$name = $this->input->post("name");
			$phone = $this->input->post("phone");
			$message = $this->input->post("message");

			$response['email'] =  $email;
			$response['message'] =  $message;

			$this->form_validation->set_rules('name', '', 'required');
			if($email != null) $this->form_validation->set_rules('email', '', 'required');

			
			if ($this->form_validation->run() AND $this->config->item('site_email') != '')
			{
				$this->load->library('Email_lib');

				$domain_name = str_replace('www.', '', $this->input->server('HTTP_HOST'));

				$subject = $this->input->post('subject');
				
				$tpl_data = array ();

				if($email != null){$tpl_data['email'] = $email;}
				if($name != null){$tpl_data['name'] = $name;}
				if($phone != null){$tpl_data['phone'] = $phone;}
				if($message != null){$tpl_data['message'] = $message;}
				if($subject != null){$tpl_data['subject'] = $subject;}

				$this->email_lib->SetFrom('info@' . $domain_name, $subject);
				$this->email_lib->AddAddress($this->config->item('site_email'));
				$this->email_lib->Subject = $subject;
				$this->email_lib->Body = $this->load->view('letter_tpl', $tpl_data, TRUE);

				$this->email_lib->Send();

				$response['success'] = TRUE;
			}

			return json_encode($response);
		}
	}
