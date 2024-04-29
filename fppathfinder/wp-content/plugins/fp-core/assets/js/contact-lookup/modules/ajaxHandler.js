const ajaxHandler = query => {
	jQuery.ajax({
		type: 'post',
		url: contactLookupData.ajax_url,
		data: {
			action: 'redtail_contact_lookup',
			search: query,
			nonce: contactLookupData.nonce
		},
		success: response => {
			if (response) {
				const resultsContainer = document.getElementById('checklist-client-lookup-results');

				resultsContainer.innerHTML = response;
			}
		},
		fail: err => {
			console.error(`There was an error: ${err}`);
		}
	});
};

export default ajaxHandler;
