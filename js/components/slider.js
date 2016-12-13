$(function(){
	$('#slider').map(function () {
		var $slides = $(this).find('.slider-bg'),
			$pimps = $(this).find('.paginator-slider').find('a'),
			prev_slide_index = 0,
			slide_index = 0,
			slider_total = $slides.length,
			slide_interval;
		function step() {
			$slides.eq(prev_slide_index).removeClass('active').fadeTo(500, 0);
			$pimps.eq(prev_slide_index).removeClass('active');
			$slides.eq(slide_index).addClass('active').fadeTo(500, 1);
			$pimps.eq(slide_index).addClass('active');
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
				})
				.find('.first').css('opacity', 1).removeClass('first');
		}
	});
});