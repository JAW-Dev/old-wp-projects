import rcpCheckbox from './rcpCheckbox';
import rcpInputWithSpan from './rcpInputWithSpan';

const rcpForm = () => {
	const forms = document.querySelectorAll('#tabs-group-settings .rcp_form');

	forms.forEach(form => {
		const fieldset = form.querySelector('fieldset');
		const wraps = fieldset.querySelectorAll('p');

		wraps.forEach(wrap => {
			wrap.classList.add('form-control');
		});
	});

	rcpCheckbox();
	rcpInputWithSpan();
};

export default rcpForm;
