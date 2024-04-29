const sharelinkAjaxHandlerV2 = button => {
	return new Promise((resolve, reject) => {
		try {
			const { nonce, advisorid, resourceid, accountid, hidemoreicons, showmoredetails, removequestions } = button.dataset;

			const checkboxes = document.querySelectorAll('.question__checkbox-top-label input[type="checkbox"]');
			const textareas = document.querySelectorAll('.notes textarea');
			let fields = {};
			const notes = {};

			fields = {};

			if (checkboxes.length > 0) {
				checkboxes.forEach(checkbox => {
					const groupId = checkbox.dataset.groupid;

					if (!fields[groupId]) {
						fields[groupId] = [];
					}
				});
			}

			for (const key in fields) {
				if (fields) {
					if (checkboxes.length > 0) {
						checkboxes.forEach(checkbox => {
							const line = {
								id: checkbox.id,
								show: checkbox.checked
							};
							if (checkbox.id.includes(`${key}_`)) {
								fields[key].push(line);
							}
						});
					}
				}
			}

			if (textareas.length > 0) {
				textareas.forEach(textarea => {
					notes[textarea.id] = textarea.value;
				});
			}

			const advisorButton = document.getElementById('checklist-control-advisor-path-button').classList.contains('active');
			const clientButton = document.getElementById('checklist-control-client-path-button').classList.contains('active');
			const groupButton = document.getElementById('checklist-control-group-path-button').classList.contains('active');

			jQuery.ajax({
				type: 'post',
				url: contactLookupData.ajax_url,
				data: {
					action: 'share_link_ajax_handler',
					postId: fpResourceLinksData.postId,
					nonce,
					clientName: '',
					contactId: 0,
					advisorid,
					resourceid,
					accountid,
					hidemoreicons,
					showmoredetails,
					removequestions,
					fields,
					notes,
					advisorButton,
					clientButton,
					groupButton
				},
				success: response => {
					if (response) {
						const input = document.getElementById('resource-share-link-modal-copy-input');

						if (input !== null) {
							input.setAttribute('value', response);
						}
						resolve();
					}
				},
				fail: err => {
					console.error(`There was an error: ${err}`);
				}
			});
		} catch (err) {
			console.error(err);
			reject();
		}
	});
};

export default sharelinkAjaxHandlerV2;
