/* global jQuery, document */

import Cookies from '../../js-cookie/src/js.cookie';

// Announcements
jQuery(document).ready(function() {
	jQuery('.announcement').each(function(i, obj) {
		const announcementId = jQuery(this).attr('id');
		if (Cookies.get(announcementId) === 'hidden') {
			jQuery(this).hide();
		}
	});
	jQuery('.announcement[class^="widget"]').on('click', '.announcement-close', function() {
		const announcementId = jQuery(this)
			.parents('.announcement')
			.attr('id');
		Cookies.set(announcementId, 'hidden', { expires: 7 });
		jQuery('#' + announcementId).slideUp();
	});
});

// CE banner
jQuery(document).ready(function() {
	// console.log(Cookies.get());
	jQuery('.post').each(function(i, obj) {
		const article = jQuery(this)
			.attr('class')
			.split(' ')[0];
		if (Cookies.get(article) === 'ce-hidden') {
			jQuery(this)
				.find('.ce-banner')
				.hide();
		}
	});

	jQuery('.ce-banner-close').on('click', function() {
		const article = jQuery(this)
			.closest('.post')
			.attr('class')
			.split(' ')[0];
		Cookies.set(article, 'ce-hidden', { expires: 30 });
		jQuery(this)
			.parent()
			.hide();
	});
});

// Home banner
jQuery(document).ready(function() {
	const { pathname } = window.location;

	jQuery('.hero .close').on('click', function() {
		Cookies.set('home-hero', 'hidden', { expires: 30 });
		jQuery('.hero, .testimonials').hide();
	});

	if (pathname.indexOf('member') > -1 || pathname.indexOf('subscription-confirmed') > -1 || pathname.indexOf('thanks-for-buying') > -1) {
		Cookies.set('home-hero', 'hidden', { expires: 30 });
	}
});

jQuery(document).ready(() => {
	const names = ['KitcesMemberAdministrator', 'KitcesMemberEditor', 'KitcesMemberAuthor'];

	names.forEach(name => {
		const setCookie = Cookies.get(name);

		if (setCookie && typeof __gaTracker !== 'undefined') {
			__gaTracker('set', 'dimension5', 'true');
		}
	});
});
