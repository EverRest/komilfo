<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	/**
	 * @var array $menu
	 * @var array $parents
	 * @var boolean $is_main
	 */

	if (array_key_exists(0, $menu)) {
		echo '<ul>';

		foreach ($menu[0] as $k => $v) {
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

			echo '<li><a href="' . $url . '"' . $class . $target . '>' . $v['name'] . '</a>'; 

			if (array_key_exists($v['id'], $menu)) {
				echo '<ul class="submenu">';

				foreach ($menu[$v['id']] as $k => $_v) {
					$_v['id'] = (int)$_v['id'];

					$url = '';
					$target = '';
					extract(link_attributes($_v['url'], $_v['static_url'], $_v['main'], $_v['target']));

					$class = array();

					if (in_array($_v['id'], $parents, true)) {
						$class[] = 'active';
					} elseif ((int)$_v['main'] === 1 and $is_main) {
						$class[] = 'active';
					}

					$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';

					echo '<li><a href="' . $url . '"' . $class . $target . '>' . $_v['name'] . '</a></li>';
				}

				echo '</ul>';
			}

			echo '</li>';
		}

		echo '</ul>';
	}