<?php

	if (!defined('BASEPATH')) exit('No direct script access allowed');

	function link_attributes($url, $static_url, $main, $target)
	{
		$ci =& get_instance();
		$result = array('url' => '', 'target' => '');

		if ($static_url !== '') {
			if (preg_match('!^\w+://! i', $static_url) === 1) {
				$result['url'] = $static_url;
				if ((int)$target === 1) {
					$result['target'] = ' target="_blank"';
				}
			} else {
				if (mb_substr($static_url, mb_strlen($static_url) - 5) === '.html') {
					$result['url'] = base_url($static_url);
				} elseif (mb_substr($static_url, 0, 1) === '#') {
					$result['url'] = $ci->config->base_url($static_url);
				} else {
					$result['url'] = $ci->uri->full_url($static_url);
				}
			}
		} else {
			$result['url'] = (int)$main === 0 ? $ci->uri->full_url($url) : $ci->uri->full_url();
		}

		return $result;
	}