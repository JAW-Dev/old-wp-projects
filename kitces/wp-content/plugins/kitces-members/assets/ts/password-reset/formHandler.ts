import slideNotification from './../utillities/slideNotification';
import resetFormButton from './../utillities/resetFormButton';

declare var kitcesData: any;

const formHandler = () => {
	const form = <HTMLFormElement>document.getElementById('password-reset-form');

	form.addEventListener('submit', e => {
		e.preventDefault();
		const formData = jQuery(form).serialize();
		const button = <HTMLInputElement>document.getElementById('password-reset-form-submit');
		const buttonText = 'Change Password';
		const overlay = <HTMLElement>document.getElementById('password-reset-overlay');
		const pass1 = <HTMLInputElement>document.getElementById('password-1');
		const pass2 = <HTMLInputElement>document.getElementById('password-2');

		overlay.style.display = 'block';

		jQuery.ajax({
			type: 'post',
			url: kitcesData.adminAjax,
			data: {
				action: 'kitces_password_reset',
				data: formData
			},
			beforeSend: () => {
				button.value = 'Submitting';
				button.setAttribute('disabled', 'disabled');
			},
			success: response => {
				response = JSON.parse(response);

				resetFormButton(button, buttonText);
				overlay.style.display = 'none';

				pass1.value = '';
				pass2.value = '';

				if (!response.success) {
					return false;
				}

				if ('url' in response) {
					window.location.href = response.url;
				}

				slideNotification('password-reset-form-notification');
			},
			error: (xhr, ajaxOptions, thrownError) => {
				resetFormButton(button, buttonText);
				console.error(`${xhr.status}: ${thrownError}`);
			}
		});
	});
};

export default formHandler;
