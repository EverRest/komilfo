<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @param $int
	 * @param $expressions
	 * @return string
	 */
	function declension($int, $expressions)
	{
		if (count($expressions) < 3) {
			$expressions[2] = $expressions[1];
		}

		$int = (int)$int;
		$count = $int % 100;

		if ($count >= 5 and $count <= 20) {
			$result = $expressions['2'];
		} else {
			$count = $count % 10;
			if ($count === 1) {
				$result = $expressions['0'];
			} elseif ($count >= 2 and $count <= 4) {
				$result = $expressions['1'];
			} else {
				$result = $expressions['2'];
			}
		}

		return $result;
	}

	/**
	 * @param $from
	 * @param $to
	 * @return string
	 */
	function passed_time($from, $to)
	{
		$date = array();

		$date_from = new DateTime(date('Y-m-d H:i:s', $from));
		$date_to = new DateTime(date('Y-m-d H:i:s', $to));
		$date_interval = $date_from->diff($date_to);

		$y = $date_interval->format('%y');
		if ($y > 0) {
			$date[] = $y . ' р.';
		}

		$m = $date_interval->format('%m');
		if ($m > 0) {
			$date[] = $m . ' міс.';
		}

		$d = $date_interval->format('%d');
		if ($d > 0) {
			$date[] = $d . ' дн.';
		}

		$h = $date_interval->format('%h');
		if ($h > 0) {
			$date[] = $h . ' год.';
		}

		$i = $date_interval->format('%i');
		if ($i > 0) {
			$date[] = $i . ' хв.';
		}

		if (count($date) === 0) {
			$s = $date_interval->format('%s');
			if ($s > 0) {
				$date[] = $s . ' сек.';
			}
		}

		return implode(' ', $date);
	}

	/**
	 * Обрізка тексту
	 *
	 * @param $string
	 * @param int $length
	 * @param string $etc
	 * @param bool $break_words
	 * @param bool $middle
	 * @return string
	 */
	function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
	{
		if ($length === 0) {
			return '';
		}

		if (mb_strlen($string, 'UTF-8') > $length) {
			$length -= min($length, mb_strlen($etc, 'UTF-8'));

			if (!$break_words and !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1, 'UTF-8'));
			}

			if (!$middle) {
				return mb_substr($string, 0, $length, 'UTF-8') . $etc;
			}

			return mb_substr($string, 0, $length / 2, 'UTF-8') . $etc . mb_substr($string, -$length / 2, $length, 'UTF-8');
		}

		return $string;
	}

	if (!function_exists('mb_ucfirst')) {
		/**
		 * @param string $str
		 * @param bool $other_lower
		 * @return string
		 */
		function mb_ucfirst($str, $other_lower = true)
		{
			if ($str === '') {
				return $str;
			}

			$letter = mb_strtoupper(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8');

			$str = mb_substr($str, 1, mb_strlen($str), 'UTF-8');
			if ($other_lower) {
				$str = mb_convert_case($str, MB_CASE_LOWER, 'UTF-8');
			}

			return $letter . $str;
		}
	}

	if (!function_exists('mb_lcfirst')) {
		/**
		 * @param string $str
		 * @return string
		 */
		function mb_lcfirst($str)
		{
			return mb_strtolower(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($str, 1, mb_strlen($str), 'UTF-8');
		}
	}

	/**
	 * Отримання рейтингу у вигляді зірок
	 *
	 * @param int $comments
	 * @param float $stars
	 * @return string
	 */
	function stars_rating($comments, $stars) {
		$rating = 0;
		$half = 0;

		if ($comments > 0) {
			$rating = $stars / $comments;
			$half = $rating - floor($rating);
			$rating -= $half;
			$half = $half >= 0.5 ? 1: 0;
		}

		return
			str_repeat('<b class="active"></b>', $rating)
			. str_repeat('<b class="half"></b>', $half)
			. (($rating + $half < 5) ? str_repeat('<b></b>', 5 - ($rating + $half)) : '');
	}

	/**
	 * Конвертація BB кодів
	 *
	 * @param string $text
	 * @return string
	 */
	function convert_bb_codes($text)
	{
		$find = array(
			'~\[b\](.*?)\[/b\]~s',
			'~\[i\](.*?)\[/i\]~s',
			'~\[break\](.*?)\[/break\]~s',
			'~\[link\]((?:ftp|https?)://.*?)\[/link\]~s',
			'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
			'~\[video\](.*?)\[/video\]~s',
		);

		$replace = array(
			'<b>$1</b>',
			'<i>$1</i>',
			'<span class="page_break">$1</span>',
			'<a href="$1">$1</a>',
			'<img src="$1" alt="">',
			'<iframe width="560" height="315" src="https://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
		);

		return preg_replace($find, $replace, $text);
	}

	/**
	 * Очистка BB кодів
	 *
	 * @param string $text
	 * @return string
	 */
	function clean_bb_codes($text) {
		$find = array(
			'~\[b\](.*?)\[/b\]~s',
			'~\[i\](.*?)\[/i\]~s',
			'~\[break\](.*?)\[/break\]~s',
			'~\[link\]((?:ftp|https?)://.*?)\[/link\]~s',
			'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
			'~\[video\](.*?)\[/video\]~s',
		);

		$replace = array(
			'$1',
			'$1',
			'',
			'',
			'',
			'',
		);

		return preg_replace($find, $replace, $text);
	}

	/**
	 * Перетворення текстових посилань на посилання html
	 *
	 * @param string $text
	 * @param string $domain
	 * @return string mixed
	 */
	function convert_links($text, $domain) {
		$domain = str_replace('http://', '', $domain);
		$domain = trim($domain, '/');

		return preg_replace_callback(
			'@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@',
			function ($m) use ($domain) {
				if (array_key_exists(2, $m) and $m[2] === $domain) {
					return '<a href="' . $m[0] . '">' . $m[0] . '</a>';
				} elseif (array_key_exists(0, $m)) {
					return $m[0];
				} else {
					return '';
				}
			},
			$text
		);
	}