jQuery.cachedScript = function(url, options) {
	options = $.extend(options || {}, {
		dataType: "script",
		cache: true,
		url: url
	});
	return jQuery.ajax(options);
};
var global_helper = {
	settings: {
		delete_alert: true
	},
	set_setting: function(setting, value) {
		this.settings[setting] = value;
	},
	get_setting: function (setting) {
		return (this.settings[setting] !== undefined) ? this.settings[setting] : null;
	},
	loader: function ($component) {
		if ($component.find('.component_loader').hasClass('active')) {
			$component.find('.component_loader').removeClass('active').hide();
		} else {
			$component.find('.component_loader').addClass('active').show();
		}
	},
	confirmation: function (_options, callback) {
		if (this.get_setting('delete_alert')) {
			var $overlay = $('#confirm_overlay'),
				$modal = $('#confirm_modal'),
				confirmation_defaults = {
					msg: '',
					icon_class: 'fm cm_delete',
					cancel_class: 'fm confirm_no',
					cancel_text: 'Скасувати',
					success_class: 'fm confirm_yes',
					success_text: 'Видалити'
				},
				options;
			if ($.type(_options) == 'object') {
				options = $.extend(confirmation_defaults, _options);
			} else {
				confirmation_defaults.msg = _options;
				options = confirmation_defaults;
			}
			$overlay.css('height', $(document).height());
			if (options.msg != '') {
				$('.confirm_modal').find('p').html(options.msg);
			}
			$('#modal_icon').attr('class', options.icon_class);
			$('#confirm_no')
				.attr('class', options.cancel_class)
				.html('<b></b>' + options.cancel_text)
				.off('click')
				.on('click', function (e) {
					e.preventDefault();
					$overlay.add($modal).hide();
				});
			$('#confirm_yes')
				.attr('class', options.success_class)
				.html('<b></b>' + options.success_text)
				.off('click')
				.on('click', function (e) {
					e.preventDefault();
					$overlay.add($modal).hide();
					if ($.type(callback) === 'function') {
						callback();
					}
				});
			$overlay.add('#confirm_modal').show();
		} else {
			if ($.type(callback) === 'function') {
				callback();
			}
		}
	},
	js: {},
	check_js: function (uri) {
		return this.js[uri] !== undefined ? this.js[uri][0] : false;
	},
	load_js: function (uri, event) {
		if ($.inArray(uri, this.js) === -1) {
			this.js[uri] = [false, event];
			$.cachedScript(uri)
				.done(function () {
					global_helper.loaded_js(uri);
				})
				.fail(function () {
					console.log(uri + ' load fail');
				});
		} else {
			if (global_helper.check_js(uri)) {
				global_helper.loaded_js(uri);
			}
		}
	},
	loaded_js: function (uri) {
		if (this.js[uri] !== undefined) {
			this.js[uri][0] = true;
			$(document).trigger(this.js[uri][1]);
		}
	},
	css: [],
	check_css: function (uri) {
		return this.css[uri] !== undefined;
	},
	load_css: function (uri) {
		if (!global_helper.check_css(uri)) {
			this.css.push(uri);
			$.get(uri, function(response) {
				$('<style type="text/css"></style>').html(response).appendTo('head');
			});
		}
	},
	nl2br: function (str) {
		return str.replace(/([^>])\n/g, '$1<br>');
	},
	photo_grid: function ($obj, min_width) {
		$obj.map(function () {
			var $ul = $(this).find('ul').eq(0),
				row_width = $ul.outerWidth(true),
				item_margin = parseInt($ul.children().eq(0).css('margin-left')) + parseInt($ul.children().eq(0).css('margin-right')),
				items_in_row = Math.floor(row_width / (min_width + item_margin)),
				plus_width = (row_width - (items_in_row * (min_width + item_margin))) / items_in_row;
			$ul.children().map(function () {
				$(this)
					.add($(this).find('.vertical'))
					.css('width', Math.floor(min_width + plus_width));
			});
		});
	},
	row_decor: function ($link) {
		$link
			.removeClass('grey')
			.each(function () {
				var index = $(this).index();
				$(this).find('.number').eq(0).text(index + 1);
				if (index % 2 == 0) $(this).addClass('grey');
			});
	},
	//сортування li в лісті компонентів
	fild_sortable: function ($component, $link, settings, sorter_el) {
		$component.sortable({
			forcePlaceholderSize: settings.forcePlaceholderSize,
			opacity: settings.opacity,
			listType: settings.listType,
			handle: settings.handle,
			items: settings.items,
			toleranceElement: settings.toleranceElement,
			placeholder: settings.placeholder,
			axis: settings.axis,
			helper: settings.helper,
			update: function (e, obj) {
				global_helper.row_decor($link);
				var position = [];
				$link.each(function () {
					position[$(this).index()] = $(this).data('item-id');
				});
				if (position.length > 0) {
					if ($.type(sorter_el) === 'function') {
						sorter_el(position, obj);
					}
				}
			}
		});
		
	},
	photo_crop: function ($link, $crop, thumb_w, thumb_h, big_w, big_h, on_crop) {
		var api = null,
			width = $link.data('width') > thumb_w ? thumb_w : $link.data('width'),
			height = width * $link.data('height') / $link.data('width'),
			s_width = 1000 - width - 60,
			s_height = s_width * $link.data('height') / $link.data('width');
		$('body').append(
			Mustache.render(
				$crop.html(),
				{
					source: $link.data('src'),
					width: width,
					height: height,
					s_width: s_width,
					s_height: s_height
				}
			)
		);
		$('#crop_preview').css('width', thumb_w + 'px').css('height', thumb_h + 'px');
		$('#crop_overlay').css('height', $(document).height());
		$('#crop_modal').css('top', $(document).scrollTop() + 50);
		$('#crop_source').find('img').Jcrop({
				keySupport: false,
				aspectRatio: big_w/big_h,
				setSelect: [0, 0, thumb_w, thumb_h],
				realSizes: [$link.data('width'), $link.data('height')],
				onChange: function (coords) {
					crop_preview($('#crop_preview').find('div'), coords, thumb_w, thumb_h, s_width, s_height);
				}
			},
			function () {
				api = this;
				$('[name="proportion"]').off('change').on('change', function () {
					if ($(this).prop('checked')) {
						$(this).closest('label').addClass('active');
						api.setOptions({aspectRatio: big_w/big_h});
					} else {
						$(this).closest('label').removeClass('active');
						api.setOptions({aspectRatio: 0});
					}
					api.focus();
				});
				$('#crop_cancel').off('click').on('click', function (e) {
					e.preventDefault();
					api.destroy();
					api = null;
					$('#crop_modal').add('#crop_overlay').remove();
				});
				$('#crop_save').off('click').on('click', function (e) {
					e.preventDefault();
					$('#crop_modal').add('#crop_overlay').remove();
					if ($.type(on_crop) === 'function') {
						on_crop(api, s_width);
					}
					api.destroy();
					api = null;
				});
			});
	},
	photo_watermark: function ($link, $watermark, on_save) {
		var width = $link.data('width') > 500 ? 500 : $link.data('width'),
			height = width * $link.data('height') / $link.data('width'),
			a_w = Math.round(width / 3),
			a_h = Math.round(height / 3);
		$('body').append(
			Mustache.render(
				$watermark.html(),
				{
					src: $link.data('src') + '?t=' + Math.random(),
					width: width,
					height: height,
					margin: width / 2
				}
			)
		);
		var $tiles = $('#watermark_tiles');
		if ($link.data('position') > 0) {
			$tiles.find('[data-value="' + $link.data('position') + '"]').addClass('active');
		}
		$tiles.find('a').map(function (i) {
			$(this).css({
				width: (a_w - (i % 3 == 2 ? 2 : 1)) + 'px',
				height: (a_h - 1) + 'px',
				left: (i % 3) * a_w + 'px'
			});
			if (i < 3) $(this).css('top', 0);
			if (i >= 3 && i <= 5) $(this).css('top', a_h + 'px');
			if (i > 5) $(this).css('top', (a_h * 2) + 'px');
		});
		$tiles.off('click', 'a').on('click', 'a', function (e) {
			e.preventDefault();
			$(this).toggleClass('active').siblings().removeClass('active');
		});
		$('#cancel_watermark').off('click').on('click', function (e) {
			e.preventDefault();
			$('#watermark_modal').add('#watermark_overlay').remove();
		});
		$('#save_watermark').off('click').on('click', function (e) {
			e.preventDefault();
			$('#watermark_modal').add('#watermark_overlay').remove();
			if ($.type(on_save) === 'function') {
				on_save($tiles.find('a.active').data('value'));
			}
		});
	}
};
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
	if (coords.w >= coords.h) {
		if ((max_w * coords.h / coords.w) < max_h) {
			max_h = max_w * coords.h / coords.w;
		} else {
			max_w = max_h * coords.w / coords.h;
		}
	}
	if (coords.w < coords.h) {
		if ((max_h * coords.w / coords.h) < max_w) {
			max_w = max_h * coords.w / coords.h;
		} else {
			max_h = max_w * coords.h / coords.w;
		}
	}
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
						console.log('change');
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
			onDelete: function () {},
			component_script: function () {}
		};
		return this.each(function () {
			var opt = $.extend({}, $.fn.component.defaults, options),
				$component = $(this),
				$component_panel = $(this).find('.adcom_panel');
			/**
			 * Переміщення компоненту вверх
			 */
			$component_panel.on('click', '.up_component', function (e) {
				e.preventDefault();
				if (!$(this).hasClass('no_active')) {
					var $prev_component = $component.prevAll('.admin_component').eq(0),
						$this_copy = $component.clone();
					$component.remove();
					$this_copy.insertBefore($prev_component);
					$this_copy.component(opt);
					global_helper.loader($component);
					component_controls();
					component_position();
				}
			});
			/**
			 * Переміщення компоненту вниз
			 */
			$component_panel.on('click', '.down_component', function (event) {
				event.preventDefault();
				console.log('asdasdasd');
				if (!$(this).hasClass('no_active')) {
					var $next_component = $component.nextAll('.admin_component').eq(0),
						$this_copy = $component.clone();
					$component.remove();
					$this_copy.insertAfter($next_component);
					$this_copy.component(opt);
					global_helper.loader($component);
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
				$(this).toggleClass('active');
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
				global_helper.loader($component);
				$.post(
					$component.data('visibility-url'),
					{
						status : status,
						component_id : $component.data('component-id')
					},
					function (response) {
						if (response.success) {
							global_helper.loader($component);
						}
					},
					'json'
				);
			});
			/**
			 * Видалення компоненту
			 */
			$component_panel.on('click', '.delete_component', function (e) {
				e.preventDefault();
				global_helper.confirmation(
					'Видалити компонент назавжди?',
					function () {
						global_helper.loader($component);
						$.post(
							$component.data('delete-url'),
							{
								component_id: $component.data('component-id'),
								menu_id: $component.data('menu-id') || 0
							},
							function (response) {
								if (response.success) {
									$component.remove();
									component_controls();
									opt.onDelete();
								}
							},
							'json'
						);
					}
				);
			});
			opt.component_script();
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
		$(this).bind("ajaxComplete", function(){ $('.controls').style_input(); });
	});
});