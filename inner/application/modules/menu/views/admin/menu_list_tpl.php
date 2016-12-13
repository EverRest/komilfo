<?php defined('ROOT_PATH') OR exit('No direct script access allowed');

	function build_menu($menu_index, $menu_id, $items, $item, $last)
	{
		foreach ($item as $val)
		{ if($val['id'] == 1) continue;
			echo '<li id="menu_' . $val['id'] . '" data-menu-id="' . $val['id'] . '">';
			echo '<div class="holder' . (($val['hidden'] == 1) ? ' hidden' : '') . '">';
			echo '<div class="cell last_edit' . (($val['id'] == $last) ? ' active' : '') . '"></div>';
			echo '<div class="cell w_20 icon"><a href="#" class="hide-show ' . (($val['hidden'] == 0) ? ' active' : '') . '"></a></div>';
			echo '<div class="cell w_30 number_cell"><div class="number">' . $val['position'] . '</div><div class="add_items"><a href="#" class="up_add"></a><a href="#" class="child_add"></a><a href="#" class="down_add"></a></div></div>';
			echo '<div class="cell w_20 icon"><a class="picture' . ($val['image'] != '' ? ' active' : '') . '" href="' . base_url((LANG == DEF_LANG ? '' : LANG . '/') . 'admin/menu/edit/?menu_index=' . $menu_index . '&menu_id=' . $menu_id . '&item_id=' . $val['id']) . '"></a></div>';
			echo '<div class="cell w_20 icon"><a class="edit"></a></div>';
			echo '<div class="cell auto">';
			echo '<span class="menu_item"><a href="' . ($val['static_url'] != '' ? $val['static_url'] : base_url((LANG == DEF_LANG ? '' : LANG . '/') . $val['url'])) . '/" target="_blank">' . (($val['name'] != '') ? stripslashes($val['name']) : (($val['def_name'] != '') ? '<i>' . $val['def_name'] . '</i>' : 'Новий пункт меню')) . '</a></span>';
			echo '<div class="fm for_link_set">';
			echo '<div class="evry_title"><label class="block_label">лінк:</label><input type="text" value="' . $val['static_url'] . '" /></div>';
			echo '<div class="evry_title"><label class="block_label">відкривати лінк:</label>';
			echo '<div class="no_float"><div class="controls">';
			echo '<label class="radio_label ' . (($val['target'] == 0) ? 'active' : '') . '" data-value="0"><i></i>на тій же сторінці</label>';
			echo '<label class="radio_label ' . (($val['target'] == 1) ? 'active' : '') . '" data-value="1"><i></i>в новій вкладці</label>';
			echo '</div></div></div>';
			echo '</div>';
			echo '</div>';
			echo '<div class="cell w_70 icon no_padding"><div class="fm step_left"><a href="#"></a></div><div class="fm step_right"><a href="#"></a></div><div class="fm double_step_right"><a href="#"></a></div></div>';
			echo '<div class="cell w_24 icon"><a href="#" class="set_link' . (($val['static_url'] != '') ? ' link' : ' noset_link') . '">' . (($val['static_url'] != '') ? '' : 'лінк') . '</a></div>';
			echo '<div class="cell w_20 icon"><a href="#" class="single_arrows menu_sorter"></a></div>';
			echo '<div class="cell w_20 icon adm_hidden main_page_cell"><a href="#" class="main_page' . (($val['main'] == 1) ? ' active' : '') . '"></a></div>';
			echo '<div class="cell w_20 icon"><a href="#" class="delete"></a></div>';
			echo '</div>';

			if (isset($items['children'][$val['id']]))
			{
				echo '<ul>';
				build_menu($menu_index, $menu_id, $items, $items['children'][$val['id']], $last);
				echo '</ul>';
			}

			echo '</li>';
		}
	}

	if (is_array($menu['root']) > 0) build_menu($menu_index, $menu_id, $menu, $menu['root'], $last_menu);