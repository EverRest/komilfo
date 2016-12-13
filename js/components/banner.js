var $slides = $('.slider_bg'), $pimps = $('.banner_pag').find('a.pag'), slide_index = 0, slide_interval, $slider_left = $('.ar_left'), $slider_right = $('.ar_right');

function sliding() {
	slide_interval = setInterval(function () {
		$slides.eq(slide_index).removeClass('active').fadeTo(500, 0);
		$pimps.eq(slide_index).removeClass('active');

		slide_index++;
		if (slide_index > $slides.length - 1) slide_index = 0;
		$slides.eq(slide_index).addClass('active').fadeTo(500, 1);
		$pimps.eq(slide_index).addClass('active');
	}, 5000);
}

if ($slides.length > 1) {
	sliding();

	$slider_left.add($slider_right).show();

	$pimps.on('click', function (e) {
		e.preventDefault();

		clearInterval(slide_interval);

		$slides.eq(slide_index).removeClass('active').fadeTo(500, 0);
		$pimps.eq(slide_index).removeClass('active');

		slide_index = $(this).index() - 1;
		$slides.eq(slide_index).addClass('active').fadeTo(500, 1);
		$pimps.eq(slide_index).addClass('active');

		sliding();
	});

	$slider_left.on('click', function (e) {
		e.preventDefault();

		clearInterval(slide_interval);

		$slides.eq(slide_index).removeClass('active').fadeTo(500, 0);
		$pimps.eq(slide_index).removeClass('active');

		slide_index--;
		if (slide_index < 0) slide_index = $slides.length - 1;

		$slides.eq(slide_index).addClass('active').fadeTo(500, 1);
		$pimps.eq(slide_index).addClass('active');

		sliding();
	});

	$slider_right.on('click', function (e) {
		e.preventDefault();

		clearInterval(slide_interval);

		$slides.eq(slide_index).removeClass('active').fadeTo(500, 0);
		$pimps.eq(slide_index).removeClass('active');

		slide_index++;
		if (slide_index > $slides.length - 1) slide_index = 0;

		$slides.eq(slide_index).addClass('active').fadeTo(500, 1);
		$pimps.eq(slide_index).addClass('active');

		sliding();
	});
}