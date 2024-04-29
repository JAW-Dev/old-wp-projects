const slick = require('slick-carousel');

jQuery(document).ready(function () {
	// This doesn't seem to be hooked to anything
	// jQuery('.box-testimonials').slick({
	//   arrows: false,
	//   autoplay: true,
	//   autoplaySpeed: 5000
	// });

	jQuery('.nerds-eye-view-testimonials')
		.not('.slick-initialized')
		.slick({
			arrows: false,
			autoplay: true,
			autoplaySpeed: 5000,
			adaptiveHeight: true
		});

	let count = 0.5;

	jQuery('.nerds-eye-view-testimonials').on('afterChange', (event, slick, currentSlide, nextSlide) => {
		const totalSlides = slick.$slides.length;

		// Pause slider after 3 rotations.
		if (count >= totalSlides * 3) {
			jQuery('.nerds-eye-view-testimonials').slick('slickPause');
		}

		count += 0.5;
	});

	jQuery('.kind-words-slider').each(function () {
		const kindWordsSlider = jQuery(this);
		const kindWordsSliderParent = jQuery(this).parent('.kind-words-slider-wrap');

		kindWordsSlider.slick({
			dots: true,
			arrows: true,
			autoplay: true,
			autoplaySpeed: 5000,
			adaptiveHeight: false,
			nextArrow: kindWordsSliderParent.find('.right-arrow'),
			prevArrow: kindWordsSliderParent.find('.left-arrow'),
			appendDots: kindWordsSliderParent,
			fade: true,
			cssEase: 'linear'
		});
	});

	jQuery('.kind-words-slider-last').slick({
		dots: true,
		arrows: true,
		autoplay: true,
		autoplaySpeed: 5000,
		adaptiveHeight: false,
		nextArrow: jQuery('.kind-words-slider-wrap-last .right-arrow'),
		prevArrow: jQuery('.kind-words-slider-wrap-last .left-arrow'),
		appendDots: jQuery('.kind-words-slider-wrap-last'),
		fade: true,
		cssEase: 'linear'
	});

	jQuery('.podcast-reviews-slider-inner').each(function () {
		const podcastReviewsSlider = jQuery(this);
		const podcastReviewsSliderParent = jQuery(this).parent('.podcast-reviews-slider-outer');

		podcastReviewsSlider.slick({
			dots: true,
			arrows: true,
			autoplay: true,
			autoplaySpeed: 5000,
			adaptiveHeight: false,
			nextArrow: podcastReviewsSliderParent.find('.right-arrow'),
			prevArrow: podcastReviewsSliderParent.find('.left-arrow'),
			appendDots: podcastReviewsSliderParent,
			fade: true,
			cssEase: 'linear'
		});
	});
});
