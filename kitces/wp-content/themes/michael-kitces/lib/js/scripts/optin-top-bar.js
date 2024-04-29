/* global document, jQuery, window */

jQuery(document).ready(() => {
	function getTopBarHeight() {
		const hasTopBar = 'cpro-ib-open';
		let barHeight = 0; // Get the topbar height.
		if (jQuery('html').hasClass(hasTopBar)) {
			const topBar = jQuery('.cpro-active-step.cp-top');
			barHeight = topBar.outerHeight(); // Get the topbar height.
		}
		return barHeight;
	}

	function setTopBarHeight(barHeight) {
		// Had to apply to both in case user starts scolling
		// before cpro top bar is loaded.
		jQuery('.site-header, .site-header.is-fixed').css('top', `${barHeight}px`);

		jQuery('.cp-close-field').on('click', () => {
			jQuery('.site-header.is-fixed').css('top', `0px`);
		});
	}

	jQuery(document).on('cp-load-field-animation', () => {
		const barHeight = getTopBarHeight(); // Get the topbar height.
		setTopBarHeight(barHeight);
	});
	jQuery(window).resize(() => {
		const barHeight = getTopBarHeight(); // Get the topbar height.
		setTopBarHeight(barHeight);
	});
});
