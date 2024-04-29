// JS related to gutenberg blocks, so that it can be pulled apart more easily

jQuery('.agenda-details .button-wrap button.button').on('click', function() {
	let agendaItem = jQuery(this).parents('.agenda-item');

	if (agendaItem.length) {
		agendaItem.toggleClass('is-open');

		let agendaDesc = agendaItem.find('.agenda-details > .description');
		let headshotWrap = agendaItem.find('.people-grid-item .image-wrap');
		let bioLink = agendaItem.find('.people-grid-item a.inline-modal');

		if (agendaItem.hasClass('is-open')) {
			agendaDesc.slideDown();
			headshotWrap.fadeIn();
			bioLink.fadeIn();
		} else {
			agendaDesc.slideUp();
			headshotWrap.fadeOut();
			bioLink.fadeOut();
		}
	}
});

// Modal popping stuff for MK Modal block.
document.addEventListener('DOMContentLoaded', function() {
	let modalBlocks = jQuery('.mk-modal-inner-inner');

	if (modalBlocks.length) {
		modalBlocks.each(function(index, value) {
			let blockWrap = jQuery(this).parents('.mk-modal-outer');
			let blockID = blockWrap.attr('id');
			let modalLinks = jQuery('a[href^="#' + blockID + '"]');

			if (modalLinks.length) {
				modalLinks.addClass('inline-gb-block-modal');
				modalLinks.addClass('no-slide');
			}
		});

		jQuery('.inline-gb-block-modal').modaal({
			type: 'inline',
			width: 670
		});
	}

	jQuery('.mk-modal-inner .button-wrap .button.close-the-modal').on('click', function() {
		jQuery('.inline-gb-block-modal').modaal('close');
	});
});
