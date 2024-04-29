/* global document, jQuery, StarRating, window */

// Import Moduels
import Cookies from 'js-cookie';
import hasUserRated from './hasUserRated';

const starClick = (parentSelector, childSelector, selectedClass) => {
	jQuery(document).ready(() => {
		const parentElement = jQuery(parentSelector);

		if (!hasUserRated(parentElement) && !parentElement.hasClass('.rated')) {
			jQuery(`${parentSelector} ${childSelector}`).on('click', function (e) { // eslint-disable-line
				const { target } = e;
				const nonce = parentElement.data('nonce');
				const rating = parseInt(jQuery(this).data('value'), 10);
				const version = parentElement.data('version');
				const stars = jQuery(this)
					.parent()
					.children(childSelector);

				for (let i = 0; i < stars.length; i++) {
					jQuery(stars[i]).removeClass(selectedClass);
				}

				for (let i = 0; i < rating; i++) {
					jQuery(stars[i]).addClass(selectedClass);
				}

				jQuery.ajax({
					type: 'post',
					url: StarRating.ajaxurl,
					data: {
						action: 'kitces_rate_post',
						post_id: StarRating.postID,
						user_id: StarRating.userID,
						rating,
						version,
						nonce
					},
					success(response) {
						const current = jQuery(parentElement);

						// Find the other rating element.
						const parentId = jQuery(target)
							.parent()
							.parent()
							.attr('id');

						const ratings = jQuery('.star-ratings');
						const ratingsParent = jQuery(`#${parentId}`);
						let type = '';

						if (jQuery(ratingsParent).hasClass('star-ratings-start')) {
							type = 'star';
						}

						if (jQuery(ratingsParent).hasClass('star-ratings-nerd')) {
							type = 'nerd';
						}

						let other;

						ratings.each((index, element) => {
							const elementId = jQuery(element).attr('id');
							if (elementId !== parentId) {
								other = element;
							}
						});

						// Add select the stars for the other rating element.
						// const otherStars = jQuery(other).find('.star-ratings__star');
						const otherStars = jQuery(other)
							.find('.star-ratings__star')
							.parent()
							.parent()
							.parent();

						otherStars.each((index, star) => {
							jQuery(star).css('display', 'none');
							// if (index < response) {
							// 	jQuery(star).addClass('selected');
							// }
						});

						// Show the message.
						jQuery('.star-ratings__message p').removeClass('hide');

						// Set the ratings as rated.
						current.addClass('rated');
						jQuery(`${parentSelector} ${childSelector}`).off('click');
						jQuery(`${parentSelector} ${childSelector}`).off('mouseover');

						// Set the cookie.
						Cookies.set(`kitces-post-rating-${StarRating.postID}`, rating, {
							expires: 365,
							path: window.location.pathname,
							domian: window.location.host
						});
					}
				});
			});
		}
	});
};

export default starClick;
