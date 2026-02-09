const blogfoelSlider = function ($scope, $) {
	const wId = $scope.data("id");
	const wrapper = document.querySelector(`.elementor-element-${wId}`);

	if (!wrapper) return;

	const outerWrapper = wrapper.querySelector(".blogfoel-slider-wrap");

	if (!outerWrapper) return;

	const widgetId = outerWrapper.dataset.wid;

	const sliderWrapper = document.querySelector(`#blogfoel-slider-${widgetId}`);

	if (!sliderWrapper) return;

	// Read dataset values
	const slideToShow = parseInt(sliderWrapper.dataset.slideToShow);
	const slideToShowMobile = parseInt(sliderWrapper.dataset.slideToShowMobile);
	const slideToShowTablet = parseInt(sliderWrapper.dataset.slideToShowTablet);

	const slideToScroll = parseInt(sliderWrapper.dataset.slidesToScroll);
	const slideToScrollMobile = parseInt(sliderWrapper.dataset.slidesToScrollMobile);
	const slideToScrollTablet = parseInt(sliderWrapper.dataset.slidesToScrollTablet);

	const slideSpaceBetween = parseInt(sliderWrapper.dataset.slidesSpaceBetween);
	const slideSpaceBetweenMobile = parseInt(sliderWrapper.dataset.slidesSpaceBetweenMobile);
	const slideSpaceBetweenTablet = parseInt(sliderWrapper.dataset.slidesSpaceBetweenTablet);

	const autoplay = sliderWrapper.dataset.autoplay;
	const autoplaySpeed = parseInt(sliderWrapper.dataset.autoplaySpeed);

	const transitionBetweenSlides = parseInt(sliderWrapper.dataset.transition_between_slides);

	
	const loop = sliderWrapper.dataset.loop;
	const mousewheel = sliderWrapper.dataset.mousewheel === "true";
	const keyboardControl = sliderWrapper.dataset.keyboard_control === "true";
	const clickable_check = ( sliderWrapper.dataset.clickable == true ) ? true : false;

	const swiperClass = `.swiper-${widgetId}`;

	const swiper = new Swiper(swiperClass, {
		loop: loop,
		autoplay: autoplay
			? {
					delay: autoplaySpeed,
					disableOnInteraction: false,
			  }
			: false,
		mousewheel: mousewheel
			? {
					enabled: true,
			  }
			: false,
		keyboard: keyboardControl,
		speed: transitionBetweenSlides,
		scrollbar: {
			el: ".swiper-scrollbar",
			draggable: true,
			hide: true,
			snapOnRelease: true,
		},
		// If we need pagination
		pagination: {
			el: ".swiper-pagination",
			clickable: clickable_check,
		},
		// Navigation arrows
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
		breakpoints: {
			// when window width is >= 320px
			320: {
				slidesPerView: slideToShowMobile,
				spaceBetween: slideSpaceBetweenMobile,
				slidesPerGroup: slideToScrollMobile,
				// direction: directionMobile,
			},
			// when window width is >= 480px
			767: {
				slidesPerView: slideToShowTablet,
				spaceBetween: slideSpaceBetweenTablet,
				slidesPerGroup: slideToScrollTablet,
				// direction: directionTablet,
			},
			// when window width is >= 640px
			1024: {
				slidesPerView: slideToShow,
				spaceBetween: slideSpaceBetween,
				slidesPerGroup: slideToScroll,
				// direction: direction,
			},
		},
	});
};
jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/blognews-post-carousel-1.default",
		blogfoelSlider
	);
});