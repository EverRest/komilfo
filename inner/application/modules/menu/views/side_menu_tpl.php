<?php defined('ROOT_PATH') OR exit('No direct script access allowed');
	
if (array_key_exists($menu_id, $menu)) {
	$image = '';
	$alt = '';
	$show = false;
	foreach ($menu[0] as $key => $value) {
		if($value['id'] == $menu_id){
			$image = $value['image'];
			$alt = $value['name'];
			$show = true;
		}
	}
	if($show){
		echo '<section class="first-menu pbl">
	            <div class="container">
	                <div class="row">
	                    <div class="list-fm bg-box col-sm-9 col-xs-24">
	                        <div class="title-main">Wedding</div>
	                        <nav class="menu-fm">';
							echo '<ul>'; // start 1 level ul

							foreach ($menu[$menu_id] as $k => $v) {
								$url = '';
								$target = '';
								extract(link_attributes($v['url'], $v['static_url'], $v['main'], $v['target']));
								$_url = $url;

								$v['id'] = (int)$v['id'];

								$class = array();

								if (in_array($v['id'], $parents, true)) {
									$class[] = 'active';
									$class[] = 'drop_menu';
								}

								if ((int)$v['main'] === 1 AND $is_main) {
									$class[] = 'active';
								}

								$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';

								echo '<li>'; // start 1 level li
									echo '<a href="' . $url . '"' . $class . $target . '>' . stripslashes($v['name']) . '</a>';
								echo '</li>'; // end 1 level li
							}

								echo '</ul>'; // end 1 level ul
							echo '</nav>
						</div>
					<div class="photo-fm bg-box col-sm-15 col-xs-24"><img src="'.base_url('upload/menu/'.$menu_id.'/s_'.$image).'" alt="'.$alt.'"></div>
				</div>
			</div>
		</section>';
	}
}                                