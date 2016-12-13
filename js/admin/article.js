(function ($) {

	$.fn.article = function (options) {

		$.fn.article.defaults = {
			component_id: '',
			menu_id: '',
			def_uri: ''
		};

		var opt = $.extend({}, $.fn.article.defaults, options);

		return this.each(function () {

			var $component = $(this);
			var $component_panel = $component.find('.sub_admin_top');

			/**
			 * Виклик форми редагування тексту
			 */
			$component_panel.off('click').on('click', 'a.edit', function (event) {
				event.preventDefault();

				$component_panel.find('.editing_component a.edit, .hot_keys_for_component').hide();
				$component_panel.find('.editing_component a.save').show();
				$component_panel.find('.editing_component a.apply').show();
				$component_panel.find('.editing_component a.cancel').show();

				var uri = $(this).attr('href'),
					request = {
						component_id: opt.component_id,
						menu_id: opt.menu_id
					};

				$.post(
					uri,
					request,
					function (response) {
						$component.find('.sub_admin_bottom a').show();
						$component.find('.admin_view_article').html(response);
						$('.article_' + opt.component_id).ckeditor({height: 400});
					}
				);
			});

			/**
			 * Зміна мови редагування
			 */
			$component.find('.sub_admin_bottom a').off('click').on('click', function (event) {
				event.preventDefault();

				$component.find('.sub_admin_bottom a.active').removeClass('active');
				$(this).addClass('active');

				$component.find('.article_tab').hide();
				$component.find('#article_tab_' + $(this).attr('rel')).show();
			});
			/**
			 * Застосування компоненту
			 */
			$component.on('click', '.editing_component a.apply, a.apply_adm', function (event) {
				event.preventDefault();
				$('.article_' + opt.component_id).ckeditor({action: 'update'});
				$('#component_form_' + opt.component_id).ajaxSubmit({
					beforeSubmit:function () {
						$component.find('.loader').show();
					},
					success:function (response) {
						$component.find('.loader').hide();
						$component.find('.for_sucsess .sucsess').stop().fadeTo(200, 1).delay(2000).fadeTo(200, 0);
					}
				});
			});

			/**
			 * Збереження компоненту
			 */
			$component.on('click', '.editing_component a.save, a.save_adm', function (e) {
				e.preventDefault();
				$('.article_' + opt.component_id).ckeditor({action: 'update'});
				$('#component_form_' + opt.component_id).ajaxSubmit({
					beforeSubmit:function () {
						$component.find('.loader').show();
					},
					success:function (response) {
						$component.find('.loader').hide();
						$component.find('.for_sucsess .sucsess').stop().fadeTo(200, 1).delay(500).fadeTo(200, 0, function () {
							$.post(
								opt.def_uri,
								{
									'component':'article',
									'action':'get',
									'component_id':opt.component_id
								},
								function (response) {
									$('.article_' + opt.component_id).ckeditor({action: 'destroy'});
									$component.find('.sub_admin_bottom a').hide();
									$component.find('.admin_view_article').html(response);
									$component_panel.find('.editing_component a.edit, .hot_keys_for_component').show();
									$component_panel.find('.editing_component a.save').hide();
									$component_panel.find('.editing_component a.apply').hide();
									$component_panel.find('.editing_component a.cancel').hide();
								}
							);
						});
					}
				});
			});

			/**
			 * Завершення редагування
			 */
			$component.on('click', '.editing_component a.cancel, a.cansel_adm', function (event) {
				event.preventDefault();

				$.post(
					opt.def_uri,
					{
						'component':'article',
						'action':'get',
						'component_id':opt.component_id
					},
					function (response) {
						$('.article_' + opt.component_id).ckeditor({action: 'destroy'});
						$component.find('.sub_admin_bottom a').hide();
						$component.find('.admin_view_article').html(response);
						$component_panel.find('.editing_component a.edit, .hot_keys_for_component').show();
						$component_panel.find('.editing_component a.save').hide();
						$component_panel.find('.editing_component a.apply').hide();
						$component_panel.find('.editing_component a.cancel').hide();
					}
				);
			});

			/**
			 * Переміщення компоненту вверх
			 */
			$component_panel.on('click', 'a.up_component', function (event) {
				event.preventDefault();
				if (!$(this).hasClass('no_active')) {
					var $prev_component = $component.prevAll('.admin_component:eq(0)');
					var this_copy = $component.clone();

					$component.remove();

					$(this_copy).article(opt);

					$(this_copy).insertBefore($prev_component);
					component_controls();
					component_position();
				}
			});

			/**
			 * Переміщення компоненту вниз
			 */
			$component_panel.on('click', 'a.down_component', function (event) {
				event.preventDefault();

				if (!$(this).hasClass('no_active')) {
					var $next_component = $component.nextAll('.admin_component:eq(0)');
					var this_copy = $component.clone();

					$component.remove();

					$(this_copy).article(opt);

					$(this_copy).insertAfter($next_component);
					component_controls();
					component_position();
				}
			});

			/**
			 * Видалення компоненту
			 */
			$component_panel.on('click', 'a.delete_component', function (event) {
				event.preventDefault();

				confirmation('Видалити статтю назавжди?', function () {
					$.post(
						opt.delete_uri,
						{
							component_id: opt.component_id,
							menu_id: opt.menu_id
						},
						function (response) {
							$component.remove();
							component_controls();
						}
					);
				});
			});
		});
	}
})(jQuery);