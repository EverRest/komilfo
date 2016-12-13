<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Captcha_lib
	{
		protected $ci;

		private $prefix = 'captcha_';
		private $font_path = '/font.ttf';

		public function __construct()
		{
			$this->ci = & get_instance();
			$this->font_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $this->font_path;
		}

		/**
		 * Отримання зображення коду перевірки
		 * @param $module
		 * @param int $width
		 * @param int $height
		 * @param int $count
		 * @internal param string $text
		 * @return mixed
		 */
		function get_image($module, $width = 126, $height = 36, $count = 4)
		{
			$font_size_min = 24; /* минимальная высота символа */
			$font_size_max = 26; /* максимальная высота символа */
			$font_file = $this->font_path; /* путь к файлу относительно w3captcha.php */
			$char_angle_min = 0; /* максимальный наклон символа влево */
			$char_angle_max = 0; /* максимальный наклон символа вправо */
			$char_angle_shadow = 3000; /* размер тени */
			$char_align = 30; /* выравнивание символа по-вертикали */

			/* позиция первого символа по-горизонтали */
			$start = 15;

			/* интервал между началами символов */
			$interval = 25;

			$chars = '23456789qwertyupasdfghkzxcvbnm'; /* набор символов */
			$noise = 9; /* уровень шума */

			$image = imagecreatetruecolor($width, $height);
			$background_color = imagecolorallocate($image, 255, 255, 255); /* rbg-колір фону */
			$font_color = imagecolorallocate($image, 119,41, 119); /* rbg-колір 1 */
			$font_color2 = imagecolorallocate($image, 162, 39, 162); /* rbg-колір 2 */

			imagefill($image, 0, 0, $background_color);

			$num_chars = strlen($chars);
			$str = '';
			for ($i = 0; $i < $count; $i++)
			{
				$char = $chars[rand(0, $num_chars - 1)];
				$font_size = rand($font_size_min, $font_size_max);
				$char_angle = rand($char_angle_min, $char_angle_max);
				if ($i == 0 or $i == 2) $color_rand = $font_color;
				else $color_rand = $font_color2;
				imagettftext($image, $font_size, $char_angle, $start, $char_align, $color_rand, $font_file, $char);
				imagettftext($image, $font_size, $char_angle + $char_angle_shadow * (rand(0, 0) * 2 - 1), $start, $char_align, $background_color, $font_file, $char);
				$start += $interval;
				$str .= $char;
			}

			if ($noise)
			{
				for ($i = 0; $i < $width; $i++)
				{
					for ($j = 0; $j < $height; $j++)
					{
						$rgb = imagecolorat($image, $i, $j);
						$r = ($rgb >> 16) & 0xFF;
						$g = ($rgb >> 8) & 0xFF;
						$b = $rgb & 0xFF;
						$k = rand(-$noise, $noise);
						$rn = $r + 255 * $k / 100;
						$gn = $g + 255 * $k / 100;
						$bn = $b + 255 * $k / 100;
						if ($rn < 0) $rn = 0;
						if ($gn < 0) $gn = 0;
						if ($bn < 0) $bn = 0;
						if ($rn > 255) $rn = 255;
						if ($gn > 255) $gn = 255;
						if ($bn > 255) $bn = 255;
						$color = imagecolorallocate($image, $rn, $gn, $bn);
						imagesetpixel($image, $i, $j, $color);
					}
				}
			}

			$this->ci->session->set_userdata($this->prefix . $module, $str);

			if (function_exists("imagepng"))
			{
				header("Content-type: image/png");
				imagepng($image);
			}
			elseif (function_exists("imagegif"))
			{
				header("Content-type: image/gif");
				imagegif($image);
			}
			elseif (function_exists("imagejpeg"))
			{
				header("Content-type: image/jpeg");
				imagejpeg($image);
			}

			imagedestroy($image);
		}

		/**
		 * Валідація коду перевірки
		 * @param $word
		 * @param $module
		 * @return bool
		 */
		function validate($word, $module)
		{
			$code = $this->ci->session->userdata($this->prefix . $module);
			return ($code == $word) ? TRUE : FALSE;
		}
	}