import Cookies from '../../js-cookie/src/js.cookie';

jQuery(document).ready(function() {
	const positionShareBar = function() {
		const singlePostContent = jQuery('.single-post .entry-content');

		if (singlePostContent.length > 0) {
			const positionContent = jQuery('.single-post .entry-content').position();
			const positionContentTop = positionContent.top;
			const positionContentLeft = positionContent.left;
			const stickySharePosition = positionContentLeft - 40 - 85;
			const topFloaty = jQuery.mk_get_top_floaty_bits();

			if (positionContentLeft < 175 && jQuery(window).scrollTop() > positionContentTop) {
				jQuery('.kitces-sharing.is-floating').addClass('is-visible');
				if (jQuery(window).width() < 1503) {
					jQuery('.kitces-sharing.is-inline').removeClass('psticky');
					jQuery('.kitces-sharing.is-inline').css('top', 'auto');
					jQuery('.kitces-sharing.is-inline').css('position', 'relative');
				}
			} else {
				jQuery('.kitces-sharing.is-floating').removeClass('is-visible');
				if (jQuery(window).width() >= 1503) {
					jQuery('.kitces-sharing.is-inline').addClass('psticky');
					jQuery('.kitces-sharing.is-inline').css('top', topFloaty);
					jQuery('.kitces-sharing.is-inline').css('position', 'sticky');
					jQuery('.kitces-sharing.is-inline').css('z-index', '2');
				}
			}

			// jQuery('.kitces-sharing.is-floating').css('left', stickySharePosition);

			if (Cookies.get('kitces-mobile-share') === 'share-hidden') {
				jQuery('.kitces-sharing.is-floating').hide();
			}
		}
	};

	jQuery(window).scroll(function() {
		positionShareBar();
	});

	jQuery(window).resize(function() {
		positionShareBar();
	});

	// Handle Share bar hidden or not via cookie
	jQuery('.kitces-sharing-close').on('click', function() {
		Cookies.set('kitces-mobile-share', 'share-hidden', { expires: 14 });
		jQuery(this)
			.parent()
			.hide();
	});

	jQuery('#sharing_email #source_name').attr('maxlength', 20);
});
