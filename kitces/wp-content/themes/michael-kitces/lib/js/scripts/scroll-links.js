// Link to and scroll to a section id.
jQuery('a[href^="#"]').on('click', function(e) {
	var slideClass = jQuery(this).hasClass('no-slide');
	var target = jQuery(this.getAttribute('href'));
	var slideOffset = jQuery(this).data('slide-offset');
	var topExtra = 80;

	if (slideOffset !== undefined && 'all-the-floaties' === slideOffset) {
		var floatyTop = jQuery.mk_get_top_floaty_bits();
		topExtra = topExtra + floatyTop;
	}

	if (target.length && !slideClass) {
		var targetOffset = target.offset().top;
		var totalOffset = targetOffset - topExtra;
		e.preventDefault();
		jQuery('html, body')
			.stop()
			.animate(
				{
					scrollTop: totalOffset
				},
				300
			);
	}
});

jQuery('button.back-to-top.button').on('click', function(e) {
	e.preventDefault();
	jQuery('html, body')
		.stop()
		.animate(
			{
				scrollTop: 0
			},
			100
		);
});
