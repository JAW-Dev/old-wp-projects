import Cookies from '../../js-cookie/src/js.cookie';

jQuery(document).ready(() => {
	const cookieNames = ['kitcesnewslettersignedup', 'kitcesreadersignedup', 'KitcesMemberLogin'];

	cookieNames.forEach(key => {
		const cookie = Cookies.get(key);

		if (cookie) {
			jQuery('.cp-module-info_bar').css('display', 'none');
			jQuery('.infobar-settings').val('0');
		}
	});
});
