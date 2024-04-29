const toggleHide = selector => {
	const target = jQuery(selector);

	if (target.hasClass('show')) {
		target.removeClass('show');
		target.addClass('hide');
	}

	if (target.hasClass('hide')) {
		target.removeClass('hide');
		target.addClass('show');
	}
};

jQuery(document).ready(function() {
	jQuery('.video-modaal').modaal({
		type: 'video'
	});

	jQuery('.image-modaal').modaal({
		type: 'image'
	});

	jQuery('.graphics-library-post-image').modaal();

	jQuery('.team-member-block-bio-link, .opening-view-more').modaal();

	jQuery('.inline-modal').modaal();

	jQuery('.consult-modal').modaal({
		close_text: 'Close',
		type: 'inline',
		width: 670
	});

	jQuery('#fancy-inline, .fancy-inline').modaal({
		custom_class: 'inline-modaal',
		width: 670,
		after_close: () => {
			if (jQuery('#combo-signup-email-submit').hasClass('show')) {
				jQuery('#combo-signup-email-submit').toggleClass('hide');
				jQuery('#combo-signup-email-submit').toggleClass('show');
			}

			if (jQuery('#combo-message').hasClass('hide')) {
				jQuery('#combo-message').toggleClass('hide');
				jQuery('#combo-message').toggleClass('show');
			}
		}
	});

	jQuery('.fancybox').modaal({
		type: 'image',
		custom_class: 'inline-modaal'
	});

	jQuery('#close-modaal').on('click', () => {
		jQuery('.modaal-inner-wrapper').trigger('click');
	});

	jQuery('.email-inline').each((index, element) => {
		jQuery(element).modaal({
			width: 670
		});
	});

	jQuery('.email-inline-most-popular').each((index, element) => {
		jQuery(element).modaal({
			width: 670
		});
	});

	jQuery('#kitces_update_ce_modaal_link').modaal({
		start_open: true,
		should_open: function() {
			return window.sessionStorage.getItem("kitces_ce_modal_shown") !== 'true' && kitces_data && kitces_data.show_ce_modal && kitces_data.show_ce_modal === 'true';
	},
	after_open: function() {
		window.sessionStorage.setItem("kitces_ce_modal_shown", 'true' );
	}
} );
});
