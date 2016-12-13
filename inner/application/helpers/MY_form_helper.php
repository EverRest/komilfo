<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	if (!function_exists('clean_string')) {
		/**
		 * Очищення рядка від переносів і відступів
		 *
		 * @param int $string
		 * @return string
		 */
		function clean_string($string)
		{
			return str_replace(array("\n", "\t", "\r"), '', $string);
		}
	}