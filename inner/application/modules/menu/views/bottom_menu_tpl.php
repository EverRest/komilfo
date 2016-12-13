<?php
 	defined('ROOT_PATH') OR exit('No direct script access allowed');
 	if (isset($menu[0]))
	{
 		echo '<ul>';
 		foreach ($menu[0] as $v)
		{if($v['id'] == 1) continue;
			$url = '';
			$target = '';
			extract(link_attributes($v['url'], $v['static_url'], $v['main'], $v['target']));
			$class = array();
			if (in_array($v['id'], $parents)) $class[] = 'active nra';
			if ($v['main'] == 1 AND $is_main) $class[] = 'active nra';
			$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';
 			echo '<li>';
 			if (in_array($v['id'], $seo_link))
			{
				echo '<!--noindex--><a href="' . $url . '"' . $class . $target . ' rel="nofollow"><span>' . $v['name'] . '</span></a><!--/noindex-->';
			}
			else
			{
				echo '<a href="' . $url . '"' . $class . $target . '><span>' . $v['name'] . '</span></a>';
 			}
 			echo '</li>';
		}
 		echo '</ul>';
	}