import 'modaal';
import Cookies from 'js-cookie';

const chargebeeCreditCardModal = () => {
	const cookie = Cookies.get('kitces-credit-card-alert-dismiss');
	const { memberPage } = kitcesData;
	const { expireSoon } = kitcesData;

	if (!cookie && memberPage && expireSoon) {
		const closeButton = jQuery('#kitces-cc-alert-dismiss');
		const updateButton = jQuery('.kitces-cc-alert__update');

		jQuery('#kitces-cc-alert').modaal({
			content_source: '#kitces-cc-alert',
			start_open: true,
			hide_close: true,
			overlay_close: false
		});

		closeButton.on('click', () => {
			jQuery('#kitces-cc-alert').modaal('close');

			Cookies.set('kitces-credit-card-alert-dismiss', 'true', {
				expires: 2,
				path: window.location.pathname,
				domian: window.location.host
			});
		});

		updateButton.on('click', () => {
			jQuery('#kitces-cc-alert').modaal('close');
		});
	}
};

export default chargebeeCreditCardModal;
