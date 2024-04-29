import resetButtons from './resetButtons';

const ajaxHandler = (e, button) => {
	const currentStatus = button.dataset.isActive === '1';
	const url = currentStatus ? button.dataset.deactivationUrl : button.dataset.activationUrl;

	jQuery.ajax({
		type: 'get',
		url,
		success: response => {
			if (response) {
				const data = JSON.parse(response);
				const status = document.getElementById(`${data.slug}_status`);
				resetButtons();

				button.dataset.isActive = data.isActive;
				button.value = data.value;
				status.innerHTML = data.status;
			}
		},
		fail: err => {
			console.error(`There was an error: ${err}`);
		}
	});
};

export default ajaxHandler;
