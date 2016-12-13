<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	require_once APPPATH . 'third_party/PHPMailer/class.phpmailer.php';

	/**
	 * Class Email_lib
	 */

	class Email_lib extends PHPMailer {

		public function __construct()
		{
			parent::__construct();

			$this->CharSet = 'UTF-8';
			$this->ContentType = 'text/html';
		}
	}