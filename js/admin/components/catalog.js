(function ($) {
	$.fn.admin_catalog = function(catalog_settings) {

		$.fn.admin_catalog.settings = {
			page: 0,
			last_page: 0,
			product_add_top : 0,
			before : 0,
			after: 0,
			base_url: '',
			width: 0,

			uploader_script: '',
			uploader_css: '',
			sortable_uri: '',

			search_timer: null,
			search_uri: '',

			change_component_uri: ''
		};

		return this.each(function() {
			var $component = $(this),
				catalog_options = $.extend({}, $.fn.admin_catalog.defaults, catalog_settings),
				rows, i;

			if (catalog_options.before > 0) {
				rows = '';
				for (i = 0; i < catalog_options.before; i++) rows += '<li></li>';
				$component.find('.products_list').prepend(rows);
			}

			if (catalog_options.after > 0) {
				rows = '';
				for (i = 0; i < catalog_options.after; i++) rows += '<li></li>';
				$component.find('.products_list').append(rows);
			}

			$component
				// Управляння компонентом
				.component({
					onDelete: function () {
						$('.com_catalog').show();
					}
				})

				// Імпорт товарів
				.on('click', '.catalog_import', function (e) {
					e.preventDefault();

					if (!global_helper.check_js(catalog_options.uploader_script)) {
						//global_helper.loader($component);
						global_helper.load_css(catalog_options.uploader_css);
						global_helper.load_js(catalog_options.uploader_script, 'uploader_load');
					}

					$(document).on('uploader_load', function () {
						var $import_uploader = $component.find('.import_uploader');

						if (!$import_uploader.hasClass('set_lib')) {
							$import_uploader
								.addClass('set_lib')
								.fineUploader({
									request: {
										endpoint: $import_uploader.data('import-uri'),
										inputName: 'price_file',
										params: {}
									},
									multiple: true,
									text: {
										uploadButton: 'Виберіть або перетягніть файл прайсу',
										dragZone: '',
										dropProcessing: ''
									},
									validation: {
										allowedExtensions: ['xls'],
										sizeLimit: $import_uploader.data('max-file-size'),
										productLimit: 1
									},
									messages: {
										typeError: "Дозволено завантажувати: {extensions}.",
										sizeError: "Розмір файлу не повинен перевищувати {sizeLimit}.",
										tooManyproductsError: "Дозволено завантажувати файлів: {productLimit}."
									}
								})
							.on('submit', function () {
								$('.import_box').fineUploader('setParams', {
									import_type: $component.find('[name="import_type"]:checked').val(),
									after_import: $component.find('[name="after_import"]:checked').val(),
									menu_id: $component.data('menu-id'),
									component_id: $component.data('component-id')
								});
							})
							.on('complete', function (event, id, fileName, response) {
								if (response.success) {
									$component.find('.qq-upload-success').remove();
									$component.find('.import_box').text('Товари успішно імпортовано');
									setTimeout(function () { window.location.reload(); }, 2000);
								}
							});
						}
						//global_helper.loader($component);
					});

					$component.find('.confirm_overlay').add($component.find('.import_modal')).show();
				})
				.on('click', '.import_no', function (e) {
					e.preventDefault();
					$component.find('.confirm_overlay').add($component.find('.import_modal')).hide();
				})

				// Експрот товарів
				.on('click', '.catalog_export', function (e) {
					e.preventDefault();
					$component.find('.confirm_overlay').add($component.find('.export_modal')).show();
				})
				.on('click', '.export_yes', function (e) {
					e.preventDefault();
					$component.find('.confirm_overlay').add($component.find('.export_modal')).hide();
					window.location.href = $(this).attr('href').replace('{{ export_type }}', $component.find('[name="export_type"]:checked').val());
				})
				.on('click', '.export_no', function (e) {
					e.preventDefault();
					$component.find('.confirm_overlay').add($component.find('.export_modal')).hide();
				})

				// Налаштування виводу товарів
				.on('click', 'a.per_page', function (e) {
					e.preventDefault();

					if (!$(this).hasClass('active')) {
						setcookie('a_catalog_per_page', $(this).data('value'), 86400, '/');
						window.location.href = catalog_options.base_url;
					}
				})
				.on('click', '.sort_cell', function (e) {
					e.preventDefault();

					if ($(this).data('value') !== undefined) {
						setcookie('a_catalog_sort', $(this).data('value'), 86400, '/');
					} else {
						setcookie('a_catalog_sort', null, -200, '/');
					}

					window.location.reload();
				})
				.on('click', '.default_sorting', function (e) {
					e.preventDefault();

					setcookie('a_catalog_sort', null, -20, '/');
					window.location.reload();
				})
				.on('click', '.add_bottom', function (e) {
					e.preventDefault();

					var $b = $(this).find('b');

					if (!$b.hasClass('active')) {
						$b.addClass('active');
						catalog_options.product_add_top = 0;
					} else {
						$b.removeClass('active');
						catalog_options.product_add_top = 1;
					}
				})
				.on('click', '.add_product', function (e) {
					e.preventDefault();

					global_helper.loader($component);
					var $link = $(this);

					$.post(
						$link.attr('href'),
						{
							component_id: $component.data('component-id'),
							menu_id: $component.data('menu-id'),
							add_top: catalog_options.product_add_top
						},
						function (response) {
							if (response.success) {
								if (catalog_options.product_add_top === 1 && catalog_options.page != 1) {
									window.location.href = '<?=$base_url;?>';
								} else if (catalog_options.product_add_top === 0 && catalog_options.page != catalog_options.last_page) {
									window.location.href = catalog_options.base_url + '?page=' + catalog_options.last_page;
								} else {
									if ($component.find('.products_list').find('.admin_massage').length > 0) {
										$component.find('.products_list').html('');
									}

									var product = Mustache.render($('#catalog_product_template').html(), response);

									if (catalog_options.product_add_top === 1) {
										$component.find('.products_list').prepend(product);
									} else {
										$component.find('.products_list').append(product);
									}

									$component.trigger('style');
									global_helper.loader($component);
								}
							}
						},
						'json'
					);
				})

				// Сортування товарів
				.find('.products_list').sortable({
					axis: 'y',
					handle: '.sorter a',
					scroll: true,
					crollSpeed: 2000,
					forcePlaceholderSize: true,
					placeholder: "ui-state-highlight",
					update: function () {
						global_helper.loader($component);

						var products = [];
						$component.find('.products_list').find('li').map(function () {
							products.push($(this).data('product-id'));
						});

						$.post(
							catalog_options.sortable_uri,
							{
								products: products,
								menu_id: $component.data('component-id')
							},
							function (response) {
								if (response.success) {
									$component.trigger('style');
									global_helper.loader($component);
								}
							},
							'json'
						);
					}
				})
				.end()

				// Стилізація чекбоксів вибору
				.find('.controls').style_input()
				.end()
				.on('change', '.admin_menu :checkbox', function () {

					if ($(this).closest('li').hasClass('th')) {
						if ($(this).prop('checked')) {
							$(this).closest('.admin_menu').find('label').addClass('active').find(':checkbox').prop('checked', true);
						} else {
							$(this).closest('.admin_menu').find('label').removeClass('active').find(':checkbox').prop('checked', false);
						}
					}

					var ck_all = 0;

					$component.find('.admin_menu').find('li').find(':checkbox').map(function () {
						if (!$(this).closest('li').hasClass('th')) if ($(this).prop('checked')) ck_all++;
					});

					if (ck_all > 0) {
						$component.find('.component_selector').show();

						$(this).closest('.admin_menu').find('.delete_selected').show();
						$(this).closest('.admin_menu').find('label').eq(0).addClass('active').find(':checkbox').prop('checked', true);
					} else {
						$component.find('.component_selector').hide();

						$(this).closest('.admin_menu').find('.delete_selected').hide();
						$(this).closest('.admin_menu').find('label').eq(0).removeClass('active').find(':checkbox').prop('checked', false);
					}
				})

				.on('click', '.hide-show', function (e) {
					e.preventDefault();

					global_helper.loader($component);

					var $link = $(this);
					$link.toggleClass('active');

					$.post(
						$link.attr('href'),
						{
							product_id: $link.closest('li').data('product-id'),
							status: $link.hasClass('active') ? 0 : 1,
							menu_id: $component.data('menu-id')
						},
						function (response) {
							if (response.success) {
								global_helper.loader($component);
							}
						},
						'json'
					);
				})
				.on('click', '.duble_click', function (e) {
					e.preventDefault();

					global_helper.loader($component);
					var $link = $(this);

					$.post(
						$link.attr('href'),
						{
							product_id: $link.closest('li').data('product-id'),
							menu_id: $component.data('menu-id')
						},
						function (response) {
							if (response.success) {

								if ($component.find('.products_list').find('.admin_massage').length > 0) {
									$component.find('.products_list').html('');
								}

								$(
									Mustache.render(
										$('#catalog_product_template').html(),
										$.extend(
											response,
											{
												image: response.image !== '' ? '/upload/catalog/' + (Math.ceil(response.product_id / 100) * 100) + '/' + response.product_id + '/t_' + response.image + '"' : ''
											}
										)
									)
								).insertAfter($link.closest('li'));

								$component.trigger('style');
								global_helper.loader($component);
							}
						},
						'json'
					);
				})
				.on('click', '.single_arrows', function (e) {
					e.preventDefault();
				})
				.on('click', '.delete', function (e) {
					e.preventDefault();

					var $link = $(this);

					global_helper.confirmation('Видалити товар?', function () {

						global_helper.loader($component);

						$.post(
							$link.attr('href'),
							{
								product_id: $link.closest('li').data('product-id'),
								menu_id: $component.data('menu-id')
							},
							function (response) {
								if (response.success) {
									$link.closest('li').slideUp(function () {
										$(this).remove();

										if ($component.find('.products_list').find('li').length === 0) {
											$component.find('.products_list').html('<li><div class="fm admin_massage">В даному компоненті немає товарів</div></li>');
										}

										$component.trigger('style');
										global_helper.loader($component);
									});
								}
							},
							'json'
						);
					});
				})
				.on('click', '.delete_selected', function (e) {
					e.preventDefault();

					global_helper.confirmation('Видалити вибрані товари?', function () {

						global_helper.loader($component);

						$.when(
							$component.find('.products_list').find(':checked').each(function () {
								if (!$(this).closest('li').hasClass('th')) {

									var $link = $(this).closest('li').find('.delete');

									$.post(
										$link.attr('href'),
										{
											product_id: $link.closest('li').data('product-id'),
											menu_id: $component.data('menu-id')
										},
										function (response) {
											if (response.success) {
												$link.closest('li').slideUp(function () {
													$(this).remove();
												});
											}
										},
										'json'
									);
								}
							})
						).then(function () {
								$component.find('.delete_selected').hide();
								$component.find('.admin_menu').find('label').eq(0).removeClass('active').find(':checkbox').prop('checked', false);

								if ($component.find('.products_list').find('li').length === 0) {
									$component.find('.products_list').html('<li><div class="fm admin_massage">В даному компоненті немає товарів</div></li>');
								}

								$component.trigger('style');
								global_helper.loader($component);
							});
					});
				})

				// Пошук
				.on('keyup paste', '.component_search', function () {
					clearTimeout(catalog_options.search_timer);

					var $this = $(this);

					catalog_options.search_timer = setTimeout(function () {

						global_helper.loader($component);

						$.post(
							catalog_options.search_uri,
							{
								menu_id: $component.data('menu-id'),
								component_id: $component.data('component-id'),
								query: $this.val()
							},
							function (response) {
								if (response.success) {
									if (response.products.length > 0) {
										var products = '',
											template = $('#catalog_product_template').html();

										Mustache.parse(template);

										$.each(response.products, function (i, val) {
											products += Mustache.render(
												template,
												$.extend(
													val,
													{
														width: catalog_options.width,
														image: val.image !== '' ? '/upload/catalog/' + (Math.ceil(val.product_id / 100) * 100) + '/' + val.product_id + '/t_' + val.image : '',
														search: $this.val() !== '',
														screen_price_old: val.screen_price_old == 0 ? null : val.screen_price_old
													}
												)
											);
										});

										$component.find('.products_list').html(products);
										$component.trigger('style');
									} else {
										$('.products_list').html('<li><div class="admin_massage">Товари не знайдено</div></li>');
									}

									if ($this.val() !== '') {
										$component.find('.hide_search').hide();
										$component.find('.admin_paginator').hide();
										$this.closest('div').find('a').show();
									} else {
										$component.find('.hide_search').show();
										$component.find('.admin_paginator').show();
										$this.closest('div').find('a').hide();
									}

									global_helper.loader($component);
								}
							},
							'json'
						);
					}, 500);
				})

				// Перенесення  товарів
				.on('click', '.move_products', function (e) {
					e.preventDefault();

					var selected_component = $component.find('[name="component"]:checked').val(),
						products_list = [];

					if ($.isNumeric(selected_component)) {
						$component.find('.products_list').find('[type="checkbox"]').each(function () {
							if ($(this).prop('checked')) {
								products_list.push($(this).val());
							}
						});

						if (products_list.length > 0) {

							global_helper.loader($component);

							$.post(
								catalog_options.change_component_uri,
								{
									component_id: selected_component,
									products: products_list,
									menu_id: $component.data('menu-id')
								},
								function (response) {
									if (response.success) {
										for (var i = 0; i < products_list.length; i++) {
											$component.find('li[data-id="' + products_list[i] + '"]').slideUp(function () {
												$(this).remove();
											});
										}

										$component.trigger('style').find('[name="product_all"]').prop('checked', false);
										global_helper.loader($component);
									}
								},
								'json'
							);
						}
					}
				})

				// Ситилізація рядків
				.on('mouseover', '.holder', function () {
					if (!$(this).closest('li').hasClass('th')) {
						$(this).addClass('active');
					}
				})
				.on('mouseout', '.holder', function () {
					$(this).removeClass('active');
				})
				.on('style', function () {
					$component.find('.admin_menu').map(function () {
						$(this)
							.find('li').removeClass('grey')
							.end()
							.find('li:odd').addClass('grey');
					});
				})
				.trigger('style');
		});
	}
})(jQuery);