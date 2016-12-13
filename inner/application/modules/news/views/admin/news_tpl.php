<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="news" data-css-class="news" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/news/delete_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="<?=(($hidden == 0) ? 'news' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
			<a class="fm add" href="#" id="add_news"><b></b>Додати товар</a>
			<a href="#" class="fm show_hide"><b></b><?=(($hidden == 0) ? 'Приховати' : 'Показати');?></a>
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
					<div class="cell no_padding " style="width: 75px;">Зображення послуги</div>
					<div class="cell w_20"></div>
					<div class="cell auto">Назва послуги</div>
					<div class="cell w_20"></div>
					<div class="cell w_20"></div>
				</div>
			</li>
		</ul>
		<ul class="admin_news">
			<?php if (isset($news) AND count($news) > 0): ?>
				<?php foreach ($news as $row): ?>
					<li data-id="<?=$row['news_id'];?>">
						<div class="holder">
							<div class="cell last_edit<?php if ($row['news_id'] == $last) echo ' active'; ?>"></div>
							<div class="cell w_30 number"></div>
							<div class="cell w_20 icon"><a href="#" class="hide-show<?php if ($row['hidden'] == 0) echo ' active'; ?>"></a></div>
							<div class="cell no_padding" style="width: 75px;"><?php if ($row['image'] !== NULL): ?><img src="<?=base_url('upload/news/' . $this->init_model->dir_by_id($row['news_id']) . '/' . $row['news_id'] . '/t_' . $row['image'] . '?=' . time());?>" alt="" style="width: 75px;"><?php endif; ?></div>
							<div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/news/edit?menu_id=' . $menu_id . '&news_id=' . $row['news_id']);?>" class="edit"></a></div>
							<div class="cell auto"><?=(($row['title'] != '') ? $row['title'] : 'Товар без назви');?></div>
							<div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div>
							<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
						</div>
					</li>
		<?php endforeach; ?><?php endif; ?>
		</ul>
	</div>
	<?php if ($pagination != '') echo  '<div class="fm admin_paginator">' . $pagination . '</div>'; ?>
</div>

<script type="text/javascript">
//<![CDATA[
	$(document).ready(function () {

		var $component = $('#admin_component_<?=$component_id;?>');

		function row_decor() {
			$component.find('.admin_news').find('li')
				.removeClass('grey')
				.each(function () {
					var index = $(this).index();
					$(this).find('.number').eq(0).text(index + 1);
					if (index % 2 == 0) $(this).addClass('grey');
				});
		}

		row_decor();

		$component.component({
			onDelete: function () {
				$('.com_news').show();
			}
		});

		$component.on('click', '#add_news', function (e) {
			e.preventDefault();
			component_loader_show($('.component_loader'), '');
			$.post(
				'<?=$this->uri->full_url('admin/news/insert');?>',
				{
					menu_id: <?=$menu_id?>,
					component_id: <?=$component_id;?>
				},
				function (response) {
					if (response.success) {
						$component.find('.last_edit').removeClass('active');
						var row = $('<li data-id="' + response.news_id + '"><div class="holder"><div class="cell last_edit active"></div><div class="cell w_30 number"></div><div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div><div class="cell no_padding" style="width:75px;"></div><div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/news/edit?menu_id=' . $menu_id . '&news_id=');?>' + response.news_id + '" class="edit"></a></div><div class="cell auto">Товар без назви</div><div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div><div class="cell w_20 icon"><a href="#" class="delete"></a></div></div></li>');
						$component.find('.admin_news').prepend(row);
						row_decor();
						component_loader_hide($('.component_loader'), '');
					}
				},
				'json'
			);
		});

		if (jQuery.ui) {
			$component.sortable({
				forcePlaceholderSize: true,
				opacity: .6,
				listType: 'ul',
				handle: '.sorter a',
				items: 'li',
				toleranceElement: '> div',
				update: function (e, obj) {
					row_decor();
					var position = [];

					$component.find('.admin_news').find('li').each(function () {
						position[$(this).index()] = $(this).data('id');
					});

					if (position.length > 0) {
						component_loader_show($('.component_loader'), '');
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
									component_loader_hide($('.component_loader'), '');
								}
							},
							'json'
						);
					}
				},
				placeholder: "ui-state-highlight",
				axis: 'y',
				helper: 'clone'
			});
		} else {
			$.getScript(
				'<?=base_url('js/admin/ui/jquery-ui-1.10.3.custom.min.js');?>',
				function () {
					$component.sortable({
						forcePlaceholderSize: true,
						opacity: .6,
						listType: 'ul',
						handle: '.sorter a',
						items: 'li',
						toleranceElement: '> div',
						update: function (e, obj) {
							row_decor();
							var position = [];

							$component.find('.admin_news').find('li').each(function () {
								position[$(this).index()] = $(this).data('id');
							});

							if (position.length > 0) {
								component_loader_show($('.component_loader'), '');
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
											component_loader_hide($('.component_loader'), '');
										}
									},
									'json'
								);
							}
						},
						placeholder: "ui-state-highlight",
						axis: 'y',
						helper: 'clone'
					});
				}
			);
		}

		$component
			.on('click', '.hide-show', function (e) {
				e.preventDefault();
				var $link = $(this),
					status = 1;

				if ($link.hasClass('active')) {
					$link.removeClass('active');
				} else {
					$link.addClass('active');
					status = 0;
				}

				component_loader_show($('.component_loader'), '');

				$.post(
					'<?=$this->uri->full_url('admin/news/visible');?>',
					{
						menu_id: <?=$menu_id?>,
						news_id: $link.closest('li').data('id'),
						status: status
					},
					function (response) {
						if (response.success) {
							$component.find('.last_edit').removeClass('active');
							$link.closest('.holder').find('.last_edit').addClass('active');
							component_loader_hide($('.component_loader'), '');
						}
					},
					'json'
				);
			})
			.on('click', '.delete', function (e) {
				e.preventDefault();

				var $link = $(this),
					news_id = $link.closest('li').data('id');

				confirmation('Видалити послуги?', function () {
					component_loader_show($('.component_loader'), '');
					$.post(
						'<?=$this->uri->full_url('admin/news/delete');?>',
						{
							menu_id: <?=$menu_id?>,
							news_id: news_id
						},
						function (response) {
							if (response.success) {
								$link.closest('li').slideUp().remove();
								row_decor();
								component_loader_hide($('.component_loader'), '');
							}
						},
						'json'
					);
				});
			})
			.on('mouseover', '.admin_news .holder', function () {
				$(this).addClass('active');
			})
			.on('mouseout', '.admin_news .holder', function () {
				$(this).removeClass('active');
			});
	});
//]]>
</script>