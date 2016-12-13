<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-module="feedback" data-css-class="feedback" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/feedback/delete_component?menu_id='.$menu_id.'');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="<?=(($hidden == 0) ? 'feedback' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Зворотній зв'язок</div></div>
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
	<div class="fm admin_view_article">
	<?php if ($total_rows > 0): ?>
		<div class="fm admin_menu for_messages"><?php echo $messages; ?></div>
	<?php else: ?>
		<div class="fm admin_massage">Повідомлень немає</div>
	<?php endif; ?>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		var $component = $('#admin_component_<?=$component_id;?>'),
			total_rows = <?php echo $total_rows; ?>,
			c_page = 0;

		function row_decor() {
			$('.admin_menu ul')
				.find('li[class!="th"]').removeClass('grey')
				.end()
				.find('li:even[class!="th"]').addClass('grey');
		}
		
		$('.checkbox').style_input();

		function pagination(current_page, first_page) {
			$component.find('.admin_paginator').empty();

			if (total_rows > 10) {
				$component.find('.admin_paginator').pagination(
					total_rows,
					{
						items_per_page: 10,
						current_page: current_page,
						load_first_page: first_page,
						callback: function (page) {
							c_page = page;

							component_loader_show($component.find('.component_loader'), '');

							$.post(
								'<?php echo $this->uri->full_url('/admin/feedback/get'); ?>',
								{
									page: page
								},
								function (response) {
									if (response.error === 0) {
										$component.find('.admin_menu').html(response.messages);
										row_decor();
										$('.checkbox').style_input();
										component_loader_hide($component.find('.component_loader'), '');
									}
								},
								'json'
							);

							return false;
						}
					}
				);
			} else {
				if (first_page === true) {
					component_loader_show($component.find('.component_loader'), '');
					$.post(
						'<?php echo $this->uri->full_url('/admin/feedback/get'); ?>',
						{
							page: c_page
						},
						function (response) {
							if (response.error === 0) {
								component_loader_hide($component.find('.component_loader'), '');
								$component.find('.admin_menu').html(response.messages);
								row_decor();
							}
						},
						'json'
					);
				}
			}
		}

		row_decor();
		pagination(0, false);

		$component
			.component({
				onDelete: function () {
					$('a.feedback').show();
				}
			})
			.find('.admin_menu')
				.on('mouseover', '.holder', function () {
					if (!$(this).closest('li').hasClass('th')) $(this).addClass('active');
				})
				.on('mouseout', '.holder', function () {
					if (!$(this).closest('li').hasClass('th')) $(this).removeClass('active');
				})
				.on('change', ':checkbox', function () {

					if ($(this).closest('li').hasClass('th')) {
						if ($(this).prop('checked')) {
							$(this).closest('ul').find('label').addClass('active').find(':checkbox').prop('checked', true);
						} else {
							$(this).closest('ul').find('label').removeClass('active').find(':checkbox').prop('checked', false);
						}
					}

					var c_all = 0, ck_all = 0;

					$('.admin_menu').find('li').find(':checkbox').map(function () {
						if (!$(this).closest('li').hasClass('th')) {
							c_all++;
							if ($(this).prop('checked')) ck_all++;
						}
					});

					if (c_all == ck_all) {
						$(this).closest('ul').find('.delete_selected').show();
						$(this).closest('ul').find('label').eq(0).addClass('active').find(':checkbox').prop('checked', true);
					} else {
						$(this).closest('ul').find('.delete_selected').hide();
						$(this).closest('ul').find('label').eq(0).removeClass('active').find(':checkbox').prop('checked', false);
					}
				})
				.on('click', '.delete_selected', function (e) {
					e.preventDefault();

					var $del_link = $(this);

					confirmation('Видалити вибрані повідомлення?', function () {
						component_loader_show($component.find('.component_loader'), '');
						$del_link.closest('ul').find(':checked').each(function () {
							if (!$(this).closest('li').hasClass('th')) {
								var $link = $(this);

								$.post(
									'<?php echo $this->uri->full_url('admin/feedback/delete'); ?>',
									{
										message_id: $link.val()
									},
									function (response) {

										$link.closest('li').slideUp(function () {
											$(this).remove();
											row_decor();
											component_loader_hide($component.find('.component_loader'), '');
											
											total_rows--;

											if ($del_link.closest('ul').find('li').length === 1) {
												if (total_rows === 0) {
													$del_link.closest('.admin_menu').replaceWith('<div class="fm admin_massage">Повідомлень немає</div>');
												} else {
													if (total_rows <= 10) {
														c_page = 0;
														pagination(c_page, true);
													} else {
														if (Math.ceil(total_rows/10) == c_page && c_page > 0) c_page--;
														pagination(c_page, true);
													}
												}
											}
										});
									},
									'json'
								);
							}
						});
					});
				})
				.on('click', '.delete', function (e) {
					e.preventDefault();

					var $link = $(this);

					confirmation('Видалити повідомлення?', function () {
						
						component_loader_show($component.find('.component_loader'), '');
						
						$.post(
							'<?php echo $this->uri->full_url('admin/feedback/delete'); ?>',
							{
								message_id: $link.closest('li').data('id')
							},
							function (response) {
								$link.closest('li').slideUp(function () {
									var $messages_list = $link.closest('.admin_menu');
									$(this).remove();
									row_decor();
									component_loader_hide($component.find('.component_loader'), '');

									total_rows--;

									if ($messages_list.find('li').length === 1) {
										if (total_rows === 0) {
											$messages_list.replaceWith('<div class="fm admin_massage">Повідомлень немає</div>');
										} else {
											if (total_rows <= 10) {
												c_page = 0;
												pagination(c_page, true);
											} else {
												if (Math.ceil(total_rows/10) == c_page && c_page > 0) c_page--;
												pagination(c_page, true);
											}
										}
									}
								});
							},
							'json'
						);
					});
				});
	});
</script>