import Cookies from '../../js-cookie/src/js.cookie';

jQuery(() => {
	const signupWidget = jQuery('.mk-signup-widget');
	const introWidget = jQuery('.mk-intro-widget');
	const cookies = ['kitcesreadersignedup', 'KitcesMemberAdministrator', 'KitcesMemberEditor', 'KitcesMemberAuthor', 'KitcesMemberAuthor'];
	let displayLogin = true;

	for (let i = 0; i < cookies.length; i++) {
		const cookie = Cookies.get(cookies[i]);

		if (cookie) {
			introWidget.css('display', 'block');
			displayLogin = false;
			break;
		}
	}

	if (displayLogin) {
		signupWidget.css('display', 'block');
	}
});
