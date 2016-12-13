$(function () {
	var $form = $('#rent_form'),
		photos = 0;

	$form.find('.region_dropdown')
		.dropdown_list()
		.on('change', function (e, data) {
			$form.find('.city_dropdown').trigger('reset');
			$form.find('.city_dropdown').find('li').addClass('hidden').hide();

			if (data.value === '') {
				$form.find('.city_dropdown').trigger('disabled');
			} else {
				$form.find('.city_dropdown').trigger('enabled').find('.region_' + data.value).removeClass('hidden').show();
				$(this).removeClass('error');
			}
		});

	$form.find('.city_dropdown')
		.dropdown_list()
		.on('change', function (e, data) {
			if (data.value !== '') {
				$(this).removeClass('error');
			}
		});

	$form.find('[name="square"]')
		.add($form.find('[name="contact"]'))
		.add($form.find('[name="phone"]'))
		.add($form.find('[name="description"]'))
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

			if ($.inArray(ext[ext.length - 1], ['jpg', 'jpeg', 'png', 'gif']) === -1) {
				return false;
			}

			$(this).addClass('hidden');
			$(this).closest('.fee_upload').find('.fee_up').addClass('active');
			$(this).closest('.long_div').find('label').text(file_name);

			photos++;

			if (photos < 5) {
				$form.find('.rent_photos').append($('#rent_photo_template').html());
			}
		})
		.on('click', '.fee_up', function (e) {
			e.preventDefault();

			if (!$form.find('.rent_send').hasClass('disabled')) {
				$(this).closest('.long_div').remove();

				photos--;

				if (photos === 4) {
					$form.find('.rent_photos').append($('#rent_photo_template').html());
				}
			}
		});


	$form.on('click', '.rent_send', function (e) {
		e.preventDefault();

		var $link = $(this),
			label = $link.text(),
			errors = false;

		if (!$link.hasClass('disabled')) {
			$link.addClass('disabled');

			if (!$.isNumeric($form.find('[name="region_id"]').val())) {
				errors = true;
				$form.find('.region_dropdown').addClass('error');
			}

			if (!$.isNumeric($form.find('[name="city_id"]').val())) {
				errors = true;
				$form.find('.city_dropdown').addClass('error');
			}

			if ($.trim($form.find('[name="square"]').val()) === '') {
				errors = true;
				$form.find('[name="square"]').addClass('error');
			}

			if ($.trim($form.find('[name="contact"]').val()) === '') {
				errors = true;
				$form.find('[name="contact"]').addClass('error');
			}

			if ($.trim($form.find('[name="phone"]').val()) === '') {
				errors = true;
				$form.find('[name="phone"]').addClass('error');
			}

			if ($.trim($form.find('[name="description"]').val()) === '') {
				errors = true;
				$form.find('[name="description"]').addClass('error');
			}

			if (!errors) {
				$link.text(get_lang('wait') + '...');

				$form.ajaxSubmit({
					dataType: 'json',
					success: function (response) {
						if (response.success) {
							$link.text(get_lang('sent') + '!');

							$form.find('.region_dropdown').trigger('reset');
							$form.find('.city_dropdown').trigger('reset').trigger('disabled');

							$form.find('[name="square"]')
								.add($form.find('[name="contact"]'))
								.add($form.find('[name="phone"]'))
								.add($form.find('[name="description"]'))
								.val('');

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
});