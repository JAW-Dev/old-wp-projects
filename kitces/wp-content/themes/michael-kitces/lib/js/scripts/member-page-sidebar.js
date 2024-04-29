import Cookies from '../../js-cookie/src/js.cookie';

jQuery('.member-sidebar-toggle-width').on('click', function() {
	let theButton = jQuery(this);
	jQuery('.member-sidebar').toggleClass('side-bar-hidden');
	theButton.toggleClass('side-bar-hidden');

	if (theButton.hasClass('side-bar-hidden')) {
		Cookies.remove('member-side-bar-hidden');
		Cookies.set('member-side-bar-hidden', 'closed', { expires: 365 });
	} else {
		Cookies.remove('member-side-bar-hidden');
	}
});
