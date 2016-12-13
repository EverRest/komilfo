(function ($) {

	$.fn.feedback = function (options) {

		$.fn.feedback.defaults = {
			component_id:'',
			menu_id:'',
			def_uri:''
		};

		var opt = $.extend({}, $.fn.feedback.defaults, options);

		return this.each(function () {

			var $component = $(this);
			var $component_panel = $component.find('.sub_admin_top');

			/**
			 * Переміщення компоненту вверх
			 */
			$component_panel.on('click', 'a.up_component', function (event) {
				event.preventDefault();
				if (!$(this).hasClass('no_active')) {
					var $prev_component = $component.prevAll('.admin_component:eq(0)');
					var this_copy = $component.clone();

					$component.remove();

					$(this_copy).feedback(opt);

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

					$(this_copy).feedback(opt);

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

				confirmation('Видалити зворотній зв`язок?', function () {
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