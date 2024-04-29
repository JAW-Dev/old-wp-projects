/* global jQuery, StarRating */

// Import Modules
import Cookies from 'js-cookie';

const hasUserRated = parentSelector => {
	const parentElement = jQuery(parentSelector);
	const cookie = Cookies.get(`kitces-post-rating-${StarRating.postID}`);

	if (cookie) {
		jQuery(parentElement).addClass('rated');
		return true;
	}

	return false;
};

export default hasUserRated;
