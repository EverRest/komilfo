<?php
	if (!defined('BASEPATH')) {
		exit('No direct script access allowed');
	}

	function translit($str, $lang = null)
	{
		
		if ($lang === null) {
			$lang = LANG;
		}

		$new_str = '';
		$str = mb_strtolower($str, 'UTF-8');
		$str_length = mb_strlen($str, 'UTF-8');

		$tr = array(
			' ' => '-',
			'_' => '-',
		);


		if ($lang === 'ua') {
			$tr['а'] = 'a';
			$tr['б'] = 'b';
			$tr['в'] = 'v';
			$tr['г'] = array(
				'h',
				'gh', // після літери "З"
			);
			$tr['ґ'] = 'g';
			$tr['д'] = 'd';
			$tr['е'] = 'e';
			$tr['є'] = array(
				'ie',
				'ye', // на початку слова
			);
			$tr['ж'] = 'zh';
			$tr['з'] = 'z';
			$tr['и'] = 'y';
			$tr['і'] = 'i';
			$tr['Ї'] = array(
				'i',
				'yi', // на початку слова
			);
			$tr['й'] = array(
				'i',
				'y', // на початку слова
			);
			$tr['к'] = 'k';
			$tr['л'] = 'l';
			$tr['м'] = 'm';
			$tr['н'] = 'n';
			$tr['о'] = 'o';
			$tr['п'] = 'p';
			$tr['р'] = 'r';
			$tr['с'] = 's';
			$tr['т'] = 't';
			$tr['у'] = 'u';
			$tr['ф'] = 'f';
			$tr['х'] = 'kh';
			$tr['ц'] = 'ts';
			$tr['ч'] = 'ch';
			$tr['ш'] = 'sh';
			$tr['щ'] = 'shch';
			$tr['ь'] = '';
			$tr['ю'] = array(
				'iu',
				'yu', // на початку слова
			);
			$tr['я'] = array(
				'ia',
				'ya', // на початку слова
			);

			$prev_z = false;

			for ($i = 0; $i < $str_length; $i++) {
				$letter = mb_substr($str, $i, 1, 'UTF-8');

				if (isset($tr[$letter])) {
					if (is_array($tr[$letter])) {
						$new_str .= ($prev_z OR $i === 0) ? $tr[$letter][1] : $tr[$letter][0];
					} else {
						$new_str .= $tr[$letter];
					}
				} else {
					$new_str .= $letter;
				}

				if ($letter === 'з') {
					$prev_z = true;
				} else {
					$prev_z = false;
				}
			}

			$str = $new_str;
		}

		if (LANG === 'ru') {
			$tr['а'] = 'a';
			$tr['б'] = 'b';
			$tr['в'] = 'v';
			$tr['г'] = 'g';
			$tr['д'] = 'd';
			$tr['е'] = 'e';
			$tr['ё'] = 'jo';
			$tr['ж'] = 'zh';
			$tr['з'] = 'z';
			$tr['и'] = 'i';
			$tr['й'] = 'jj';
			$tr['к'] = 'k';
			$tr['л'] = 'l';
			$tr['м'] = 'm';
			$tr['н'] = 'n';
			$tr['о'] = 'o';
			$tr['п'] = 'p';
			$tr['р'] = 'r';
			$tr['с'] = 's';
			$tr['т'] = 't';
			$tr['у'] = 'u';
			$tr['ф'] = 'f';
			$tr['х'] = 'kh';
			$tr['ц'] = 'c';
			$tr['ч'] = 'ch';
			$tr['ш'] = 'sh';
			$tr['щ'] = 'shh';
			$tr['ъ'] = '';
			$tr['ы'] = 'y';
			$tr['ь'] = '';
			$tr['э'] = 'eh';
			$tr['ю'] = 'ju';
			$tr['я'] = 'ja';

			$str = strtr($str, $tr);
		}


		$str = strtr($str, $tr);

		if (preg_match('/[^A-Za-z0-9_\-]/', $str)) {
			$str = preg_replace('/[^A-Za-z0-9_\-]/', '', $str);
		}

		return $str;
	}

	function translit_filename($st, $coder = 'utf-8')
	{
		$st = mb_strtolower($st, $coder);
		$st = str_replace(array(
				'?',
				'!',
				',',
				':',
				';',
				'*',
				'(',
				')',
				'{',
				'}',
				'***91;',
				'***93;',
				'%',
				'#',
				'№',
				'@',
				'$',
				'^',
				'-',
				'+',
				'/',
				'\\',
				'=',
				'|',
				'"',
				'\'',
				'[',
				']',
				'а',
				'б',
				'в',
				'г',
				'д',
				'е',
				'ё',
				'з',
				'и',
				'й',
				'к',
				'л',
				'м',
				'н',
				'о',
				'п',
				'р',
				'с',
				'т',
				'у',
				'ф',
				'х',
				'ъ',
				'ы',
				'э',
				' ',
				'ж',
				'ц',
				'ч',
				'ш',
				'щ',
				'ь',
				'ю',
				'я'
			),
			array(
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'_',
				'a',
				'b',
				'v',
				'g',
				'd',
				'e',
				'e',
				'z',
				'i',
				'y',
				'k',
				'l',
				'm',
				'n',
				'o',
				'p',
				'r',
				's',
				't',
				'u',
				'f',
				'h',
				'j',
				'i',
				'e',
				'_',
				'zh',
				'ts',
				'ch',
				'sh',
				'shch',
				'',
				'yu',
				'ya'
			),
			$st
		);

		$st = preg_replace("/[^a-z0-9\_\-\.]/", "", $st);
		$st = trim($st, '_');
		$st = preg_replace("/_{2,}/", "_", $st);

		return $st;
	}