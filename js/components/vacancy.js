$(function () {
	var $form = $('#vacancy_form'),
		$location_dd = $('.location_dropdown'),
		$vacancy_dd = $('.vacancy_dropdown'),
		$w = $('.av_window'),

		$slider = $('.vac_slider_box'),
		$slides = $(this).find('.vac_one_slide'),
		$pimps = $(this).find('.paging').find('a'),
		prev_slide_index = 0,
		slide_index = 0,
		width = $slides.eq(0).outerWidth(true);

	function step() {
		$pimps.eq(prev_slide_index).removeClass('active');

		$slider.find('.ob_long').stop(true, true).animate({'left': -Math.abs(width * slide_index)}, 200, function () {
			$pimps.eq(slide_index).addClass('active');
		});
	}

	$pimps.on('click', '.paging a', function (e) {
		e.preventDefault();

		prev_slide_index = slide_index;
		slide_index = $(this).index();
		step();
	});

	function vac_load() {
		$w.html('<div class="loader"></div>').removeClass('hidden');

		$.post(
			full_url('vacancy/get_list'),
			{
				city_id: $location_dd.find('.selected').length > 0 ? $location_dd.find('.selected').find('a').data('value') : 0,
				title: $vacancy_dd.find('.selected').length > 0 ? $vacancy_dd.find('.selected').find('a').data('value') : ''
			},
			function (response) {
				if (response.hasOwnProperty('success') && response.success) {
					prev_slide_index = 0;
					slide_index = 0;

					$slider.html(response.list);

					$w.html('').addClass('hidden');
				}
			},
			'json'
		);
	}

	$location_dd
		.dropdown_list({
			hide_on_select: false,
			input: true
		})
		.on('change', function (e, data) {
			vac_load();
		});

	$vacancy_dd
		.dropdown_list({
			hide_on_select: false,
			input: true
		})
		.on('change', function (e, data) {
			vac_load();
		});

	$form.find('[name="name"]')
		.add($form.find('[name="phone"]'))
		.add($form.find('[name="vac"]'))
		.add($form.find('[name="message"]'))
		.on('keyup paste blur', function () {
			if ($.trim($(this).val()) !== '') {
				$(this).removeClass('error');
			}
		});

	$form
		.on('change', '[type="file"]', function () {
			var file_name = $(this).val(),
				last_index = file_name.lastIndexOf("\\");

			if (last_index >= 0) {
				file_name = file_name.substring(last_index + 1);
			}

			var ext = file_name.split('.');

			if ($.inArray(ext[ext.length - 1], ['doc', 'docx', 'pdf']) === -1) {
				return false;
			}

			$(this).addClass('hidden');
			$(this).closest('.fee_upload').find('.fee_up').addClass('active');
			$(this).closest('.long_div').find('label').text(file_name);
		})
		.on('click', '.fee_up', function (e) {
			e.preventDefault();

			$(this).closest('.long_div').html($('#vacancy_r_template').html());
		});


	$form.on('click', '.vacancy_send', function (e) {
		e.preventDefault();

		var $link = $(this),
			label = $link.text(),
			errors = false;

		if (!$link.hasClass('disabled')) {
			$link.addClass('disabled');

			if ($.trim($form.find('[name="name"]').val()) === '') {
				errors = true;
				$form.find('[name="name"]').addClass('error');
			}

			if ($.trim($form.find('[name="phone"]').val()) === '') {
				errors = true;
				$form.find('[name="phone"]').addClass('error');
			}

			if (!$.isNumeric($form.find('[name="vac_id"]').val())) {
				errors = true;
				$form.find('[name="vac"]').addClass('error');
			}

			if (!errors) {
				$link.text(get_lang('wait') + '...');

				$form.ajaxSubmit({
					dataType: 'json',
					success: function (response) {
						if (response.success) {
							$link.text(get_lang('sent') + '!');

							$form.find('[name="name"]')
								.add($form.find('[name="phone"]'))
								.add($form.find('[name="vac"]'))
								.add($form.find('[name="vac_id"]'))
								.add($form.find('[name="message"]'))
								.val('');

							$form.find('.vac_r').html($('#vacancy_r_template').html());

							setTimeout(function () {
								$link.removeClass('disabled').text(label);
							}, 3000);
						} else {
							$link.removeClass('disabled').text(label);
						}
					}
				});
			} else {
				$link.removeClass('disabled');
			}
		}
	});

	$slider.find('.vac_slider').on('click', 'a', function (e) {
		e.preventDefault();

		var $link = $(this);
		$w.html('<div class="loader"></div>').removeClass('hidden');

		$.post(
			full_url('vacancy/get_vacancy'),
			{
				vacancy_id: $link.data('vacancy-id')
			},
			function (response) {
				if (response.hasOwnProperty('success') && response.success) {
					$w.html(response.item);
				}
			},
			'json'
		);
	});

	$w
		.on('click', '.sho_close', function (e) {
			e.preventDefault();

			$w.addClass('hidden');
		})
		.on('click', '.common_but', function (e) {
			e.preventDefault();

			$form.find('[name="vac_id"]').val($(this).data('vacancy-id'));
			$form.find('[name="vac"]').val($(this).data('title'));

			$('html, body').animate({ scrollTop: $form.offset().top }, 200);
		});
});