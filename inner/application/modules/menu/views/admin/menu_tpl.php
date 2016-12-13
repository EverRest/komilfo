<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
	$this->template_lib->set_js('admin/jquery.mjs.nestedSortable.js');
?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="menu"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm add" id="add_menu"><b></b>Додати пункт меню</a>
			<a href="#" class="fm add_bottom"><b class="active"></b>Додавати внизу</a>
			<a href="#" class="fm change_main"><b></b>Змінити головну сторінку</a>
		</div>
		<?php if (count($languages) > 1): ?>
		<div class="fmr component_lang">
			<?php foreach ($languages as $key => $val): ?>
			<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="fm admin_menu"><ul id="admin_sortable_menu"><?=$menu;?></ul></div>
</div>

<script type="text/javascript">
//<![CDATA[
	var menu_index = <?=$menu_index;?>,
		menu_min_level = 1,
		menu_max_level = 3,
		menu_add_top = 0,
		move_menu_id = 0;

	function save_position() {
		component_loader_show($('.component_loader'), '');

		var items = [];

		$('#menu_' + move_menu_id).closest('ul').find('li').each(function (i, val) {
			items[i] = {
				id: $(val).data('menu-id'),
				position: i,
				parent_id: $(val).data('parent-id'),
				level: $(val).data('level')
			}
		});

		$.post(
			'<?php echo $this->uri->full_url('/admin/menu/update_position/'); ?>',
			{
				menu_id: move_menu_id,
				items : items
			},
			function (response) {
				if (response.error === 0) component_loader_hide($('.component_loader'), '');
			},
			'json'
		);
	}

	function set_menu_data() {

		init_sortable();

		$('#admin_sortable_menu').find('li').map(function (i) {

			var level = $(this).parents('ul').length, parent_id = 0;
			if (level > 0) parent_id = $(this).parents('li').eq(0).data('menu-id');

			$(this)
				.data('level', level - 1).data('parent-id', parent_id)
				.find('.number').eq(0).text(i + 1)
				.end().end()
				.find('.step_left').css('visibility', 'hidden')
				.end()
				.find('.step_right').css('visibility', 'hidden')
				.end()
				.find('.double_step_right').css('visibility', 'hidden');

			if (i % 2 == 0) {
				$(this).addClass('grey');
			} else {
				$(this).removeClass('grey');
			}

			if (level > 1) {
				if ($(this).find('.auto').eq(0).find('b').length === 0) $(this).find('.auto').eq(0).prepend('<b></b>');
				$(this).find('.step_left').eq(0).css('visibility', 'visible');
			} else {
				$(this).find('.auto').eq(0).find('b').remove();
			}

			if ($(this).index() > 0) {
				$(this).find('.double_step_right').eq(0).css('visibility', 'visible');

				if ($(this).find('ul').length > 0 && ((level + 1) != menu_max_level)) $(this).find('.step_right').eq(0).css('visibility', 'visible');
			}

			if ($(this).find('ul').length > 0) {
				$(this).find('.menu_sorter').eq(0).addClass('double_arrows');
			} else {
				$(this).find('.menu_sorter').eq(0).removeClass('double_arrows');
			}

			//if (($item.find('ul').length + val.depth) === menu_max_level) {
			//	$item.find('.double_step_right').eq(0).css('visibility', 'hidden');
			//}

			if (level >= menu_max_level) {
				$(this)
					.find('.step_right').eq(0).css('visibility', 'hidden')
					.end().end()
					.find('.child_add').eq(0).addClass('no_active');

				if (level === menu_max_level) $(this).find('.double_step_right').eq(0).css('visibility', 'hidden');
				if (level > menu_max_level) $(this).find('.auto').eq(0).find('span').addClass('through');
			} else {
				$(this)
					.find('.child_add').eq(0).removeClass('no_active')
					.end()
					.find('.auto').eq(0).find('span').removeClass('through');
			}

			//if (menu_index === 2 || menu_index === 3) $(this).find('.picture').closest('.cell').addClass('adm_hidden');
			//if (menu_index === 2 || menu_index === 4) $(this).find('.step_left').closest('.cell').addClass('adm_hidden');

			$(this).find('.picture').closest('.cell').addClass('adm_hidden');
			if ($(this).find('.main_page').eq(0).hasClass('active')) $(this).find('.delete').eq(0).hide();
		});
	}

	function init_sortable() {
		$('#admin_sortable_menu').nestedSortable({
			forcePlaceholderSize: true,
			opacity: .4,
			tabSize: 5,
			listType : 'ul',
			handle: 'a.menu_sorter',
			items: 'li',
			toleranceElement: '> div',
			update : function (event, ui) {
				move_menu_id = $(ui.item).data('menu-id');
				$('.last_edit.active').removeClass('active');
				$('#menu_' + move_menu_id).find('.last_edit').eq(0).addClass('active');
				set_menu_data();
				save_position();
			},
			placeholder: "ui-state-highlight",
			axis : 'y',
			helper : 'clone',
			protectRoot : false,
			maxLevels : menu_max_level
		});
	}

	function build_row(response) {
		$('#admin_sortable_menu').find('.last_edit.active').removeClass('active');
		move_menu_id = response.menu_id;

		var item = '<li id="menu_' + response.menu_id + '" data-menu-id="' + response.menu_id + '"><div class="holder">';
			item += '<div class="cell last_edit active"></div>';
			item += '<div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div>';
			item += '<div class="cell w_30 number_cell"><div class="number"></div><div class="add_items"><a href="#" class="up_add"></a><a href="#" class="child_add"></a><a href="#" class="down_add"></a></div></div>';
			item += '<div class="cell w_20 icon"><a class="picture" href="' + (LANG != DEF_LANG ? '/' + LANG : '') + '/admin/menu/edit/?menu_index=<?=$menu_index;?>&menu_id=<?=$menu_id;?>&item_id=' + response.menu_id + '"></a></div>';
			item += '<div class="cell w_20 icon"><a class="edit"></a></div>';
			item += '<div class="cell auto"><span class="menu_item"><a href="#">Новий пункт меню</a></span><div class="fm for_link_set"><div class="evry_title"><label class="block_label">лінк:</label><input type="text" value=""></div><div class="evry_title"><label class="block_label">відкривати лінк:</label><div class="no_float"><div class="controls"><label class="radio_label" data-value="0"><i></i>на тій же сторінці</label><label class="radio_label" data-value="1"><i></i>в новій вкладці</label></div></div></div></div></div>';
			item += '<div class="cell w_70 icon no_padding"><div class="fm step_left"><a href="#"></a></div><div class="fm step_right"><a href="#"></a></div><div class="fm double_step_right"><a href="#"></a></div></div>';
			item += '<div class="cell w_24 icon"><a class="set_link noset_link" href="#">лінк</a></div>';
			item += '<div class="cell w_20 icon"><a href="#" class="single_arrows menu_sorter"></a></div>';
			item += '<div class="cell w_20 icon' + (!$('.change_main').find('b').hasClass('active') ? ' adm_hidden' : '') + '"><a href="#" class="main_page"></a></div>';
			item += '<div class="cell w_20 icon"><a href="#" class="delete"></a></div>';
			item += '</div></li>';

		return item;
	}

	$(function() {
		set_menu_data();

		$('.component_lang').on('click', 'a', function (e) {
			e.preventDefault();

			var language = $(this).siblings().removeClass('active').end().addClass('active').data('language');
			component_loader_show($('.component_loader'), '');

			$.post(
				'<?php echo $this->uri->full_url('/admin/menu/load'); ?>',
				{
					menu_id: <?php echo $menu_id; ?>,
					menu_index : menu_index,
					language : language
				},
				function (response) {
					if (response.error === 0) {
						$('.admin_menu').find('ul').first().html(response.menu);
						set_menu_data();
						component_loader_hide($('.component_loader'), '');
					}
				},
				'json'
			);
		});

		$('.change_main').on('click', function (e) {
			e.preventDefault();

			if ($(this).find('b').hasClass('active')) {
				$(this).removeClass('active').find('b').removeClass('active');
				$('.main_page_cell').addClass('adm_hidden');
			} else {
				$(this).addClass('active').find('b').addClass('active');
				$('.main_page_cell').removeClass('adm_hidden');
			}
		});

		/**
		 * quick menu edit
		 */
		$('#admin_sortable_menu')
			.on('click', '.edit', function (e) {
				e.preventDefault();

				var $holder = $(this).closest('.holder'), item, url, target;

				if (!$(this).hasClass('active')) {
					item = $holder.find('.auto').find('span.menu_item').text();
					if (item == 'Новий пункт меню') item = '';
					$holder.find('.auto').find('span.menu_item').html('<input type="text" name="item" value="' + item + '" placeholder="Введіть назву пункту меню">');
					$holder.find('.auto').find('span.menu_item').find('input').focus();
					$(this).addClass('active');
				} else {
					$(this).removeClass('active');

					$('#admin_sortable_menu').find('.last_edit.active').removeClass('active');
					$holder.find('.last_edit').addClass('active');

					component_loader_show($('.component_loader'), '');

					if ($holder.find('.auto').find('.for_link_set').css('display') === 'none') {
						item = $holder.find('.auto').find('input').eq(0).val();

						$.post(
							'<?=$this->uri->full_url('admin/menu/update');?>',
							{
								id : $holder.closest('li').data('menu-id'),
								name : item,
								language : ($('a.flags.active').length > 0) ? $('a.flags.active').data('language') : '<?=LANG;?>',
								menu_index: menu_index
							},
							function (response) {
								if (response.error === 0) {
									$holder.find('.auto').find('span.menu_item').html('<a href="' + response.link + '" target="_blank">' + item + '</a>');
									component_loader_hide($('.component_loader'), '');
								}
							},
							'json'
						);
					} else {
						url = $holder.find('.auto').find('.for_link_set').find('input').eq(0).val();
						target = $holder.find('.controls').find('label.active').data('value');

						$holder.find('.auto').find('.for_link_set').hide();

						if (url === '') {
							$holder.find('a.set_link').removeClass('link').text('лінк').addClass('noset_link');
						} else {
							$holder.find('a.set_link').text('').addClass('link').removeClass('noset_link');
						}

						$.post(
							'<?=$this->uri->full_url('/admin/menu/update_link/');?>',
							{
								id : $holder.closest('li').data('menu-id'),
								url : url,
								target : target,
								language : ($('a.flags.active').length > 0) ? $('a.flags.active').data('language') : '<?=LANG;?>'
							},
							function (response) {
								if (response.error === 0) {
									component_loader_hide($('.component_loader'), '');
									$holder.find('.auto').find('span.menu_item').find('a').attr('href', response.link);
								}
							},
							'json'
						);
					}
				}
			})
			.on('keydown', '.auto input', function (e) {
				if (e.keyCode == 13) {
					var $holder = $(this).closest('.holder'), $link = $holder.find('.cell a.edit'), item, url, target;

					$('#admin_sortable_menu').find('.last_edit.active').removeClass('active');
					$holder.find('.last_edit').addClass('active');

					$link.removeClass('active');
					component_loader_show($('.component_loader'), '');

					if ($holder.find('.auto').find('.for_link_set').css('display') === 'none') {
						item = $(this).val();

						$.post(
							'<?=$this->uri->full_url('/admin/menu/update');?>',
							{
								id : $holder.closest('li').data('menu-id'),
								name : item,
								language : ($('a.flags.active').length > 0) ? $('a.flags.active').data('language') : '<?=LANG;?>',
								menu_index: menu_index
							},
							function (response) {
								if (response.error === 0) {
									$holder.find('.auto').find('span.menu_item').html('<a href="' + response.link + '" target="_blank">' + item + '</a>');
									component_loader_hide($('.component_loader'), '');
								}
							},
							'json'
						);
					} else {
						url = $(this).val();
						target = $holder.find('.controls').find('label.active').data('value');

						$holder.find('.auto').find('.for_link_set').hide();

						if (url === '') {
							$holder.find('.set_link').removeClass('link').text('лінк').addClass('noset_link');
						} else {
							$holder.find('.set_link').text('').addClass('link').removeClass('noset_link');
						}

						$.post(
							'<?=$this->uri->full_url('/admin/menu/update_link/');?>',
							{
								id : $holder.closest('li').data('menu-id'),
								url : url,
								target : target,
								language : ($('a.flags.active').length > 0) ? $('a.flags.active').data('language') : '<?=LANG;?>'
							},
							function (response) {
								if (response.error === 0) {
									component_loader_hide($('.component_loader'), '');
									$holder.find('.auto').find('span.menu_item').find('a').attr('href', response.link);
								}
							},
							'json'
						);
					}
				}
			})
			.on('click', '.set_link', function (e) { /* link edit */
				e.preventDefault();

				$('.admin_menu')
					.find('.for_link_set').slideUp()
					.end()
					.find('a.edit').removeClass('active');

				var $holder = $(this).closest('.holder');

				if ($holder.find('.auto').find('.for_link_set').css('display') === 'none') {
					$holder
						.find('.auto').find('.for_link_set').stop().slideDown()
						.end().end()
						.find('a.edit').addClass('active');
				} else {
					$holder
						.find('.auto').find('.for_link_set').stop().slideUp()
						.end().end()
						.find('a.edit').removeClass('active');
				}
			})
			.on('click', '.hide-show', function (e) { /* menu show/hide */
				e.preventDefault();

				var $holder = $(this).closest('.holder'),
					status = 0;

				if ($holder.hasClass('hidden')) {
					$holder.removeClass('hidden');
					$(this).addClass('active');
				} else {
					$holder.addClass('hidden');
					$(this).removeClass('active');
					status = 1;
				}

				$('#admin_sortable_menu').find('.last_edit.active').removeClass('active');
				$holder.find('.last_edit').addClass('active');

				component_loader_show($('.component_loader'), '');

				$.post(
					'<?=$this->uri->full_url('/admin/menu/hidden/');?>',
					{
						id : $holder.closest('li').data('menu-id'),
						status : status
					},
					function (response) {
						if (response.error === 0) component_loader_hide($('.component_loader'), '');
					},
					'json'
				);
			})
			.on('click', '.main_page', function (e) { /* menu set_main */
				e.preventDefault();

				var $li = $(this).closest('li');

				if (!$(this).hasClass('active')) {

					$('.admin_menu')
						.find('.main_page').removeClass('active')
						.end()
						.find('.last_edit.active').removeClass('active')
						.end()
						.find('.delete').show();

					$li
						.find('.main_page').eq(0).addClass('active')
						.end().end()
						.find('.last_edit').eq(0).addClass('active')
						.end().end()
						.find('.delete').eq(0).hide();

					component_loader_show($('.component_loader'), '');

					$.post(
						'<?=$this->uri->full_url('admin/menu/set_main');?>',
						{
							id : $li.data('menu-id'),
							menu_index : menu_index
						},
						function (response) {
							if (response.error === 0) component_loader_hide($('.component_loader'), '');
						},
						'json'
					);
				}
			})
			.on('click', '.step_left', function (e) { /* menu click level */
				e.preventDefault();

				var $li = $(this).closest('li'), $ul = $(this).closest('ul'), $li_prev = $(this).parents('li').eq(1), $li_clone = $li.clone();

				$li.remove();
				$li_prev.after($li_clone);

				if ($ul.find('li').length === 0) $li_prev.find('ul').remove();
				move_menu_id = $li.data('menu-id');

				$('#admin_sortable_menu').find('.last_edit.active').removeClass('active');
				$li_clone.find('.holder').removeClass('active').end().find('.last_edit').eq(0).addClass('active').end().end().find('.add_items').eq(0).hide().end().end().find('.number').eq(0).show();

				set_menu_data();
				save_position();
			})
			.on('click', '.step_right', function (e) {
				e.preventDefault();

				var $parent = $(this).closest('li'),
					$parent_clone = $parent.clone(),
					$prev_item = $parent.prev('li');

				move_menu_id = $parent.data('menu-id');
				$parent.remove();

				$('#admin_sortable_menu').find('.last_edit.active').removeClass('active');
				$parent_clone.find('.last_edit').eq(0).addClass('active');

				if ($prev_item.find('ul').length > 0) {
					$prev_item.find('ul').eq(0).append($parent_clone);
				} else {
					$prev_item.append('<ul></ul>');
					$prev_item.find('ul').eq(0).append($parent_clone);
				}

				set_menu_data();
				save_position();
			})
			.on('click', '.double_step_right', function (e) {
				e.preventDefault();

				var $childs = $(this).closest('li').find('ul').length > 0 ? $(this).closest('li').find('ul').html() : '';
				$(this).closest('li').find('ul').remove();

				var $parent = $(this).closest('li').prev('li');
				var $clone = $(this).closest('li').clone();

				move_menu_id = $(this).closest('li').data('menu-id');
				$(this).closest('li').remove();

				$('#admin_sortable_menu').find('.last_edit.active').removeClass('active');
				$clone.find('.last_edit').eq(0).addClass('active');

				if ($parent.find('ul').length === 0) $parent.append('<ul></ul>');

				$parent.find('ul').eq(0).append($clone);

				if ($childs != '') $parent.find('ul').eq(0).append($childs);

				set_menu_data();
				save_position();
			})
			.on('click', '.controls label', function (e) { /* radios */
				e.preventDefault();
				$(this)
					.closest('.controls').find('label').removeClass('active')
					.end().end()
					.addClass('active');
			})
			.on('click', '.delete', function (e) { /* Видалення пункту меню */
				e.preventDefault();

				var $ul = $(this).closest('ul'),
					$li = $(this).closest('li');

				confirmation('Видалити пункт меню?<br><span>Якщо є дочірні пункти меню, то вони також будуть видалені!</span>', function () {

					component_loader_show($('.component_loader'), '');

					$.post(
						'<?=$this->uri->full_url('admin/menu/delete');?>',
						{
							id : $li.data('menu-id')
						},
						function (response) {
							$li.remove();

							if ($ul.find('li').length === 0) {
								$ul.remove();
							} else {
								set_menu_data();
							}

							component_loader_hide($('.component_loader'), '');
						},
						'json'
					);
				});
			})
			.on('click', '.up_add', function (e) {
				e.preventDefault();

				var $link = $(this),
					$li = $link.closest('li'),
					item_id = $link.parents('li').eq(1).length > 0 ? $link.parents('li').eq(1).data('menu-id') : 0;

				move_menu_id = $li.data('menu-id');

				$.post(
					'<?=$this->uri->full_url('admin/menu/insert');?>',
					{
						menu_index : menu_index,
						item_id: item_id
					},
					function (response) {
						if (response.error === 0) {
							$li.before(build_row(response));

							set_menu_data();
							save_position();
						}
					},
					'json'
				);
			})
			.on('click', '.child_add', function (e) {
				e.preventDefault();

				var $link = $(this),
					$li = $link.closest('li');

				move_menu_id = $li.data('menu-id');

				$.post(
					'<?=$this->uri->full_url('admin/menu/insert');?>',
					{
						menu_index : menu_index,
						item_id: move_menu_id
					},
					function (response) {
						if (response.error === 0) {
							if ($li.find('ul').length === 0) $li.append('<ul></ul>');

							if (menu_add_top === 0) {
								$li.find('ul').eq(0).append(build_row(response));
							} else {
								$li.find('ul').eq(0).prepend(build_row(response));
							}

							set_menu_data();
							save_position();
						}
					},
					'json'
				);
			})
			.on('click', '.down_add', function (e) {
				e.preventDefault();

				var $link = $(this),
					$li = $link.closest('li'),
					item_id = $link.parents('li').eq(1).length > 0 ? $link.parents('li').eq(1).data('menu-id') : 0;

				move_menu_id = $li.data('menu-id');

				$.post(
					'<?=$this->uri->full_url('admin/menu/insert');?>',
					{
						menu_index : menu_index,
						item_id: item_id
					},
					function (response) {
						if (response.error === 0) {
							$li.after(build_row(response));

							set_menu_data();
							save_position();
						}
					},
					'json'
				);
			})
			.on('click keydown', 'a.menu_sorter', function (e) {
				e.preventDefault();
			})
			.on('mouseenter', '.holder', function () {
				$(this).addClass('active').find('.number_cell').find('.number').hide().end().find('.add_items').show();
			})
			.on('mouseleave', '.holder', function () {
				$(this).removeClass('active').find('.number_cell').find('.add_items').hide().end().find('.number').show();
			});

			$('.add_bottom').on('click', function (e) {
				e.preventDefault();

				if (!$(this).find('b').hasClass('active')) {
					$(this).find('b').addClass('active');
					menu_add_top = 0;
				} else {
					$(this).find('b').removeClass('active');
					menu_add_top = 1;
				}
			});

		/**
		 * Додавання меню
		 */
		$('#add_menu').on('click', function (e) {
			e.preventDefault();

			$.post(
				'<?=$this->uri->full_url('admin/menu/insert');?>',
				{
					menu_index : menu_index
				},
				function (response) {
					if (response.error === 0) {
						if ($('.admin_menu').find('ul').length === 0) {
							$('.admin_menu').html('<ul></ul>');
						}

						if (menu_add_top === 0) {
							$('.admin_menu').find('ul').eq(0).append(build_row(response));
						} else {
							$('.admin_menu').find('ul').eq(0).prepend(build_row(response));
						}

						move_menu_id = response.menu_id;

						set_menu_data();
						save_position();
					}
				},
				'json'
			);
		});
	});
//]]>
</script>