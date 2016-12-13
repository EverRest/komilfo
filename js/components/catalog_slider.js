$(function(){
	if($(window).width() <= 767 ){
		$('.carousel-place').map(function () {
			var $slides = $(this).find('.item-gallery'),
				$right_button = $(this).find('.right_btn'),
				$left_button = $(this).find('.left_btn'),
				prev_slide_index = 0,
				slide_index = 0,
				left_href = false,
				right_href = true,
				slider_total = $slides.length,
				slide_interval;
				url = base_url().split('undefined')
				base_url = url[0];

			if($right_button.attr('href') != '#' && $right_button.attr('href') != ''){
				if($right_button.attr('href') != base_url){
					right_href = true;
				}else{
					right_href = false;
				}
			}else{
				right_href = false;
			}

			if($left_button.attr('href') != '#' && $left_button.attr('href') != ''){
				if($left_button.attr('href') != base_url){
					left_href = true;
				}else{
					left_href = false;
				}
			}else{
				left_href = false;
			}

			function arrows(){
				if(slide_index > 0) {
					if(!left_href) {
						$left_button.removeClass('hidden');
					}else {
						$left_button.removeClass('hidden');
					}
				}else if(slide_index == 0 ) {
					if(left_href ) {
						$left_button.removeClass('hidden');
					}else {
						$left_button.addClass('hidden');
					}
				}

				if(slide_index <= (slider_total-1) && slider_total > 1) {
					if(!right_href) {
						$right_button.removeClass('hidden');
					}else {
						$right_button.removeClass('hidden');
					}
				}else if(slide_index == (slider_total-1)  && slider_total == 1){
					if(right_href) {
						$right_button.removeClass('hidden');
					}else {
						// console.log($right_button);
						$right_button.addClass('hidden');
					}
				}
			}

			arrows();

			function step() {
				arrows();

				$slides.eq(prev_slide_index).removeClass('active').fadeTo(500, 0);
				$slides.eq(slide_index).addClass('active').fadeTo(500, 1);
			}

			if (slider_total > 1 && slider_total <= 3) {
				$right_button.on('click', function(event) {
					
					if (slide_index >= 0 && slide_index < (slider_total-1)) {
						event.preventDefault();
						prev_slide_index = slide_index;
						slide_index++;

						step();
					}
				});
				$left_button.on('click', function(event) {
					if (slide_index > 0 && slide_index <= (slider_total-1)) {
						event.preventDefault();
						prev_slide_index = slide_index;
						slide_index--;

						step();
					}
				});
			}
		});
	}
});
$(window).resize(function(){
	if($(window).width() > 767 ){
		$('.carousel-place').find('.item-gallery').each(function(){$(this).removeClass('active').css({opacity: 1});});
	}
	// else if($(window).width() < 768 ){
	// 	if($(window).width() <= 767 ){
	// 	$('.carousel-place').map(function () {
	// 		var $slides = $(this).find('.item-gallery'),
	// 			$right_button = $(this).find('.right_btn'),
	// 			$left_button = $(this).find('.left_btn'),
	// 			prev_slide_index = 0,
	// 			slide_index = 0,
	// 			left_href = false,
	// 			right_href = true,
	// 			slider_total = $slides.length,
	// 			slide_interval;
	// 			url = base_url().split('undefined')
	// 			base_url = url[0];

	// 		if($right_button.attr('href') != '#' && $right_button.attr('href') != ''){
	// 			if($right_button.attr('href') != base_url){
	// 				right_href = true;
	// 			}else{
	// 				right_href = false;
	// 			}
	// 		}else{
	// 			right_href = false;
	// 		}

	// 		if($left_button.attr('href') != '#' && $left_button.attr('href') != ''){
	// 			if($left_button.attr('href') != base_url){
	// 				left_href = true;
	// 			}else{
	// 				left_href = false;
	// 			}
	// 		}else{
	// 			left_href = false;
	// 		}

	// 		function arrows(){
	// 			if(slide_index > 0) {
	// 				if(!left_href) {
	// 					$left_button.removeClass('hidden');
	// 				}else {
	// 					$left_button.removeClass('hidden');
	// 				}
	// 			}else if(slide_index == 0 ) {
	// 				if(left_href ) {
	// 					$left_button.removeClass('hidden');
	// 				}else {
	// 					$left_button.addClass('hidden');
	// 				}
	// 			}

	// 			if(slide_index <= (slider_total-1) && slider_total > 1) {
	// 				if(!right_href) {
	// 					$right_button.removeClass('hidden');
	// 				}else {
	// 					$right_button.removeClass('hidden');
	// 				}
	// 			}else if(slide_index == (slider_total-1)  && slider_total == 1){
	// 				if(right_href) {
	// 					$right_button.removeClass('hidden');
	// 				}else {
	// 					// console.log($right_button);
	// 					$right_button.addClass('hidden');
	// 				}
	// 			}
	// 		}

	// 		arrows();

	// 		function step() {
	// 			arrows();

	// 			$slides.eq(prev_slide_index).removeClass('active').fadeTo(500, 0);
	// 			$slides.eq(slide_index).addClass('active').fadeTo(500, 1);
	// 		}

	// 		if (slider_total > 1 && slider_total <= 3) {
	// 			$right_button.on('click', function(event) {
					
	// 				if (slide_index >= 0 && slide_index < (slider_total-1)) {
	// 					event.preventDefault();
	// 					prev_slide_index = slide_index;
	// 					slide_index++;

	// 					step();
	// 				}
	// 			});
	// 			$left_button.on('click', function(event) {
	// 				if (slide_index > 0 && slide_index <= (slider_total-1)) {
	// 					event.preventDefault();
	// 					prev_slide_index = slide_index;
	// 					slide_index--;

	// 					step();
	// 				}
	// 			});
	// 		}
	// 	});
	// }
	// }
});
