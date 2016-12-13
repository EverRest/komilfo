<?php

	class MY_Form_validation extends CI_Form_validation
	{
		public function _error_array()
		{
			return array_values($this->_error_array);
		}

		protected function _build_error_msg($line, $field = '', $param = '')
		{
			return $field;
		}
	}