function confirmation(options, callback) {
	if (delete_alert) {

		var defaults = {
				msg: '',
				icon_class: 'fm cm_delete',
				cancel_class: 'fm confirm_no',
				cancel_text: 'Скасувати',
				success_class: 'fm confirm_yes',
				success_text: 'Видалити'
			},
			settings;

		if ($.type(options) == 'object') {
			settings = $.extend(defaults, options);
		} else {
			defaults.msg = options;
			settings = defaults;
		}

		$('#confirm_overlay').css('height', $(document).height());
		if (settings.msg != '') $('.confirm_modal p').html(settings.msg);

		$('#modal_icon').attr('class', settings.icon_class);

		$('#confirm_no')
			.attr('class', settings.cancel_class)
			.html('<b></b>' + settings.cancel_text)
			.off('click')
			.on('click', function (e) {
				e.preventDefault();
				$('#confirm_overlay, .confirm_modal').hide();
			});

		$('#confirm_yes')
			.attr('class', settings.success_class)
			.html('<b></b>' + settings.success_text)
			.off('click')
			.on('click', function (e) {
				e.preventDefault();
				$('#confirm_overlay, .confirm_modal').hide();
				callback();
			});

		$('#confirm_overlay').add('#confirm_modal').show();
	} else {
		callback();
	}
}

function component_loader_show(obj, text) {
	if (obj.length > 1) {
		obj.each(function (i, _obj) {
			$(_obj).find('span').text(text).end().show();
		});
	} else {
		obj.find('span').text(text).end().show();
	}
}

function component_loader_hide(obj, text) {
	if (text != '') {
		if (obj.length > 1) {
			obj.each(function (i, _obj) {
				$(_obj).addClass('component_loader_complete').find('span').text(text);
				setTimeout(function () {
					$(_obj).removeClass('component_loader_complete').hide();
				}, 1000);
			});
		} else {
			obj.addClass('component_loader_complete').find('span').text(text);
			setTimeout(function () {
				obj.removeClass('component_loader_complete').hide();
			}, 1000);
		}
	} else {
		if (obj.length > 1) {
			obj.each(function (i, _obj) {
				$(_obj).hide();
			});
		} else {
			obj.hide();
		}
	}
}

function component_controls() {
	$('#admin_components_list')
		.find('.up_component, .down_component').removeClass('no_active')
		.end()
		.find('.up_component').first().addClass('no_active')
		.end().end()
		.find('.down_component').last().addClass('no_active');
}

function crop_preview(crop_box, coords, max_w, max_h, orig_w, orig_h) {
	var $box = $.type(crop_box) == 'string' ? $(crop_box) : crop_box, rx, ry;

	if (coords.w > coords.h) max_h = max_w * coords.h / coords.w;
	if (coords.w < coords.h) max_w = max_h * coords.w / coords.h;

	$box.css({width: max_w + 'px', height: max_h + 'px'});

	rx = max_w / coords.w;
	ry = max_h / coords.h;

	rx = (rx == 0) ? 1 : rx;
	ry = (ry == 0) ? 1 : ry;

	$box.find('img').css({
		width: Math.round(rx * orig_w) + 'px',
		height: Math.round(ry * orig_h) + 'px',
		marginLeft: '-' + Math.round(rx * coords.x) + 'px',
		marginTop: '-' + Math.round(ry * coords.y) + 'px'
	});
}

(function ($) {
	$.fn.style_input = function() {

		return this.each(function() {
			if ($(this).find('input').length > 0) {
				var $this = $(this),
					control_type = $this.find('input').eq(0).attr('type').toLowerCase();

				$this.find(':' + control_type)
					.each(function () {
						if ($(this).is(':checked')) $(this).closest('label').addClass('active');
						if ($(this).is(':disabled')) $(this).closest('label').addClass('disabled');
					})
					.off('change')
					.on('change', function (e) {
						e.preventDefault();

						if (!$(this).prop('disabled')) {
							if (control_type == 'checkbox') {
								if ($(this).prop('checked')) {
									$(this).closest('label').addClass('active');
								} else {
									$(this).closest('label').removeClass('active');
								}
							}
							if (control_type == 'radio') {
								//if (!$(this).prop('checked')) {
									$this.find('label').removeClass('active');
									$(this).closest('label').addClass('active');
								//}
							}

							$(this).trigger('ichange');
						}
					});
			}
		});
	};

	$.fn.ckeditor = function(options) {

		$.fn.ckeditor.defaults = {
			action : 'replace',
			data : '',
			width: 0,
			height: 0
		};

		var opt = $.extend({}, $.fn.ckeditor.defaults, options);

		return this.each(function() {
			if ($.type(CKEDITOR) != 'object'){
				console.error('Editor script not found');
			}

			var editor_id = $(this).attr('id') || $(this).attr('name');
			var instance = CKEDITOR.instances[editor_id];

			if (opt.action == 'replace' && $.type(instance) != 'object'){

				var ck_config = {};
				if (opt.width > 0) ck_config['width'] = opt.width + 'px';
				if (opt.height > 0) ck_config['height'] = opt.height + 'px';

				ck_config['filebrowserBrowseUrl'] = '/js/admin/kcfinder/browse.php?type=files';
				ck_config['filebrowserImageBrowseUrl'] = '/js/admin/kcfinder/browse.php?type=images';
				ck_config['filebrowserFlashBrowseUrl'] = '/js/admin/kcfinder/browse.php?type=flash';
				ck_config['filebrowserUploadUrl'] = '/js/admin/kcfinder/upload.php?type=files';
				ck_config['filebrowserImageUploadUrl'] = '/js/admin/kcfinder/upload.php?type=images';
				ck_config['filebrowserFlashUploadUrl'] = '/js/admin/kcfinder/upload.php?type=flash';

				CKEDITOR.replace(editor_id, ck_config);
			}

			if (opt.action == 'set' && $.type(instance) == 'object'){
				instance.setData(opt.data);
			}

			if (opt.action == 'update' && $.type(instance) == 'object'){
				instance.updateElement();
			}

			if (opt.action == 'destroy' && $.type(instance) == 'object'){
				instance.destroy();
			}

		});
	};

	$.fn.component = function (options) {

		$.fn.component.defaults = {
			onDelete: function () {}
		};

		var opt = $.extend({}, $.fn.component.defaults, options);

		return this.each(function () {

			var $component = $(this),
				$component_panel = $(this).find('.adcom_panel');

			/**
			 * Переміщення компоненту вверх
			 */
			$component_panel.on('click', '.up_component', function (e) {
				e.preventDefault();

				if (!$(this).hasClass('no_active')) {
					var $prev_component = $component.prevAll('.admin_component:eq(0)'),
						$this_copy = $component.clone();

					$component.remove();

					$this_copy.component();
					$this_copy.insertBefore($prev_component);

					component_loader_show($('.component_loader'), '');

					component_controls();
					component_position();
				}
			});

			/**
			 * Переміщення компоненту вниз
			 */
			$component_panel.on('click', '.down_component', function (event) {
				event.preventDefault();

				if (!$(this).hasClass('no_active')) {
					var $next_component = $component.nextAll('.admin_component:eq(0)'),
						$this_copy = $component.clone();

					$component.remove();

					$this_copy.component();
					$this_copy.insertAfter($next_component);

					component_loader_show($('.component_loader'), '');

					component_controls();
					component_position();
				}
			});

			/**
			 * Показати/приховати компонент
			 */
			$component_panel.on('click', '.show_hide', function (e) {
				e.preventDefault();

				var status;

				if ($component_panel.find('.type_of_component').find('div').hasClass('hidden')) {
					$(this).html('<b></b>Приховати');
					$component_panel
						.find('.type_of_component').find('div').removeClass('hidden').addClass($component.data('css-class'))
						.end().end()
						.find('.hidden_component').hide();
					status = 0;
				} else {
					$(this).html('<b></b>Показати');
					$component_panel
						.find('.type_of_component').find('div').removeClass($component.data('css-class')).addClass('hidden')
						.end().end()
						.find('.hidden_component').show();
					status = 1;
				}

				component_loader_show($component.find('.component_loader'), '');

				$.post(
					$component.data('visibility-url'),
					{
						status : status,
						component_id : $component.data('component-id')
					},
					function (response) {
						component_loader_hide($component.find('.component_loader'), '');
					},
					'json'
				);
			});

			/**
			 * Видалення компоненту
			 */
			$component_panel.on('click', '.delete_component', function (e) {
				e.preventDefault();

				confirmation('Видалити компонент назавжди?', function () {
					component_loader_show($component.find('.component_loader'), '');
					$.post(
						$component.data('delete-url'),
						{
							component_id: $component.data('component-id'),
							menu_id: $component.data('menu-id') || 0
						},
						function (response) {
							if (response.error === 0) {
								$component.remove();
								component_controls();
								opt.onDelete();
							}
						},
						'json'
					);
				});
			});
		});
	};

	$.fn.adm_tabs = function (options) {

		$.fn.adm_tabs.defaults = {
			onOpen: function () {}
		};

		var tabs_opt = $.extend({}, $.fn.adm_tabs.defaults, options);

		return this.each(function () {
			var $this = $(this);

			$this.find('.adm_tabs_header').on('click', 'a', function (e) {
				e.preventDefault();

				if (!$(this).hasClass('active')) {
					$(this).closest('ul').find('.active').removeClass('active');
					$(this).closest('li').addClass('active');

					$this.find('.adm_tab').addClass('adm_hidden');
					$($(this).attr('href')).removeClass('adm_hidden');

					if ($.type(tabs_opt.onOpen) == 'function') tabs_opt.onOpen($(this).attr('href'));
				}
			});
		});
	};
})(jQuery);

$(function () {
	$('body').prepend('<div id="confirm_overlay" class="confirm_overlay"></div><div id="confirm_modal" class="confirm_modal"><div class="fm cm_panel"><div class="fm cm_icon_place"><div id="modal_icon"></div></div></div><div class="fm cm_for_text"><p class="modal_name"></p></div><div class="fm cm_bottom_panel"><div class="fmr delete_or_no"><a href="#" id="confirm_no"></a><a href="#" id="confirm_yes"></a></div></div></div>');
	$('#confirm_overlay').fadeTo(100, 0.5).hide();

	$(document).ready(function () {
		$('.controls').style_input();
		$(this).bind("ajaxComplete", function(){
			$('.controls').style_input();
		});
	});
});