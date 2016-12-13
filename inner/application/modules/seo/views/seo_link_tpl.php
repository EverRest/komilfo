<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	function build_menu($items, $item, $seo_link)
	{
		foreach ($item as $val)
		{
			echo '<li>';
			echo '<div class="holder' . (in_array($val['id'], $seo_link) ? ' hidden' : '') . '"><div class="cell w_30 number"></div><div class="cell auto"><span class="seo_item">' . stripslashes($val['name']) . '</span></div><div class="cell w_20 icon"><a href="#" class="hide-show' . (!in_array($val['id'], $seo_link) ? ' active"' : '') . '" data-id="' . $val['id'] . '"></a></div></div>';

			if (isset($items['children'][$val['id']]))
			{
				echo '<ul>';
				build_menu($items, $items['children'][$val['id']], $seo_link);
				echo '</ul>';
			}

			echo '</li>';
		}
	}

?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="seo_link"></div>
		</div>
		<div class="fm component_edit_links"></div>
	</div>
	<div class="fm admin_menu">
		<ul><?php if (is_array($menus['root']) > 0) build_menu($menus, $menus['root'], $seo_link); ?></ul>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {

		$('.admin_menu ul li').each(function (i, val) {
			$(this).find('.number').text(i + 1);

			if ($(this).parents('ul').length > 1) {
				if ($(this).find('.auto').find('b').length === 0) {
					$(this).find('.auto').prepend('<b></b>');
				}
			} else {
				$(this).find('.auto').find('b').remove();
			}
		});

		$('.admin_menu')
			.on('mouseover', '.holder', function () {
				$(this).addClass('active');
			})
			.on('mouseout', '.holder', function () {
				$(this).removeClass('active');
			})
			.on('click', '.hide-show', function (e) {
				e.preventDefault();

				var $link = $(this),
					$holder = $(this).closest('.holder'),
					status = 0;

				if ($holder.hasClass('hidden')) {
					$holder.removeClass('hidden');
					$link.addClass('active');
				} else {
					$holder.addClass('hidden');
					$link.removeClass('active');
					status = 1;
				}

				component_loader_show($('.component_loader'), '');

				$.post(
					'<?=$this->uri->full_url('admin/seo/save_seo_link');?>',
					{
						menu_id : <?=$menu_id;?>,
						hide_menu : $link.data('id'),
						status : status
					},
					function (response) {
						if (response.success) component_loader_hide($('.component_loader'), '');
					},
					'json'
				);
			})
			.find('li').removeClass('grey')
			.end()
			.find('li:even').addClass('grey');

	});
</script>