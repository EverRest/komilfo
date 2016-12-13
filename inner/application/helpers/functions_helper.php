<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @param $int
	 * @param $expressions
	 * @return string
	 */
	function declension($int, $expressions)
	{
		if (count($expressions) < 3) $expressions[2] = $expressions[1];

		$int = intval($int);
		$count = $int % 100;

		if ($count >= 5 && $count <= 20)
		{
			$result = $expressions['2'];
		}
		else
		{
			$count = $count % 10;
			if ($count == 1)
			{
				$result = $expressions['0'];
			}
			elseif ($count >= 2 && $count <= 4)
			{
				$result = $expressions['1'];
			}
			else
			{
				$result = $expressions['2'];
			}
		}

		return $result;
	}

	function get_text_day($time = NULL)
	{
		if ($time === NULL) $time = time();

		if (LANG == 'en')
		{
			$days = array(
				1 => 'понеділок',
				2 => 'вівторок',
				3 => 'середа',
				4 => 'четвер',
				5 => 'п’ятниця',
				6 => 'субота',
				7 => 'неділя',
			);
		}
		elseif (LANG == 'pl')
		{
			$days = array(
				1 => 'понеділок',
				2 => 'вівторок',
				3 => 'середа',
				4 => 'четвер',
				5 => 'п’ятниця',
				6 => 'субота',
				7 => 'неділя',
			);
		}
		else
		{
			$days = array(
				1 => 'понеділок',
				2 => 'вівторок',
				3 => 'середа',
				4 => 'четвер',
				5 => 'п’ятниця',
				6 => 'субота',
				7 => 'неділя',
			);
		}

		return $days[date('N', $time)];
	}

	function get_text_month($time = NULL)
	{
		if ($time === NULL) $time = time();

		if (LANG == 'en')
		{
			$months = array(
				1 => 'січня',
				2 => 'лютого',
				3 => 'березня',
				4 => 'квітня',
				5 => 'травня',
				6 => 'червня',
				7 => 'липня',
				8 => 'серпня',
				9 => 'вересня',
				10 => 'жовтня',
				11 => 'листопада',
				12 => 'грудня',
			);
		}
		elseif (LANG == 'ru')
		{
			$months = array(
				1 => 'січня',
				2 => 'лютого',
				3 => 'березня',
				4 => 'квітня',
				5 => 'травня',
				6 => 'червня',
				7 => 'липня',
				8 => 'серпня',
				9 => 'вересня',
				10 => 'жовтня',
				11 => 'листопада',
				12 => 'грудня',
			);
		}
		else
		{
			$months = array(
				1 => 'січня',
				2 => 'лютого',
				3 => 'березня',
				4 => 'квітня',
				5 => 'травня',
				6 => 'червня',
				7 => 'липня',
				8 => 'серпня',
				9 => 'вересня',
				10 => 'жовтня',
				11 => 'листопада',
				12 => 'грудня',
			);
		}

		return $months[date('n', $time)];
	}

	/**
	 * Отримання назви місяця по номеру
	 *
	 * @param $index
	 * @return string
	 */
	function month_to_text($index)
	{
		if (LANG == 'en')
		{
			$months = array(
				1 => 'January',
				2 => 'February',
				3 => 'March',
				4 => 'April',
				5 => 'May',
				6 => 'June',
				7 => 'July',
				8 => 'August',
				9 => 'September',
				10 => 'October',
				11 => 'November',
				12 => 'December',
			);
		}
		elseif (LANG == 'ru')
		{
			$months = array(
				1 => 'Январь',
				2 => 'Февраль',
				3 => 'Март',
				4 => 'Апрель',
				5 => 'Май',
				6 => 'Июнь',
				7 => 'Июль',
				8 => 'Август',
				9 => 'Сентябрь',
				10 => 'Октябрь',
				11 => 'Ноябрь',
				12 => 'Декабрь',
			);
		}
		else
		{
			$months = array(
				1 => 'Cічень',
				2 => 'Лютий',
				3 => 'Березень',
				4 => 'Квітень',
				5 => 'Травень',
				6 => 'Червень',
				7 => 'Липень',
				8 => 'Серпень',
				9 => 'Вересень',
				10 => 'Жовтень',
				11 => 'Листопад',
				12 => 'Грудень',
			);
		}

		return $months[$index];
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
	function truncate($string, $length = 80, $etc = '...', $break_words = FALSE, $middle = FALSE)
	{
		if ($length == 0) return '';

		if (mb_strlen($string, 'UTF-8') > $length)
		{
			$length -= min($length, mb_strlen($etc, 'UTF-8'));
			if (!$break_words && !$middle)
			{
				$string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1, 'UTF-8'));
			}
			if (!$middle)
			{
				return mb_substr($string, 0, $length, 'UTF-8') . $etc;
			}
			return mb_substr($string, 0, $length / 2, 'UTF-8') . $etc . mb_substr($string, -$length / 2, $length, 'UTF-8');
		}

		return $string;
	}

	/**
	 * @param $id
	 * @return int
	 */
	function dir_by_id($id)
	{
		return ceil($id / 100) * 100;
	}