<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	$menu_id = $this->init_model->get_menu_id();

	
	if (isset($menu))
	{
		echo '<ul class="other-collections_list fm">';
		foreach ($menu as $val)
		{
			foreach ($val as $v) {
				if($v['id'] == $menu_id) continue;
				$url = '';
				$target = '';
				extract(link_attributes($v['url'], $v['static_url'], $v['main'], $v['target']));
				$class = array();
				if (in_array($v['id'], $parents)) $class[] = 'active';
				if ($v['main'] == 1 AND $is_main) $class[] = 'active';
				if (isset($menu[$v['id']])) $class[] = 'has_drops';
				$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';
				echo '<li>';
					echo '<a href="' . stripslashes($url) . '"' . $class . $target . '><span>' . $v['name'] . '</span></a>';
				echo '</li>';
			}
		}
		echo '</ul>';
	}