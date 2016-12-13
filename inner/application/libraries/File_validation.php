<?php

	class File_validation {
		/**
		 * @var int
		 */
		protected $total_valid_files = 0;

		/**
		 * @var array
		 */
		protected $file_types = array();

		/**
		 * @var array
		 */
		protected $types = array();

		/**
		 * @var int
		 */
		protected $min_size = 0;

		/**
		 * @var int
		 */
		protected $max_size = 0;

		/**
		 * @var null|array
		 */
		protected $files;

		/**
		 * @var array
		 */
		protected $errors = array();

		/**
		 * Додавання файлу для валідації
		 *
		 * @param string $name
		 * @return File_validation
		 */
		public function set_file($name) {
			if ($this->check_file($name)) {
				if (is_array($_FILES[$name]['name'])) {
					foreach ($_FILES[$name]['name'] as $key => $file_name) {
						$this->files[$key] = array(
							'name' => mb_strtolower($file_name),
							'tmp_name' => $_FILES[$name]['tmp_name'][$key],
							'size' => $_FILES[$name]['size'][$key],
							'error' => $_FILES[$name]['error'][$key],
						);
					}
				} else {
					$this->files = array($_FILES[$name]);
				}
			}

			return $this;
		}

		/**
		 * Встановлення типів файлів
		 *
		 * @param array $file_types
		 * @return File_validation
		 */
		public function set_file_types(array $file_types) {
			$this->file_types = $file_types;
			return $this;
		}

		/**
		 * Встановлення мінімального розміру файлу в байтах
		 *
		 * @param int $min_size Mb
		 * @return File_validation
		 */
		public function set_min_size($min_size = 0) {
			$this->min_size = $min_size * 1048576;
			return $this;
		}

		/**
		 * Встановлення максимального розміру файлу в байтах
		 *
		 * @param int $max_size Mb
		 * @return File_validation
		 */
		public function set_max_size($max_size = 0) {
			$this->max_size = $max_size * 1048576;
			return $this;
		}

		/**
		 * Перевірка наявності завантаженого файла
		 *
		 * @param string $name
		 * @param null|int $key
		 * @return bool
		 */
		public function check_file($name, $key = null) {
			if ($key !== null) {
				return array_key_exists($name, $_FILES) and array_key_exists($key, $_FILES[$name]['name']);
			} else {
				return array_key_exists($name, $_FILES);
			}
		}

		/**
		 * Запуск валідації
		 */
		public function run() {
			if (is_array($this->files)) {
				foreach ($this->files as $key => $file) {
					$this->validate($key, $file);
				}
			}

			return $this->total_valid_files === count($this->files);
		}

		/**
		 * Валідація файлу
		 *
		 * @param int $key
		 * @param array $file
		 */
		private function validate($key, $file) {
			//$this->errors[$key] = array();

			if ((int)$file['error'] !== 0) {
				switch ($file['error']) {
					case 1:
					case 2:
						$this->errors[$file['name']]['max_size'] = '';
						break;
					default:
						$this->errors[$file['name']]['fail_upload'] = '';
						break;
				}
			} else {

				if ($this->min_size > 0 and $this->min_size > $file['size']) {
					$this->errors[$file['name']]['min_size'] = '';
				}

				if ($this->max_size > 0 and $this->max_size < $file['size']) {
					$this->errors[$file['name']]['max_size'] = '';
				}

				if (count($this->file_types) > 0) {
					$_types = $this->get_types();
					$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

					if (!array_key_exists($ext, $_types)) {
						$this->errors[$file['name']]['file_type'] = '';
					} else {
						$types = array();

						foreach ($this->file_types as $type) {
							if (array_key_exists($type, $_types)) {

								if (is_array($_types[$type])) {
									$types = array_merge($types, $_types[$type]);
								} else {
									$types[] = $_types[$type];
								}

							}
						}

						$mime = mime_content_type($file['tmp_name']);

						if (
							(is_array($types) and !in_array($mime, $types, true))
							or (is_string($types) and $mime !== $types)
						) {
							$this->errors[$file['name']]['file_type'] = '';
						}
					}
				}
			}

			if (!array_key_exists($file['name'], $this->errors)) {
				$this->total_valid_files++;
			}
		}

		/**
		 * Отримання помилок валідації
		 *
		 * @param null|int $key
		 * @return array
		 */
		public function get_errors($key = null) {
			if ($key !== null and array_key_exists($key, $this->errors)) {
				return $this->errors[$key];
			} else {
				return $this->errors;
			}
		}

		/**
		 * Скидання всіх налаштувань
		 *
		 * @return File_validation
		 */
		public function reset() {
			$this->file_types = array();
			$this->min_size = 0;
			$this->max_size = 0;
			$this->total_valid_files = 0;
			$this->files = null;
			$this->errors = array();

			return $this;
		}

		################################################################################################################

		/**
		 * Отримання типів файлів
		 *
		 * @return array
		 */
		private function get_types() {
			if (count($this->types) === 0) {
				$this->types = $this->load_types();
			}

			return $this->types;
		}

		/**
		 * Отримання типів файлів з конфігурації
		 *
		 * @return array
		 */
		private function load_types() {
			return include(APPPATH . 'config/mimes.php');
		}
	}