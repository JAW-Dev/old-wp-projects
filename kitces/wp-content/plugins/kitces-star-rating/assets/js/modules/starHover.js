/* global document, jQuery */

// Import Moduels
import hasUserRated from './hasUserRated';

const starHover = (parentSelector, childSelector, hoverClass) => {
	jQuery(document).ready(() => {
		const parentElement = jQuery(parentSelector);

		if (!hasUserRated(parentElement) && !parentElement.hasClass('.rated')) {
			jQuery(`${parentSelector} ${childSelector}`)
				.on('mouseover', function() { // eslint-disable-line
					const rating = parseInt(jQuery(this).data('value'), 10);

					jQuery(this)
						.parent()
						.children(childSelector)
						.each(function(e) { // eslint-disable-line
							if (e < rating) {
								jQuery(this).addClass(hoverClass);
							} else {
								jQuery(this).removeClass(hoverClass);
							}
						});
				})
				.on('mouseout', function() { // eslint-disable-line
					jQuery(this)
						.parent()
						.children(childSelector)
						.each(function(e) { // eslint-disable-line
							jQuery(this).removeClass(hoverClass);
						});
				});
		}
	});
};

export default starHover;
