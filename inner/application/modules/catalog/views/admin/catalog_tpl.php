<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	/**
	 * @var array $catalog
	 * @var int $last
	 * @var int $menu_id
	 */

	

	$this->template_lib
		->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js')
		->set_js('plugins/mustache.min.js');
?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="catalog" data-css-class="catalog" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/catalog/delete_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="catalog"></div>
		</div>
		<div class="fm component_edit_links">
			<div class="fm only_text"><div>Каталог суконь</div></div>
			<a class="fm add add_catalog" href="#"><b></b>Додати сукню</a>
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
					<div class="cell w_20"></div>
					<div class="cell w_20"></div>
					<div class="cell" style="width: <?=$thumb[0]?>px;">Зображення</div>
					<div class="cell auto">Назва</div>
					<div class="cell w_20"></div>
					<div class="cell w_20"></div>
				</div>
			</li>
		</ul>
		<ul class="admin_catalog">
			<?
// echo "<pre>";
// print_r($catalog);
// echo "</pre>";exit();
			?>
			<?php foreach ($catalog as $v): ?>
				<li id="catalog_<?=$v['catalog_id'];?>"  data-item-id="<?=$v['catalog_id'];?>">
					<div class="holder">
						<div class="cell last_edit<?php if ($last === (int)$v['catalog_id']): ?> active<?php endif; ?>"></div>
						<div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div>
						<div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/catalog/edit?menu_id=' . $menu_id . '&catalog_id=' . $v['catalog_id']);?>" class="edit"></a></div>
						<div class="cell" style="width: <?=$thumb[0]?>px;"><?php if ($v['photo'] !== ''): ?><img style="max-width: 363px;" src="<?=base_url('upload/catalog/' . get_dir_code($v['catalog_id']) . '/' . $v['catalog_id'] . '/' . $v['photo']);?>" alt=""><?php endif; ?></div>
						<div class="cell auto"><span class="menu_item"><?=($v['title'] === '' ? 'Новий запис' : $v['title']);?></span></div>
						<div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div>
						<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<script id="catalog_template" type="text/html">
	<li id="catalog_{{ catalog_id }}" data-item-id="{{ catalog_id }}">
		<div class="holder">
			<div class="cell last_edit active"></div>
			<div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div>
			<div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/catalog/edit?menu_id=' . $menu_id . '&catalog_id={{ catalog_id }}');?>" class="edit"></a></div>
			<div class="cell w_350"></div>
			<div class="cell auto"><span class="menu_item">Новий запис</span></div>
			<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
		</div>
	</li>
</script>

<script type="text/javascript">

	$(function () {
		console.log(<?=$component_id;?>);
		$('#admin_component_<?=$component_id;?>').component();
	});

	$(function() {

		$('#catalog_<?=$last;?>').find('.last_edit').eq(0).addClass('active');

		var $component = $('#admin_component_<?=$component_id;?>'),
			$list = $('.admin_menu');

		$component
			.on('click', '.add_catalog', function (e) {
				e.preventDefault();

				global_helper.loader($component);

				$.post(
					'<?=$this->uri->full_url('admin/catalog/insert_catalog');?>',
					{
						component_id : <?=$component_id?>,
						menu_id : <?=$menu_id?>
					},
					function (response) {
						if (response.success) {
							$('.last_edit').removeClass('active');

							$(
								Mustache.render(
									$('#catalog_template').html(),
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
			// .on('click', '.delete_component ', function (e) {
			// 	e.preventDefault();
			// 	var $li = $(this).closest('li');
			// 	global_helper.confirmation(
			// 		'Видалити компонент назавжди?',
			// 		function () {
			// 			global_helper.loader($component);
			// 			$.post(
			// 				'<?=$this->uri->full_url('admin/catalog/delete_component');?>',
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
			// });

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
				$component.find('.admin_catalog').find('li'),
				settings,
				function sorter_el(position, obj){
					global_helper.loader($component);
					$.post(
						'<?=$this->uri->full_url('admin/catalog/save_position');?>',
						{
							menu_id: <?=$menu_id?>,
							position: position,
							// catalog_id: $component.find('.admin_catalog').find('li').data('item-id')
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
				e.preventDefault();

				var $holder = $(this).closest('.holder'),
					status = 0;

				$(this).toggleClass('active');
				if (!$(this).hasClass('active')) status = 1;

				$list.find('.last_edit.active').removeClass('active');
				$holder.find('.last_edit').addClass('active');

				global_helper.loader($component);

				$.post(
					'<?=$this->uri->full_url('admin/catalog/catalog_visibility');?>',
					{
						catalog_id: $holder.closest('li').data('item-id'),
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
					'Видалити сукню?',
					function () {

						global_helper.loader($component);

						$.post(
							'<?=$this->uri->full_url('admin/catalog/delete_catalog');?>',
							{
								catalog_id : $li.data('item-id')
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