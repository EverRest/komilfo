var LANG, DEF_LANG, _LANG = {}, C_PREFIX = '',
	phoneRegex = /^[+]?[0-9]{5,20}$/,
	ruleRegex = /^(.+)$/,
	emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i;
function get_lang(key) {
	console.log(_LANG);
	return _LANG[key] !== undefined ? _LANG[key] : key + ' TRANSLATE!';
}
jQuery.fn.dropdown = function (options) {
	var settings = $.extend({
		arrow: '',
		prevent: true,
		prevent_up: false,
		onChange: ''
	}, options);
	return this.each(function () {
		var $this = $(this);
		if ($this.find('.selected').length > 0) $this.find('span:eq(0)').html($this.find('.selected').text() + settings.arrow).end().find('input').val($this.find('.selected a').data('value')).end().find('.selected').closest('li').hide();
		$this.on('click', 'span:eq(0)', function (e) {
			e.preventDefault();
			if (!$(this).closest('.dropdown').hasClass('dropdown-open')) {
				console.log($this);
				$.when($('.dropdown').each(function () { if ($(this).hasClass('.dropdown_shop')) $(this).removeClass('dropdown-open').find('ul').eq(0).stop().slideUp(); })).then(function () {$this.addClass('dropdown-open').removeClass('hidden').find('ul').eq(0).stop().slideDown();});
			} else {
				console.log('asd');
				$this.removeClass('dropdown-open').find('ul').eq(0).stop().slideUp();
			}
		});
		$this.find('ul:eq(0)').on('click', 'a', function (e) {
			e.preventDefault();
			if (settings.prevent === false) {
				window.location.href = $(this).attr('href');
			} else {
				if (!settings.prevent_up) {
					$(this).closest('ul').find('.selected').removeClass('selected').show().end().end().closest('li').addClass('selected').hide();
					$this.find('span:eq(0)').html($(this).text() + settings.arrow).end().find('input').val($(this).data('value'));
					$this.removeClass('dropdown-open').find('ul').eq(0).stop().slideUp();
				}
				if ($.isFunction(settings.onChange)) settings.onChange($(this));
			}
		});
	});
};
function setcookie(name, value, expires, path, domain, secure) {
	expires instanceof Date ? expires = expires.toGMTString() : typeof(expires) === 'number' && (expires = (new Date(+(new Date) + expires * 1e3)).toGMTString());
	var r = [name + "=" + value], s, i;
	for(i in s = {expires: expires, path: path, domain: domain}) s[i] && r.push(i + "=" + s[i]);
	return secure && r.push("secure"), document.cookie = r.join(";"), true;
}
function full_url(uri, language) {
	if (language === undefined) {
		language = LANG;
	}
	if (uri === undefined) {
		uri = '';
	}
	return window.location.protocol + '//' + window.location.hostname + (language === DEF_LANG ? '' : '/' + language) + (uri !== '' ? '/' + uri + '/' : '');
}
function base_url(uri) {
	return window.location.protocol + '//' + window.location.hostname + '/' + uri;
}
$(function () {
	$('.print_icon').on('click', function () {
		window.open(full_url('printing/index') + '?module=' + $(this).data('module') + '&id=' + $(this).data('id'), '', 'width=800,height=600,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0');
	});
	$('.close').on('click', 'a', function(event) {
		event.preventDefault();
		/* Act on the event */
		$('.black').hide();
		$('.photo_gallery').removeAttr('style').hide();
	});

	if($(window).outerWidth(true) < 767) {
		var k = 746/1072;
	    $(function(){
	        $('div#box-gall').each(function(){
	            $(this).height($(this).width() / k);
	        });
	        $(window).resize(function(){
	            $('div#box-gall').each(function(){
	                $(this).height($(this).width() / k);
	            });
	        });
	    });
	}
	//--------------------------------------------------------------------------------------------------------------------------------------
	// $(document).on('click', function (e) {
	// 	$('.dropdown').map(function () {
	// 		if ($(this).find('.dropdown_wrapper').length > 0) {
	// 			$(this).removeClass('dropdown-open').find('.dropdown_wrapper').eq(0).addClass('hidden');
	// 		} else {
	// 			$(this).removeClass('dropdown-open').find('ul').eq(0).addClass('hidden');
	// 		}
	// 	});
	// 	$('.call_box_form').addClass('hidden');
	// 	$('.order_box_form').addClass('hidden');
	// });
});
function getImageSize( id) {
    var oHlpr = document.createElement( 'IMG');
    var oPic = document.getElementById( id);
    oHlpr.style.visibility = 'hidden';
    oHlpr.style.position = 'absolute';
    oHlpr.top = 0; oHlpr.left = 0;
    oHlpr.src = oPic.src;
    document.body.appendChild( oHlpr);
    var imSize = { 'width':oHlpr.offsetWidth,'height':oHlpr.offsetHeight }
    document.body.removeChild( oHlpr);
    return imSize;
}

function popup_position(windowHeigh, popupObject){
	var $top, $right, $result = new Object();
	if(windowHeigh >= (popupObject.outerHeight())){
		if(popupObject.outerHeight() < 100) $ph = 331/2; else $ph = popupObject.outerHeight()/2;
		$wh = windowHeigh/2;
		$top = $(window).scrollTop() + ($wh-$ph);
	}else{
		$top = $(window).scrollTop() + 50;
	}
	
	$right = -popupObject.width()/2;
	$result = {'top': $top};
	return $result;
}


$(function () {

	var $articles = $('.article_open'), read_more, read_less;
	if ($articles.length > 0) {
		$articles.map(function () {
			$(this).append(' <div class="long_div"><a href="#" class="fm read_more">' + get_lang('read_more') + ' →</a></div>');
			$(this).next('.article_close').append(' <div class="long_div"><a href="#" class="fm read_less">' + get_lang('read_less') + '</a></div>');
			$(this).find('.read_more').on('click', function (e) {
				e.preventDefault();
				$(this).closest('.article_open').next('.article_close').stop().slideDown();
				$(this).hide();
			});
			$(this).next('.article_close').find('.read_less').on('click', function (e) {
				e.preventDefault();
				$(this).closest('.article_close').stop().slideUp();
				$(this).closest('.article_close').prev('.article_open').find('.read_more').show();
			});
		});
	}
});
//--------------------------------------------------------------------------------------------------------------------------------------
$(function () {
	$(window).resize(function(){
		$width_slide = $("#slider").width();
		$proportion = $("#slider").attr("data-prp");
		$("#slider").height($width_slide/$proportion);
		$(".desc-slider").height($width_slide/$proportion);
	});
	$(document).ready(function(){
		console.log($(".video-box").height());
		// width_slide = $(".desc_slider").height();
		// // proportion = $('.desc_slider').width()/$('.desc_slider').height();
		// console.log(width_slide+" "+ proportion);
		// $('video').height(width_slide);
		// console.log($width_slide);
	})
	$(window).load(function(){
		
		console.log($(".desc_slider").height());
	})
	var $current, $prevent_element = 0;
	$('nav.menu>ul:eq(0)>li').each(function(e){
		$(this).prop('active', false);
	});
	$(document).on('mouseover', 'nav.menu>ul:eq(0)>li', function(event) {
		if(!$(event.target).prop('active') && $prevent_element != 0){
			$prevent_element.find('a').next().hide();
			$prevent_element.prop('active', false);
		}
		$current = $(this);
		if($current.find('a').next().is('ul')){
			$prevent_element = $current;
			$(event.target).prop('active', true);
			$current.find('a').next().show();	
		}
	}).on('mouseout', 'nav.menu', function(event) {
		$(this).find('li').prop('active', false);
		$(this).find('li>a').prop('active', false);
		$(this).find('li>a').next().hide();
	});;
	// var foter_height = ($('footer.footer').innerHeight() <= 300)? 300 : $('footer.footer').innerHeight();
	// $('section.for_ooter_bottom').css("padding", "0 0 " + foter_height + "px 0");
	// $('footer.footer').css("margin", "-" + foter_height + "px 0 0 0");
	var $menu = $('.main_menu');
	$menu.on('click', 'a', function (e) {
		if (scroll($(this).attr('href'))) {
			e.preventDefault();
		}
	});
	scroll(window.location.href);
	function scroll(href) {
		if (href.indexOf('#') > -1) {
			var _href = href.split('#');
			if (_href.hasOwnProperty('1') && _href[1] !== '') {
				$('html, body').animate({ scrollTop: $('#c_' + _href[1]).offset().top }, 1000);
				$(location).prop('href', href);
				return true;
			}
		}
		return false;
	}
});

$(function() {
	
	if($('.main-proto').length > 0){
		$('.main-proto').slick({
			infinite: false,
			arrows: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			speed: 500,
			fade: true,
	  		cssEase: 'linear',
	  		asNavFor: '.small-photo',
	  		responsive: [
		        {
		        	breakpoint: 650,
		    		settings: {
				        slidesToShow: 1,
				        slidesToScroll: 1,
				        dots: true
		    		}
		        }
	        ]
		});
	}
	if($('.small-photo').length > 0){
		$('.small-photo').slick({
			infinite: false,
			arrows: true,
			prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"></button>',
	        nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"></button>',
			slidesToShow: 3,
			slidesToScroll: 1,
			speed: 500,
			vertical: true,
			verticalSwiping: true,
			focusOnSelect: true,
	  		asNavFor: '.main-proto',
	  		responsive: [
		        {
		        	breakpoint: 1201,
		    		settings: {
				        slidesToShow: 2,
				        slidesToScroll: 1
		    		}
		        }
	        ]
		});
	}

	function imageResize(image, startWidth, startHeight){
		var proportion = startWidth / startHeight;

		image.each(function(){
			$(this).height($(this).outerWidth() / proportion);
		});

		$(window).resize(function(){
			image.each(function(){
				$(this).height($(this).outerWidth() / proportion);
			});
		});

	};

	imageResize($('.collection-slider_one'), 630, 730);

	imageResize($('.catalog-item'), 605, 860);

	imageResize($('.main-proto .one-photo'), 578, 860);

	$('.search-btn').on('click',  function(event) {
		event.preventDefault();
		if($(this).prev().val() != '') {
			document.location = "search/?query="+$(this).prev().val();
		}else{
			$(this).prev().addClass('error');
		}
	});

	$('.search').on('keypress', function(event) {
		if(event.keyCode == 13){
			if($(this).val() != '') {
				document.location = "search/?query="+$(this).val();
			}else{
				$(this).addClass('error');
			}
		}else{
			$(this).removeClass('error');
		}
	});

	$('.open-popup').click(function(){
		var position = popup_position($(window).outerHeight(), $('.popup'));
		$('.popup').css(position);
		$('.popup').fadeIn(400);
		$('.black').fadeIn(400);
		return false;
	});

	$('.close-popup, .black').click(function(){
		$('.popup').fadeOut(400);
		$('.black').fadeOut(400);
		return false;
	});

	$('#send_message').on("click", function(e){
		e.preventDefault();
		var error = false,
			$this = $(this),
			$data = $(this).closest('.popup'),
			$name = $data.find('[name="name"]').val(),
			$phone = $data.find('[name="phone"]').val(),
			$email = $data.find('[name="email"]').val();
			$message = $data.find('[name="message"]').val();
			$def = $(this).text();
			$link = $(this);
				
			nameRegex = /^[A-Za-zА-Яа-яыъЇї ]+$/;

		if($name!=null)
		{
			if($name.length == 0 || !nameRegex.test($name)){$data.find('[name="name"]').addClass("error"); error=true;}else{$data.find('[name="name"]').removeClass('error');}
		}
		if($phone!=null)
		{
			if($phone.length == 0 || !phoneRegex.test($phone)){$data.find('[name="phone"]').addClass("error"); error = true;}else{$data.find('[name="phone"]').removeClass('error');}
		}
		if($email!=null)
		{
			if ($email.length == 0 || !emailRegex.test($email)) {$data.find('[name="email"]').addClass("error");error = true;}else{$data.find('[name="email"]').removeClass('error'); error = false;}
		}
		if($message!=null)
		{
			if($message.length == 0 ){$data.find('[name="message"]').addClass("error"); error=true;}else{$data.find('[name="message"]').removeClass('error');}
		}
	
		if(!error)
		{
			var msg_1 = '';
			if (LANG == 'ua') msg_1 = 'Надсилається';
			if (LANG == 'ru') msg_1 = 'Отправляется';
			if (LANG == 'en') msg_1 = 'Sending';
			var msg_2 = '';
			if (LANG == 'ua') msg_2 = 'Надіслано';
			if (LANG == 'ru') msg_2 = 'Отправлено';
			if (LANG == 'en') msg_2 = 'Sent';
			$link.text(msg_1);
			
			$.post(
				'call/send/',
				{name:$name, phone:$phone, email:$email, message:$message, subject:"Запитання."},
				function(response)
				{
					if(response.success === true) {
							setTimeout(function(){$link.text(msg_2); $this.css("background-color", '#538e5d'); }, 1000);
							function second_passed() {
								$data.find("input").map(function () {
									$(this).val('');
									$data.removeClass('active');
								});
								$link.text($def);
								$data.find('textarea').val('');
								$this.css("background-color", '#96928d');
								$('.black').fadeOut(400);
								$data.fadeOut(400);
							}
							setTimeout(second_passed, 2500);
					}
					else
					{
						$link.text("Error!");
					}
				},
				'json'
			);
		}
	});

});
