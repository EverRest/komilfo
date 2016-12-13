<?php defined('BASEPATH') OR exit('No direct script access allowed');

	function build_menu($menu, $id = 0, $_checked = array())
	{
		echo '<ul>';

		foreach ($menu[$id] as $val)
		{
			$checked = in_array($val['id'], $_checked) ? ' checked="checked"' : '';
			echo '<li><div class="holder"><div class="cell w_20"><div class="controls"><label for="site_menu_' . $val['id'] . '" class="check_label"><i></i><input type="checkbox" id="site_menu_' . $val['id'] . '" name="site_menu[]" value="' . $val['id'] . '"' . $checked . '></label></div></div><div class="cell auto">' . ($id > 0 ? '<b></b>' : '') . $val['name'] . '</div></div>';

			if (isset($menu[$val['id']])) build_menu($menu, $val['id'], $_checked);

			echo '</li>';
		}

		echo '</ul>';
	}