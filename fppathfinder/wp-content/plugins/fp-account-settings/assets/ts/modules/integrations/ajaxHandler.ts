import resetButtons from './resetButtons';

const ajaxHandler = (e, button: HTMLInputElement) => {
	const currentStatus: any = button.dataset.isActive === '1';
	const url: string = currentStatus ? button.dataset.deactivationUrl : button.dataset.activationUrl;

	jQuery.ajax({
		type: 'post',
		url,
		success: response => {
			if (response) {
				const data = JSON.parse(response);
				const buttonClasses = button.classList;
				const status = document.getElementById(`${data.slug}_status`);
				const statusClasses = status.classList;
				resetButtons();

				button.dataset.isActive = data.isActive;
				button.value = data.value;
				status.innerHTML = data.status;
				buttonClasses.remove('pill-button__grey');
				buttonClasses.add('pill-button__red');
				statusClasses.remove('inactive');
				statusClasses.add('active');
			}
		}
	});
};

export default ajaxHandler;
