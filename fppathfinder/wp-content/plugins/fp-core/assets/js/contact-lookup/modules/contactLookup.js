import ajaxHandler from './ajaxHandler';

const contactLookup = () => {
	const activeCRM = contactLookupData.active_crm;

	// Do not run if shared link.
	if (!activeCRM) {
		return;
	}

	document.addEventListener('DOMContentLoaded', () => {
		const lookupField = document.getElementById('client-name');

		lookupField.addEventListener('input', e => {
			const query = e.currentTarget.value;

			if (query.length < 3) {
				return;
			}

			setTimeout(() => {
				ajaxHandler(query);
			}, 300);
		});
	});
};

export default contactLookup;
