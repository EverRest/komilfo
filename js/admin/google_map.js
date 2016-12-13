(function ($) {

	$.fn.google_map = function (options) {

		$.fn.google_map.defaults = {
			component_id : '',
			menu_id : '',
			def_uri : ''
		};

		var opt = $.extend({}, $.fn.google_map.defaults, options);

		return this.each(function () {

			var $component = $(this);
			var $component_panel = $component.find('.sub_admin_top');

			/**
			 * Виклик форми редагування
			 */
			$component_panel.off('click').on('click', 'a.edit', function (event) {
				event.preventDefault();

				$component_panel.find('.editing_component a.edit, .hot_keys_for_component').hide();
				$component_panel.find('.editing_component a.save').show();
				$component_panel.find('.editing_component a.cancel').show();

				var uri = $(this).attr('href'),
					request = {
						component_id : opt.component_id,
						menu_id : opt.menu_id
					};

				$.post(
					uri,
					request,
					function (response) {
						$component.find('.sub_admin_bottom a').show();
						$component.find('.admin_view_article').html(response);
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

				$component.find('.map_tab').hide();
				$component.find('#map_tab_' + $(this).attr('rel')).show();
			});

			/**
			 * Збереження компоненту
			 */
			$component.on('click', '.editing_component a.save, a.save_adm', function (event) {
				event.preventDefault();

				$('#component_form_' + opt.component_id).ajaxSubmit({
					beforeSubmit: function () {
						if ($.trim($component.find('input[name="center_x"]').val()) === '') {
							$('#center_x_error_' + opt.component_id).show();
							$('#center_x_error_' + opt.component_id).parents('.evry_title:eq(0)').addClass('wrong');
							return false;
						}
						if ($.trim($component.find('input[name="center_y"]').val()) === '') {
							$('#center_y_error_' + opt.component_id).show();
							$('#center_y_error_' + opt.component_id).parents('.evry_title:eq(0)').addClass('wrong');
							return false;
						}
						if ($.trim($component.find('input[name="zoom"]').val()) === '') {
							$('#zoom_error_' + opt.component_id).show();
							$('#zoom_error_' + opt.component_id).parents('.evry_title:eq(0)').addClass('wrong');
							return false;
						}
						if ($.trim($component.find('input[name="point_x"]').val()) === '') {
							$('#point_x_error_' + opt.component_id).show();
							$('#point_x_error_' + opt.component_id).parents('.evry_title:eq(0)').addClass('wrong');
							return false;
						}
						if ($.trim($component.find('input[name="point_y"]').val()) === '') {
							$('#point_y_error_' + opt.component_id).show();
							$('#point_y_error_' + opt.component_id).parents('.evry_title:eq(0)').addClass('wrong');
							return false;
						}
						$component.find('.loader').show();
					},
					success: function (response) {
						$component.find('.loader').hide();
						$component.find('.for_sucsess .sucsess').fadeTo(200, 1).delay(2000).fadeTo(200, 0);
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
						'component' : 'google_map',
						'action' : 'get',
						'component_id' : opt.component_id
					},
					function (response) {
						$component.find('.sub_admin_bottom a').hide();
						$component.find('.admin_view_article').html(response);
						$component_panel.find('.editing_component a.edit, .hot_keys_for_component').show();
						$component_panel.find('.editing_component a.cancel').hide();
						$component_panel.find('.editing_component a.save').hide();
					}
				);
			});

			/**
			 * Очищення валідаційних повідомлень
			 */
			$component.on('keyup blur paste', 'input[name="center_x"]', function () {
				$('#center_x_error_' + opt.component_id).hide();
				$(this).parents('.evry_title:eq(0)').removeClass('wrong');
			});
			$component.on('keyup blur paste', 'input[name="center_y"]', function () {
				$('#center_y_error_' + opt.component_id).hide();
				$('#center_y_error_' + opt.component_id).parents('.evry_title:eq(0)').removeClass('wrong');
			});
			$component.on('keyup blur paste', 'input[name="zoom"]', function () {
				$('#zoom_error_' + opt.component_id).hide();
				$('#zoom_error_' + opt.component_id).parents('.evry_title:eq(0)').removeClass('wrong');
			});
			$component.on('keyup blur paste', 'input[name="point_x"]', function () {
				$('#point_x_error_' + opt.component_id).hide();
				$('#point_x_error_' + opt.component_id).parents('.evry_title:eq(0)').removeClass('wrong');
			});
			$component.on('keyup blur paste', 'input[name="point_y"]', function () {
				$('#point_y_error_' + opt.component_id).hide();
				$('#point_y_error_' + opt.component_id).parents('.evry_title:eq(0)').removeClass('wrong');
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

					$(this_copy).google_map(opt);

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

					$(this_copy).google_map(opt);

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

				confirmation('Видалити Google мапу?', function () {
					$.post(
						opt.delete_uri,
						{
							'component_id':opt.component_id
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