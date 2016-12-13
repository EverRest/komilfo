<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	if (!function_exists('get_dir_code')) {
		/**
		 * Отримання назви директориї для кожних ста записів
		 *
		 * @param int $item_id
		 * @return int
		 */
		function get_dir_code($item_id)
		{
			return ceil($item_id / 100) * 100;
		}
	}

	if (!function_exists('get_dir_path')) {
		/**
		 * Отримання повного шляху директорії
		 *
		 * @param string $path
		 * @param bool $create
		 * @return int
		 */
		function get_dir_path($path, $create = true)
		{
			$path = ROOT_PATH . trim($path, '/') . '/';

			if ($create and !file_exists($path)) {
				mkdir($path, 0777, true);
			}

			return $path;
		}
	}

	if (!function_exists('is_dir_empty')) {
		/**
		 * Перевірка на пусту директорію
		 *
		 * @param string $path
		 * @return bool
		 */
		function is_dir_empty($path)
		{
			return (file_exists($path) and is_dir($path)) ? count(scandir($path)) === 2 : false;
		}
	}