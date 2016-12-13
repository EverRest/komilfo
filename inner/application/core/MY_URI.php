<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class MY_URI extends CI_URI {

		/**
		 * Встановлення мови сайту та мовного сегменту для пормування посилань
		 */
		public function set_language()
		{
			$multi_lenguages = $this->config->item('multi_languages');
			$languages = $this->config->item('languages');

			$database_languages = $this->config->item('database_languages');
			$default_language = $this->config->item('def_lang');

			if (!$multi_lenguages)
			{
				define('LANG', $default_language);
				define('DEF_LANG', $default_language);

				foreach ($database_languages as $k => $v)
				{
					if (isset($languages[$v]))
					{
						$database_languages[$k] = array(
							'url' => $v == $default_language ? '' : $v,
							'code' => $v,
							'name' => $languages[$v]
						);
					}
					else
					{
						unset($database_languages[$k]);
					}
				}

				$this->config->set_item('database_languages', $database_languages);
			}
			else
			{
				$current_language = $multi_lenguages ? $this->segment(1) : $this->config->item('language');

				if (is_array($database_languages) AND count($database_languages) > 0)
				{
					if (in_array($current_language, $database_languages))
					{
						if ($current_language == $default_language)
						{
							$segments = $this->segment_array();
							if (isset($segments[1])) unset($segments[1]);
							header('Location: ' . $this->full_url(implode($segments)));
							exit;
						}
						else
						{
							define('LANG', $current_language);
							define('LANG_SEGMENT', $current_language);
						}
					}
					else
					{
						define('LANG', $default_language);
					}

					foreach ($database_languages as $k => $v)
					{
						if (isset($languages[$v]))
						{
							$database_languages[$k] = array(
								'url' => $v == $default_language ? '' : $v,
								'code' => $v,
								'name' => $languages[$v]
							);
						}
						else
						{
							unset($database_languages[$k]);
						}
					}

					$this->config->set_item('database_languages', $database_languages);

					define('DEF_LANG', $default_language);
				}
				else
				{
					show_error('Site languages are not configured!', 500, 'Config error');
				}
			}
		}

		/**
		 * Формування посилання
		 *
		 * @param string $uri
		 * @param string $lang
		 *
		 * @return string
		 */
		public function full_url($uri = '', $lang = NULL)
		{
			if ($this->config->item('multi_languages'))
			{
				if ($lang != '')
				{
					$uri = $lang . '/' . trim($uri, '/');
				}
				else
				{
					if (defined('LANG_SEGMENT') AND $lang === NULL) $uri = LANG_SEGMENT . '/' . trim($uri, '/');
				}
			}

			return $this->config->site_url($uri);
		}

		/**
		 * Отримання заданих сегментів посилання
		 *
		 * @param array $segments
		 *
		 * @return array
		 */
		function parse_url($segments = array())
		{
			$url = $this->uri_string();
			$url = explode('/', $url);

			if (count($segments) > 0 AND count($url) > 0)
			{
				foreach ($url as $key => $val)
				{
					if (isset($segments[$val]) AND isset($url[$key + 1]))
					{
						$segments[$val] = $url[$key + 1];
					}
				}
			}

			return $segments;
		}

		/**
		 * Очистка посилання від непотрібних сигментів
		 *
		 * @param array $clean_segments
		 *
		 * @return string
		 */
		function clean_url($clean_segments = array())
		{
			$clean_segments = array_merge($this->config->item('clean_segments'), $clean_segments);

			$url = $this->uri_string();
			$url = explode('/', $url);

			if (count($url) > 0 AND count($clean_segments) > 0)
			{
				$delete_next = FALSE;

				foreach ($url as $key => $val)
				{
					if ($delete_next)
					{
						unset($url[$key]);
						$delete_next = FALSE;

						continue;
					}

					if (isset($clean_segments[$val]))
					{
						unset($url[$key]);
						if ($clean_segments[$val] == 1) $delete_next = TRUE;
					}
				}

			}

			return implode('/', $url);
		}
	}