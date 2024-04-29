jQuery.mk_get_top_floaty_bits = function mk_get_top_floaty_bits() {
	var adminBar = jQuery('#wpadminbar');
	var header = jQuery('.site-header.is-fixed');
	var topBar = jQuery('html.cpro-ib-open');
	var top = 0;

	if (adminBar.length && adminBar.css('position') === 'fixed') {
		top = top + adminBar.outerHeight();
	}

	if (topBar.length) {
		var theBar = jQuery('.cpro-active-step.cp-top');
		barHeight = theBar.outerHeight();

		if (barHeight > 0) {
			top = top + barHeight;
		}
	}

	if (header.length) {
		top = top + header.outerHeight();
	}

	return top;
};
