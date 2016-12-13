<?php

	if (!defined('BASEPATH')) exit('No direct script access allowed');

	function link_attributes($url, $static_url, $main, $target)
	{
		$ci = get_instance();
		$result = array('url' => '', 'target' => '');

		if ($static_url != '')
		{
			if (preg_match('!^\w+://! i', $static_url))
			{
				$result['url'] = $static_url;
				if ($target == 1) $result['target'] = ' target="_blank"';
			}
			else
			{
				$result['url'] = $ci->uri->full_url($static_url);
			}
		}
		else
		{
			$result['url'] = ($main == 0) ? $ci->uri->full_url($url) : $ci->uri->full_url();
		}

		return $result;
	}