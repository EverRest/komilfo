<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$this->template_lib
		->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js')
		->set_js('plugins/mustache.min.js');
?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="news" data-css-class="news" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/news/delete_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="<?=(($hidden == 0) ? 'news' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
			<a class="fm add" href="#" id="add_news"><b></b>Додати новину</a>
			<a href="#" class="fm show_hide <?=(($hidden == 0) ? "active": "");?>"><b></b><?=(($hidden == 0) ? 'Приховати' : 'Показати');?></a>
		</div>
		<div class="fmr component_del">
			<a href="#" class="fm delete_component"><b></b></a>
		</div>
		<div class="fmr component_pos">
			<a href="#" class="fm up_component"><b></b></a>
			<a href="#" class="fm down_component"><b></b></a>
		</div>
	</div>
	<div class="fm admin_menu">
		<ul>
			<li class="th">
				<div class="holder">
					<div class="cell last_edit"></div>
					<div class="cell w_30"></div>
					<div class="cell w_20"></div>
					<div class="cell no_padding" style="width:<?=$image_thumb[0];?>px">Зображення до новини</div>
					<div class="cell w_20"></div>
					<div class="cell auto">Назва новини</div>
					<div class="cell w_20"></div>
					<div class="cell w_20"></div>
				</div>
			</li>
		</ul>
		<?
		// echo "<pre>";
		// print_r($news);
		// echo "</pre>";exit();
		?>
		<ul class="admin_news">
			<?php if (isset($news) AND count($news) > 0): ?>
				<?php foreach ($news as $row): ?>
					<li data-item-id="<?=$row['news_id'];?>">
						<div class="holder">
							<div class="cell last_edit<?php if ($row['news_id'] == $last) echo ' active'; ?>"></div>
							<div class="cell w_30 number"></div>
							<div class="cell w_20 icon"><a href="#" class="hide-show<?php if ($row['hidden'] == 0) echo ' active'; ?>"></a></div>
							<div class="cell no_padding" style="width:<?=$image_thumb[0];?>px"><?php if ($row['image'] !== NULL): ?><img src="<?=base_url('upload/news/' . get_dir_code($row['news_id']) . '/' . $row['news_id'] . '/t_' . $row['image'] . '?=' . time());?>" alt=""><?php endif; ?></div>
							<div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/news/edit?menu_id=' . $menu_id . '&news_id=' . $row['news_id']);?>" class="edit"></a></div>
							<div class="cell auto"><?=(($row['title'] != '') ? $row['title'] : '<span class="menu_item">Новий запис</span>');?></div>
							<div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div>
							<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
						</div>
					</li>
		<?php endforeach; ?><?php endif; ?>
		</ul>
	</div>
	<?php if ($pagination != '') echo  '<div class="fm admin_paginator">' . $pagination . '</div>'; ?>
</div>
<script id="news_template" type="text/html">
	<li id="news_{{ news_id }}" data-item-id="{{ news_id }}">
		<div class="holder">
			<div class="cell last_edit active"></div>
			<div class="cell w_30 number"></div>
			<div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div>
			<div class="cell no_padding" style="width:<?=$image_thumb[0];?>px"></div>
			<div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/news/edit?menu_id=' . $menu_id . '&news_id={{ news_id }}');?>" class="edit"></a></div>
			<div class="cell auto"><span class="menu_item">Новий запис</span></div>
			<div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div>
			<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
		</div>
	</li>
</script>
<script type="text/javascript">
	var base_url = "<?=base_url()?>";

	$(function () {
		$('#admin_component_<?=$component_id;?>').component();
	});
	$(function() {
		$('#news_<?=$last;?>').find('.last_edit').eq(0).addClass('active');
		var $component = $('#admin_component_<?=$component_id;?>'),
			$list = $component.find('.admin_menu');
		$component
			.on('click', '#add_news', function (e) {
				e.preventDefault();
				global_helper.loader($component);
				$.post(
					'<?=$this->uri->full_url('admin/news/insert_news');?>',
					{component_id: <?=$component_id;?>, menu_id: <?=$menu_id?>},
					function (response) {
						if (response.success) {
							$('.last_edit').removeClass('active');
							$(
								Mustache.render(
									$('#news_template').html(),
									response
								)
							).insertAfter($list.find('li').eq(0));
							$list.trigger('list.style');
							global_helper.loader($component);
						}
					},
					'json'
				);
			})
			.on('click', '.show_hide', function (e) {
				e.preventDefault();
				global_helper.loader($component);
				var $holder = $(this).closest('.holder'),
					status = 0;
				$(this).toggleClass('active');
				if (!$(this).hasClass('active')) status = 1;
				$.post(
					'<?=$this->uri->full_url('admin/news/component_visible');?>',
					{
						menu_id: <?=$menu_id?>,
						component_id: <?=$component_id;?>,
						status: status
					},
					function (response) {
						if (response.success) {
							global_helper.loader($component);
						}
					},
					'json'
				);
			})
			// .on('click', '.delete_component ', function (e) {
			// 	e.preventDefault();
			// 	var $li = $(this).closest('li');
			// 	global_helper.confirmation(
			// 		'Видалити компонент назавжди?',
			// 		function () {
			// 			global_helper.loader($component);
			// 			$.post(
			// 				'<?=$this->uri->full_url('admin/details/delete_component');?>',
			// 				{
			// 					menu_id: <?=$menu_id?>,
			// 					component_id : <?=$component_id?>
			// 				},
			// 				function (response) {
			// 					if (response.success) {
			// 						global_helper.loader($component);
			// 						$component.remove();
			// 					}
			// 				},
			// 				'json'
			// 			);
			// 		}
			// 	);
			// })
			;
			var settings = {
				base_url: base_url,
				forcePlaceholderSize: true,
				opacity: "0.6",
				listType: 'ul',
				handle: '.sorter a',
				items: 'li',
				toleranceElement: '> div',
				placeholder: "ui-state-highlight",
				axis: 'y',
				helper: 'clone'
			};
			global_helper.fild_sortable(
				$component, 
				$component.find('.admin_news').find('li'),
				settings,
				function sorter_el(position, obj){
					global_helper.loader($component);
					$.post(
						'<?=$this->uri->full_url('admin/news/save_position');?>',
						{
							menu_id: <?=$menu_id?>,
							position: position
						},
						function (response) {
							if (response.success) {
								$component.find('.last_edit').removeClass('active');
								$(obj.item).find('.last_edit').eq(0).addClass('active');
								global_helper.loader($component);
							}
						},
						'json'
					);
				}
			);
		$list
			.on('click', '.hide-show', function (e) {
				e.preventDefaul
				var $holder = $(this).closest('.holder'),
					status = 0;
				$(this).toggleClass('active');
				if (!$(this).hasClass('active')) status = 1;
				$list.find('.last_edit.active').removeClass('active');
				$holder.find('.last_edit').addClass('active');
				global_helper.loader($component);
				$.post(
					'<?=$this->uri->full_url('admin/news/visible');?>',
					{
						menu_id: <?=$menu_id?>,
						news_id: $holder.closest('li').data('item-id'),
						status: status
					},
					function (response) {
						if (response.success) {
							global_helper.loader($component);
						}
					},
					'json'
				);
			})
			.on('click', '.delete', function (e) {
				e.preventDefault();
				var $li = $(this).closest('li');
				global_helper.confirmation(
					'Видалити новину?',
					function () {
						global_helper.loader($component);
						$.post(
							'<?=$this->uri->full_url('admin/news/delete');?>',
							{
								news_id : $li.data('item-id')
							},
							function (response) {
								if (response.success) {
									$li.remove();
									$list.trigger('list.style');
									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				);
			})
			.on('mouseenter', '.holder', function () {
				if (!$(this).closest('li').hasClass('th')) {
					$(this).addClass('active');
				}
			})
			.on('mouseleave', '.holder', function () {
				if (!$(this).closest('li').hasClass('th')) {
					$(this).removeClass('active');
				}
			})
			.on('list.style', function () {
				$list.find('li').map(function (i) {
					if (i % 2 === 0) {
						$(this).addClass('grey');
					} else {
						$(this).removeClass('grey');
					}
				});
			})
			.trigger('list.style');
	});
</script>