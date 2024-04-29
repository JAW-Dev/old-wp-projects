import ajaxHandler from './ajaxHandler';

const onSubmitHandler = () => {
	const forms = document.querySelectorAll('.signup-email-submit__form');

	forms.forEach(form => {
		form.addEventListener('submit', e => {
			e.preventDefault();

			const emailInput = form.querySelector('.signup-email-submit__email-input');
			const email = emailInput.value;
			const { plan, member, modal, iic } = emailInput.dataset;
			const nonce = form.querySelector('#chargebee_email_lookup_field').value;

			if (!email) {
				const error = document.createElement('div');
				error.className = 'error-message';
				error.innerHTML = 'Email Rquired!';
				error.setAttribute('style', 'color: red; font-size: 0.8rem; margin-bottom: 0.5rem;');

				form.insertBefore(error, emailInput);

				return false;
			}

			ajaxHandler(email, nonce, plan, member, modal, iic);
		});
	});
};

export default onSubmitHandler;
