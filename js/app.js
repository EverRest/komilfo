(function ($) {
    'use strict';

    var elements = {
        $root: $("html, body"),
        $mainSlider: $(".main-slider"),
        $mobileMenuIcon: $(".mobile-menu-icon"),
        $menuList: $(".menu-list"),
        $menuListItem: $(".menu-list .menu-list-item"),
        $menuListItemLink: $(".menu-list-item a"),
        $worksSlider: $(".works-slider")
    };

    // Mobile navigation
    $(function () {
        elements.$mobileMenuIcon.click(function () {
            elements.$menuList.stop(true, true).slideToggle();
            $(this).toggleClass('active');
        });

        elements.$menuListItem.each(function () {
            if ($(this).children().hasClass("sub-nav-list")) {
                $(this).click(function (e) {
                    e.preventDefault();
                    $(this).children(".sub-menu-list").stop(true, false).slideToggle();
                });
            }
        });
    });

    // Header Slider
    if (elements.$mainSlider.length) {
        var swiper = new Swiper(elements.$mainSlider, {
            // pagination: '.swiper-pagination',
            // paginationClickable: true,
            loop: true,
            onTransitionEnd: function (swiper) {
                toggleSwiperCaptionAnimation(swiper);
                $(window).trigger("resize");
            }
        });
    }


    /**
     * toggleSwiperCaptionAnimation
     * @description  toggle swiper animations on active slides
     */
    function toggleSwiperCaptionAnimation(swiper) {
        var prevSlide = $(swiper.container),
            nextSlide = $(swiper.slides[swiper.activeIndex]);

        prevSlide
            .find("[data-caption-animate]")
            .each(function () {
                var $this = $(this);
                $this
                    .removeClass("animated")
                    .removeClass($this.attr("data-caption-animate"))
                    .addClass("not-animated");
            });

        nextSlide
            .find("[data-caption-animate]")
            .each(function () {
                var $this = $(this),
                    delay = $this.attr("data-caption-delay");

                setTimeout(function () {
                    $this
                        .removeClass("not-animated")
                        .addClass($this.attr("data-caption-animate"))
                        .addClass("animated");
                }, delay ? parseInt(delay) : 0);
            });
    }

    /**
     * Init Of Site Scroll Animation
     * @description  elents animated by scrolling
     */
    if ($('[data-aos]').length) {
        AOS.init({
            easing: 'linear',
            disable: 'mobile'
        });
    }

    // Works Slider
    if (elements.$worksSlider.length) {
        var swiper = new Swiper(elements.$worksSlider, {
            paginationClickable: true,
            nextButton: '.btn-next',
            prevButton: '.btn-prev',
            loop: true
        });
    }

    // Anchor
    elements.$menuListItemLink.click(function () {
        elements.$root.animate({
            scrollTop: $($(this).attr('href')).offset().top
        }, 1500);
        return false;
    });

}(jQuery));