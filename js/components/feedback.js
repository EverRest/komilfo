$(function () {
	$('.fb_form').map(function () {
		var $form = $(this),
			request = {},
			error;

		$form.find('.dropdown').dropdown_list();

		$form.find('[type="text"], textarea').on('blur paste keyup', function (e) {
			if (e.keyCode === 13) {
				$(this).closest('form').trigger('submit');
			} else {
				if ($.trim($(this).val()) !== '') {
					$(this).removeClass('error');
				}
			}
		});

		$form.find('.fb_send').on('click', function (e) {
			e.preventDefault();

			$(this).closest('form').trigger('submit');
		});

		$form.on('submit', function (e) {
			e.preventDefault();

			if (!$form.find('.fb_send').hasClass('disabled')) {
				error = false;

				$form.find('[type="text"], textarea, [type="hidden"]').map(function () {
					var $self = $(this),
						rules = $self.data('rules'),
						value = $.trim($self.val());

					if (rules !== undefined) {
						if (rules !== '') {
							rules = rules.split('|');

							$.each(rules, function (i, val) {
								if (val === 'required') {
									if (value === '') {
										$self.addClass('error');
										error = true;
									}
								}

								if (val === 'email') {
									if (!emailRegex.test(value)) {
										$self.addClass('error');
										error = true;
									}
								}
							});
						}
					}

					request[$(this).attr('name')] = $(this).val();
				});

				console.log(error);

				if (!error) {
					$form.find('.fb_send').addClass('disabled').text(get_lang('wait') + '...');

					$.post(
						$form.attr('action'),
						request,
						function (response) {
							if (response.success) {
								$form.find('[type="text"], textarea').val('');
								$form.find('.dropdown').trigger('reset', true);
								$form.find('.fb_send').removeClass('disabled').text(get_lang('sent') + '!');
							}

							setTimeout(function () {
								$form.find('.fb_send').removeClass('disabled').text(get_lang('send'));
							}, 2000);
						},
						'json'
					);
				}
			}
		});
	});
});