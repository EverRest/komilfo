<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	class Seo_lib {

		public function generate_keywords($text = '')
		{
			if ($text == '')
			{
				return '';
			}

			$search = array(
				"'ё'",
				"'<script[^>]*?>.*?</script>'si",
				"'<[\/\!]*?[^<>]*?>'si",
				"'([\r\n])[\s]+'",
				"'&(quot|#34);'i",
				"'&(amp|#38);'i",
				"'&(lt|#60);'i",
				"'&(gt|#62);'i",
				"'&(nbsp|#160);'i",
				"'&(iexcl|#161);'i",
				"'&(cent|#162);'i",
				"'&(pound|#163);'i",
				"'&(copy|#169);'i",
				"'&(laquo|#171);'i",
				"'&(raquo|#187);'i",
				"'&(ndash|#150);'i",
				"'&(mdash|#151);'i",
				//"'&#(\d+);'e"
			);

			$replace = array(
				"е",
				" ",
				" ",
				"\\1 ",
				"\" ",
				" ",
				" ",
				" ",
				" ",
				chr(161),
				chr(162),
				chr(163),
				chr(169),
				"",
				"",
				"–",
				"—",
				"chr(\\1)"
			);

			$del_symbols = array(
				",", ".", ";", ":", "\"", "#", "\$", "%", "^",
				"!", "@", "`", "~", "*", "-", "=", "+", "\\",
				"|", "/", ">", "<", "(", ")", "&", "?", "¹", "\t",
				"\r", "\n", "{", "}", "[", "]", "'", "“", "”", "•",
				"«", "»", "—"
			);

			$text = mb_convert_case($text, CASE_UPPER, 'UTF-8');
			$text = preg_replace($search, $replace, $text);
			$text = str_replace($del_symbols, array(" "), $text);

			$_words = array(
				'как', 'для', 'что', 'или', 'это', 'этих', 'была', 'всех', 'вас', 'они', 'оно', 'еще', 'когда', 'где', 'эта', 'лишь', 'уже',
				'вам', 'нет', 'если', 'надо', 'все', 'так', 'его', 'чем', 'при', 'даже', 'мне', 'есть', 'раз', 'два', 'лише', 'він', 'вона',
				'воно', 'вони', 'які', 'такі', 'вже', 'так', 'яких', 'нашій', 'меня', 'очень', 'також', 'таких', 'бути', 'всіх', 'щодо',
				'його', 'йому', 'якщо', 'проте', 'доволі', 'наразі', 'можна', ''
			);
			foreach ($_words as $key => $word)
			{
				$_words[$key] = '/\s' . $word . '\s/i';
			}
			$_words[] = '/^(\d+)$/';
			$text = preg_replace($_words, '', $text);
			$text = preg_replace("( +)", " ", $text);
			$text = explode(" ", trim($text));

			$tmp_arr = array();

			foreach ($text as $val)
			{
				if (mb_strlen($val) >= 4)
				{
					if (array_key_exists($val, $tmp_arr))
					{
						$tmp_arr[$val]++;
					}
					else
					{
						$tmp_arr[$val] = 1;
					}
				}
			}

			arsort($tmp_arr);

			$tmp_arr = array_slice($tmp_arr, 0, 30);

			if (count($tmp_arr) <= 15)
			{
				$_tmp_arr = array_flip($tmp_arr);
				$str = array_shift($_tmp_arr);
			}
			else
			{
				$need = ceil(count($tmp_arr) / 5);
				$tmp_arr = array_slice($tmp_arr, 0, $need, TRUE);

				$str = "";

				foreach ($tmp_arr as $key => $val)
				{
					$str .= $key . ", ";
				}
			}

			return trim(mb_substr($str, 0, mb_strlen($str) - 2));
		}

		/**
		 * Автоматична генерація опису сторінки
		 * @param string $text
		 *
		 * @return string
		 */
		public function generate_description($text = '')
		{
			if ($text == '')
			{
				return '';
			}

			$text = strip_tags($text);

			if (mb_strlen($text, 'UTF-8') > 350)
			{
				$length = 350;

				$string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($text, 0, $length + 1, 'UTF-8'));
				$string = str_replace('"', '', $string);
				$string = str_replace("\n", ' ', $string);
				$string = str_replace("\t", ' ', $string);
				$string = str_replace("\r", ' ', $string);

				//return mb_substr($string, 0, $length / 2, 'UTF-8') . mb_substr($string, -$length / 2, $length, 'UTF-8');

				return $string;
			}

			return $text;
		}
	}