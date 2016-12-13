$(function () {
	$('.ob_timer').map(function () {

		var $self = $(this);

		$self
			.countdown($(this).data('date'))
			.on('update.countdown', function (e) {
				$self.find('.ctd').text((e.offset.totalDays.toString().length !== 2 ? '0' : '') + e.offset.totalDays);
				$self.find('.cth').text((e.offset.hours.toString().length !== 2 ? '0' : '') + e.offset.hours);
				$self.find('.ctm').text((e.offset.minutes.toString().length !== 2 ? '0' : '') + e.offset.minutes);
			});
	});

	$('.ob_slider_box').map(function () {
		var $slider = $(this),
			$slides = $(this).find('.ob_one_slide'),
			$left = $(this).find('.left'),
			$right = $(this).find('.right'),
			$pimps = $(this).find('.paging').find('a'),
			prev_slide_index = 0,
			slide_index = 0,
			slider_total = $slides.length,
			slide_interval,
			width = $slides.eq(0).outerWidth(true);

		function step() {
			$pimps.eq(prev_slide_index).removeClass('active');

			$slider.find('.ob_long').stop(true, true).animate({'left': -Math.abs(width * slide_index)}, 200, function () {
				$pimps.eq(slide_index).addClass('active');
			});
		}

		function sliding() {
			slide_interval = setInterval(function () {
				prev_slide_index = slide_index;

				slide_index++;
				if (slide_index > slider_total - 1) {
					slide_index = 0;
				}

				step();
			}, 5000);
		}

		if (slider_total > 1) {
			sliding();

			$left.add($right).removeClass('hidden');

			$left.on('click', function (e) {
				e.preventDefault();

				prev_slide_index = slide_index;

				slide_index--;
				if (slide_index < 0) {
					slide_index = slider_total - 1;
				}

				step();
			});

			$right.on('click', function (e) {
				e.preventDefault();

				prev_slide_index = slide_index;

				slide_index++;
				if (slide_index > slider_total - 1) {
					slide_index = 0;
				}

				step();
			});

			$pimps.on('click', function (e) {
				e.preventDefault();

				prev_slide_index = slide_index;
				slide_index = $(this).index();
				step();
			});

			$(this)
				.on('mouseenter', function () {
					clearInterval(slide_interval);
				})
				.on('mouseleave', function () {
					sliding();
				});
		}
	});
});