function mk_handle_inline_or_float(navWrap, entryContentOffset) {
	let body = jQuery('body');
	body.removeClass('inpost-nav-is-not-floating');
	if (entryContentOffset.left < 175) {
		var top = jQuery.mk_get_top_floaty_bits();
		navWrap.removeClass('is-floating');
		navWrap.addClass('inline-toggleable');
		navWrap.css('width', 'auto');
		navWrap.addClass('psticky');
		navWrap.css('top', top);
		navWrap.css('position', 'sticky');
		navWrap.css('z-index', '2');
		navWrap.removeClass('is-open');
		navWrap.find('.inpost-nav-items').hide();
		body.addClass('inpost-nav-is-not-floating');
	} else {
		navWrap.removeClass('inline-toggleable');
		navWrap.addClass('is-floating');
		navWrap.css('width', entryContentOffset.left);
		navWrap.css('left', entryContentOffset.left + 32);
		navWrap.removeClass('is-open');
		navWrap.find('.inpost-nav-items').show();
	}
}

function mk_handle_float_top(navWrap, theWindow) {
	let windowScrollTopFT = theWindow.scrollTop();
	let executiveOffset = jQuery('.executive-summary').offset();
	let executiveOffsetTop = executiveOffset.top;

	if (executiveOffsetTop - (windowScrollTopFT + 180) < 0) {
		navWrap.css('position', 'fixed');
		navWrap.css('top', '180px');
	} else {
		navWrap.css('position', 'absolute');
		navWrap.css('top', 'auto');
	}
}

jQuery(function() {
	let navWrap = jQuery('.inpost-nav-wrap');
	let globalCurrentHeading = '';
	let globalCurrentDetails = [];

	if (navWrap.length) {
		jQuery('body').addClass('has-inpost-nav');
		let theWindow = jQuery(window);
		let entryContent = jQuery('.entry-content');
		let entryContentOffset = entryContent.offset();
		let headings = [];
		mk_handle_inline_or_float(navWrap, entryContentOffset);

		jQuery('.inpost-nav-items a').each(function(i) {
			let link = jQuery(this);
			let headingID = link.attr('href');
			let heading = jQuery(headingID);
			let headingOffset = heading.offset();
			let titleOveride = heading.data('nav-title');

			if (titleOveride !== undefined) {
				link.text(titleOveride);
			}

			headings.push([headingOffset.top, headingID]);
		});

		jQuery(window).on('scroll', function() {
			if (navWrap.hasClass('is-floating')) {
				let currentHeading = '';
				let windowScrollTop = theWindow.scrollTop();
				let floatyTop = jQuery.mk_get_top_floaty_bits();

				mk_handle_float_top(navWrap, theWindow);

				jQuery(headings).each(function(i, v) {
					let thisTop = v[0];
					let thisID = v[1];
					let thisOne = thisTop - (windowScrollTop + floatyTop + 96);

					if (thisOne < 0) {
						currentHeading = thisID;
						globalCurrentDetails = [thisTop, thisID];
					}
				});

				if (globalCurrentHeading !== currentHeading) {
					globalCurrentHeading = currentHeading;

					jQuery('.inpost-nav-items a').removeClass('current-heading');
					jQuery('a[href="' + globalCurrentHeading + '"').addClass('current-heading');
				} else {
					if (globalCurrentHeading.length) {
						let currentTop = globalCurrentDetails[0];
						let currentOne = currentTop - (windowScrollTop + floatyTop + 96);

						if (currentOne > 0) {
							jQuery('.inpost-nav-items a').removeClass('current-heading');
							globalCurrentHeading = '';
							globalCurrentDetails = [];
							currentHeading = '';
						}
					}
				}
			}
		});

		theWindow.resize(function(e) {
			mk_handle_float_top(navWrap, theWindow);
			mk_handle_inline_or_float(navWrap, entryContent.offset());
		});

		setTimeout(function() {
			mk_handle_float_top(navWrap, theWindow);
			mk_handle_inline_or_float(navWrap, entryContent.offset());
		}, 500);

		setTimeout(function() {
			mk_handle_float_top(navWrap, theWindow);
			mk_handle_inline_or_float(navWrap, entryContent.offset());
		}, 2500);

		setTimeout(function() {
			mk_handle_float_top(navWrap, theWindow);
			mk_handle_inline_or_float(navWrap, entryContent.offset());
		}, 5000);

		jQuery('.cp-close-link,  .cp-button-field').on('click', function() {
			setTimeout(function() {
				mk_handle_float_top(navWrap, theWindow);
				mk_handle_inline_or_float(navWrap, entryContent.offset());
			}, 200);

			setTimeout(function() {
				mk_handle_float_top(navWrap, theWindow);
				mk_handle_inline_or_float(navWrap, entryContent.offset());
			}, 500);
		});

		jQuery('a.mobile-nav-title').on('click', function(e) {
			e.preventDefault();
			if (navWrap.hasClass('is-open')) {
				navWrap.removeClass('is-open');
				navWrap.find('.inpost-nav-items').slideUp();
			} else {
				navWrap.addClass('is-open');
				navWrap.find('.inpost-nav-items').slideDown();
			}
		});

		jQuery('.inline-toggleable .inpost-nav-items > a').on('click', function() {
			navWrap.removeClass('is-open');
			navWrap.find('.inpost-nav-items').slideUp();
		});
	}
});
