<?php
	if (!defined('ROOT_PATH')) exit('No direct script access allowed');

	function get_date($date, $words)
	{
		if ((($date % 10) > 4 && ($date % 10) < 10) || ($date > 10 && $date < 20))
		{
			return $words[1];
		}
		if (($date % 10) > 1 && ($date % 10) < 5)
		{
			return $words[2];
		}
		if (($date % 10) == 1)
		{
			return $words[0];
		}
		else
		{
			return $words[1];
		}
	}

	function timespan($time = '', $format = '', $date_format = 'd.m.Y')
	{
		$output_date = array();

		$texts = array(
			'years' => LANG == 'ua' ? array('рік', 'років', 'роки') : array('год', 'лет', 'года'),
			'months' => LANG == 'ua' ? array('місяць', 'місяців', 'місяці') : array('месяц', 'месяцев', 'месяца'),
			'days' => LANG == 'ua' ? array('день', 'днів', 'дня') : array('день', 'дней', 'дня'),
			'hours' => LANG == 'ua' ? array('година', 'годин', 'години') : array('час', 'часов', 'часа'),
			'minuts' => LANG == 'ua' ? array('хвилина', 'хвилин', 'хвилини') : array('минута', 'минут', 'минуты'),
			'seconds' => LANG == 'ua' ? array('секунда', 'секунд', 'секунди') : array('секунда', 'секунд', 'секунды'),
			'today' => LANG == 'ua' ? 'сьогодні' : 'сегодня',
			'ago' => LANG == 'ua' ? 'тому' : 'назад',
			'less_minute' => LANG == 'ua' ? 'меньше хвилини тому' : 'меньше минуты назад',
		);

		$now = new DateTime();
		$from = new DateTime(date('Y-m-d H:i', $time));
		$interval = $now->diff($from);

		$intervals = array(
			'years' => $interval->format('%y%'),
			'months' => $interval->format('%m%'),
			'days' => $interval->format('%d%'),
			'full_days' => $interval->format('%a%'),
			'hours' => $interval->format('%h%'),
			'minuts' => $interval->format('%i%'),
			'seconds' => $interval->format('%s%'),
		);

		if ($format == 'today')
		{
			if ($intervals['days'] == 0)
			{
				if ($intervals['minuts'] > 20)
				{
					$output_date[] = $texts['today'] . ',' . date('H:i');
				}
				else
				{
					if ($intervals['hours'] == 0 AND $intervals['minuts'] == 0)
					{
						$output_date[] = $texts['less_minute'];
					}
					else
					{
						if ($intervals['hours'] > 0) $output_date[] = $intervals['hours'] . ' ' . get_date($intervals['hours'], $texts['hours']);
						if ($intervals['minuts'] > 0) $output_date[] = $intervals['minuts'] . ' ' . get_date($intervals['minuts'], $texts['minuts']);

						$output_date[] = $texts['ago'];
					}
				}
			}
		}

		if (count($output_date) == 0)
		{
			$output_date = date($date_format, $time);
		}
		else
		{
			$output_date = implode(' ', $output_date);
		}

		return $output_date;
	}