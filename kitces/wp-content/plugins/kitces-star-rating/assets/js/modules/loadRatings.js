/* global jQuery, StarRating */

// Import Modules
import Cookies from 'js-cookie';
import starHover from './starHover';
import starClick from './starClick';

const bodyRatings = () => {
	const rand = Math.floor(Math.random() * StarRating.versionsNum);
	const settings = StarRating.bodySettings;
	const randSettings = settings[rand];

	const starRatings = jQuery('.star-ratings__body');
	const starRatingsStars = jQuery('.star-ratings__body .star-ratings__stars');
	const id = rand + 1;
	const classId = `star-ratings-${id}`;
	const classType = `star-ratings-${randSettings.star_type}`;

	starRatings.attr('id', classId);
	starRatings.addClass(classId);
	starRatings.addClass(classType);

	const question = jQuery('.star-ratings__question');
	question.text(randSettings.ratings_question);

	const rating = Cookies.get(`kitces-post-rating-${StarRating.postID}`) ? Cookies.get(`kitces-post-rating-${StarRating.postID}`) : '0';
	const type = Cookies.get('kitces-post-rating-type') ? Cookies.get('kitces-post-rating-type') : randSettings.star_type;

	if (rating > 0) {
		jQuery(starRatingsStars).addClass('rated');
	}

	const versionName = randSettings.version_name;
	const version = versionName.replace(/\s+/g, '-').toLowerCase();
	jQuery(starRatingsStars).attr('data-version', version);

	for (let i = 0; i < 5; i++) {
		const show = !(i >= rating);
		const num = i + 1;
		const solid = show ? ' selected' : '';
		starRatingsStars.append(`<div class="star-ratings__star star-ratings__star-${num}${solid} star-ratings-nerd" data-value="${num}"></div>`);
	}

	const message = jQuery('.star-ratings__message p');
	message.text(randSettings.thank_you_message);

	starHover('.star-ratings__body .star-ratings__stars', '.star-ratings__star', 'show');
	starClick('.star-ratings__body .star-ratings__stars', '.star-ratings__star', 'selected');
};

const headerRatings = () => {
	const settings = StarRating.headerSettings;

	const starRatings = jQuery('.star-ratings__header');
	const starRatingsStars = jQuery('.star-ratings__header .star-ratings__stars');
	const classId = 'star-ratings-0';
	const classType = `star-ratings-${settings.star_type}`;

	starRatings.attr('id', classId);
	starRatings.addClass(classId);
	starRatings.addClass(classType);

	const question = jQuery('.star-ratings__question');
	question.text(settings.ratings_question);

	const rating = Cookies.get(`kitces-post-rating-${StarRating.postID}`) ? Cookies.get(`kitces-post-rating-${StarRating.postID}`) : '0';

	if (rating > 0) {
		jQuery(starRatingsStars).addClass('rated');
	}

	const versionName = settings.version_name;
	const version = versionName.replace(/\s+/g, '-').toLowerCase();
	jQuery(starRatingsStars).attr('data-version', version);

	for (let i = 0; i < 5; i++) {
		const show = !(i >= rating);
		const num = i + 1;
		const solid = show ? ' selected' : '';
		starRatingsStars.append(`<div class="star-ratings__star star-ratings__star-${num}${solid} star-ratings-nerd" data-value="${num}"></div>`);
	}

	const message = jQuery('.star-ratings__message p');
	message.text(settings.thank_you_message);

	starHover('.star-ratings__header .star-ratings__stars', '.star-ratings__star', 'show');
	starClick('.star-ratings__header .star-ratings__stars', '.star-ratings__star', 'selected');
};

const loadRatings = () => {
	bodyRatings();
	headerRatings();
};

export default loadRatings;
