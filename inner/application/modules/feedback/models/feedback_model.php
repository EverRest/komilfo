<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * Class Feedback_model
	 */
	class Feedback_model extends CI_Model {

		public function insert($set)
		{
			$this->db->insert('feedback', $set);
		}

	}