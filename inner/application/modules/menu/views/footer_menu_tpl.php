<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @var array $menu
	 * @var array $parents
	 * @var boolean $is_main
	 */

	if (array_key_exists(0, $menu)) {
		echo '<ul>';

		foreach ($menu[0] as $v) {
			$v['id'] = (int)$v['id'];

			$url = '';
			$target = '';
			extract(link_attributes($v['url'], $v['static_url'], $v['main'], $v['target']));

			$class = array();
			if (in_array($v['id'], $parents, true)) {
				$class[] = 'active';
			} elseif ((int)$v['main'] === 1 and $is_main) {
				$class[] = 'active';
			}

			$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';

			echo '<li><b>|</b><a href="' . $url . '"' . $class . $target . '>' . $v['name'] . '</a></li>';
		}

		echo '</ul>';
	}