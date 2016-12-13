<?php

	class MY_Form_validation extends CI_Form_validation
	{
		/**
		 * Отримання масиву помилок
		 *
		 * @return array
		 */
		public function get_error_array()
		{
			return array_values($this->_error_array);
		}

		/**
		 * @param $line
		 * @param string $field
		 * @param string $param
		 * @return string
		 */
		protected function _build_error_msg($line, $field = '', $param = '')
		{
			return $field;
		}

		/**
		 * Порівння поля з цілим числом
		 *
		 * @param $a
		 * @param $b
		 * @return bool
		 */
		public function equal_int($a, $b)
		{
			return (int)$a === (int)$b;
		}

		/**
		 * Порівняння поля з дробним числом
		 *
		 * @param $a
		 * @param $b
		 * @return bool
		 */
		public function equal_float($a, $b)
		{
			return (float)$a === (float)$b;
		}

		/**
		 * Порівння поля з рядком
		 *
		 * @param $a
		 * @param $b
		 * @return bool
		 */
		public function equal_string($a, $b)
		{
			return (string)$a === (string)$b;
		}
	}