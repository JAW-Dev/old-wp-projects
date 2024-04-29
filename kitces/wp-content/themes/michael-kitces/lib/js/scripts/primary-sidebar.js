import Cookies from '../../js-cookie/src/js.cookie';

jQuery('.member-primary-sidebar-toggle').on('click', function() {
	let theButton = jQuery(this);
	jQuery('.content-sidebar-wrap').toggleClass('closed-sidebar');
	theButton.toggleClass('closed-sidebar');

	if (theButton.hasClass('closed-sidebar')) {
		Cookies.remove('member-closed-primary-sidebar');
		Cookies.set('member-closed-primary-sidebar', 'closed', { expires: 365 });
	} else {
		Cookies.remove('member-closed-primary-sidebar');
	}
});
