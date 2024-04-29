const formatEmails = (total = 0, newEmailList = {}) => {
	const emails = {};

	if (total === 0) {
		const emailsTextara = document.getElementById('acsync-user-emails-list');
		newEmailList = emailsTextara.value.split('\n');
	}

	for (const i in newEmailList) {
		if (newEmailList[i]) {
			emails[i] = newEmailList[i];
		}
	}

	return emails;
};

const toolsSyncAjaxHandler = (button, total = 0, newEmailList = {}, cycle = 0, percent = 0) => {
	const { nonce } = button.dataset;
	let emails = {};

	if (total > 0) {
		emails = formatEmails(total, newEmailList);
	}

	if (total == '0') {
		const emailsTextara = document.getElementById('acsync-user-emails-list');
		newEmailList = emailsTextara.value.split('\n');

		emails = formatEmails(0, newEmailList);

		for (const i in emails) {
			if (emails[i]) {
				total++;
			}
		}
	}

	if (total == '0') {
		return false;
	}

	jQuery.ajax({
		type: 'post',
		url: ajaxurl,
		data: {
			action: 'tools_ac_sync',
			nonce,
			emails,
			total,
			cycle
		},
		beforeSend() {
			button.innerHTML = 'Syncing';
		},
		success(response) {
			let progressIndicator = document.getElementById('progress-bar-percent');
			let progressBar = document.getElementById('progress-bar-progress');
			const data = JSON.parse(response);
			cycle = data.cycle;

			if (data.remainder > 0) {
				percent = (cycle / data.total) * 100;
				emails = {};

				for (const i in data.emails) {
					if (data.emails[i]) {
						emails[i] = data.emails[i];
					}
				}

				emails = formatEmails(data.remainder, emails);

				progressIndicator.innerHTML = Math.ceil(percent);
				// TODO: REMOVE!
				console.log(progressBar.style.width);
				progressBar.style.width = `${Math.ceil(percent)}%`;
				// TODO: REMOVE!
				console.log(progressBar.style.width);

				toolsSyncAjaxHandler(button, data.total, emails, cycle, percent);
			} else if (data.remainder === 0) {
				progressIndicator = document.getElementById('progress-bar-percent');
				progressBar = document.getElementById('progress-bar-progress');
				button.innerHTML = 'Sync Users';
				progressIndicator.innerHTML = 100;
				progressBar.style.width = '100%';
				console.log('success');
			} else {
				console.log(response);
			}
		}
	});
};

export default toolsSyncAjaxHandler;
