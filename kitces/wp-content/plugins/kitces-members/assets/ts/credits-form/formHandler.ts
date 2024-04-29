import slideNotification from './../utillities/slideNotification';
import resetFormButton from './../utillities/resetFormButton';

declare var kitcesData: any;

const formHandler = () => {
	const form = <HTMLFormElement>document.getElementById('ac-credits-form');
	const buttonText = 'Submit';

	form.addEventListener('submit', e => {
		e.preventDefault();
		const formData = jQuery(form).serialize();
		const button = <HTMLInputElement>document.getElementById('ac-credits-form-submit');
		const overlay = <HTMLElement>document.getElementById('ce-form-overlay');

		overlay.style.display = 'block';

		jQuery.ajax({
			type: 'post',
			url: kitcesData.adminAjax,
			data: {
				action: 'ac_credits_form',
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

				if (!response.success) {
					return false;
				}

				slideNotification('ac-profile-field-notification');
				location.reload();
			},
			error: (xhr, ajaxOptions, thrownError) => {
				resetFormButton(button, buttonText);
				console.error(`${xhr.status}: ${thrownError}`);
			}
		});
	});
};

export default formHandler;
